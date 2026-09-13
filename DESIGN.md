---
name: Diskominfo Kota Kendari
description: Situs resmi Dinas Komunikasi dan Informatika Kota Kendari, dibangun sebagai aula pelayanan dengan papan display dan loket berwarna zona.
colors:
  aula-wall: "#f3f5f9"
  papan-navy: "#0d1526"
  papan-seam: "#050a14"
  bezel-steel: "#25324d"
  led-amber: "#ffb000"
  ink: "#0f1a2e"
  body-text: "#1c2536"
  muted-text: "#4a5568"
  rule-line: "#d9dfea"
  surface-white: "#ffffff"
  office-blue: "#0b4ea2"
  office-blue-deep: "#0f3c75"
  office-blue-dark: "#0b2f5c"
  office-blue-bright: "#2777c8"
  blue-soft-rule: "#8bb9ea"
  blue-mist: "#c0d8f4"
  blue-pale: "#e2edfa"
  blue-wash: "#f2f7fd"
  category-blue: "#0d478e"
  zone-a: "#0b4ea2"
  zone-b: "#0a7e6e"
  zone-c: "#c0266d"
  zone-d: "#d62d20"
  zone-a-lit: "#6aa6f2"
  zone-b-lit: "#3cc9ae"
  zone-c-lit: "#f36aa6"
  zone-d-lit: "#ff6a5c"
  emergency-press: "#b8241a"
typography:
  display:
    fontFamily: "IBM Plex Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "2.25rem (sm 3rem, xl 3.75rem)"
    fontWeight: 600
    lineHeight: 1.02
    letterSpacing: "-0.025em"
    fontVariation: "'wdth' 85"
  headline:
    fontFamily: "IBM Plex Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "1.875rem (lg 2.5rem)"
    fontWeight: 600
    lineHeight: 1.25
    letterSpacing: "-0.025em"
    fontVariation: "'wdth' 85"
  title:
    fontFamily: "IBM Plex Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "1.5rem"
    fontWeight: 600
    lineHeight: 1.33
    fontVariation: "'wdth' 85"
  title-small:
    fontFamily: "IBM Plex Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "1.125rem"
    fontWeight: 600
    lineHeight: 1.375
  body:
    fontFamily: "IBM Plex Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "1rem (lg 1.125rem for lead text)"
    fontWeight: 400
    lineHeight: 1.625
  label:
    fontFamily: "IBM Plex Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "0.875rem"
    fontWeight: 600
    lineHeight: 1.43
  caption:
    fontFamily: "IBM Plex Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "0.75rem"
    fontWeight: 400
    lineHeight: 1.33
  led-display:
    fontFamily: "IBM Plex Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "7rem to 14rem"
    fontWeight: 700
    lineHeight: 0.85
    letterSpacing: "-0.025em"
    fontFeature: "tnum"
    fontVariation: "'wdth' 85"
  led-small:
    fontFamily: "IBM Plex Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "1.125rem to 1.75rem"
    fontWeight: 700
    lineHeight: 1
    fontFeature: "tnum"
    fontVariation: "'wdth' 85"
rounded:
  sm: "4px"
spacing:
  gutter-mobile: "16px"
  gutter-desktop: "32px"
  card-pad: "20px"
  panel-pad-mobile: "24px"
  panel-pad-desktop: "40px"
  heading-gap: "40px"
  section-mobile: "64px"
  section-desktop: "96px"
