<!DOCTYPE html>
<html lang="fr">
  <head>
    <title><?php bloginfo('name'); ?> &raquo; <?php is_front_page() ? bloginfo('description') : wp_title(''); ?></title>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- CSS de Bootstrap --> 
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Ajout d'une nouvelle feuille de style qui sera spécifique à notre thème -->
    <link href="<?php bloginfo('template_directory');?>/blog.css" rel="stylesheet" />

    <?php wp_head(); ?>
  </head>

  <body>
    <div class="header">
    	<div class="container">
                <div class="site-branding">
                    <div class="twp-site-branding alt-bgcolor">
                        <div class="branding-center">
                            <?php
                            if (is_front_page() && is_home()) : ?>
                                <span class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                                                            rel="home"><?php bloginfo('name'); ?></a></span>
                            <?php else : ?>
                                <span class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                                                            rel="home"><?php bloginfo('name'); ?></a></span>
                                <?php
                            endif;
                            $description = get_bloginfo('description', 'display');
                            if ($description || is_customize_preview()) : ?>
                                <p class="site-description"><?php echo esc_html($description); /* WPCS: xss ok. */ ?></p>
                                <?php
                            endif; ?>
                        </div>
                    </div>
                </div><!-- .site-branding -->
                
        	<nav id="site-navigation" class="main-navigation" role="navigation">
		          <?php wp_nav_menu( array('theme_location' => 'menu-principal','menu_class' => 'primary-menu') ); ?>
            </nav>
     	</div>
    </div>
    <div class="container">
