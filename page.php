<?php
/**
 * Default Page Template
 *
 * @package IBEW_Local_53
 */

get_header();
?>

<?php while (have_posts()) : the_post();
    $hero_type   = get_post_meta(get_the_ID(), 'ibew_hero_type', true) ?: 'none';
    $hero_title  = get_post_meta(get_the_ID(), 'ibew_hero_title', true);
    $hero_subtext = get_post_meta(get_the_ID(), 'ibew_hero_subtext', true);
    $hero_pill   = get_post_meta(get_the_ID(), 'ibew_hero_pill', true);
    $display_title = $hero_title !== '' ? $hero_title : get_the_title();
    $show_hero   = $hero_type === 'resources' || $hero_type === 'events';
?>
    <?php if ($show_hero && $hero_type === 'resources') : ?>
        <section class="archive-hero resources-hero">
            <div class="archive-hero-container reveal-fade-up">
                <h1 class="hero-title"><?php echo esc_html($display_title); ?></h1>
                <?php if ($hero_subtext !== '') : ?>
                    <p class="hero-subtext"><?php echo esc_html($hero_subtext); ?></p>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($show_hero && $hero_type === 'events') : ?>
        <section class="archive-hero events-hero">
            <div class="events-hero-card">
                <?php if ($hero_pill !== '') : ?>
                    <div class="hero-pill"><?php echo esc_html($hero_pill); ?></div>
                <?php endif; ?>
                <h1 class="hero-title"><?php echo esc_html($display_title); ?></h1>
                <?php if ($hero_subtext !== '') : ?>
                    <p class="hero-subtext"><?php echo esc_html($hero_subtext); ?></p>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <article class="page-content">
        <div class="page-container">
            <?php if (!$show_hero) : ?>
                <h1 class="page-title"><?php the_title(); ?></h1>
            <?php endif; ?>
            <div class="page-content-wrapper">
                <?php the_content(); ?>
            </div>
        </div>
    </article>
<?php endwhile; ?>

<?php
get_footer();

