=== MonkeyDesign Product Carousel for Elementor ===
Contributors: monkeydesign2026, freemius
Tags:              woocommerce, elementor, product carousel, woocommerce carousel, product slider
Requires at least: 6.0
Tested up to:      6.8
Requires PHP:      7.4
Stable tag:        1.0.5
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

WooCommerce product carousel and slider widget for Elementor. Show latest, sale, featured or best-selling products — flexible card builder, no jQuery.

== Description ==

**MonkeyDesign Product Carousel** adds a fully featured WooCommerce product carousel and slider widget to Elementor. Drop it on any page, pick your product source, style the cards — done.

Looking for a **WooCommerce carousel** or **product slider for Elementor**? This widget gives you both, with a powerful query engine that goes beyond simple product lists.

The main value is not the sliding itself (Swiper handles that), but the **product query engine** and the **card builder**: control where products come from and exactly what each card shows.

= Key free features =

**Product sources**
* Latest products
* Featured products
* On sale
* Best sellers (cached for performance)
* By category or tag
* Manual product selection (comma-separated IDs)
* Related products

**Layout**
* Unlimited columns — set independently for desktop, tablet, and mobile
* Two skins: **Classic** (bordered card) and **Minimal** (frameless)
* Configurable gap between slides
* Center mode (active slide centered, adjacent slides partially visible)

**Card builder — every element independently toggleable**
* Product image (eager LCP on first slide, lazy on the rest)
* Title, price, star rating
* Sale / featured / out-of-stock badge
* Add to cart button
* Short description, stock status

**Navigation & autoplay**
* Arrow buttons and pagination dots
* Touch drag, mouse drag, keyboard navigation
* Autoplay with configurable delay, pause on hover, infinite loop

**Performance**
* Swiper.js (not jQuery-dependent Slick)
* No jQuery
* CSS and JS load only on pages that contain the widget
* First slide image gets `loading="eager"` and `fetchpriority="high"` — improves LCP

**Accessibility**
* Screen reader labels on arrows via Swiper's a11y module
* Full keyboard navigation
* Visible focus styles on all interactive elements
* Arrows are real `<button>` elements with `aria-label`
* Respects `prefers-reduced-motion` (disables autoplay and transition animations)

= PRO features =

* **Overlay skin** — image fills the card, content slides up on hover
* **Quick view modal** — open product details without leaving the page
* **AJAX add to cart** — spinner, success state, and toast notification on add to cart
* **Wishlist button** — let customers save products for later
* **Sale countdown timer** — display a live countdown on sale products
* **Navigation style controls** — custom arrow icon, color, size, background, and shadow; dot color and active state color
* **Progress bar** — animated progress bar below the carousel
* **Fraction display** — current / total slide counter
* **Scrollbar** — draggable scrollbar navigation
* **Thumbnail navigation** — row of thumbnail slides below the main carousel
* **Slider sync** — link two carousels on the same page to scroll together

= Template override =

Developers can override the card template from their theme:

1. Create `your-theme/product-carousel/card.php`
2. Copy the contents of `product-carousel/templates/card.php`
3. Modify as needed

The rendered card HTML is also filterable: `apply_filters( 'mdpc_card_html', $html, $product, $settings )`.

== Installation ==

1. Upload the `monkeydesign-product-carousel` folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** menu in WordPress
3. Open any page in Elementor, find the **🐒 MonkeyDesign** widget section, and drag **Product Carousel** onto the canvas

**Requirements:** WooCommerce 7.0+ and Elementor 3.5+ must be installed and active.

== Frequently Asked Questions ==

= Does this work with Elementor Free (without Elementor Pro)? =

Yes. The plugin only uses Elementor's public widget API, which is part of the free version.

= Does it require WooCommerce? =

Yes. WooCommerce must be active — the plugin uses its product data and query functions.

= Will it slow down my site? =

No. Swiper.js and the carousel CSS only load on pages where you actually place the widget. No jQuery. No CDN requests — all assets are bundled with the plugin.

= Can I override the card template from my theme? =

Yes. Copy `monkeydesign-product-carousel/templates/card.php` to `your-theme/product-carousel/card.php` and modify it freely.

= How does the Best Sellers source work? =

It queries the `total_sales` product meta and caches the result in a transient for 1 hour by default. You can change the cache duration with the `mdpc_best_sellers_cache_seconds` filter.

= How do I control the product query? =

Each widget instance has its own source, limit, orderby, and order settings. You can also filter the WP_Query arguments programmatically with the `mdpc_query_args` filter.

= Is the plugin accessible? =

Accessibility is treated as a baseline free feature — it is never locked behind PRO. The widget uses Swiper's built-in a11y module, real `<button>` elements for navigation arrows, keyboard navigation, visible focus styles, and honours `prefers-reduced-motion`.

= Where can I get support? =

Use the WordPress.org support forum for the free version. PRO licence holders have priority email support via the MonkeyDesign dashboard.

== Screenshots ==

1. The Product Carousel widget on a page — Classic skin, 3 columns desktop layout.
2. The Elementor panel showing the 🐒 MonkeyDesign section with the Product Carousel widget.
3. Query controls — product source selector with category, tag, and manual selection options.
4. Card element toggles — enable or disable image, title, price, rating, badge, and add to cart independently.
5. Autoplay controls — delay, pause on hover, infinite loop, and transition speed settings.
6. Minimal skin variant — frameless cards with outline add-to-cart button.
7. The plugin Settings page — system status showing WooCommerce and Elementor versions.

== Changelog ==

= 1.0.5 =
* New: Quick view button text is now editable per widget.
* New: "Added to cart" confirmation message text is now editable.
* New: confirmation message position is now configurable — under the widget, or anchored to a corner/edge of the page with a custom pixel offset (7 positions total).
* New: translations added — German, Spanish, French, Hungarian, Polish, Italian, Portuguese (Brazil), Dutch.
* Improved: PRO-only code is now excluded from the free package at the build level for stronger separation between plans.

= 1.0.2 =
* Readme: removed features not yet available in this version.
* Card renderer: theme template override now works by copying card.php to your-theme/product-carousel/card.php.

= 1.0.1 =
* Freemius SDK integration improved: licence activation, account and upgrade flow.

= 1.0.0 =
* Initial release.
* Query engine: latest, featured, on sale, best sellers (cached), by category/tag, manual, related products.
* Card builder with 8 independently toggleable elements.
* Two free skins: Classic and Minimal.
* Responsive column control for desktop, tablet, and mobile.
* Arrows, dots, touch/mouse/keyboard navigation.
* Autoplay with pause on hover, loop, and configurable speed.
* LCP optimisation: first slide image gets `loading="eager"` and `fetchpriority="high"`.
* Conditional asset loading — CSS/JS only on pages with the widget.
* `prefers-reduced-motion` support in both CSS and JS.
* Template override support from theme.
* `mdpc_card_html` and `mdpc_query_args` developer filters.
* Freemius SDK integration for PRO upgrade flow and licence management.

== Upgrade Notice ==

= 1.0.5 =
New: editable Quick View & cart confirmation text, 7 configurable toast positions, and 8 new languages (DE, ES, FR, HU, PL, IT, PT-BR, NL).

= 1.0.0 =
Initial release — no upgrade needed.