components:
  button-led:
    backgroundColor: "{colors.led-amber}"
    textColor: "{colors.papan-navy}"
    typography: "{typography.label}"
    rounded: "{rounded.sm}"
    padding: "12px 20px"
  button-ghost-on-papan:
    backgroundColor: "transparent"
    textColor: "{colors.surface-white}"
    typography: "{typography.label}"
    rounded: "{rounded.sm}"
    padding: "12px 20px"
  button-emergency:
    backgroundColor: "{colors.zone-d}"
    textColor: "{colors.surface-white}"
    typography: "{typography.label}"
    rounded: "{rounded.sm}"
    padding: "12px 20px"
  button-emergency-hover:
    backgroundColor: "{colors.emergency-press}"
  button-zone:
    backgroundColor: "{colors.zone-b}"
    textColor: "{colors.surface-white}"
    typography: "{typography.label}"
    rounded: "{rounded.sm}"
    padding: "12px 20px"
  chip-category:
    backgroundColor: "{colors.blue-wash}"
    textColor: "{colors.category-blue}"
    typography: "{typography.caption}"
    rounded: "{rounded.sm}"
    padding: "4px 8px"
  card-news:
    backgroundColor: "{colors.surface-white}"
    textColor: "{colors.ink}"
    rounded: "{rounded.sm}"
    padding: "20px"
  nav-bar:
    backgroundColor: "{colors.papan-navy}"
    textColor: "{colors.blue-pale}"
    height: "68px"
  topbar:
    backgroundColor: "{colors.papan-navy}"
    textColor: "{colors.blue-mist}"
    typography: "{typography.caption}"
    height: "36px"
  loket-tile:
    backgroundColor: "{colors.papan-navy}"
    textColor: "{colors.surface-white}"
    padding: "24px"
    height: "144px"
  section-sign:
    backgroundColor: "{colors.papan-navy}"
    textColor: "{colors.led-amber}"
    rounded: "{rounded.sm}"
    size: "44px"
---

# Design System: Diskominfo Kota Kendari

## Overview

**Creative North Star: "Aula Loket Pelayanan"**

The site is a public service hall. The light hall wall (aula) is the page ground; set into it are navy display boards (papan) framed by a steel bezel, lit with amber LED numerals, and four service counters (loket) each wearing its zone color. A visitor reads the hall the way they would read a real one: the board names the office, calls the counters, and runs the news ticker along its base; the wall carries the readable material (requirements, news, welcome, divisions, gallery) in white panels with thin rules.

Authority comes from precision, not ornament. The palette is restrained on the wall and saturated only where a counter or a board speaks. Blue stays the base identity: office blue is zone A, the link color, and the focus ring. Density is moderate; sections breathe at 64px on phones and 96px on desktop, and the board modules butt against each other with 1px dark seams rather than floating as separate cards.

Motion is board behavior: modules power on row by row on load, counter codes blink twice when called (hover or focus), the zone color rises up a counter tile, and the ticker scrolls and pauses on hover. All of it sits behind `prefers-reduced-motion: no-preference`.

**Key Characteristics:**
- Light hall wall with navy display boards set into it; boards are the only dark grounds.
- Amber LED numerals for everything that counts or tells time.
- Four zone colors (A blue, B teal, C magenta, 112 red) as the counter wayfinding system.
- One family, IBM Plex Sans; headings and board signage at 85% width.
- One corner radius, 4px, everywhere.
- Seamed modules (1px gaps over a darker ground) instead of floating cards.

## Colors

A blue-based institutional palette: neutral hall grounds, a deep navy board, one amber signal color, and four zone colors reserved for the service counters.

### Primary
- **Office Blue** (office-blue): the base identity color. Zone A counter, links and "Semua berita" style text links, category text, focus outline on the wall. Its deeper steps (office-blue-deep, office-blue-dark) are link hover and the darkest division stripe.
- **Papan Navy** (papan-navy): the display board ground. Topbar, nav, the hero board, the 112 board, news day tiles, gallery tiles, section-sign squares, footer, back-to-top button. Also the selected state of a counter tab.

### Secondary
- **LED Amber** (led-amber): the signal. LED numerals (clock, visitor counters, dates, step numbers on the board), the primary CTA on the board, the active nav underline, the ticker label, icons on navy, text selection, and the focus outline inside a board.

### Tertiary
- **Zone colors** (zone-a, zone-b, zone-c, zone-d): one per counter, assigned in `loket` data by zone key and applied as the `--zona` custom property so a single component serves all four. Solid zone colors fill surfaces (counter code squares, tab underline, rising fill, zone buttons, check icons on white). Zone D red is also the emergency action color everywhere 112 appears.
- **Lit zone variants** (zone-a-lit through zone-d-lit): the same zones brightened for text on the navy board (counter codes, step numerals, the 112 display figure).

