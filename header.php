<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="header-container">
        <div class="header-left">
            <?php if (has_custom_logo()) : ?>
                <div class="site-logo">
                    <?php the_custom_logo(); ?>
                </div>
            <?php else : ?>
                <div class="site-logo">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/IBEW53.avif" alt="IBEW Local 53" class="logo-seal" />
                </div>
            <?php endif; ?>
            <div class="site-branding">
                <h1 class="site-title">IBEW LOCAL 53</h1>
                <p class="site-subtitle">International Brotherhood of Electrical Workers</p>
            </div>
        </div>
        
        <nav class="main-navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_class' => 'nav-menu',
                'container' => false,
            ));
            ?>
        </nav>
        
        <div class="header-right">
            <a href="#" class="member-login-btn">
                <span class="login-icon">🔒</span>
                <span>Member Login</span>
            </a>
        </div>
    </div>
</header>

<main class="site-main">

