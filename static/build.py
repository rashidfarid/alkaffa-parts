#!/usr/bin/env python3
"""Build the static Al Kaffa Parts website into ../site for Cloudflare.

Run: python3 static/build.py

Pages come from static/products.json and static/models.json. The design, animation and
scripts come from the WordPress theme in alkaffa-parts/, so both stay the same.
Uses the Python standard library only.
"""
import hashlib
import json
import re
import shutil
from datetime import date
from html import escape
from pathlib import Path
from urllib.parse import quote

HERE = Path(__file__).resolve().parent
ROOT = HERE.parent
THEME = ROOT / "alkaffa-parts"
OUT = ROOT / "site"

SITE_URL = "https://alkafaparts.com"
NAME = "Al Kaffa Parts"
TAGLINE = "Mitsubishi Pajero spare parts specialists"
PHONE = "+971 50 124 9137"
TEL = "+971501249137"
WHATSAPP = "971501249137"
EMAIL = "alkaffahusedparts@gmail.com"
ADDRESS = "Al Sajaa, Sharjah, United Arab Emirates"
HOURS = "Sat – Thu: 8:00 AM – 9:00 PM"
HERO_TITLE = "Spare parts for your Mitsubishi Pajero"

PRODUCTS = json.loads((HERE / "products.json").read_text("utf-8"))
MODELS = json.loads((HERE / "models.json").read_text("utf-8"))
MODEL_BY_SLUG = {m["slug"]: m for m in MODELS}
FRONT_PAGE = (THEME / "front-page.php").read_text("utf-8")

asset_versions = {}


def e(text):
    return escape(str(text), quote=True)


def asset(path):
    """Root-relative asset URL with a content hash, so a changed file always gets a fresh URL."""
    return "/assets/" + path + "?v=" + asset_versions[path]


def whatsapp_url(text=""):
    url = "https://wa.me/" + WHATSAPP
    return url + "?text=" + quote(text, safe="") if text else url


def product_path(p):
    return "/product/" + p["slug"] + "/"


def order_url(p):
    lines = [
        "Hello Al Kaffa Parts, I want to order (please send the price):",
        "• " + p["name"],
        "Part no.: " + p["sku"],
        SITE_URL + product_path(p),
        "My Pajero year / VIN: ",
    ]
    return whatsapp_url("\n".join(lines))


def fit_names(p):
    return [MODEL_BY_SLUG[s]["name"] for s in p["models"]]


def fitment_label(p):
    gens = [n.replace("Pajero", "").replace("Gen", "").strip() for n in fit_names(p)]
    return " & ".join(gens) + " Gen"


def trim(text, limit=160):
    text = re.sub(r"\s+", " ", text).strip()
    return text if len(text) <= limit else text[: limit - 3].rstrip() + "…"


WA_ICON = (
    '<svg class="wa-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18.2a8.2 8.2 0 0 1-4.2-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4.3-.4.8-1.4.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.1 5.1 0 0 0 1.1 2.7 11.7 11.7 0 0 0 4.5 4c1.7.7 2.3.8 3.2.6a2.7 2.7 0 0 0 1.8-1.2 2.2 2.2 0 0 0 .1-1.3c0-.1-.2-.2-.4-.3Z"/></svg>'
)
CALL_ICON = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2Z"/></svg>'


def logo_mark(css_class):
    svg = (THEME / "assets/images/logo-mark.svg").read_text("utf-8")
    inner = re.sub(r"^<svg[^>]*>|</svg>\s*$", "", svg.strip()).replace("\n", "")
    return f'<svg class="{css_class}" viewBox="0 0 64 64" aria-hidden="true" focusable="false">{inner}</svg>'


def search_form(placeholder="Part name, part no. or OEM no."):
    return (
        '<form class="part-search" role="search" method="get" action="/shop/">'
        '<label class="screen-reader-text">Search parts'
        f'<input type="search" name="q" placeholder="{e(placeholder)}"></label>'
        '<button class="btn" type="submit">Search</button></form>'
    )


