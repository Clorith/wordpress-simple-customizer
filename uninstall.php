<?php
    /**
     * Script ran on plugin uninstall
     *
     * Will remove the following data
     *  - Capabilities
     *  - Customizations
     */
    if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
        exit();

    /**
     * Remove capability from all roles
     */
    global $wp_roles;
    $roles = $wp_roles->get_names();

    foreach( $roles AS $role_name => $role_label )
    {
        $wp_roles->remove_cap( $role_name, 'customizer_can_use_advanced' );
    }


    /**
     * Remove customizations from the database
     */
    delete_option( 'simple_customize' );
    delete_option( 'simple_customize_version' );
    delete_option( 'simple_customize_active_theme' );
