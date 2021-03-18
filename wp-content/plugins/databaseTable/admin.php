<?php
/**
 * Plugin Name:       Database Table
 * Plugin URI:        https://github.com/iktakhairul/wordpress-plugin-development.git
 * Description:       This is ikta's plugin. Anyone who is interested for me please mail me to 'databaseTable@gmail.com'.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Shah Md. Iktakhairul Islam
 * Author URI:        https://github.com/iktakhairul/wordpress-plugin-development.git
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       my-plugin
 */

define('PLUGIN_DIR_PATH', plugin_dir_path(__file__));
define('PLUGINS_URL', plugins_url());
defined('ABSPATH') or die("Unauthorized Access!");

add_action('admin_menu', 'add_custom_menu');

function add_custom_menu() {
    add_menu_page(
        'Database',
        'Database',
        'manage_options',
        'database-plugin',
        'custom_plugin_func',
        'dashicons-database-view',
        9
    );
    add_submenu_page(
        'database-plugin',
        'All Pages',
        'All Pages',
        'manage_options',
        'database-plugin',
        'database_allPage_sub_func'
    );
    add_submenu_page(
        'database-plugin',
        'add new page',
        'add new page',
        'manage_options',
        'add page',
        'database_newPage_sub_func'
    );
}

function custom_plugin_func() {
   $url = 'https://github.com/iktakhairul/wordpress-plugin-development/blob/main/sample.json';
   // $url = plugins_url('/assets/sample.json', __FILE__);
    $args = array(
        'headers' => array(
            'Content-Type' => 'application\json'
        ),
    );
    $data_option = get_option('sample_json_result');

    if ($data_option == false) {
        $response = wp_remote_get($url, $args);
        $body = wp_remote_retrieve_body($response);

        if ($body != null | !empty($body)) {
            update_option('sample_json_result', $body);
        }
        return;
    }
    $data = json_decode($data_option);
    include_once PLUGIN_DIR_PATH.'/views/all-pages.php';
    ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Database Page</h1>
            <table id="database_table-example" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>User Id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone Number</th>
                    <th>Email Address</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($data->users as $data_line){
                            echo '<tr>';
                            echo '<td>'.$data_line->userId.'</td>';
                            echo '<td>'.$data_line->firstName.'</td>';
                            echo '<td>'.$data_line->lastName.'</td>';
                            echo '<td>'.$data_line->phoneNumber.'</td>';
                            echo '<td>'.$data_line->emailAddress.'</td>';
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    <?php
}

function database_allPage_sub_func() {
   // include_once PLUGIN_DIR_PATH.'/views/all-pages.php';
}

function database_newPage_sub_func() {
    include_once PLUGIN_DIR_PATH.'/views/add-new-pages.php';
}

add_action('admin_enqueue_scripts', 'my_admin_scripts');

function my_admin_scripts( $hook )  {

    if ('toplevel_page_database-plugin' != $hook) {
        return;
    }
    wp_enqueue_style('table_style', plugins_url('/assets/css/jquery.dataTables.min.css', __FILE__), array(), '1.0.0', 'all');
    wp_enqueue_script('table_script', plugins_url('/assets/js/jquery.dataTables.min.js', __FILE__), array('jquery'), '1.0.0', true);
    wp_enqueue_script('table_script-init', plugins_url('/assets/js/init.js', __FILE__), array('table_script'), '1.0.0', true);
}
