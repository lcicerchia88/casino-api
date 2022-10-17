<?php
/**
 * Plugin Name: Casino API
 * Plugin URI: http://example.com
 * Author: Luciano Cicerchia
 * Author URI: http://lucianocicerchia.com
 * Description: Plugin that fetchs an array of reviews from an external REST API and display them in a nice list to the user
 * Version: 1.0.0
 * License: GPL2
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: casino-api
*/

// Bootstrap and JS already added in functions.php of the plugin via wp_enqueue_style

require_once ('/home/customer/www/ezequielc13.sg-host.com/public_html/wp-content/plugins/casino_api/functions.php');

add_action('admin_menu', 'admin_casino_plugin_menu');

add_shortcode ('show_reviews_casino', 'show_reviews_casino_function'); // I create a shortcode in this first instance just 
//to be able to show the results in any page, for testing purposes

function show_reviews_casino_function (){
    
    $jsonObj = get_api_response();
    $filtered_json = filter_json ($jsonObj);
    print_table ($filtered_json);
}