### Neutral
- **Aula Wall** (aula-wall): page background; hover wash in menus.
- **Surface White** (surface-white): reading panels, news cards, division cells, tabs at rest, dropdown menus.
- **Ink** (ink): headings on the wall.
- **Body Text** (body-text): running text and list items.
- **Muted Text** (muted-text): summaries, descriptions, meta rows.
- **Rule Line** (rule-line): every 1px border and seam on the wall.
- **Bezel Steel** (bezel-steel): board frame, borders and dividers inside navy grounds, ticker separators.
- **Papan Seam** (papan-seam): the darker ground behind board modules that shows through 1px gaps as module joints.
- **Blue Mist / Blue Pale** (blue-mist, blue-pale): secondary and body text on navy.
- **Blue Wash** (blue-wash): category chip ground.

### Named Rules
**The Blue Base Rule.** Blue is the identity. New surfaces start from the wall, navy, and office blue; zone teal, magenta, and red never replace blue as a page's main color.

**The Zone Belongs To The Counter Rule.** Zone colors mark the four services and nothing else (the footer's four-color strip is the counters' own signature). Do not use teal or magenta as general accents, and use zone D red only for 112 and emergency action.

**The Amber Is Signal Rule.** Amber appears on navy, as numerals, the primary board CTA, icons, and the active marker. It never fills large wall areas and is never body text on white.

## Typography

**Display Font:** IBM Plex Sans at 85% width (with ui-sans-serif, system-ui)
**Body Font:** IBM Plex Sans at 100% width
**Label/Mono Font:** none; numerals use IBM Plex Sans with tabular figures

**Character:** One humanist grotesque in two widths. The condensed cut reads as painted hall signage and board lettering; the normal width carries reading text in a formal, even voice.

### Hierarchy
- **Display** (600, 2.25rem rising to 3.75rem, line-height 1.02, 85% width): the office name on the hero board only, balanced, max about 16ch.
- **Headline** (600, 1.875rem to 2.5rem, leading tight, 85% width): section headings via the section sign, and the 112 board heading. Lead news headline uses 1.875rem to 2.25rem.
- **Title** (600, 1.5rem, 85% width): panel headings ("Persyaratan", "Tahapan"); division names at 1.25rem.
- **Title Small** (600, 1.125rem, normal width): news card titles, list titles.
- **Body** (400, 1rem, line-height 1.625; lead text 1.125rem on desktop): measures held at 46ch to 68ch.
- **Label** (600, 0.875rem): buttons, text links, tab names.
- **Caption** (400, 0.75rem): topbar, meta rows, legal line.
- **LED Display** (700, 7rem to 14rem, tabular, 85% width, amber or lit zone, dot mask): the lead news day, gallery days, kepala dinas initials, the 112 figure.
- **LED Small** (700, 1.125rem to 1.75rem, tabular, 85% width, solid amber): clock, visitor counts, ticker dates, news day tiles, step numbers.

### Named Rules
**The One Family Rule.** IBM Plex Sans is the only typeface. Display and signage use `font-stretch: 85%` of the same variable family (wdth axis 85 to 100); never add a second face, including a monospace for numerals.

**The Dot Mask Threshold Rule.** The LED dot mask (4px radial mask) is only for display-size numerals, about 4rem and up. Small LED numerals (the clock, visitor counters, ticker dates, news day tiles, step numbers) stay solid amber; the mask breaks their legibility.

**The Formal Voice Rule.** Public copy is formal Indonesian, sentence case, with no em dashes and no marketing language.

## Layout

A single centered container (max 1280px, 16px gutters on mobile, 32px from `lg`) holds every section. Sections alternate between the aula wall and full-bleed white bands bordered top and bottom by a rule line, spaced 64px on mobile and 96px on desktop, with 40px between a section sign and its content on desktop.

Boards and grids are built from seamed modules: a grid with a 1px gap over a darker ground (papan-seam inside boards, rule-line on the wall), so modules read as one panel with joints. Asymmetric two-column splits carry content: hero 1.2fr/1fr, requirements/steps 5fr/7fr, lead news/list 7fr/5fr, 112 figure/copy 5fr/7fr, welcome 320px/fluid. Counters run 2 across on mobile and 4 on desktop; divisions 1, 2, then 4. The gallery is a 2-then-4 column grid with the first item spanning 2x2.

