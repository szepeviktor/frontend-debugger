=== Frontend Debugger ===
Contributors: szepe.viktor
Donate link: https://szepe.net/wp-donate/
Tags: debug, debugging, developer, development, HTML, source, frontend
Requires at least: 3.0.1
Tested up to: 4.2.1
Stable tag: 0.7.1
License: GPLv2 or later

Display prettified page source on the frontend.

== Description ==

= Only for development! =

Displays:

* Header
* Thumbnails
* The Loop (content)
* Footer
* Included template files
* Highlight script, link and style elements

If you are not an administrators, add `?view-source` to the URL.

Notice: This plugin uses regular expressions and `eval()` to get the current template apart.

TODO: sidebar, search-form, other template parts.

The control panel contains:

* Header, Thumbnails, The Loop, Footer, Included files anchors
* Toogle button for line numbers
* Toggle button for line wrapping
* Toggle button for showing line ends
* Button for highlighting

The control panel's state is kept in your browser's [localStorage](https://developer.mozilla.org/en-US/docs/Web/API/Window/localStorage).

Development of this plugin goes on on [GitHub](https://github.com/szepeviktor/frontend-debugger).
Please contribute by sending PR-s implementing my TODO-s.


== Installation ==

This section describes how to install the plugin and get it working.

1. Uppack the ZIP to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. The Loop
2. The Header.
3. Included files.

== Changelog ==

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