def page(path, title, description, body, image=None, og_type="website", schema=None, noindex=False):
    """Wrap a page body in the shared head, header and footer, and write it to site/."""
    image = image or SITE_URL + asset("images/models/pajero-gen-4.webp")
    url = SITE_URL + path
    wa = whatsapp_url("Hello Al Kaffa Parts, I need a part for my Pajero.")
    ld = ""
    if schema:
        data = json.dumps(schema, ensure_ascii=False, separators=(",", ":")).replace("</", "<\\/")
        ld = f'<script type="application/ld+json">{data}</script>\n'
    robots = '<meta name="robots" content="noindex">\n' if noindex else ""
    canonical = "" if noindex else f'<link rel="canonical" href="{e(url)}">\n'
    model_links = "".join(
        f'<li><a href="/pajero-model/{m["slug"]}/">{e(m["name"])}</a></li>' for m in MODELS
    )
    car = asset("images/models/pajero-gen-4.webp")
    html = f"""<!doctype html>
<html lang="en-US">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script>document.documentElement.classList.add('js');</script>
<title>{e(title)}</title>
<meta name="description" content="{e(trim(description))}">
{canonical}{robots}<meta property="og:site_name" content="{e(NAME)}">
<meta property="og:title" content="{e(title)}">
<meta property="og:type" content="{og_type}">
<meta property="og:description" content="{e(trim(description))}">
<meta property="og:url" content="{e(url)}">
<meta property="og:image" content="{e(image)}">
<meta name="twitter:card" content="summary_large_image">
<link rel="icon" type="image/svg+xml" href="{asset('images/logo-mark.svg')}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600&family=Barlow+Condensed:wght@600;700&display=swap">
<link rel="stylesheet" href="{asset('css/style.css')}">
{ld}</head>
<body>
<a class="skip-link screen-reader-text" href="#content">Skip to content</a>

<div class="topbar">
	<div class="wrap">
		<span class="topbar__hours">{e(HOURS)}</span>
		<span class="topbar__links">
			<a href="tel:{TEL}">{e(PHONE)}</a>
			<a href="{e(wa)}" target="_blank" rel="noopener">WhatsApp</a>
			<span>Genuine &amp; OEM Pajero parts</span>
		</span>
	</div>
</div>

<header class="site-header">
	<div class="wrap">
		<a class="brand" href="/" rel="home">
			<img class="brand__car" src="{car}" alt="" width="1200" height="598">
			<span class="brand__text">
				<span class="brand__name">Al Kaffa <span>Parts</span></span>
				<span class="brand__tag">Pajero specialists · UAE</span>
			</span>
		</a>
		<nav class="main-nav" id="main-nav" aria-label="Main menu">
			<ul class="menu">
				<li><a href="/">Home</a></li>
				<li><a href="/shop/">All Parts</a></li>
				<li><a href="/contact/">Contact</a></li>
			</ul>
		</nav>
		<div class="header-actions">
			<a class="header-wa" href="{e(wa)}" target="_blank" rel="noopener">{WA_ICON}<span class="header-wa__text"><small>Order on WhatsApp</small>{e(PHONE)}</span></a>
			<button class="icon-btn search-toggle" type="button" aria-controls="header-search" aria-expanded="false" aria-label="Search parts">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
			</button>
			<button class="icon-btn menu-toggle" type="button" aria-controls="main-nav" aria-expanded="false" aria-label="Menu">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
			</button>
		</div>
	</div>
	<div class="header-search" id="header-search" hidden>
		<div class="wrap">{search_form()}</div>
	</div>
</header>

<div id="content" class="site-content">
{body}
</div>

<footer class="site-footer">
	<div class="wrap footer-grid">
		<div data-reveal>
			<h3 class="footer-brand"><img class="footer-brand__car" src="{car}" alt="" width="1200" height="598" loading="lazy">{e(NAME)}</h3>
			<p>{e(TAGLINE)}</p>
			<p>{e(ADDRESS)}</p>
		</div>
		<div data-reveal>
			<h3>Shop by model</h3>
			<ul>{model_links}<li><a href="/shop/">All parts</a></li></ul>
		</div>
		<div data-reveal>
			<h3>Contact</h3>
			<ul>
				<li><a href="tel:{TEL}">{e(PHONE)}</a></li>
				<li><a href="{e(wa)}" target="_blank" rel="noopener">WhatsApp</a></li>
				<li><a href="mailto:{EMAIL}">{EMAIL}</a></li>
				<li>{e(HOURS)}</li>
			</ul>
		</div>
	</div>
	<div class="footer-bottom">
		<div class="wrap">&copy; {date.today().year} {e(NAME)}. Mitsubishi and Pajero are trademarks of their respective owner. We are an independent parts supplier.</div>
	</div>
</footer>

<div class="wa-widget">
	<div class="wa-bubble" id="wa-bubble" role="dialog" aria-label="Chat on WhatsApp" hidden>
		<button class="wa-bubble__close" type="button" aria-label="Close">&times;</button>
		<div class="wa-bubble__head">{logo_mark('wa-bubble__logo')}<div><strong>{e(NAME)}</strong><span>Pajero parts team</span></div></div>
		<p class="wa-bubble__msg">Hi! Need a Pajero part? Send us a photo of the part or your VIN and we will send you the price.</p>
		<a class="btn btn--whatsapp" href="{e(wa)}" target="_blank" rel="noopener">{WA_ICON}Start chat</a>
	</div>
	<a class="wa-float" href="{e(wa)}" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">{WA_ICON}<span class="wa-float__badge" aria-hidden="true">1</span></a>
</div>

<nav class="mobile-bar" aria-label="Contact">
	<a class="mobile-bar__call" href="tel:{TEL}">{CALL_ICON}Call</a>
	<a class="mobile-bar__wa" href="{e(wa)}" target="_blank" rel="noopener">{WA_ICON}Order on WhatsApp</a>
</nav>

<script src="{asset('js/main.js')}" defer></script>
<script src="{asset('js/static.js')}" defer></script>
</body>
</html>
"""
    target = OUT / "404.html" if path == "/404.html" else OUT / path.strip("/") / "index.html"
    target.parent.mkdir(parents=True, exist_ok=True)
    target.write_text(html, "utf-8")


