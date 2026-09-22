---
name: Diskominfo Kota Kendari
description: Portal resmi Dinas Komunikasi dan Informatika Kota Kendari, berpola beranda komdigi.go.id dengan biru instansi dominan.
colors:
  biru-50: "#f2f7fd"
  biru-100: "#e2edfa"
  biru-200: "#c0d8f4"
  biru-300: "#8bb9ea"
  biru-400: "#4f95dc"
  biru-500: "#2777c8"
  biru-600: "#0b4ea2"
  biru-700: "#0d478e"
  biru-800: "#0f3c75"
  biru-900: "#0b2f5c"
  biru-950: "#071f3d"
  langit: "#eef4fb"
  toska: "#0d7f77"
  nila: "#2f3f9e"
  darurat: "#d62d20"
  darurat-deep: "#b8241a"
  teks: "#1c2536"
  teks-lembut: "#4a5568"
  garis: "#dbe3ef"
  putih: "#ffffff"
typography:
  display:
    fontFamily: "IBM Plex Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "clamp(2rem, 5vw, 3.5rem)"
    fontWeight: 700
    lineHeight: 1.12
    letterSpacing: "-0.025em"
  headline:
    fontFamily: "IBM Plex Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "clamp(1.5rem, 3.5vw, 2.75rem)"
    fontWeight: 700
    lineHeight: 1.25
  section-title:
    fontFamily: "IBM Plex Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "1.25rem"
    fontWeight: 700
    lineHeight: 1.4
    letterSpacing: "0.025em"
  title:
    fontFamily: "IBM Plex Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "1rem"
    fontWeight: 600
    lineHeight: 1.375
  card-label:
    fontFamily: "IBM Plex Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "0.875rem"
    fontWeight: 700
    lineHeight: 1.375
    letterSpacing: "0.025em"
  body:
    fontFamily: "IBM Plex Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.625
  nav:
    fontFamily: "IBM Plex Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "13px"
    fontWeight: 600
    letterSpacing: "0.025em"
  stat:
    fontFamily: "IBM Plex Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "clamp(3.75rem, 6vw, 4.5rem)"
    fontWeight: 700
    lineHeight: 1
    letterSpacing: "-0.025em"
    fontFeature: "tnum"
  meta:
    fontFamily: "IBM Plex Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "0.75rem"
    fontWeight: 400
    lineHeight: 1.33
rounded:
  base: "4px"
spacing:
  gap-tight: "12px"
  gap: "16px"
  card: "20px"
  panel: "32px"
  section: "56px"
  section-lg: "80px"
  gutter: "16px"
  gutter-lg: "32px"
  container: "1280px"
components:
  button-primary:
    backgroundColor: "{colors.biru-600}"
    textColor: "{colors.putih}"
    rounded: "{rounded.base}"
    padding: "12px 20px"
  button-primary-hover:
    backgroundColor: "{colors.biru-700}"
  button-on-blue:
    backgroundColor: "{colors.putih}"
    textColor: "{colors.biru-800}"
    rounded: "{rounded.base}"
    padding: "12px 20px"
  button-on-blue-hover:
    backgroundColor: "{colors.biru-50}"
  button-emergency:
    backgroundColor: "{colors.darurat}"
    textColor: "{colors.putih}"
    rounded: "{rounded.base}"
    padding: "10px 14px"
  button-emergency-hover:
    backgroundColor: "{colors.darurat-deep}"
  arrow-square:
    textColor: "{colors.biru-600}"
    rounded: "{rounded.base}"
    size: "36px"
  input-search:
    backgroundColor: "{colors.putih}"
    textColor: "{colors.teks}"
    rounded: "{rounded.base}"
    height: "44px"
    padding: "0 48px 0 16px"
  nav-main:
    backgroundColor: "{colors.putih}"
    textColor: "{colors.biru-950}"
    typography: "{typography.nav}"
    height: "52px"
  card-news:
    backgroundColor: "{colors.putih}"
    textColor: "{colors.biru-950}"
    rounded: "{rounded.base}"
    padding: "16px"
  card-service:
    textColor: "{colors.putih}"
    rounded: "{rounded.base}"
    padding: "20px"
  panel-stat:
    backgroundColor: "{colors.toska}"
    textColor: "{colors.putih}"
    rounded: "{rounded.base}"
    padding: "32px"
  chip-topic:
    textColor: "{colors.putih}"
    rounded: "{rounded.base}"
    padding: "6px 12px"
