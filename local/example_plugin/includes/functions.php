<?php


function my_plugin_enqueue_scripts() {
    if (!wp_script_is('jquery', 'enqueued')) {
        wp_enqueue_script('jquery');
    }
}

add_action('wp_enqueue_scripts', 'my_plugin_enqueue_scripts');
function my_plugin_shortcode() {
    // Shortcode HTML
    $html = '
        <div id="my-plugin-container">
            <input type="text" id="my-plugin-textbox" />
            <button id="my-plugin-button">Search</button>
            <div id="my-plugin-result"></div>
        </div>
    ';

    // AJAX script
    $html .= '
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            $("#my-plugin-button").click(function() {
                var searchText = $("#my-plugin-textbox").val();
                $.ajax({
                    url: "' . admin_url('admin-ajax.php') . '",
                    type: "POST",
                    data: {
                        action: "my_plugin_search",
                        searchText: searchText
                    },
                    dataType: "json", // Expect a JSON response
                    success: function(response) {
                        if(response.success) {
                            // Update the result div with the returned HTML
                            $("#my-plugin-result").html(response.html);
                        } else {
                            console.error("Error: ", response);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle any AJAX errors
                        console.error("AJAX Error: ", status, error);
                    }
                });
            });
        });
        </script>
    ';

    return $html;
}
add_shortcode('my-plugin-shortcode', 'my_plugin_shortcode');


function my_plugin_ajax_search() {
    $searchText = sanitize_text_field($_POST['searchText']);

    // Perform the API call
    $api_result = my_plugin_make_api_call($searchText); // Assume this function makes the API call and returns JSON

    // Decode the JSON result to an object
    $dataObject = json_decode($api_result);

    // Initialize an HTML string for the output
    $html = '<div class="grid-container">';

    // Check if data is set and is an array
    if (isset($dataObject->data) && is_array($dataObject->data)) {
        // Loop through each item in the data array
        foreach ($dataObject->data as $item) {
            // Construct HTML for each GIF using the embed_url
            if (isset($item->embed_url)) {
                $html .= '<div class="grid-item">';
                $html .= '<iframe src="' . esc_url($item->embed_url) . '" frameborder="0" allowfullscreen></iframe>';
                $html .= '</div>';
            }
        }
    }

    $html .= '</div>';

    // Return the HTML string as a JSON response
    echo json_encode(array('success' => true, 'html' => $html));

    wp_die(); // All AJAX handlers should die to avoid unexpected output
}

// Register AJAX action for both logged-in and logged-out users
add_action('wp_ajax_my_plugin_search', 'my_plugin_ajax_search');
add_action('wp_ajax_nopriv_my_plugin_search', 'my_plugin_ajax_search');


function my_plugin_make_api_call($searchText) {
    $api_key = get_option('my_plugin_api_key');
    $api_url = 'https://api.giphy.com/v1/gifs/search';

    // Append query parameters to the URL
    $api_url = add_query_arg(array(
        'api_key' => $api_key,
        'q' => $searchText,
        'limit' => '10',
        'offset' => '0',
        'rating' => 'g',
        'lang' => 'en',
        'bundle' => 'messaging_non_clips'
    ), $api_url);

    // Make the request
    $response = wp_remote_get($api_url);

    // Check for request error
    if (is_wp_error($response)) {
        return 'Error: ' . $response->get_error_message();
    }

    // Retrieve the body's response if no errors are found
    $body = wp_remote_retrieve_body($response);

    return $body;
}