def product_card(p):
    fits = ", ".join(n.replace("Pajero ", "") for n in fit_names(p))
    search = " ".join([p["name"], p["sku"], p["category_name"], fits, p["short"]]).lower()
    return f"""<li class="product" data-search="{e(search)}" data-sku="{e(p['sku'].lower())}" data-url="{product_path(p)}">
	<a class="woocommerce-LoopProduct-link" href="{product_path(p)}">
		<img src="{asset('images/products/' + p['slug'] + '-600.jpg')}" alt="{e(p['alt'])}" width="600" height="600" loading="lazy">
		<h2 class="woocommerce-loop-product__title">{e(p['name'])}</h2>
	</a>
	<p class="loop-fits">Fits: {e(fits)}</p>
	<span class="price"><span class="price-ask">Price on WhatsApp</span></span>
	<a class="button loop-wa" href="{e(order_url(p))}" target="_blank" rel="noopener">{WA_ICON}Order on WhatsApp</a>
	<a class="button" href="{product_path(p)}">View details</a>
</li>"""


def product_grid(items, columns=3):
    cards = "\n".join(product_card(p) for p in items)
    return f'<div class="woocommerce"><ul class="products columns-{columns}">\n{cards}\n</ul></div>'


def listing_page(path, title, description, heading, items, meta="", crumbs=None, active=""):
    chips = [f'<a class="chip{"" if active else " is-active"}" href="/shop/">All models</a>']
    for m in MODELS:
        on = " is-active" if active == m["slug"] else ""
        chips.append(f'<a class="chip{on}" href="/pajero-model/{m["slug"]}/">{e(m["name"])}</a>')
    trail = '<a href="/">Home</a>' + "".join(f"&nbsp;/&nbsp;{c}" for c in (crumbs or [e(heading)]))
    meta_html = f'<p class="shop-header__meta">{e(meta)}</p>' if meta else ""
    body = f"""<main class="site-main site-main--shop">
	<div class="wrap">
		<nav class="woocommerce-breadcrumb static-crumbs" aria-label="Breadcrumb">{trail}</nav>
		<header class="shop-header" data-reveal>
			<h1>{e(heading)}</h1>
			{meta_html}
		</header>
		<nav class="model-filter" aria-label="Filter by Pajero model">{''.join(chips)}</nav>
		<p class="search-status" id="search-status" hidden></p>
		{product_grid(items, 3)}
		<div class="empty-state" id="search-empty" hidden>
			<h2>Can’t find it here?</h2>
			<p>We stock many more Pajero parts than we list. Send us a photo of the part or your VIN.</p>
			<a class="btn btn--whatsapp" href="{e(whatsapp_url('Hello Al Kaffa Parts, I am looking for a part for my Pajero: '))}" target="_blank" rel="noopener">{WA_ICON}Ask on WhatsApp</a>
		</div>
	</div>
</main>"""
    page(path, title, description, body)


