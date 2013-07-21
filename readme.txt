=== Simple customize ===
Contributors: Clorith
Tags: theme, customization, css, design
Requires at least: 3.4
Tested up to: 3.5.2
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily customize your sites theme using the WordPress Customize API. This can be done by point and clicking on what you wish to make adjustments to.

== Description ==

The plugin will (in theory) allow any user to modify the look of any theme to their own desire.

When active, the plugin will add a new section to your Customize screen entitled Simple Customize, containing input fields for various elements needed to display a new customize option to the user.

The unique feature that makes this plugin so universally accessible is the "Find element" button. Once clicked it will trigger on any element you click in the preview window, auto-populating the fields to add customization* as best it can. Some basic knowledge of CSS is desirable, in order to ensure that the CSS selector is correct. Given that it iterates through the entire DOM to create the selector, you might want to modify it to something less specific or more specific.

Once you are happy with everything in the options, "Add element" to have your customize option implemented. The CSS will be loaded before any other CSS, so assuming the theme doesn't have any !important flats on its styling, it will be prioritized.

Of course, should you wish to manually implement or remove a customize option, you can use the handy Simple Customize options page!

Caution: this plugin is still experimental to say the least, so use at your own discretion. Stylings can be undone by deleting them, or by disabling the plugin altogether.

== Installation ==

1. Upload the `simple-customize` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You will find the Simple Customize option under then `Appearance` menu

== Frequently asked questions ==

= Do I need to know HTML/CSS/Programming to use this plugin? =

No existing knowledge is required, although some basic knowledge of CSS is recommended.

== Screenshots ==



== Changelog ==



== Upgrade notice ==

