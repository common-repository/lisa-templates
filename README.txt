=== Lisa Templates ===
Contributors: pierreminik
Donate link:
Tags: template, twig, timber, tailor, acf, acf pro, polylang
Requires at least: 4.8
Tested up to: 4.9
Stable tag: 1.1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Easily write templates filled with custom data that can be loaded through a shortcode.

== Description ==

Allow users to write Twig-templates that can easily be filled with custom meta data.

Manage your custom meta data with a plugin like **ACF Pro** and render it with **Lisa Templates**.

Tested and working with the following plugins.

* [ACF and ACF Pro](https://www.advancedcustomfields.com)
* [Tailor](https://www.tailorwp.com)
* [Polylang](https://wordpress.org/plugins/polylang/)

Example of custom query to load data:

`{
    "post_type": [
        "post"
    ],
    "post_status": [
        "publish"
    ],
    "posts_per_page": 10,
    "order": "DESC",
    "orderby": "date"
}`

Dependencies:

* [Timber](https://wordpress.org/plugins/timber-library/)

### Now available: [Lisa Templates Pro](https://templates.lisa.gl)

**Lisa Templates Pro**: autoload the templates based on customized conditions.

### Where can I learn more about Twig-templates?

Lisa Templates is running Twig through a plugin called Timber. Learn more about Timber at their [excellent documentation site](https://timber.github.io/docs/).

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/lisa` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Write templates in the Lisa Templates tab


== Frequently Asked Questions ==


== Screenshots ==

1. The template editor.
2. The rendered content on the front-page.

== Changelog ==

**1.1.1**
Squeezed minor bugs.

**1.1.0**
You can now load the templates through widgets.

**1.0.1**

Fixed a bug where the template could autoload.

**1.0.0**

Initial release.

== Upgrade Notice ==
