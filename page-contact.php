<?php
/**
 * Contact Page Template
 *
 * @package IBEW_Local_53
 */

get_header();
?>

<div class="contact-page-wrapper">
    <!-- Contact Hero -->
    <section class="contact-hero">
        <div class="contact-hero-card">
            <div class="hero-pill">Get in Touch</div>
            <h1 class="hero-title">
                Contact <span class="gold-text">IBEW Local 53</span>
            </h1>
            <p class="hero-subtext">Have questions about membership, benefits, or upcoming events?<br>We're here to help. Reach out to our team today.</p>
        </div>
    </section>

    <div class="contact-container">
        <div class="contact-layout">
            <!-- Left Card: General Information -->
            <div class="contact-card info-card reveal-fade-right">
                <h2 class="contact-card-title">General Information</h2>
                
                <div class="contact-info-list">
                    <!-- Visit Us -->
                    <div class="info-item">
                        <div class="info-icon-box info-icon-blue">
                            <span class="material-icons">location_on</span>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Visit Us</span>
                            <p class="info-value">1100 Admiral Blvd<br>Kansas City, MO 64106</p>
                        </div>
                    </div>
                    
                    <!-- Call Us -->
                    <div class="info-item">
                        <div class="info-icon-box info-icon-red">
                            <span class="material-icons">phone</span>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Call Us</span>
                            <p class="info-value"><a href="tel:+18164215464">(816)-421-5464</a></p>
                        </div>
                    </div>
                    
                    <!-- Email Us -->
                    <div class="info-item">
                        <div class="info-icon-box info-icon-gold">
                            <span class="material-icons">email</span>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Email Us</span>
                            <p class="info-value"><a href="mailto:localrep@ibewlocal53.org">localrep@ibewlocal53.org</a></p>
                        </div>
                    </div>
                    
                    <!-- Fax -->
                    <div class="info-item">
                        <div class="info-icon-box info-icon-gray">
                            <span class="material-icons">fax</span>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Fax</span>
                            <p class="info-value">(816)-842-1447</p>
                        </div>
                    </div>
                    
                    <!-- Hours -->
                    <div class="info-item info-item-hours">
                        <div class="info-icon-box info-icon-gray">
                            <svg class="hours-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20ZM12.5 7H11V13L16.2 16.2L17 14.9L12.5 12.2V7Z" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="info-content info-hours-content">
                            <div class="hours-group">
                                <span class="info-label">Mon - Thurs</span>
                                <p class="info-value">8:00am - 4:30pm</p>
                            </div>
                            <div class="hours-group">
                                <span class="info-label">Friday</span>
                                <p class="info-value">8:00am - 4:00pm</p>
                            </div>
                            <div class="hours-group">
                                <span class="info-label">Lunch Close</span>
                                <p class="info-value">12:00pm - 1:00pm</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Card: Contact Form -->
            <div class="contact-card form-card reveal-fade-left reveal-delay-2">
                <div class="form-header">
                    <h2 class="contact-card-title">Send us a Message</h2>
                    <p class="form-helper-text">Fill out the form below and we will get back to you shortly.</p>
                </div>
                
                <div class="form-content">
                    <?php
                    // Display the page content from the WordPress editor
                    // Add your Formidable Forms block or shortcode when editing this page
                    if (have_posts()) :
                        while (have_posts()) : the_post();
                            the_content();
                        endwhile;
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
