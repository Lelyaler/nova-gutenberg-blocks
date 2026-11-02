# Nova Gutenberg Blocks

Next-generation interactive Gutenberg blocks suite for WordPress (Block API v3, Dynamic SSR, Schema.org JSON-LD).

A modular WordPress Gutenberg blocks suite designed for fintech, SaaS, and content platforms. Built following official WordPress Core development guidelines, leveraging modern Block API v3 (`block.json`), dynamic Server-Side Rendering (`render.php`) for minimal layout shifts, and progressive client-side hydration in vanilla JavaScript without external dependencies.

---

## Architectural Highlights

- **Block API v3 Standard**: Each block is encapsulated with its own `block.json` declaring metadata, attributes, controls, and dependencies.
- **Server-Side Dynamic Rendering (`render.php`)**: Pages render complete semantic HTML on initial server load for optimal Core Web Vitals (LCP, CLS) and search engine crawlability.
- **Progressive Client-Side Hydration**: Lightweight vanilla JavaScript (~3.8 KB) attaches event listeners to sliders, calculation engines, and disclosure accordions without framework overhead.
- **Automated Schema.org JSON-LD Generation**: The FAQ block automatically constructs and enqueues Google-compliant `FAQPage` microdata into `wp_footer` for Rich Snippets eligibility.
- **Native Block Editor Controls**: Gutenberg editor integration featuring `InspectorControls`, `PanelBody`, `RangeControl`, `SelectControl`, `ColorPalette`, and live inline `RichText` editing.
- **Custom Block Category**: Automatically registers a dedicated **Nova Interactive Blocks** section in the Gutenberg block inserter.

---

## Included Blocks

### 1. Yield & ROI Calculator (`nova-blocks/yield-calculator`)
- **Real-Time Compounding Engine**: Computes interest formulas dynamically based on deposit amount, APY rate, duration (3 to 24 months), and compounding frequency (daily, monthly, annual).
- **Interactive Range Slider**: Custom range track with dynamic fill interpolation and quick-preset deposit chips ($2,500, $10,000, $25,000, $50,000).
- **SVG Growth Metric**: Visual progress meter comparing portfolio growth against baseline deposit.
- **Inspector Customization**: Configurable currency symbols, APY ranges, accent colors, and custom Call to Action (CTA) buttons.

### 2. Pricing & Tier Matrix (`nova-blocks/pricing-matrix`)
- **Billing Switcher**: Interactive animated toggle with configurable annual discount badge.
- **Feature Comparison Cards**: Responsive grid layout with highlighted popular card, custom badges, and SVG checkmarks.
- **Screen-Reader Support**: Compliant with WCAG 2.1 AA keyboard navigation and ARIA landmarks.

### 3. SEO FAQ Accordion & Schema (`nova-blocks/faq-accordion`)
- **Accessible Accordion Pattern**: WAI-ARIA compliant disclosure buttons (`aria-expanded`, `aria-controls`, `role="region"`) with smooth CSS height interpolation.
- **Dual Mode**: Supports single-item accordion or multi-open configuration via block settings.
- **Automated Schema.org FAQPage Rich Snippet**: Injects structured JSON-LD into the footer for search engine indexing.

---

## Installation & Usage

1. Copy the plugin directory into your WordPress plugins directory:
   ```bash
   git clone https://github.com/Lelyaler/nova-gutenberg-blocks.git wp-content/plugins/nova-gutenberg-blocks
   ```
2. Activate the plugin via **WordPress Admin > Plugins > Installed Plugins > Nova Gutenberg Blocks**.
3. Open any Page or Post in the WordPress Block Editor (Gutenberg).
4. Click the `+` inserter and select blocks from the **Nova Interactive Blocks** category.
5. Configure block parameters via the sidebar Inspector.

---

## Standards & Quality

- **PHP 8.2+ Strict Typing**: Built with object-oriented PHP, typed properties, strict sanitization (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`), and nonce checks.
- **Zero jQuery**: The frontend scripts use modern vanilla JavaScript with no third-party runtime dependencies.
- **Performance**: Semantic markup optimized for Core Web Vitals and accessible screen-reader navigation.
