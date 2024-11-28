<?php

// Добавляем поддержку <title> тега
add_theme_support('title-tag');

// Добавляем поддержку превью постов
add_theme_support('post-thumbnails');

/* Подключение стилей и скриптов */
function connect_scripts()
{
  /* подключение стилей */
  wp_enqueue_style('main', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'connect_scripts');

// Регистрируем меню
function register_menus()
{
  $locatoins = array(
    'header' => __('Main menu', 'band_digital'),
    'footer' => __('Footer menu', 'band_digital')
  );
  register_nav_menus($locatoins);
}
add_action('init', 'register_menus');

// Регистрация подвала
function register_footer_widget_area()
{
  register_sidebar(array(
    'name' => 'Footer Widget Area',
    'id' => 'footer-widget-area',
    'before_widget' => '<div class="footer-widget">',
    'after_widget' => '</div>',
    'before_title' => '<h4>',
    'after_title' => '</h4>',
  ));
}
add_action('widgets_init', 'register_footer_widget_area');