def home_page():
    carrier = re.search(r'<svg class="hero__carrier".*?</svg>', FRONT_PAGE, re.S).group(0)
    road = re.search(r'<div class="hero__road".*?</svg>\s*</div>', FRONT_PAGE, re.S).group(0)
    car = asset("images/models/pajero-gen-4.webp")
    wa = whatsapp_url("Hello Al Kaffa Parts, I need a part for my Pajero. My VIN is: ")
    ticker = "<li>Genuine &amp; OEM quality</li><li>Pajero 3rd &amp; 4th Gen · 2000 – 2019</li><li>Fitment checked by VIN</li><li>Fast delivery</li><li>Cash on delivery</li>"
    popular = sorted(PRODUCTS, key=lambda p: p["name"])[:8]
    model_links = "".join(
        f'<li><a href="/pajero-model/{m["slug"]}/">Mitsubishi {e(m["name"])} parts<small>{e(m["description"])}</small></a></li>'
        for m in MODELS
    )
    body = f"""<section class="hero">
	<div class="hero__desert" aria-hidden="true"><span class="hero__mtn"></span><span class="hero__dune-far"></span><span class="hero__dune-near"></span></div>
	{road}
	<div class="hero__stage" aria-hidden="true">
		<div class="hero__car" style="--car:url('{car}')">
			<img src="{car}" alt="" width="1200" height="598" fetchpriority="high">
			<span class="hero__wheel hero__wheel--front"><img src="{asset('images/models/rim-front.webp')}" alt=""></span>
			{carrier}
			<span class="hero__wheel hero__wheel--far"><img src="{asset('images/models/rim-far.webp')}" alt=""></span>
			<span class="hero__wheel hero__wheel--rear"><img src="{asset('images/models/rim-rear.webp')}" alt=""></span>
		</div>
		<span class="hero__dust"></span><span class="hero__dust"></span><span class="hero__dust"></span>
	</div>
	<div class="wrap">
		<div class="hero__content">
			<p class="hero__eyebrow anim-up">Pajero parts specialists</p>
			<h1 class="anim-up" style="--d:.1s">{e(HERO_TITLE)}</h1>
			<ul class="hero__trust anim-up" style="--d:.4s">
				<li>Price before you pay</li>
				<li>Fitment checked by VIN</li>
				<li>Cash on delivery</li>
			</ul>
			<div class="hero__search anim-up" style="--d:.5s">{search_form()}</div>
		</div>
	</div>
</section>

<section class="usps"><div class="ticker"><ul>{ticker}</ul><ul aria-hidden="true">{ticker}</ul></div></section>

<section class="section new-arrivals">
	<div class="wrap">
		<div class="section__head" data-reveal>
			<div><p class="eyebrow">Just in</p><h2>New arrivals</h2></div>
			<a href="/shop/">All parts &rarr;</a>
		</div>
		<div data-reveal>{product_grid(PRODUCTS, 3)}</div>
	</div>
</section>

<section class="section wa-order">
	<div class="wrap wa-order__grid">
		<div data-reveal>
			<p class="eyebrow">The fastest way to order</p>
			<h2>Order on WhatsApp in 3 easy steps</h2>
			<ol class="wa-steps">
				<li><strong>Send a photo or your VIN</strong><span>Snap the old part, or share your chassis number and Pajero year.</span></li>
				<li><strong>Get the price &amp; fitment check</strong><span>We confirm the exact part for your Pajero and send you the price.</span></li>
				<li><strong>Delivered to your door</strong><span>Confirm on WhatsApp and pay cash on delivery.</span></li>
			</ol>
			<a class="btn btn--whatsapp btn--lg" href="{e(wa)}" target="_blank" rel="noopener">{WA_ICON}Chat now · {e(PHONE)}</a>
		</div>
		<div class="wa-phone" data-reveal style="--d:.15s" aria-hidden="true">
			<div class="wa-phone__head">{logo_mark('wa-phone__logo')}<div><strong>{e(NAME)}</strong><span>online</span></div></div>
			<div class="wa-phone__chat">
				<p class="wa-msg wa-msg--out" style="--i:0">Hi, do you have tail lights for my Pajero 2012?<time>10:02</time></p>
				<p class="wa-msg wa-msg--out wa-msg--img" style="--i:1"><img src="{asset('images/parts/lighting.svg')}" alt="" width="160" height="160" loading="lazy"><time>10:02</time></p>
				<p class="wa-msg wa-msg--typing" style="--i:2"><i></i><i></i><i></i></p>
				<p class="wa-msg wa-msg--in" style="--i:3">Hello! Yes, in stock. The clear chrome tail light set fits your 2012 Pajero. Sending you photos and the price now.<time>10:03</time></p>
				<p class="wa-msg wa-msg--out" style="--i:4">Great, please deliver to Dubai<time>10:04</time></p>
				<p class="wa-msg wa-msg--in" style="--i:5">Done! Your order is on the way. Pay cash on delivery.<time>10:04</time></p>
			</div>
		</div>
	</div>
</section>

<section class="section">
	<div class="wrap">
		<div class="section__head" data-reveal>
			<div><p class="eyebrow">Popular</p><h2>Best-selling Pajero parts</h2></div>
			<a href="/shop/">View all &rarr;</a>
		</div>
		<div data-reveal>{product_grid(popular, 4)}</div>
	</div>
</section>

<section class="section seo-intro">
	<div class="wrap seo-intro__grid" data-reveal>
		<div>
			<p class="eyebrow">Pajero parts specialists</p>
			<h2>Mitsubishi Pajero spare parts in the UAE</h2>
			<p>Al Kaffa Parts supplies new, OEM-quality and genuine used parts for the Mitsubishi Pajero 3rd generation (2000 – 2006, V60 / V70) and 4th generation (2006 – 2019, V80 / V90): brake parts, filters, engine and suspension parts, headlamps, bumpers, doors, fenders and other body parts.</p>
			<p>Not sure which part fits? Send us your VIN or a photo of the old part on WhatsApp. We check the fitment, send you the price, and deliver across the UAE, including Dubai, Sharjah, Abu Dhabi, Ajman and Al Ain. Pay cash on delivery.</p>
		</div>
		<ul class="seo-intro__links">
			{model_links}
			<li><a href="/product-category/body-parts/">Pajero body parts, new &amp; used<small>Bumpers, bonnet, doors, fenders, mirrors</small></a></li>
		</ul>
	</div>
</section>

<section class="stats">
	<div class="wrap">
		<div class="stats__item" data-reveal><strong data-count="20">0</strong><span>Years of Pajero covered</span></div>
		<div class="stats__item" data-reveal style="--d:.08s"><strong data-count="2" data-suffix="">0</strong><span>Generations supported</span></div>
		<div class="stats__item" data-reveal style="--d:.16s"><strong data-count="{len(PRODUCTS)}">0</strong><span>Parts in stock</span></div>
		<div class="stats__item" data-reveal style="--d:.24s"><strong data-count="100" data-suffix="%">0</strong><span>Fitment checked</span></div>
	</div>
</section>

<section class="cta-band">
	<div class="wrap" data-reveal>
		<div>
			<h2>Can’t find your part?</h2>
			<p>Send us your chassis (VIN) number and a photo — we will source the exact part for your Pajero.</p>
		</div>
		<a class="btn btn--whatsapp" href="{e(wa)}" target="_blank" rel="noopener">Chat on WhatsApp</a>
	</div>
</section>"""
    description = "Mitsubishi Pajero spare parts in UAE for the 3rd and 4th generation (2000 – 2019): new, OEM-quality and used body parts. Order on WhatsApp, cash on delivery."
    schema = {
        "@context": "https://schema.org",
        "@type": "AutoPartsStore",
        "name": NAME,
        "description": description,
        "url": SITE_URL + "/",
        "logo": SITE_URL + asset("images/logo-mark.svg"),
        "image": SITE_URL + car,
        "telephone": TEL,
        "email": EMAIL,
        "areaServed": {"@type": "Country", "name": "United Arab Emirates"},
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Al Sajaa",
            "addressLocality": "Sharjah",
            "addressCountry": "AE",
        },
        "knowsAbout": [
            "Mitsubishi Pajero spare parts",
            "Pajero used body parts",
            "Mitsubishi Pajero 3rd generation",
            "Mitsubishi Pajero 4th generation",
        ],
    }
    page("/", f"Mitsubishi Pajero Spare Parts in UAE – New & Used – {NAME}", description, body, schema=schema)


