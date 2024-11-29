<?php

// Добавляем поддержку <title> тега
add_theme_support('title-tag');

// Добавляем поддержку комментариев
add_theme_support('comments');

// Добавляем поддержку превью постов
add_theme_support('post-thumbnails');



/* Подключение стилей и скриптов */
function connect_scripts()
{
  /* подключение стилей */
  wp_enqueue_style('main', get_stylesheet_uri());

  /* подключение скриптов */
  wp_enqueue_script('form', get_template_directory_uri() . '/assets/js/form.js', array(), '', true);
  wp_enqueue_script('snap-scroll', get_template_directory_uri() . '/assets/js/snap-scroll.js', array(), '', true);
}
add_action('wp_enqueue_scripts', 'connect_scripts');

// Регистрируем меню
function register_menus()
{
  $locatoins = array(
    'header' => __('Main menu', 'theme'),
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

// Регистрация услуг
function register_services_post_type()
{
  register_post_type('service', [
    'labels' => [
      'name' => 'Услуги',
      'singular_name' => 'Услуга',
      'add_new' => 'Добавить услугу',
      'edit_item' => 'Редактировать услугу',
    ],
    'public' => true,
    'has_archive' => true,
    'supports' => ['title', 'editor', 'thumbnail', 'custom-fields', 'comments'],
    'menu_icon' => 'dashicons-hammer',
  ]);
}
add_action('init', 'register_services_post_type');


// Регистрация таксономии для услуг
function register_service_tags()
{
  register_taxonomy('service_tag', 'service', [
    'labels' => [
      'name' => 'Метки услуг',
      'singular_name' => 'Метка услуги',
      'search_items' => 'Найти метки',
      'all_items' => 'Все метки',
      'edit_item' => 'Редактировать метку',
      'add_new_item' => 'Добавить новую метку',
    ],
    'public' => true,
    'hierarchical' => false, // false - метки (теги), true - категории
    'show_admin_column' => true,
    'show_in_rest' => true, // Включаем поддержку Gutenberg
  ]);
}
add_action('init', 'register_service_tags');