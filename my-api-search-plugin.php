<?php
/**
 * Plugin Name: My API Search Plugin
 * Description: A plugin that adds a search bar and displays results from an external API.
 * Version: 1.0
 * Author: Your Name
 */

// Enqueue JavaScript and CSS
function my_api_search_plugin_enqueue_scripts() {
    wp_enqueue_script('my-api-search-plugin-script', plugins_url('my-api-search-plugin.js', __FILE__), array('jquery'), null, true);
    wp_localize_script('my-api-search-plugin-script', 'myApiSearchPlugin', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
    wp_enqueue_style('my-api-search-plugin-style', plugins_url('my-api-search-plugin.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'my_api_search_plugin_enqueue_scripts');

// Add shortcode to display search form
function my_api_search_plugin_shortcode() {
    ob_start();
    ?>
    <div id="my-api-search-plugin">
        <input type="text" id="my-api-search-input" placeholder="Search...">
        <button id="my-api-search-button">Search</button>
        <div id="my-api-search-results"></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('my_api_search', 'my_api_search_plugin_shortcode');

// Handle AJAX request
function my_api_search_plugin_ajax_handler() {
    $search_query = sanitize_text_field($_POST['search_query']);
    $api_url = 'https://api.example.com/search?q=' . urlencode($search_query);
    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        wp_send_json_error('Error fetching data from API');
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        wp_send_json_error('Error decoding API response');
    }

    wp_send_json_success($data);
}
add_action('wp_ajax_my_api_search', 'my_api_search_plugin_ajax_handler');
add_action('wp_ajax_nopriv_my_api_search', 'my_api_search_plugin_ajax_handler');
