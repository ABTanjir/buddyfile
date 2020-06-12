<?php
/*
Plugin Name: Buddy File
Plugin URI: http://abtanjir.com
Description: Interview Requirements Demo Plugin
Version: 1.0
Author: ABTanjir
Author URI: http://abtanjir.com
*/

global $wpdb; 
define('_buddy_table', $wpdb->prefix . 'buddy_files');
define('_buddy_table_version', '1.0');

#create custom table on activate plugin
function buddy_activated(){      
    $db_table_name = _buddy_table;
      
    $sql = "CREATE TABLE $db_table_name (
                  id int(11) NOT NULL auto_increment,
                  user_id int(11) NOT NULL,
                  file_path varchar(200) NOT NULL,
                  UNIQUE KEY id (id)
        );";
  
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    add_option( 'test_db_version', _buddy_table_version );
    $upload_dir = wp_upload_dir()['basedir'].'/filebuddy';
    file_put_contents($upload_dir."/index.php", 'Access denied');
} 
register_activation_hook( __FILE__, 'buddy_activated' );

#load js file to upload image
add_action('wp_enqueue_scripts','load_uploader');
function load_uploader() {
    wp_enqueue_script( 'load_uploader', plugins_url( '/assets/uploader/uploader.min.js', __FILE__ ), array('jquery'));
    wp_register_style('load_uploader', plugins_url('/assets/uploader/uploader.min.css',__FILE__ ));
    wp_enqueue_style('load_uploader');
}

add_action('wp_enqueue_scripts','load_scripts');
function load_scripts() {
    wp_enqueue_script( 'load_scripts', plugins_url( '/assets/scripts.js', __FILE__), array('jquery'));
    wp_enqueue_style('load_scripts');
}

/**
 * file load and template for special page
 * uses: create new page > select page template named "Buddy File"
 */
add_filter( 'theme_page_templates', 'buddyfile_template', 10, 4 );
function buddyfile_template( $post_templates, $wp_theme, $post, $post_type ) {
    // Add custom template named template-custom.php to select dropdown 
    $post_templates['filebuddy-template.php'] = __('Buddy File');
    return $post_templates;
}

add_filter( 'page_template', 'buddyfile_page_template' );
function buddyfile_page_template( $page_template ){
    if ( get_page_template_slug() == 'filebuddy-template.php' ) {
        $page_template = dirname( __FILE__ ) . '/filebuddy-template.php';
    }
    return $page_template;
}