The header is sticky: 36px topbar plus 68px nav. On mobile the hero board reorders so the counters sit directly under the office name and CTAs, above the photograph; the nav collapses to a flat list with all submenus open.

**Current state, not system:** inner pages (profil dinas, berita, dokumen, galeri, layanan) share header, footer, font, and radius but still open with the older page-title band (office-blue-deep ground with a soft radial motif, bold normal-width title). They have not been brought into the hall world.

## Elevation & Depth

Mostly flat with tonal layering: the navy board sits on the light wall, and depth inside the board comes from seams and the steel bezel rather than shadow. Shadows are few, soft, navy-tinted, and structural: they belong to the board frame and to things that float above the page.

### Shadow Vocabulary
- **Board frame** (`box-shadow: inset 0 1px 0 rgba(255,255,255,0.1), 0 32px 64px -32px rgba(13,21,38,0.65)`): the steel bezel around a display board; the inset line is the lit top edge of the metal.
- **Nav drop** (`box-shadow: 0 10px 30px -18px rgba(13,21,38,0.7)`): under the sticky nav.
- **Menu float** (`box-shadow: 0 18px 40px -16px rgba(13,21,38,0.35)`): dropdown menus.
- **Card lift on hover** (`box-shadow: 0 18px 36px -20px rgba(13,21,38,0.35)`): news cards, only on hover, with border shifting to blue-soft-rule.
- **Floating button** (`box-shadow: 0 12px 28px -10px rgba(13,21,38,0.6)`): back-to-top.

### Named Rules
**The Flat Wall Rule.** Content on the wall is flat at rest: white panels with 1px rules. Shadow appears only on the board frame, floating chrome, and hover.

## Shapes

One corner: 4px (Tailwind `rounded`) on every button, chip, tab, panel, card, image frame, tile, and board bezel. Nothing is pill-shaped or circular; even the ticker separators and tagline dividers are 6px squares. Borders are 1px (rule-line on the wall, bezel-steel on navy); accent bars are flat 3px to 6px strips (nav underline, tab underline, division stripes, footer zone strip). Board modules are clipped (`overflow: hidden`) so the seams stop cleanly at the bezel radius.

**The Four Pixel Rule.** Border radius is `rounded` (4px) everywhere. Never `rounded-lg`, `rounded-xl`, `rounded-full`, or anything larger, on any element.

## Components

### Buttons
Firm, compact, and legible; they press down 1px on active.
- **Shape:** gently squared corners (4px).
- **LED primary (on a board):** amber ground, navy text, 600 at 0.875rem, 12px by 20px padding, trailing arrow icon; hover lightens to #ffc433.
- **Ghost (on a board):** transparent with a 1px white/25% border, white text; hover raises the border to white/60% and adds a white/5% wash.
- **Emergency:** zone D red ground, white text, leading phone icon, "Hubungi 112"; hover deepens to emergency-press. The same button appears in the nav at 8px by 14px.
- **Zone:** the counter's zone color with white text; hover brightens 10%. The 112 panel swaps it for an outline button (rule-line border, ink text).
- **Text link:** office blue, 600 at 0.875rem, trailing arrow that slides 4px right on hover; hover to office-blue-deep.
- **Focus:** 2px office-blue outline, 2px offset; amber inside boards.

### Chips
- **Category chip:** blue-wash ground, category-blue text, 600 at 0.75rem, 4px radius.
- **Report chip (on a board):** transparent with a 1px bezel-steel border, blue-pale text at 0.875rem.

### Cards / Containers
- **News card:** white, 1px rule-line border, 4px radius, 16:9 media over a navy ground (without a photo the ground shows the category name in condensed white and an amber photo icon), 20px body padding, meta row separated by a top rule. Hover: border to blue-soft-rule, card lift shadow, photo scales 1.04.
- **Reading panel:** white modules seamed by 1px rule-line inside a 4px bordered frame, 24px padding on mobile and 40px on desktop.
- **Division cell:** white, 6px top stripe stepping through office-blue-dark, office-blue, office-blue-bright, blue-soft-rule.

### Inputs / Fields
Not used on the homepage; no field system is established yet.

