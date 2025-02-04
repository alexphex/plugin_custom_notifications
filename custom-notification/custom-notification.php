<?php

/**
 * Plugin Name: fortest custom notification
 * Description: allows site administrators to add custom notifications that will be displayed on all pages of the site
 *
 * Author:      me & copilot
 *
 * Version:     1.0
 */

// Функция для добавления опции уведомлений в меню администратора
function custom_notifications_menu() {
    add_menu_page(
        'Custom Notifications',
        'Notifications',
        'manage_options',
        'custom-notifications',
        'custom_notifications_page',
        'dashicons-megaphone',
        100
    );
}
add_action('admin_menu', 'custom_notifications_menu');

// Функция для отображения страницы настроек уведомлений
function custom_notifications_page() {
    ?>
    <div class="wrap">
        <h1>Custom Notifications</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('custom_notifications_settings');
            do_settings_sections('custom-notifications');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Функция для регистрации настроек уведомлений
function custom_notifications_settings_init() {
    register_setting('custom_notifications_settings', 'custom_notifications_message');
    register_setting('custom_notifications_settings', 'custom_notifications_bg_color');
    register_setting('custom_notifications_settings', 'custom_notifications_text_color');

    add_settings_section(
        'custom_notifications_section',
        'Notification Settings',
        'custom_notifications_section_callback',
        'custom-notifications'
    );

    add_settings_field(
        'custom_notifications_message',
        'Notification Message',
        'custom_notifications_message_callback',
        'custom-notifications',
        'custom_notifications_section'
    );

    add_settings_field(
        'custom_notifications_bg_color',
        'Background Color',
        'custom_notifications_bg_color_callback',
        'custom-notifications',
        'custom_notifications_section'
    );

    add_settings_field(
        'custom_notifications_text_color',
        'Text Color',
        'custom_notifications_text_color_callback',
        'custom-notifications',
        'custom_notifications_section'
    );
}
add_action('admin_init', 'custom_notifications_settings_init');

function custom_notifications_section_callback() {
    echo 'Настройки для пользовательских уведомлений.';
}

function custom_notifications_message_callback() {
    $message = get_option('custom_notifications_message');
    echo '<textarea name="custom_notifications_message" rows="5" cols="50">' . esc_textarea($message) . '</textarea>';
}

function custom_notifications_bg_color_callback() {
    $color = get_option('custom_notifications_bg_color');
    echo '<input type="text" name="custom_notifications_bg_color" value="' . esc_attr($color) . '" class="color-field" />';
}

function custom_notifications_text_color_callback() {
    $color = get_option('custom_notifications_text_color');
    echo '<input type="text" name="custom_notifications_text_color" value="' . esc_attr($color) . '" class="color-field" />';
}

// Функция для подключения скриптов и стилей цветового поля
function custom_notifications_enqueue_scripts($hook) {
    if ($hook != 'toplevel_page_custom-notifications') {
        return;
    }
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('custom-notifications-script', plugins_url('/custom-notifications.js', __FILE__), array('wp-color-picker'), false, true);
}
add_action('admin_enqueue_scripts', 'custom_notifications_enqueue_scripts');

// Функция для отображения уведомлений на фронтенде
function display_custom_notifications() {
    $message = get_option('custom_notifications_message');
    $bg_color = get_option('custom_notifications_bg_color');
    $text_color = get_option('custom_notifications_text_color');
    if ($message) {
        echo '<div class="custom-notification" style="background-color:' . esc_attr($bg_color) . '; color:' . esc_attr($text_color) . ';">';
        echo '<p>' . esc_html($message) . '</p>';
        echo '<button class="custom-notification-close">×</button>';
        echo '</div>';
    }
}
add_action('wp_footer', 'display_custom_notifications');

// Функция для подключения стилей и скриптов на фронтенде
function custom_notifications_frontend_scripts() {
    wp_enqueue_style('custom-notifications-style', plugins_url('/custom-notifications.css', __FILE__));
    wp_enqueue_script('custom-notifications-frontend-script', plugins_url('/custom-notifications-frontend.js', __FILE__), array('jquery'), false, true);
}
add_action('wp_enqueue_scripts', 'custom_notifications_frontend_scripts');