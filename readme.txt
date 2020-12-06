=== Simple customize ===
Contributors: Clorith
Author URI: http://www.clorith.net
Plugin URI: http://www.clorith.net/wordpress-simple-customize/
Donate link: https://www.paypal.me/clorith
Tags: theme, customization, customisation, css, design
Requires at least: 3.4
Tested up to: 5.6
Stable tag: 1.7.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

It's your site, now customize it!

== Description ==

Personalise your own website, no matter what theme and what customization options are added by the themes creator.

When active, the plugin will add a new section to your Customize screen entitled Simple Customize, containing input fields for various elements needed to display a new customize option.

You can easily find what you wish to customize by using the *Find element* button, which will let you point and click in the preview window to find what you are looking for.
Although no prior knowledge of CSS or HTML is required to use the plugin, basic understanding of CSS will definitely help you make the most out of this plugin, an in turn your own site.

Once you are happy with everything in the options, use the *Add element* button to have your customize option implemented.
The CSS will be queued up in such a manner that it will try to be the dominant rule, so assuming the theme doesn't have any [!important](http://stackoverflow.com/questions/3706819/what-are-the-implications-of-using-important-in-css) flags on its styling, your own styling will appear.

You are also able to implement 3rd party fonts using the plugins own options page, where you may add manual styling alternatives, as well as create categories for grouping your own styles.

All customizations are theme-specific, this means if you ever change your theme, you will get a clean slate to work off when customizing it.
Of course, should you ever revert back to your old theme, your previous custom styling will still be there.

**Note:** You should always be cautious of modifying other peoples work, respect the time and dedication put forth by your themes author.

== Installation ==

1. Upload the `simple-customize` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You will find the Simple Customize option under then `Appearance` menu

== Frequently asked questions ==

= Do I need to know HTML/CSS/Programming to use this plugin? =

No existing knowledge is required, although some basic knowledge of CSS is recommended.

= Why can't I change styles from the Simple Customize options page? =

Styling your site is never an easy task, and to give you the best possible tools for the job, the WordPress live preview page is required, for this reason it was decided to disable any style changing from the options page to make sure the styling looks like you intend when you make the changes.

== Screenshots ==

1. The Simple Customize alternative added to the WordPress customize page
2. The view after clicking the find button
3. The fields have gotten a list of available options for you to choose from
4. Our new customize option under the colors category
5. We decided to change the color a little bit. Thanks to the color picker, this is made even easier!


== Changelog ==

= 1.7.1 =
* Adjusted some styling for icons not properly aligned any more
* Made Google Fonts require a custom API key with notices on how to get one.
* New tested-with, 3 years later, still seems to work 🎉

= 1.7.0 =
* Fixed error preventing loading of the customizer screen in some cases.
* Updated to use the Customizer Previewer API, more reliable and future proof!