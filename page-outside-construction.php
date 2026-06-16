<?php
/**
 * Template Name: Outside Construction Page
 *
 * @package IBEW_Local_53
 */

get_header();

// Resource link URLs — update PDF paths here when files change.
$out_of_work_form_url        = home_url('/out-of-work/');
$books_referral_policy_url   = content_url('uploads/2026/04/Local-53-Out-of-Work-and-Referral-Policy-Updated-4-17-2025.pdf');
$jurisdiction_kansas_url     = content_url('uploads/2026/04/LU-53-Jurisdiction-Kansas.pdf');
$jurisdiction_missouri_url   = content_url('uploads/2026/04/LU-53-Jurisdiction-Missouri.pdf');

// URLs for QR codes
$mobile_app_qr_ios_url     = 'https://apps.apple.com/us/app/laborpower/id1612891005';
$mobile_app_qr_android_url = 'https://play.google.com/store/apps/details?id=com.workingsystems.apps.lpmobile&pcampaignid=web_share';
// URLs for app store downloads
$mobile_app_apple_store_url  = 'https://apps.apple.com/us/app/laborpower-mobile/id1024823657';
$mobile_app_google_play_url  = 'https://play.google.com/store/apps/details?id=com.workingsystems.lpmobile&hl=en';
?>

<!-- Outside Construction Hero Section -->
<section class="archive-hero resources-hero">
    <div class="archive-hero-container reveal-fade-up">
        <h1 class="hero-title">Outside Construction</h1>
        <p class="hero-subtext">Access essential forms, policies, jurisdiction documents, and the mobile app for outside construction members.</p>
    </div>
</section>

<!-- Main Content -->
<div class="resources-page-container">

    <!-- Resource Links Section -->
    <section class="external-resources-section">
        <div class="external-resources-header reveal-fade-up">
            <div class="header-accent"></div>
            <div class="header-content">
                <h2 class="section-title">Resource Links</h2>
                <p class="section-subtitle">Forms, policies, and jurisdiction documents for outside construction.</p>
            </div>
        </div>

        <div class="external-links-grid reveal-stagger">
            <a href="<?php echo esc_url($out_of_work_form_url); ?>" class="external-link-item">
                <span class="material-icons external-link-type-icon is-url" aria-hidden="true">assignment</span>
                <span class="link-text">Out of Work Form</span>
            </a>
            <a href="<?php echo esc_url($books_referral_policy_url); ?>" class="external-link-item" target="_blank" rel="noopener noreferrer">
                <span class="material-icons external-link-type-icon is-pdf" aria-hidden="true">picture_as_pdf</span>
                <span class="link-text">Books &amp; Referral Policy</span>
            </a>
            <a href="<?php echo esc_url($jurisdiction_kansas_url); ?>" class="external-link-item" target="_blank" rel="noopener noreferrer">
                <span class="material-icons external-link-type-icon is-pdf" aria-hidden="true">picture_as_pdf</span>
                <span class="link-text">Jurisdiction – Kansas</span>
            </a>
            <a href="<?php echo esc_url($jurisdiction_missouri_url); ?>" class="external-link-item" target="_blank" rel="noopener noreferrer">
                <span class="material-icons external-link-type-icon is-pdf" aria-hidden="true">picture_as_pdf</span>
                <span class="link-text">Jurisdiction – Missouri</span>
            </a>
        </div>
    </section>

    <!-- How to use the Mobile App Section -->
    <section class="mobile-app-section">
        <div class="mobile-app-header reveal-fade-up">
            <div class="header-accent"></div>
            <div class="header-content">
                <h2 class="section-title">How to use the Mobile App</h2>
                <p class="section-subtitle">Scan the QR code below for your device to view instructions.</p>
            </div>
        </div>
        <div class="mobile-app-download-row reveal-fade-up">
            <p class="mobile-app-download-label">Download the app</p>
            <p class="mobile-app-download-tagline">Pay union dues easily through Labor Power App</p>
            <div class="mobile-app-store-badges">
                <a href="<?php echo esc_url($mobile_app_apple_store_url); ?>" class="mobile-app-store-link" target="_blank" rel="noopener noreferrer" aria-label="Download on the App Store">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/Download_on_the_App_Store_Badge.svg" alt="Download on the App Store" class="store-badge" width="120" height="40" />
                </a>
                <a href="<?php echo esc_url($mobile_app_google_play_url); ?>" class="mobile-app-store-link" target="_blank" rel="noopener noreferrer" aria-label="Get it on Google Play">
                    <img src="https://play.google.com/intl/en_us/badges/static/images/badges/en_badge_web_generic.png" alt="Get it on Google Play" class="store-badge store-badge-google" width="135" height="58" />
                </a>
            </div>
        </div>
        <div class="mobile-app-qr-grid reveal-stagger">
            <div class="mobile-app-qr-item">
                <div class="mobile-app-qr-image">
                    <img src="<?php echo esc_url('https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . rawurlencode($mobile_app_qr_ios_url)); ?>" alt="QR code – iOS app instructions" width="200" height="200" />
                </div>
                <p class="mobile-app-qr-label">iOS</p>
            </div>
            <div class="mobile-app-qr-item">
                <div class="mobile-app-qr-image">
                    <img src="<?php echo esc_url('https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . rawurlencode($mobile_app_qr_android_url)); ?>" alt="QR code – Android app instructions" width="200" height="200" />
                </div>
                <p class="mobile-app-qr-label">Android</p>
            </div>
        </div>
    </section>

    <!-- Help CTA Section -->
    <section class="resources-cta-section">
        <div class="cta-content reveal-fade-up">
            <h3 class="cta-title">Still can't find what you need?</h3>
            <p class="cta-text">Our staff is available at the Main Hall to assist with specific jurisdiction documentation and member requests.</p>
        </div>
        <div class="cta-actions reveal-fade-up reveal-delay-1">
            <a href="mailto:localrep@ibewlocal53.org" class="btn btn-cta-gold">
                Email Us
                <span class="material-icons">arrow_forward</span>
            </a>
            <a href="tel:+18164315434" class="btn btn-cta-outline">
                Call (816) 431-5434
            </a>
        </div>
    </section>

</div>

<?php get_footer(); ?>
