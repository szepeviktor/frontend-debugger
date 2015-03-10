=== Frontend Debugger ===
Contributors: szepe.viktor
Donate link: https://szepe.net/wp-donate/
Tags: debug, debugging, developer, development, HTML, source, frontend
Requires at least: 3.0.1
Tested up to: 4.1.1
Stable tag: 0.3
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

If you are not an administrators, add `?view-source` to the URL.

Notice: This plugin uses regular expressions and `eval()` to get the current template apart.

TODO: sidebar, search-form, other template parts.

== Installation ==

This section describes how to install the plugin and get it working.

1. Uppack the ZIP to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 0.3 =
* Include remote scripts and styles.

= 0.2 =
* Initial release.
