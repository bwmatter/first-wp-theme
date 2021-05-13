<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php esc_attr_e('text/html; charset=utf-8'); ?>">
<meta name="<?php esc_attr_e('viewport'); ?>" content="<?php esc_attr_e('width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no'); ?>">
<meta http-equiv="<?php esc_attr_e('x-ua-compatible'); ?>" content="<?php esc_attr_e('ie=edge'); ?>">
<meta name="<?php esc_attr_e('referrer'); ?>" content="<?php esc_attr_e('always'); ?>">
<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="site-navbar" class="" role="banner">

  <figure id="nav-logo" class="">
    <?php b4st_navbar_brand();?>
  </figure>

  <nav id="menu" class=""> <!-- Customize this and the button as required by the design -->
    <?php
      wp_nav_menu( array(
        'theme_location'  => 'navbar',
        'container'       => 'false',
        'menu_class'      => '',
        'fallback_cb'     => '__return_false',
        'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        'depth'           => 2,
        'walker'          => new b4st_walker_nav_menu()
      ) );
    ?>
  </nav>

  <button id="menu-toggle" class="menuButton d-flex" aria-label="Menu" aria-expanded="false" aria-controls="menu">
    <span class="text"><?php esc_html_e('MENU'); ?></span><span class="top"></span><span class="middle"></span><span class="bottom"></span>
  </button>

</div><!--/#site-navbar -->