</main>

<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-left">
            <div class="footer-branding">
                <div class="footer-logo">
                    <a href="<?php echo home_url('/'); ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/IBEW53.avif" alt="IBEW Local 53" />
                    </a>
                </div>
                <h3 class="footer-site-title">IBEW LOCAL 53</h3>
            </div>
            <p class="footer-description">Representing the electrical workers of Greater Kansas City since 1910. Building better lives for our members and a brighter future for our community.</p>
            <div class="social-icons">
                <a href="#" class="social-icon" aria-label="Facebook">f</a>
                <a href="#" class="social-icon" aria-label="Twitter">tw</a>
                <a href="#" class="social-icon" aria-label="Instagram">IG</a>
            </div>
        </div>
        
        <div class="footer-column">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="<?php echo home_url('/'); ?>">Home</a></li>
                <li><a href="<?php echo home_url('/about'); ?>">About</a></li>
                <li><a href="<?php echo home_url('/news'); ?>">News</a></li>
                <li><a href="<?php echo home_url('/events'); ?>">Events</a></li>
                <li><a href="<?php echo home_url('/contact'); ?>">Contact Us</a></li>
            </ul>
        </div>
        
        <div class="footer-column">
            <h4>Resources</h4>
            <ul>
                <li><a href="#">Member Login</a></li>
                <li><a href="#">Pay Dues</a></li>
                <li><a href="#">Benefits Portal</a></li>
                <li><a href="#">Job Board</a></li>
                <li><a href="#">Training Calendar</a></li>
            </ul>
        </div>
        
        <div class="footer-column">
            <h4>Contact</h4>
            <div class="footer-contact">
                <p>1100 Admiral Boulevard  Kansas City, MO 64106</p>
                <p><a href="tel:+18164315434">(816) 431-5434</a></p>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> IBEW LOCAL 53. All rights reserved.</p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

