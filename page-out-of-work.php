<?php
/**
 * Out of Work Form Page Template
 *
 * Use this template for the page that displays the Out of Work registration form.
 * Slug: out-of-work (assign in Page attributes).
 *
 * @package IBEW_Local_53
 */

get_header();
?>

<div class="out-of-work-page-wrapper">
    <section class="contact-hero out-of-work-hero">
        <div class="contact-hero-card">
            <div class="hero-pill">Registration</div>
            <h1 class="hero-title">
                Out of Work <span class="gold-text">Form</span>
            </h1>
            <p class="hero-subtext">Register with IBEW Local 53 when you're out of work. Complete the form below with your information and qualifications.</p>
        </div>
    </section>

    <div class="out-of-work-container">
        <div class="out-of-work-form-card">
            <div class="out-of-work-form-content">
                <?php
                while ( have_posts() ) :
                    the_post();
                    the_content();
                endwhile;
                ?>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
