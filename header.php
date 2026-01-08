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
                <h1 class="site-title">
                    <span class="title-line">IBEW LOCAL</span>
                    <span class="title-number">53</span>
                </h1>
                <p class="site-subtitle">International Brotherhood of Electrical Workers - Kansas City</p>
            </div>
        </div>
        
        <div class="header-right">
            <nav class="main-navigation">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class' => 'nav-menu',
                    'container' => false,
                ));
                ?>
            </nav>
            <a href="#" class="member-login-btn">
                <span class="material-icons login-icon">login</span>
                <span class="login-text">Member Login</span>
            </a>
        </div>
    </div>
</header>

<main class="site-main">