---

# Design System: Diskominfo Kota Kendari

## Overview

**Creative North Star: "Portal Biru Instansi"**

The public site reads as a sibling of komdigi.go.id: an institutional portal where government blue carries the identity from the navy topbar to the footer, and white and pale-sky bands carry the reading. The owner pinned this world (blue-dominant theme, Komdigi homepage module order) after rejecting an earlier display-board concept; nothing from that concept survives. Authority comes from order and consistency, not ornament: a strict 1280px container, bold uppercase section headings, square 4px corners on everything, and one sans family.

Density is portal-grade. The homepage stacks full-width bands in Komdigi's order: blue news highlight carousel, gradient services panel, public document search split panel, the dark Transformasi Digital profile band, gallery with a teal statistics card, a horizontal news carousel on the sky band, then a two-column zone of topics and documents with a sidebar (Kepala Dinas greeting, Kendari Siaga 112), related links, footer. Komdigi's content is never copied; every module is filled only with real Diskominfo data from the API or the organisation's own static facts.

Komdigi's circles and pills are deliberately translated to 4px squares. Motion is quiet: a highlight title rising in on slide change, gentle card lifts on hover, all neutralised under reduced motion.

**Key Characteristics:**
- Blue-dominant: navy and institutional blue bands alternate with white and langit (#eef4fb) reading bands.
- Every corner is Tailwind `rounded` (4px); no pills, no circles.
- IBM Plex Sans only, self-hosted, weights 400 to 700.
- Uppercase bold labels for section headings, navigation and card titles.
- Square outlined arrow buttons as the recurring "go" affordance.
- Red appears only for the 112 emergency service.

## Colors

A single blue family from pale biru-50 to navy biru-950 does nearly all the work, with two supporting hues for specific panels and one reserved red.

### Primary
- **Biru Instansi** (biru-600): the institutional action blue. Primary buttons, search submit squares, active nav underline, link text, category labels, document file badges, selection highlight, focus ring.
- **Biru Tua** (biru-800): the logo row background, inner page title bands, the document search right panel, text on white buttons.
- **Biru Malam** (biru-900): footer, Kendari Siaga 112 panel, gradient starts.
- **Navy Topbar** (biru-950): topbar, highlight and Transformasi Digital band bases, footer copyright strip, heading text on white.
- **Biru Terang to Pucat** (biru-500, biru-400, biru-300, biru-200, biru-100, biru-50): hover lifts on blue, icon tints on navy (300), secondary text on navy (200), body text on blue (100), light hover fill (50).

### Secondary
- **Toska** (toska): the visitor statistics card only, Komdigi's number card translated. White text.
- **Nila** (nila): start of the "Pilih Topik Berita" gradient (nila to biru-700). Hover text of topic chips.

### Tertiary
- **Merah Darurat** (darurat, hover darurat-deep): the 112 emergency service only. Header "Hubungi 112" button, the 112 service icon square, the Kendari Siaga icon square.

### Neutral
- **Putih** (putih): page background, cards, the sticky nav bar, white buttons on blue.
- **Langit** (langit): pale-sky bands and panels (news carousel band, document search left panel, greeting header, gallery count tile, dropdown hover).
- **Teks** (teks): default body text.
- **Teks Lembut** (teks-lembut): secondary text, descriptions, metadata, placeholders.
- **Garis** (garis): card borders, dividers, nav bottom border.

### Named Rules
**The Blue Dominance Rule.** The site is blue first. New surfaces take their bands from the biru scale, langit and white; toska and nila appear only as the panel accents already assigned.

**The Red Is 112 Rule.** Merah Darurat marks the 112 emergency service and nothing else: no error decoration, no badges, no highlights.

**The Logo On Blue Rule.** The official logo (`web/public/img/kominfo-logo.webp`) has a white wordmark and must sit on biru-800 or darker. That is why the logo row is blue where Komdigi's header is white.

**The Gradient Legibility Rule.** Body text on a gradient panel must sit over biru-600 or darker stops (services panel biru-900 to biru-600, topic panel nila to biru-700). Never end a text-bearing gradient in biru-500 or lighter.

**The Default Cover Rule.** A record without an image (news without a cover, a gallery photo whose file is missing) shows `gambar-default.webp`: the official logo on the navy network and signal-ring motif, composed to survive 16:10, 4:3 and square crops. It is also the `onerror` fallback (`GAMBAR_DEFAULT` and `cadanganGambar` in `web/src/lib/api.ts`) and the admin news table's `defaultImageUrl`. Never fall back to an empty fill with an icon.

**The Light By Default Rule.** Both the public site and the Filament admin open in light mode regardless of the OS preference.
- **One shared choice.** Both read Filament's own localStorage key `theme` (`light`, `dark` or `system`). They share one origin behind Caddy, so a choice made in one follows into the other.
- **Public site.** The topbar "Mode gelap" button writes `light` or `dark`. The Base head script maps the stored value to `data-tema="gelap"` on `<html>` and re-applies it on a bfcache `pageshow`. The `dark:` variant is bound to that attribute, never to `prefers-color-scheme`.
- **Admin.** It uses `defaultThemeMode(ThemeMode::Light)` and keeps Filament's switcher. The login page button dispatches Filament's `theme-changed` event instead of writing storage itself.

**The Dark Mode Mapping Rule.** In dark mode:
- The neutral tokens flip in place: langit `#0a1d38`, teks `#dfe8f5`, teks-lembut `#9fb6d4`, garis `#1d3558`.
- The page background is malam `#05152b` and card surfaces are malam-kartu `#0c2344`.
- Headings on formerly white surfaces become white, biru-600 links become biru-300, and biru-50/100 fills become biru-900.
- Blue panels, the hero, the footer and white buttons sitting on blue do not change.
- The organisation chart (HTML, `struktur-organisasi.astro`) sits on malam-kartu; its biru-900 and biru-700 unit boxes stay, sub-unit boxes turn biru-900 with biru-100 text, connectors biru-700. The fallback image keeps its white box.
- The sticky nav swaps the navy-wordmark logo for the original white one.

## Typography

**Display Font:** IBM Plex Sans (with ui-sans-serif, system-ui, sans-serif)
**Body Font:** IBM Plex Sans
**Label/Mono Font:** IBM Plex Sans (tabular numerals for clocks, counts, stats)

**Character:** One humanist-technical sans, used the way Komdigi uses its sans: heavy and tight for headlines, uppercase and slightly tracked for section labels and navigation, plain and relaxed for reading.

### Hierarchy
- **Display** (700, 2rem mobile to 3rem at sm to 3.5rem at lg, line-height 1.12, tight tracking): highlight carousel news titles only.
- **Headline** (700, 1.5rem to 2.75rem): band-level statements such as "Transformasi Digital Pemerintah Kota Kendari", the document search titles, inner page titles (1.875rem to 2.25rem).
- **Section Title** (700, 1.125rem to 1.25rem, uppercase, tracking 0.025em, biru-950): homepage section headings ("KEGIATAN DAN INFORMASI PUBLIK"), sidebar headings, footer column headings at 14px white.
- **Title** (600, 1rem, line-height 1.375, biru-950): card headlines (news, documents), clamped to 2 or 3 lines.
- **Card Label** (700, 14px, uppercase, tracking 0.025em): service card and bidang titles on blue; gallery captions at 12px.
- **Body** (400, 1rem or 14px in cards, line-height 1.625): descriptions and prose, max about 60 to 62ch in bands.
- **Nav** (600, 13px, uppercase, tracking 0.025em): the sticky main navigation.
- **Stat** (700, 3.75rem to 4.5rem, line-height 1, tabular): the visitor total on the toska card.
- **Meta** (400, 12px, teks-lembut or biru-200 on blue): dates, download counts.

### Named Rules
**The One Family Rule.** IBM Plex Sans is the only typeface, served from `/fonts/` (latin and latin-ext woff2, preloaded, `font-display: swap`). No Google Fonts, no second family, no mono or serif.

**The Formal Copy Rule.** Public copy is formal Indonesian with no em dashes and no invented claims; ranges use a hyphen ("2025-2029").

## Layout

A single centered container (`wadah`: max 1280px, 16px gutters, 32px from lg) holds every band. Bands run full bleed; content does not.

Homepage module order is fixed to Komdigi's pattern: highlight carousel (min-height 26rem, 32rem at lg; title left, numbered slide squares right, a strip of three more news items plus "Semua berita" at the base) → services gradient panel (4 columns at lg, 2 at sm) → document search split panel (2fr/3fr) → Transformasi Digital navy band with four bidang → gallery grid (2fr) beside the toska stat card (1fr) → news carousel on langit (4 cards visible at lg, 2 at sm, 78% width snap cards on mobile) → main 2fr column (topic panel, latest documents in 3 columns at xl) beside a 1fr sidebar (Kepala Dinas greeting, Kendari Siaga 112) → related links row → footer.

Vertical rhythm: sections use 56px padding (80px at lg); dark bands 64px (96px at lg). Grid gaps are 12px to 16px for cards, 24px to 48px between columns. Panels pad 24px, 32px at sm, 40px at lg. Mobile collapses every grid to one column, horizontal strips become scroll-snap rows with hidden scrollbars (`tanpa-scrollbar`), and the documents list shows three items.

Inner pages (profil dinas, berita, dokumen, galeri, layanan) inherit header, footer, font, colors and radius. Their title band (JudulHalaman, also used by the news detail page with a slot for category and date) sits on the abstract circuit-trace background `latar-sirkuit.webp` (anchored right) under a navy left-to-right scrim, with breadcrumb above the title. Their content layouts predate the Komdigi pattern and have not been rebuilt; this is the current state, not a rule. Galeri has two routes, Foto (`/galeri`) and Video (`/galeri/video`), switched by a Foto/Video tab pair above the grid; videos are YouTube links managed in the admin panel, shown as thumbnail cards that swap to a youtube-nocookie embed on click.

## Elevation & Depth

Mostly flat, with depth carried by tonal bands (white, langit, blue, navy) and photo-backed dark bands with left-to-right navy gradient scrims. Shadows are soft, navy-tinted (rgba of biru-950) and pulled in with a negative spread so they read as a glow under the element, never a hard edge.

### Shadow Vocabulary
- **Panel lift** (`box-shadow: 0 24px 48px -28px rgba(7,31,61,0.7)`): the services gradient panel resting over white.
- **Raised card** (`kartu-timbul`: `0 1px 2px rgba(7,31,61,0.06), 0 10px 24px -12px rgba(7,31,61,0.28)` at rest; `0 2px 4px rgba(7,31,61,0.08), 0 20px 40px -18px rgba(7,31,61,0.42)` with a biru-200 border on hover): news, gallery photo, video and document cards. News cards add a 2px upward translate on hover.
- **Dropdown** (`box-shadow: 0 18px 40px -16px rgba(7,31,61,0.35)`): nav submenus.
- **Sticky bar** (`box-shadow: 0 6px 18px -14px rgba(7,31,61,0.45)`): the desktop nav; the mobile blue header uses `0 8px 24px -16px rgba(7,31,61,0.8)`.
- **Floating button** (`box-shadow: 0 12px 28px -10px rgba(7,31,61,0.6)`): back-to-top.

### Named Rules
**The Raised Card Rule.** Every listing card (news, gallery photo, video, document) uses `kartu-timbul`: white, garis border and a soft navy shadow at rest, so cards never dissolve into the white page. Do not hand-roll per-page card shadows; change the utility.

**The Motif Rule.** `motif-komdigi` (two faint white radial glows) goes only on a solid blue background (biru-800 or biru-900). Never combine it with a gradient background on the same element: both set `background-image` and the gradient disappears.

**The Dot Grid Rule.** The `kisi-titik` dot grid is reserved for the Transformasi Digital band only.

## Shapes

**The Four Pixel Rule.** Every corner is Tailwind `rounded` (4px): cards, panels, buttons, inputs, chips, icon squares, avatars, slide selectors, social buttons, prose images. Komdigi's circular icon holders, pill chips and round arrow buttons become 4px squares. No `rounded-lg`, `rounded-xl` or `rounded-full` anywhere.

Borders are 1px: garis on white, white at 15 to 40 percent opacity on blue. Icon holders are solid squares (48px services and bidang, 44px to 64px elsewhere). The active nav item is marked by a 3px biru-600 bar at the bottom edge. Icons are 24px outline SVG strokes (Ikon.astro), sized 14px to 40px.

## Components

### Buttons
Solid, square, confident.
- **Shape:** 4px corners.
- **Primary:** biru-600 fill, white text, semibold 14px; hover biru-700.
- **On blue:** white fill, biru-800 text ("Baca selengkapnya" on the highlight, "Hubungi 112" in the sidebar); hover biru-50; press nudges down 1px.
- **Emergency:** darurat fill, white, phone icon, "Hubungi 112" (shortens to "112" on mobile); hover darurat-deep. Only for 112.
- **Arrow square:** 28px to 40px outlined square with a right arrow, beside or below a label. On white: biru-300 border, fills biru-600 with white icon on hover. On blue: white/40 border, fills white on hover. This is the system's "go" signature.
- **Outline on blue:** white/25 border, transparent, hover white/10.

### Chips
- **Topic chip:** white/10 fill, white/25 border, 14px medium label with a small arrow; hover inverts to white with nila text.
- **Category tag (highlight):** biru-600 fill, 12px semibold white, hover biru-500. Only real category names.

### Cards / Containers
- **Corner Style:** 4px.
- **News card:** white, garis border, 16:10 image (`gambar-default.webp` when missing), 16px padding, clamped biru-950 title turning biru-600 on hover, meta row with category in biru-600 and calendar date.
- **Service card:** on the gradient panel, white/10 fill, white/15 border, 20px padding, icon square (white with biru-700 icon, or darurat for 112), uppercase label, arrow square at the base; hover lifts 4px and brightens.
- **Document card:** white, garis border, biru-600 extension badge, download count, title, category, clamped description, "Unduh dokumen" link; hover biru-300 border and card shadow.
- **Stat card:** toska fill, white, large tabular total with a divided list of counts.
- **Gallery tile:** 4:3, `gambar-default.webp` when missing, navy bottom scrim with uppercase caption; a langit count tile closes the grid.

### Inputs / Fields
- **Style:** white field, 44px to 48px tall, 4px corners, teks text, teks-lembut placeholder, with a 36px biru-600 submit square set inside the right edge.
- **Focus:** 2px biru-300 outline (on blue backgrounds).

### Navigation
- **Topbar:** biru-950, 36px tall, clock icon, live WITA time (Asia/Makassar, `HH.MM.SS WITA`, white semibold tabular) and a time-of-day greeting; phone and email on the right. Never a date.
- **Logo row:** biru-800, 72px (80px at lg), logo, site search (desktop), emergency button. Sticky on mobile with a menu toggle that opens a flat list with search and open submenus.
- **Main nav (desktop):** white sticky bar, 52px, uppercase 13px semibold biru-950 items; hover and active biru-600 with a 3px underline bar; submenus as white 256px dropdowns with langit hover (Profil Dinas, Layanan, Galeri with Foto and Video). Once the logo row has scrolled away (IntersectionObserver, no scroll listener) the bar stays white, its shadow deepens, and the logo slides in on the left using `kominfo-logo-gelap.webp`, a derived variant whose white wordmark is recolored navy so it reads on white. The original white-wordmark logo is used only on blue grounds.
- **Footer:** biru-900 with motif, logo, description, three uppercase link columns, address and contacts row, biru-950 copyright strip.

### Section Heading (signature)
Uppercase bold biru-950 title on the left, "Lihat lainnya" link with a 28px arrow square on the right, 24px (32px at lg) below. A light variant exists for blue bands.

### Highlight Carousel (signature)
Fixed abstract network background (`latar-jaringan.webp`: constellation nodes and flowing data lines) under a navy-to-transparent scrim, the same for every slide, display title with rising entrance (650ms, cubic-bezier(0.16, 1, 0.3, 1)), 40px numbered square selectors (active is white fill), 7s autoplay paused on hover or focus and disabled under reduced motion.

### Focus
Double ring on every focusable element: 2px biru-600 outline offset 2px over a 2px white box-shadow, visible on both white and navy.

### Admin Panel (Filament)
The admin follows this system too, without a Vite theme build.
- **Colours.** `AdminPanelProvider` passes the biru-* scale as `primary`. It passes a blue-tinted `gray` whose 900 and 950 steps equal malam-kartu and malam, so the admin's dark mode matches the site's.
- **Font.** IBM Plex Sans, served from Astro's `/fonts`.
- **Shape.** `resources/views/filament/admin/gaya.blade.php` is an unlayered `<style>` injected at `STYLES_AFTER`. It overrides only shape, because Filament's CSS is layered. It sets:
  - every radius to 4px and square avatars;
  - a biru-800 topbar carrying the official white logo, the WITA clock and a "Lihat situs" link;
  - a white sidebar with uppercase group labels and a 3px active bar;
  - a 3px eyebrow bar above page headings that have no breadcrumbs;
  - kartu-timbul shadows on sections, tables, stats and resource forms;
  - uppercase table headers;
  - stats with a 3px blue top bar.
- **Dashboard.** It opens with the `SambutanAdmin` band: the network background, a WITA greeting and shortcuts to create berita, dokumen, foto and video.
- **Clock script.** `filament.admin.skrip-jam` runs once at `BODY_END` on every panel page, including login.

## Do's and Don'ts

### Do:
- **Do** keep the Komdigi homepage module order and fill each module only with real Diskominfo data.
- **Do** use 4px `rounded` for every corner, translating any Komdigi circle or pill into a square.
- **Do** place the official logo on biru-800 or darker.
- **Do** show the topbar clock in WITA (Asia/Makassar) with a greeting, updating every second.
- **Do** keep body text on gradients over biru-600 or darker stops.
- **Do** show `gambar-default.webp` for any record without an image, never an empty fill with an icon.
- **Do** set `autocomplete="off"` on every search input.
- **Do** use abstract technology imagery, not literal photos, for dark image bands: `latar-jaringan.webp` for the homepage highlight, `latar-sirkuit.webp` for inner page title bands. Both are generated procedurally in the site palette (2560px) with provenance sidecars.
- **Do** use the uppercase section heading with the arrow-square "Lihat lainnya" link for homepage sections.

### Don't:
- **Don't** use `rounded-lg`, `rounded-xl`, `rounded-full` or any radius other than 4px.
- **Don't** load or add any typeface besides self-hosted IBM Plex Sans.
- **Don't** show a date in the topbar.
- **Don't** use red for anything but the 112 emergency service.
- **Don't** combine `motif-komdigi` with a gradient background on the same element.
- **Don't** use the `kisi-titik` dot grid outside the Transformasi Digital band.
- **Don't** write em dashes, marketing tone or invented claims (testimonials, achievements, SLA figures) in public copy.
- **Don't** put the white-wordmark logo on white or langit.
