<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <header>
    <div class="header-container">
      <a class="header-container-brand" href="/"><?php bloginfo('name'); ?></a>

      <?php
      wp_nav_menu([
        'theme_location' => 'header',
        'menu' => '',
        'container' => false,
        'menu_class' => 'header-container-navbar',
        'menu_id' => true,
        'echo' => true,
        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        'depth' => 1,
      ]);
      ?>
    </div>
  </header>