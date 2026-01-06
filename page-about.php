<?php
/**
 * About Page Template
 *
 * @package IBEW_Local_53
 */

get_header();
?>

<?php while (have_posts()) : the_post(); ?>
    <!-- About Hero -->
    <section class="archive-hero">
        <div class="hero-card gradient-hero">
            <div class="hero-pill">ABOUT US</div>
            <h1 class="hero-title">
                About <span class="gold-text">Local 53</span>
            </h1>
            <p class="hero-subtext">Learn about our history, mission, and commitment to electrical workers in Kansas City.</p>
        </div>
    </section>
    
    <article class="page-content">
        <div class="page-container">
            <div class="page-content-wrapper">
                <?php the_content(); ?>
            </div>
        </div>
    </article>
<?php endwhile; ?>

<?php
get_footer();