def product_page(p):
    fit = fitment_label(p)
    chips = "".join(
        f'<a class="chip" href="/pajero-model/{s}/">{e(MODEL_BY_SLUG[s]["name"])}</a>' for s in p["models"]
    )
    check = whatsapp_url(
        f"Hello, does this part fit my Pajero? {p['name']} ({SITE_URL}{product_path(p)}) My VIN is: "
    )
    related = [x for x in PRODUCTS if x["category"] == p["category"] and x["sku"] != p["sku"]][:3]
    if len(related) < 3:
        related += [x for x in PRODUCTS if x["sku"] != p["sku"] and x not in related][: 3 - len(related)]
    image = asset("images/products/" + p["slug"] + ".jpg")
    body = f"""<main class="site-main site-main--shop">
	<div class="wrap">
		<nav class="woocommerce-breadcrumb static-crumbs" aria-label="Breadcrumb"><a href="/">Home</a>&nbsp;/&nbsp;<a href="/product-category/{p['category']}/">{e(p['category_name'])}</a>&nbsp;/&nbsp;{e(p['name'])}</nav>
		<article class="static-product">
			<div class="static-product__image"><img src="{image}" alt="{e(p['alt'])}" width="1000" height="1000" fetchpriority="high"></div>
			<div class="static-product__summary">
				<h1 class="product_title">{e(p['name'])}</h1>
				<p class="price"><span class="price-ask">Price on WhatsApp</span></p>
				<div class="wa-order-box">
					<a class="btn btn--whatsapp btn--lg" href="{e(order_url(p))}" target="_blank" rel="noopener">{WA_ICON}Order on WhatsApp</a>
					<span>Fastest way to order · We confirm price &amp; fitment · Cash on delivery</span>
				</div>
				<p class="static-product__short">{e(p['short'])}</p>
				<div class="fitment-box">
					<div class="fitment-box__row"><span class="fitment-box__label">Fits</span><span class="chips">{chips}</span></div>
					<div class="fitment-box__row"><span class="fitment-box__label">Part no.</span><code>{e(p['sku'])}</code></div>
					<p class="fitment-box__note">Unsure if it fits? Send us your VIN and we will confirm before you buy.</p>
					<a class="btn btn--ghost" href="{e(check)}" target="_blank" rel="noopener">Check fitment on WhatsApp</a>
				</div>
			</div>
		</article>
		<section class="static-product__description entry-content">
			<h2>Description</h2>
			{p['description']}
		</section>
		<section class="static-product__related">
			<h2>Related parts</h2>
			{product_grid(related, 3)}
		</section>
	</div>
</main>"""
    schema = {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": p["name"],
        "sku": p["sku"],
        "description": p["short"],
        "image": SITE_URL + image,
        "url": SITE_URL + product_path(p),
    }
    page(
        product_path(p),
        f"{p['name']} for Mitsubishi Pajero {fit} – {NAME}",
        p["short"],
        body,
        image=SITE_URL + image,
        og_type="product",
        schema=schema,
    )


