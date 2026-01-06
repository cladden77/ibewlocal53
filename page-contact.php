<?php
/**
 * Contact Page Template
 *
 * @package IBEW_Local_53
 */

get_header();

$contact_status = isset($_GET['contact']) ? $_GET['contact'] : '';
?>

<!-- Contact Hero -->
<section class="archive-hero">
    <div class="hero-card gradient-hero">
        <div class="hero-pill">GET IN TOUCH</div>
        <h1 class="hero-title">
            Contact <span class="gold-text">IBEW Local 53</span>
        </h1>
        <p class="hero-subtext">We're here to help. Reach out with questions, comments, or to learn more about joining our union.</p>
    </div>
</section>

<div class="contact-container">
    <div class="contact-layout">
        <!-- Left Card: General Information -->
        <div class="contact-card info-card">
            <h2 class="contact-card-title">General Information</h2>
            
            <div class="info-item">
                <div class="info-icon">📍</div>
                <div class="info-content">
                    <h3>Visit us</h3>
                    <p>1234 Union Street<br>Kansas City, MO 64101</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">📞</div>
                <div class="info-content">
                    <h3>Call us</h3>
                    <p><a href="tel:+18161234567">(816) 123-4567</a></p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">✉️</div>
                <div class="info-content">
                    <h3>Email us</h3>
                    <p><a href="mailto:info@ibewlocal53.org">info@ibewlocal53.org</a></p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">📠</div>
                <div class="info-content">
                    <h3>Fax</h3>
                    <p>(816) 123-4568</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">🕐</div>
                <div class="info-content">
                    <h3>Hours</h3>
                    <p>
                        <strong>Monday - Thursday:</strong> 8:00 AM - 5:00 PM<br>
                        <strong>Friday:</strong> 8:00 AM - 4:00 PM<br>
                        <strong>Lunch:</strong> 12:00 PM - 1:00 PM (Closed)
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Right Card: Contact Form -->
        <div class="contact-card form-card">
            <div class="form-header">
                <h2 class="contact-card-title">Send us a Message</h2>
                <p class="form-helper-text">Fill out the form below and we'll get back to you as soon as possible.</p>
            </div>
            
            <?php if ($contact_status === 'success') : ?>
                <div class="form-message success">
                    <p>Thank you! Your message has been sent successfully.</p>
                </div>
            <?php elseif ($contact_status === 'error') : ?>
                <div class="form-message error">
                    <p>There was an error sending your message. Please try again.</p>
                </div>
            <?php endif; ?>
            
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" class="contact-form">
                <?php wp_nonce_field('ibew_contact_form', 'ibew_contact_nonce'); ?>
                <input type="hidden" name="action" value="ibew_contact_form" />
                <input type="hidden" name="form_time" value="<?php echo time(); ?>" />
                
                <!-- Honeypot -->
                <input type="text" name="website" value="" style="display:none;" tabindex="-1" autocomplete="off" />
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name <span class="required">*</span></label>
                        <input type="text" id="first_name" name="first_name" required />
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name <span class="required">*</span></label>
                        <input type="text" id="last_name" name="last_name" required />
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" required />
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="topic">Topic/Department</label>
                    <select id="topic" name="topic">
                        <option value="">Select a topic...</option>
                        <option value="General Inquiry">General Inquiry</option>
                        <option value="Membership">Membership</option>
                        <option value="Apprenticeship">Apprenticeship</option>
                        <option value="Contract Information">Contract Information</option>
                        <option value="Safety">Safety</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="message">Message <span class="required">*</span></label>
                    <textarea id="message" name="message" rows="6" required></textarea>
                </div>
                
                <div class="form-group checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="not_robot" required />
                        <span>I am not a robot</span>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary btn-submit">Send Message →</button>
            </form>
        </div>
    </div>
</div>

<?php
get_footer();

