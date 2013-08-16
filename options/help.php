<div style="float: left">
    <h2>
        <?php _e( 'Simple Customize field overview', 'simple-customize-plugin' ); ?>
    </h2>

    <p>
        <strong>
            <?php _e( 'Name / Label', 'simple-customize-plugin' ); ?>
        </strong>

        <br />

        <?php _e( 'The label is used to describe what you wish to change, this is also used as an identified and should be unique.', 'simple-customize-plugin' ); ?>
    </p>

    <p>
        <strong>
            <?php _e( 'Category', 'simple-customize-plugin' ); ?>
        </strong>

        <br />

        <?php _e( 'The category determines which tab the option is listed under, this can for example be used to list all header, widget and footer related customizations separately.', 'simple-customize-plugin' ); ?>
    </p>

    <p>
        <strong>
            <?php _e( 'CSS selector', 'simple-customize-plugin' ); ?>
        </strong>

        <br />

        <?php _e( 'The class and id path of the element you wish to easily make adjustments too.', 'simple-customize-plugin' ); ?>
        <br />
        <?php _e( 'It may be beneficial to have some knowledge of CSS if you wish to make modifications here manually, the plugin will be extremely specific on what it chooses.', 'simple-customize-plugin' ); ?>
    </p>

    <p>
        <strong>
            <?php _e( 'Grab only the last selector', 'simple-customize-plugin' ); ?>
        </strong>

        <br />

        <?php _e( 'In case you want a rather broad selector, or the greedy default one is too specific, enabling this checkbox will only get the last style selector found when using the Find element button.', 'simple-customize-plugin' ); ?>
    </p>

    <p>
        <strong>
            <?php _e( 'What to customize', 'simple-customize-plugin' ); ?>
        </strong>

        <br />

        <?php _e( "The attribute that you wish to change, this will be automatically populated with available options when an element is selected on your page, but can also be manually set if it's a newer attribute not known to all browsers yet.", 'simple-customize-plugin' ); ?>
    </p>

    <p>
        <strong>
            <?php _e( 'Default value', 'simple-customize-plugin' ); ?>
        </strong>

        <br />

        <?php _e( 'The default value will be automatically populated when you pick something to customize, and varies depending on the selected attribute.', 'simple-customize-plugin' ); ?>
    </p>

    <p>
        <strong>
            <?php _e( 'Find element', 'simple-customize-plugin' ); ?>
        </strong>

        <br />

        <?php _e( 'After clicking the Find element button, click any element in the preview window to auto-populate the fields.', 'simple-customize-plugin' ); ?>
    </p>

    <h2>
        <?php _e( 'The Settings Page', 'simple-customize-plugin' ); ?>
    </h2>

    <p>
        <strong>
            <?php _e( 'Do not create CSS file', 'simple-customize-plugin' ); ?>
        </strong>

        <br />

        <?php _e( "If you enable this, your CSS will not be put into it's own private CSS file, but will instead be added to the end of your html &lt;head&gt; tags. This is usefull if you wish to reduce the amount of files your visitors need to load when coming to your site.", 'simple-customize-plugin' ); ?>
        <br />
        <?php _e( 'If you use cache plugins, chances are they ahve a "minify" option If this is the case, leave this option disabled, as the minify option will combine all individual CSS files into one large one, further improving performance for your site visitors.', 'simple-customize-plugin' ); ?>
    </p>

    <p>
        <strong>
            <?php _e( 'Advanced mode', 'simple-customize-plugin' ); ?>
        </strong>

        <br />

        <?php _e( 'If you are familiar with CSS, you may find some of our pre-populated options are not flexible enough. Enable Advanced Mode to disable all pre-configured values and get handy text input fields for every option instead!', 'simple-customize-plugin' ); ?>
    </p>
</div>
<div class="preview" style="float: right; border: 1px solid #000; width: 300px;">
    <img src="<?php echo $this->pluginurl; ?>resources/images/help-overview.png" alt="" style="float: left; border-bottom: 1px solid #000;" />
    <span style="float: left; width: 100%; text-align: center; padding: 5px 0;"><?php _e( 'The customize panel', 'simple-customize-plugin' ); ?></span>
</div>
