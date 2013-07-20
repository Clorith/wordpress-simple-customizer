wordpress-simple-customizer
===========================

The plugin will (in theory) allow any user to modify the look of any theme to their own desire.

When active, the plugin will add a new section to your Customize screen entitled Simple Customize, containing input fields for various elements needed to display a new customize option to the user.

The unique feature that makes this plugin so universally accessible is the "Find element" button. Once clicked it will trigger on any element you click in the preview window, auto-populating the fields to add customization* as best it can. Some basic knowledge of CSS is desirable, in order to ensure that the CSS selector is correct. Given that it iterates through the entire DOM to create the selector, you might want to modify it to something less specific or more specific.

Once you are happy with everything in the options, "Add element" to have your customize option implemented. The CSS will be loaded before any other CSS, so assuming the theme doesn't have any !important flats on its styling, it will be prioritized.

Of course, should you wish to manually implement or remove a customize option, you can use the handy Simple Customize options page!

Caution: this plugin is still experimental to say the least, so use at your own discretion. Stylings can be undone by deleting them, or by disabling the plugin altogether.