def contact_page():
    maps = "https://www.google.com/maps/search/?api=1&query=" + quote(ADDRESS)
    wa = whatsapp_url("Hello Al Kaffa Parts, I need a part for my Pajero.")
    body = f"""<main class="site-main">
	<div class="wrap">
		<article>
			<header class="page-header" data-reveal><h1>Contact</h1></header>
			<div class="entry-content">
				<div class="contact-card">
					<p><strong>Phone</strong><a href="tel:{TEL}">{e(PHONE)}</a></p>
					<p><strong>WhatsApp</strong><a href="{e(wa)}" target="_blank" rel="noopener">{TEL}</a></p>
					<p><strong>Email</strong><a href="mailto:{EMAIL}">{EMAIL}</a></p>
					<p><strong>Garage address</strong><a href="{e(maps)}" target="_blank" rel="noopener">{e(ADDRESS)}</a></p>
					<p><strong>Opening hours</strong><span>{e(HOURS)}</span></p>
					<p class="contact-card__tip">Not sure which part fits? Send us your chassis (VIN) number and we will find the exact part for your Pajero.</p>
				</div>
			</div>
		</article>
	</div>
</main>"""
    page("/contact/", f"Contact – {NAME}", f"Contact {NAME} in Al Sajaa, Sharjah: {PHONE} on phone and WhatsApp. Mitsubishi Pajero spare parts across the UAE.", body)


