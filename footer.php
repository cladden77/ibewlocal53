</main>

<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-left">
            <?php if (has_custom_logo()) : ?>
                <div class="footer-logo">
                    <?php the_custom_logo(); ?>
                </div>
            <?php endif; ?>
            <h3 class="footer-site-title">IBEW LOCAL 53</h3>
            <p class="footer-description">Building a stronger future through skilled electrical work and union solidarity.</p>
            <div class="social-icons">
                <a href="#" class="social-icon" aria-label="Facebook">f</a>
                <a href="#" class="social-icon" aria-label="Twitter">t</a>
                <a href="#" class="social-icon" aria-label="Instagram">i</a>
                <a href="#" class="social-icon" aria-label="LinkedIn">in</a>
            </div>
        </div>
        
        <div class="footer-columns">
            <div class="footer-column">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="<?php echo home_url('/'); ?>">Home</a></li>
                    <li><a href="<?php echo home_url('/about'); ?>">About</a></li>
                    <li><a href="<?php echo home_url('/news'); ?>">News</a></li>
                    <li><a href="<?php echo home_url('/events'); ?>">Events</a></li>
                    <li><a href="<?php echo home_url('/contact'); ?>">Contact</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h4>Resources</h4>
                <ul>
                    <li><a href="#">Document Library</a></li>
                    <li><a href="#">Member Benefits</a></li>
                    <li><a href="#">Apprenticeship</a></li>
                    <li><a href="#">Safety Resources</a></li>
                    <li><a href="#">Job Board</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-right">
            <h4>Contact Info</h4>
            <div class="footer-contact">
                <p><strong>Address:</strong><br>1234 Union Street<br>Kansas City, MO 64101</p>
                <p><strong>Phone:</strong><br><a href="tel:+18161234567">(816) 123-4567</a></p>
                <p><strong>Email:</strong><br><a href="mailto:info@ibewlocal53.org">info@ibewlocal53.org</a></p>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> IBEW Local 53. All rights reserved.</p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

