<form action="" method="post">
    <table class="wp-list-table widefat" cellspacing="0">
        <thead>
        <tr>
            <th scope="col"><?php _e( 'Name', 'simple-customize-plugin' ); ?></th>
            <th scope="col"><?php _e( 'Category', 'simple-customize-plugin' ); ?></th>
            <th scope="col"><?php _e( 'Selector', 'simple-customize-plugin' ); ?></th>
            <th scope="col"><?php _e( 'Attribute', 'simple-customize-plugin' ); ?></th>
            <th scope="col"><?php _e( 'Default value', 'simple-customize-plugin' ); ?></th>
            <th scope="col"><?php _e( 'Current value', 'simple-customize-plugin' ); ?></th>
        </tr>
        </thead>

        <tfoot>
        <tr>
            <th scope="col"><?php _e( 'Name', 'simple-customize-plugin' ); ?></th>
            <th scope="col"><?php _e( 'Category', 'simple-customize-plugin' ); ?></th>
            <th scope="col"><?php _e( 'Selector', 'simple-customize-plugin' ); ?></th>
            <th scope="col"><?php _e( 'Attribute', 'simple-customize-plugin' ); ?></th>
            <th scope="col"><?php _e( 'Default value', 'simple-customize-plugin' ); ?></th>
            <th scope="col"><?php _e( 'Current value', 'simple-customize-plugin' ); ?></th>
        </tr>
        </tfoot>

        <tbody id="the-list">
        <?php

        if ( isset( $options[$theme->stylesheet] ) )
        {
            foreach( $options[$theme->stylesheet] AS $option )
            {
                ?>
                <tr>
                    <td>
                        <?php echo $option['label']; ?>
                        <div class="row-actions">
                                <span class="delete">
                                    <a href="?page=simple-customize&delete=<?php echo sanitize_title( $option['label'] ); ?>" class="delete"><?php _e( 'Delete', 'simple-customize-plugin' ); ?></a>
                                </span>
                        </div>
                    </td>
                    <td>
                        <?php echo $option['category']; ?>
                    </td>
                    <td>
                        <?php echo $option['object']; ?>
                    </td>
                    <td>
                        <?php echo ( ! empty( $option['selector_manual'] ) ? $option['selector_manual'] : $option['selector'] ); ?>
                    </td>
                    <td>
                        <?php echo $option['default']; ?>
                    </td>
                    <td>
                        <?php echo get_theme_mod( sanitize_title( $option['label'] ), $option['default'] ); ?>
                    </td>
                </tr>
            <?php
            }
        }
        ?>

        <tr>
            <td colspan="6">
                <strong><?php _e( 'Add a new selector', 'simple-customize-plugin' ); ?></strong>
            </td>
        </tr>
        <tr>
            <td><input type="text" style="width:100%;" name="label"></td>
            <td>
                <select id="simple_customize_category" name="category" style="width:100%;">
                    <optgroup label="<?php _e( 'WordPress defaults', 'simple-customize-plugin' ); ?>">
                        <option value="title_tagline"><?php _e( 'Site Title & Tagline', 'simple-customize-plugin' ); ?></option>
                        <option value="colors" selected="selected"><?php _e( 'Colors', 'simple-customize-plugin' ); ?></option>
                        <option value="header_image"><?php _e( 'Header Image', 'simple-customize-plugin' ); ?></option>
                        <option value="background_image"><?php _e( 'Background Image', 'simple-customize-plugin' ); ?></option>
                        <option value="nav"><?php _e( 'Navigation', 'simple-customize-plugin' ); ?></option>
                        <option value="static_front_page"><?php _e( 'Static Front Page', 'simple-customize-plugin' ); ?></option>
                    </optgroup>
                    <optgroup label="<?php _e( 'Your categories', 'simple-customize-plugin' ); ?>">
                        <?php

                        if ( isset( $categories[$theme->stylesheet] ) )
                        {
                            foreach( $categories[$theme->stylesheet] AS $category )
                            {
                                echo '<option value="' . sanitize_title( $category['category-label'] ) . '">' . $category['category-label'] . '</option>\\';
                            }
                        }
                        ?>
                    </optgroup>
                </select>
            </td>
            <td><input type="text" style="width:100%;" name="object"></td>
            <td><input type="text" style="width:100%;" name="selector_manual"></td>
            <td><input type="text" style="width:100%;" name="default"></td>
            <td>
                <button type="submit" class="button-primary"><?php _e( 'Add this selector', 'simple-customize-plugin' ); ?></button>
            </td>
        </tr>

        </tbody>
    </table>
</form>

<h2>
    <?php _e( 'Categories', 'simple-customize-plugin' ); ?>
</h2>

<span>
        <?php _e( 'A category is usually used to group together customization options of similar origin. One such example would be to create a category titled "Footer" and put all your customized options for the footer inside this category.', 'simple-customize-plugin' ); ?>
    </span>

<br />
<br />

<form action="" method="post">
    <table class="wp-list-table widefat" cellspacing="0">
        <thead>
        <tr>
            <th scope="col"><?php _e( 'Name', 'simple-customize-plugin' ); ?></th>
        </tr>
        </thead>

        <tfoot>
        <tr>
            <th scope="col"><?php _e( 'Name', 'simple-customize-plugin' ); ?></th>
        </tr>
        </tfoot>

        <tbody id="the-list">
        <?php

        if ( isset( $categories[$theme->stylesheet] ) )
        {
            foreach( $categories[$theme->stylesheet] AS $category )
            {
                ?>
                <tr>
                    <td colspan="2">
                        <?php echo $category['category-label']; ?>
                        <div class="row-actions">
                                <span class="delete">
                                    <a href="?page=simple-customize&category-delete=<?php echo sanitize_title( $category['category-label'] ); ?>" class="delete"><?php _e( 'Delete', 'simple-customize-plugin' ); ?></a>
                                </span>
                        </div>
                    </td>
                </tr>
            <?php
            }
        }
        ?>

        <tr>
            <td>
                <strong><?php _e( 'Add a new category', 'simple-customize-plugin' ); ?></strong>
            </td>
        </tr>
        <tr>
            <td>
                <input type="text" name="category-label">
                <button type="submit" class="button-primary"><?php _e( 'Add this category', 'simple-customize-plugin' ); ?></button>
            </td>
        </tr>

        </tbody>
    </table>
</form>