def not_found_page():
    body = f"""<main class="site-main">
	<div class="wrap empty-state">
		<h1>Page not found</h1>
		<p>Search for your part, or ask us on WhatsApp.</p>
		{search_form()}
		<p style="margin-top:20px"><a class="btn btn--whatsapp" href="{e(whatsapp_url('Hello Al Kaffa Parts, I need a part for my Pajero.'))}" target="_blank" rel="noopener">{WA_ICON}Ask on WhatsApp</a></p>
	</div>
</main>"""
    page("/404.html", f"Page not found – {NAME}", "This page could not be found.", body, noindex=True)


def copy_assets():
    """Copy assets to site/assets and record a content hash for each one."""
    files = {
        "css/style.css": (THEME / "style.css").read_text("utf-8") + "\n" + (HERE / "static.css").read_text("utf-8"),
        "js/main.js": THEME / "assets/js/main.js",
        "js/static.js": HERE / "static.js",
        "images/logo-mark.svg": THEME / "assets/images/logo-mark.svg",
        "images/parts/lighting.svg": THEME / "assets/images/parts/lighting.svg",
    }
    for name in ("pajero-gen-4", "rim-front", "rim-rear", "rim-far"):
        files[f"images/models/{name}.webp"] = THEME / f"assets/images/models/{name}.webp"
    for image in sorted((HERE / "images").glob("*.jpg")):
        files["images/products/" + image.name] = image
    for rel, src in files.items():
        data = src.encode("utf-8") if isinstance(src, str) else src.read_bytes()
        target = OUT / "assets" / rel
        target.parent.mkdir(parents=True, exist_ok=True)
        target.write_bytes(data)
        asset_versions[rel] = hashlib.md5(data).hexdigest()[:10]


def site_files(paths):
    today = date.today().isoformat()
    urls = "\n".join(f"  <url><loc>{e(SITE_URL + p)}</loc><lastmod>{today}</lastmod></url>" for p in paths)
    (OUT / "sitemap.xml").write_text(
        f'<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n{urls}\n</urlset>\n',
        "utf-8",
    )
    (OUT / "robots.txt").write_text(f"User-agent: *\nAllow: /\nSitemap: {SITE_URL}/sitemap.xml\n", "utf-8")
    (OUT / "_headers").write_text(
        "/assets/*\n  Cache-Control: public, max-age=31536000, immutable\n", "utf-8"
    )


def main():
    if OUT.is_dir():
        shutil.rmtree(OUT)
    OUT.mkdir()
    copy_assets()

    home_page()
    listing_page(
        "/shop/",
        f"Shop Mitsubishi Pajero Parts Online – UAE – {NAME}",
        "Shop Mitsubishi Pajero spare parts online in UAE: brakes, engine, suspension, lights and used body parts for the 3rd and 4th generation.",
        "All Pajero parts",
        PRODUCTS,
    )
    paths = ["/", "/shop/", "/contact/"]
    for m in MODELS:
        path = f"/pajero-model/{m['slug']}/"
        listing_page(
            path,
            f"Mitsubishi {m['name']} Spare Parts UAE ({m['description']}) – {NAME}",
            f"Spare parts for the Mitsubishi {m['name']} ({m['description']}) in UAE: engine, suspension, lights, body parts and more. Order on WhatsApp, cash on delivery.",
            m["name"],
            [p for p in PRODUCTS if m["slug"] in p["models"]],
            meta=m["description"],
            active=m["slug"],
        )
        paths.append(path)
    categories = {}
    for p in PRODUCTS:
        categories.setdefault(p["category"], p["category_name"])
    for slug, name in categories.items():
        path = f"/product-category/{slug}/"
        listing_page(
            path,
            f"Pajero {name} – Mitsubishi Pajero Parts UAE – {NAME}",
            f"{name} for Mitsubishi Pajero 3rd and 4th generation in UAE. Fitment checked by VIN. Order on WhatsApp, cash on delivery.",
            name,
            [p for p in PRODUCTS if p["category"] == slug],
        )
        paths.append(path)
    for p in PRODUCTS:
        product_page(p)
        paths.append(product_path(p))
    contact_page()
    not_found_page()
    site_files(paths)
    print(f"Built {len(paths) + 1} pages and {len(asset_versions)} assets in {OUT}")


if __name__ == "__main__":
    main()