### Navigation
- **Topbar:** navy, 36px, caption size in blue-mist. Left: amber clock icon, live WITA clock as solid LED Small numerals (HH.MM.SS) with a small "WITA" suffix, a bezel divider, then the time-of-day greeting (Selamat pagi, siang, sore, malam). Right: phone and email, revealed at md and lg. The clock is server-rendered, then ticks each second on the Asia/Makassar zone regardless of the visitor's device zone. No date is ever shown.
- **Main nav:** navy, 68px, official logo at 44px tall on the left. Items at 15px medium in blue-pale, white when active or hovered, with a 3px amber underline that scales in from the left. Dropdowns are white, 4px, bordered, with menu float shadow; items wash to aula on hover. The emergency button sits at the right.
- **Mobile:** menu button toggles a flat navy list under the nav; sub-items indent behind a bezel rule.

### Display Board (signature)
The navy board inside a steel bezel (6px padding, 8px from sm). Modules on a papan-seam ground with 1px joints power on in sequence (700ms, cubic-bezier(0.16, 1, 0.3, 1), 90ms stagger). The hero board carries: a visitor strip with LED Small counts, the office name and CTAs, the Teluk Kendari bridge photograph, four counters, and the news ticker (amber "Kabar terkini" label, LED Small dates, scroll duration 9s per item, paused on hover or focus, static and scrollable under reduced motion).

### Loket Counter (signature)
A navy tile, min 144px tall, 24px padding on desktop. A zone-colored bar (4.5% of the tile height) sits at the top edge; on hover or focus it rises to fill the tile (500ms) while the condensed lit-zone code (3rem to 3.75rem, 700) turns white and blinks twice (900ms, stepped). Service name in white 600, audience in blue-mist.

### Counter Tabs
White tabs with a 1px rule-line border; each carries a 44px zone square holding the condensed white code. Hover borders the tab in its zone; selected turns the tab navy with a 4px zone underline. Arrow keys move between tabs. Steps inside the panel use 40px navy squares with lit-zone LED numerals, joined by a 1px rule-line track.

### Section Sign
Every wall section heading opens with a 44px navy square (40px on mobile) holding an amber right arrow, beside the condensed headline, with an optional text link aligned right. It is the hall's wayfinding sign.

### Footer
Navy, opened by a 6px four-column strip in zones A, B, C, D. Logo, office line, and an outlined 112 call tile in lit zone red; link columns with condensed white headings and amber hover; address, phone, and email with amber icons; bezel rules between rows.

## Do's and Don'ts

### Do:
- **Do** use IBM Plex Sans only, with `font-stretch: 85%` for display, headings, board signage, and LED numerals.
- **Do** use the 4px radius (`rounded`) on every rounded element.
- **Do** keep blue as the base identity: aula wall, papan navy, office blue.
- **Do** show a live WITA (Asia/Makassar) clock with a time-of-day greeting in the topbar.
- **Do** place the official logo (`web/public/img/kominfo-logo.webp`, white wordmark) only on a papan navy ground; this is why the nav and footer are navy.
- **Do** set counting and time numerals in amber LED style with tabular figures, and apply the dot mask only at display size (about 4rem and up).
- **Do** drive counter color from the zone key through `--zona` and `--zona-terang`: solid zone on surfaces, lit zone for text on navy.
- **Do** build boards and grids as seamed modules with 1px gaps, not separate floating cards.
- **Do** gate board motion (power-on, counter call, ticker) behind `prefers-reduced-motion: no-preference`.
- **Do** write public copy in formal Indonesian, sentence case.

### Don't:
- **Don't** use `rounded-lg`, `rounded-xl`, `rounded-full`, or any radius above 4px.
- **Don't** add a second typeface, a monospace, or a system display face.
- **Don't** show a date in the topbar.
- **Don't** put the logo on the white wall or a light ground; the white wordmark disappears.
- **Don't** apply the LED dot mask to small numerals (clock, visitor counts, ticker dates, news day tiles, step numbers).
- **Don't** use zone teal or magenta as general accents, or zone red for anything but 112 and emergency action.
- **Don't** use em dashes in public copy.
- **Don't** return to the blue carousel hero with three floating cards.
- **Don't** treat the inner pages' blue page-title band with radial motif as the system; it is the pre-hall state awaiting migration.
