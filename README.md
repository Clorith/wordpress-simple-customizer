wordpress-simple-customizer
===========================

The plugin will (in theory) allow any user to modify the look of any theme they so desire.

When active, the plugin will add a new section to your Customize screen titled Simple Customize, containing input fields for various elements needed to display a new customize option to the user.

The unique feature that sets the plugin apart from any other it its "Find my CSS" button, once clicked, it will trigger on any element you click in the preview window, auto-populating the fields to add customization as best it can (some minor knowledge of CSS might be handy here so you can ensure the CSS selector is right, given that it iterates through the entire DOM to create the selector you might want to modify it to something less specific or more specific).

Once you are happy with everything in the options, Start customizing to have your customize option implemented, the CSS will be loaded before any other CSS so it will be prioritized (granted the theme doesn't use some kind of !important flags on it's styling).

Of course, should you wish to manually implement a customize option, or want to remove one, use the handy Simple Customize options page!

Caution; This plugin is still experimental to say the least, so use at your own discretion.
Stylings can be undone by deleting them, or by disabling the plugin altogether.