<?php

function my_plugin_settings_init() {
    register_setting('my-plugin-settings-group', 'my_plugin_api_key');

    add_settings_section(
        'my-plugin-settings-section',
        'API Key Settings',
        'my_plugin_settings_section_callback',
        'my-plugin-settings'
    );

    add_settings_field(
        'my-plugin-api-key',
        'API Key',
        'my_plugin_api_key_field_callback',
        'my-plugin-settings',
        'my-plugin-settings-section'
    );
}

add_action('admin_init', 'my_plugin_settings_init');

function my_plugin_settings_section_callback() {
    echo '<p>Enter your API Key below.</p>';
}

function my_plugin_api_key_field_callback() {
    $apiKey = get_option('my_plugin_api_key');
    echo '<input type="text" name="my_plugin_api_key" value="' . esc_attr($apiKey) . '"/>';
}
