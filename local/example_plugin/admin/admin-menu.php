<?php

function my_plugin_menu() {
    add_options_page(
        'My Plugin Settings',
        'My Plugin',
        'manage_options',
        'my-plugin-settings',
        'my_plugin_settings_page'
    );
}

add_action('admin_menu', 'my_plugin_menu');

function my_plugin_settings_page() {
    ?>
    <div class="wrap">
        <h2>My Plugin Settings</h2>
        <form action="options.php" method="POST">
            <?php
            settings_fields('my-plugin-settings-group');
            do_settings_sections('my-plugin-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
