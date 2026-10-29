# Know Islam skin

An editorial discovery and reading skin for MediaWiki 1.39 and later. Written for knowislam.wiki (MediaWiki 1.40.0).

## Install

1. Copy this folder to `skins/KnowIslam` on the server.
2. Add to `LocalSettings.php`:

```php
wfLoadSkin( 'KnowIslam' );
$wgDefaultSkin = 'knowislam';

// Optional site-wide defaults: auto, light, dark, sepia
$wgDefaultUserOptions['knowislam-scheme'] = 'auto';
// Optional: emerald, azure, plum, bronze
$wgDefaultUserOptions['knowislam-accent'] = 'emerald';

// Optional: override any colour variable site-wide
$wgKnowIslamCustomColors = [
	'--ki-bg' => '#fbfaf6',
	'--ki-accent' => '#0f766e',
];
```

3. Purge caches: `php maintenance/run.php rebuildLocalisationCache` (or `php maintenance/rebuildLocalisationCache.php` on older layouts) and hard-refresh the browser.

## Structure

- `css/landing.css`: editorial homepage, responsive topic cards and search overlay
- `js/search-overlay.js`: accessible global search overlay
- `css/themes.css`: colour variables, schemes, accents, reading sizes
- `css/base.css`, `css/components.css`, `css/content.css`: layout and typography
- `js/theme-toggle.js`, `js/menu.js`: scheme toggle and menu behaviour
- `templates/`: Mustache templates
- `includes/Hooks.php`: preferences (two tabs: Colour, Reading), body classes, early scheme script
- `includes/SkinKnowIslam.php`: template data for the landing page, search and special-page links

Visitors who are not logged in change scheme with the header button; the choice is stored in their browser and applied before first paint. Logged-in users' choice is saved to their preferences.

## Homepage and deployment notes

The landing section is rendered only when viewing the main page (not when editing it or viewing its history). The Main Page wikitext still renders below it; edit the Main Page to complement the landing section.

All landing text is in interface messages and can be edited on-wiki without touching the skin:

- `MediaWiki:Knowislam-hero-kicker`, `-hero-title`, `-hero-title-em`, `-hero-text`, `-hero-placeholder`, `-hero-button`
- `MediaWiki:Knowislam-topics-kicker`, `-topics-heading`
- Topic cards 1 to 4: `MediaWiki:Knowislam-topic-N-label`, `-symbol`, `-title`, `-text`, `-query`. Each card opens Special:Search for its `-query` text. Set `-title` to `-` to remove a card.
- `MediaWiki:Knowislam-tagline` (under the site name)

Search forms submit to `$wgScript` with `title=Special:Search`, so they work with any article path.

Validate on staging before switching the live default skin. Check the exact MediaWiki version, site logo, private extensions, search URLs, user menus, edit/history links, skin preferences, and page cache behavior. The decorative geometric mark is a placeholder and does not replace any existing uploaded official logo.

## Changelog

### 1.2.0
Merges the editorial build (landing page, search overlay) with the 1.1.0 fixes.

- Fixed: `templateDirectory` is relative to the skin. The old path only worked on 1.40 through a deprecated fallback and fails on 1.41+.
- Fixed: hero and overlay search forms posted to an undefined `link-search` and reloaded the current page. They now submit to Special:Search.
- Fixed: the landing section (with its own `<h1>`) was in every page's HTML and hidden with CSS. It is now rendered on the main page only, and the page title heading is omitted there so the hero `<h1>` is the only skin heading.
- Fixed: hero search text was light-on-white in dark mode.
- Fixed: site name in the header was empty (`sitetitle` was not in the skin's message list).
- Fixed: logged-in users had no Log out link and mobile visitors had no Log in link. Personal tools are in an account menu.
- Fixed: a visitor's saved scheme no longer flashes in late. Logged-in users' toggle saves to preferences.
- Fixed: red links readable in every scheme (`--ki-new`).
- Changed: header search field on wide screens; search overlay button on screens up to 640px.
- Changed: landing text, topic cards and interface strings moved to i18n messages; hard-coded `/index.php?title=` links replaced with generated URLs.
- Added: styles for citations, hatnotes, navboxes, message boxes, maths (inverted in dark mode), wrapped preformatted text, scrollable wide tables on mobile, and MediaWiki 1.40+ figure markup.

### 1.1.0
Editorial build: landing page, topic cards, search overlay, brand mark.

Note: `$wgKnowIslamCustomColors` applies to every scheme, including dark. Override only scheme-neutral variables (such as `--ki-accent`) unless the wiki forces a single scheme.
