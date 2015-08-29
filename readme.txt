=== Frontend Debugger ===
Contributors: szepe.viktor
Donate link: https://szepe.net/wp-donate/
Tags: debug, debugging, developer, development, HTML, source, frontend
Requires at least: 3.0.1
Tested up to: 4.3
Stable tag: 0.9.0
License: GPLv2 or later

Display prettified page source on the frontend.

== Description ==

= Only for development! =

Displays:

* Header
* Thumbnails
* The Loop (content)
* Footer
* Included template parts
* Highlight script, link and style elements

To remove **all** registered scripts and styles add `?remove-scripts` to the URL.
All these scripts and styles will be listed at the bottom of the footer as HTML comment.
This way it is possible to detect non-WordPress script printing.

If you are not an administrators add `?view-source` to the URL to activate Frontend Debugger.

Notice: This plugin uses regular expressions and `eval()` to get the current template apart.

TODO: sidebar, search-form.

The mini control panel contains:

* Header, Thumbnails, The Loop, Footer, Included files anchors
* Toogle button for line numbers
* Toggle button for line wrapping
* Toggle button for showing line ends
* Button for script and style highlighting

From the second click on the `Highlight` button loops through (scrolls to) all scripts and styles.

The control panel's state is kept in your browser's
[localStorage](https://developer.mozilla.org/en-US/docs/Web/API/Window/localStorage).

Development of this plugin goes on on [GitHub](https://github.com/szepeviktor/frontend-debugger).
Please contribute by sending PR-s implementing my TODO-s.

== Installation ==

This section describes how to install the plugin and get it working.

1. Uppack the ZIP to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. The Loop.
2. The Header.
3. Included files.

== Changelog ==

= 0.9.0 =
* Added `remove-scripts` GET parameter
* Added `Highlight` button secondary function: loop through all scripts and styles

= 0.8.0 =
* Added template part detection from barrykooij's What The File plugin.
* Updated fail2ban trigger on direct access.
* Added support for `get_template_part()` detection.
* WordPress 4.3 compatibility.

= 0.7.1 =
* Fixed site_url() instead home_url().

= 0.6 =
* Highlight script, link and style elements.

= 0.5 =
* Prevent search engine indexing.

= 0.4 =
* Display page URL.
* Some visual cosmetics.

= 0.3 =
* Include remote scripts and styles.

= 0.2 =
* Initial release.
