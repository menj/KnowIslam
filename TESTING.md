# KnowIslam skin verification checklist

## Automated checks completed on package build
- `php -l includes/Hooks.php`
- `python -m json.tool skin.json`
- `node --check js/search-overlay.js`

## Must test on a staging MediaWiki 1.40 installation
- Skin discovery and Mustache rendering, including `html-headelement` and `html-printtail`.
- Main Page recognition, rendering, caching and native page content.
- Special:Search parameters with the site's rewrite rules.
- Namespace links, tabs, log-in, editing, revisions and special pages.
- Preferences persistence for anonymous and logged-in visitors.
- Mobile menus and dialog keyboard focus, assistive technology.
- Dark, sepia and auto schemes; screen sizes 320px through desktop.
- Existing extensions, citations and article templates.

## Tested for 1.2.0
Rendered on MediaWiki 1.43 (SkinMustache API as in 1.40) with Cite and ParserFunctions, using pages from the live database: Main Page, Ismail al-Faruqi, Royal Malaysia Police, Rosina Kamis, Isaiah 52, all templates, MediaWiki:Sidebar and MediaWiki:Common.css.
- Light, dark, sepia; 1280px and 390px; logged out and logged in.
- Hero search, overlay search and topic cards reach Special:Search.
- Landing section present only on Main Page view; absent on articles and on Main Page edit.
- No JavaScript errors.

Still to test on the 1.40 server: `/topic/` article path, Scribunto-based templates, Math, uploaded images and page caching.

This is a development build, not a live-tested release. The geometric brand mark is a placeholder until the official logo is available in a deployable asset.
