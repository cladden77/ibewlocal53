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
                    <a href="<?php echo home_url('/'); ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/IBEW53.avif" alt="IBEW Local 53" class="logo-seal" />
                    </a>
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
            <a href="/members-login" class="member-login-btn">
                <span class="material-icons login-icon">login</span>
                <span class="login-text">Member Login</span>
            </a>
            <button class="mobile-menu-toggle" aria-label="Toggle mobile menu" aria-expanded="false">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </div>
        
        <!-- Mobile Menu Overlay -->
        <div class="mobile-menu-overlay" aria-hidden="true">
            <div class="mobile-menu">
                <div class="mobile-menu-header">
                    <button class="mobile-menu-close" aria-label="Close mobile menu">
                        <span class="material-icons">close</span>
                    </button>
                </div>
                <nav class="mobile-navigation">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class' => 'mobile-nav-menu',
                        'container' => false,
                    ));
                    ?>
                </nav>
                <div class="mobile-menu-footer">
                    <a href="/members-login" class="mobile-member-login-btn">
                        <span class="material-icons login-icon">login</span>
                        <span class="login-text">Member Login</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<main class="site-main">

