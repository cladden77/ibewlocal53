<?php
/**
 * About Page Template
 *
 * @package IBEW_Local_53
 */

get_header();
?>

<?php while (have_posts()) : the_post(); ?>
    <div class="about-page">
    <!-- About Hero -->
        <section class="about-hero">
            <div class="about-hero-card">
                <div class="about-hero-content">
                    <div class="about-hero-pill">Get Your Career On Track</div>
                    <h1 class="about-hero-title">About IBEW Local 53</h1>
                    <p class="about-hero-subtext">Building a stronger future for our members, our industry,<br>and our community through unity and excellence.</p>
                </div>
            </div>
        </section>

        <!-- Mission Section -->
        <section class="about-mission-section">
            <div class="section-container">
                <div class="mission-header">
                    <div class="section-eyebrow">
                        <span class="eyebrow-line"></span>
                        <span class="eyebrow-text">Our Mission</span>
                        <span class="eyebrow-line"></span>
                    </div>
                    <h2 class="about-section-title about-section-title--heavy">Empowering Electrical Workers<br>Since 1910</h2>
                </div>

                <div class="mission-content">
                    <div class="mission-image-card">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/ibew-history.png" alt="IBEW Local 53 historical photo" class="mission-image" />
                    </div>
                    <div class="mission-text">
                        <p>Charted November 3rd, 1917, the beginning of Brotherhood was created.</p>
                        <p>The vision of the IBEW has remained steady since established, to promote excellence and to improve the lives of each and every member.</p>
                    </div>
                </div>
        </div>
    </section>
    
        <!-- Values Section -->
        <section class="about-values-section">
            <div class="section-container">
                <div class="values-card">
                    <div class="section-eyebrow">
                        <span class="eyebrow-line"></span>
                        <span class="eyebrow-text">Our Promise</span>
                        <span class="eyebrow-line"></span>
                    </div>
                    <h2 class="about-section-title">Commitment to Excellence</h2>
                    <div class="commitment-list-wrapper">
                        <ul class="commitment-list">
                            <li>To organize all workers in the entire electrical industry in the United States and Canada, including all those in public utilities and electrical manufacturing into local unions.</li>
                            <li>To promote reasonable methods of work</li>
                            <li>To cultivate feelings of friendship among those of our industry</li>
                            <li>To settle all disputes between employers and employees by arbitration (if possible)</li>
                            <li>To assist each other in sickness or distress</li>
                            <li>To secure employment</li>
                            <li>To reduce the hours of daily labor</li>
                            <li>To secure adequate pay for our work</li>
                            <li>To seek a higher standard of living</li>
                            <li>To seek security for the individual</li>
                            <li>To properly elevate the moral, intellectual and social conditions of our members, their families and dependents, in the interest of a higher standard of citizenship.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Leadership Section -->
        <section class="about-leadership-section">
            <div class="section-container">
                <div class="leadership-header">
                    <div class="section-eyebrow section-eyebrow--left">
                        <span class="eyebrow-line"></span>
                        <span class="eyebrow-text">Leadership</span>
                        <span class="eyebrow-line"></span>
                    </div>
                    <h2 class="about-section-title about-section-title--left">Meet Your Officers</h2>
                    <p class="leadership-intro">Dedicated individuals elected to serve the membership and guide the future of Local 53.</p>
                </div>

                <div class="officers-grid">
                    <article class="officer-card">
                        <div class="officer-photo" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/officers/benjamin-bush.png');">
                            <div class="officer-photo-overlay">
                                <div>
                                    <h3 class="officer-name">Benjamin Bush</h3>
                                    <p class="officer-title">Business Manager/Financial Secretary</p>
                                </div>
                            </div>
                        </div>
                        <div class="officer-contact">
                            <div class="contact-item">
                                <span class="material-icons contact-icon">phone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Work</span>
                                    <span class="contact-value">(816) 421-5464 (Ext 204)</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <span class="material-icons contact-icon">smartphone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Cell</span>
                                    <span class="contact-value">(816) 499-3046</span>
                                </div>
                            </div>
                            <div class="contact-item contact-email">
                                <span class="material-icons contact-icon">email</span>
                                <a href="mailto:Bbush@ibewlocal53.org" class="contact-value">Bbush@ibewlocal53.org</a>
                            </div>
                        </div>
                    </article>

                    <article class="officer-card">
                        <div class="officer-photo" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/officers/kyle-neuenschwander.png');">
                            <div class="officer-photo-overlay">
                                <div>
                                    <h3 class="officer-name">Kyle Neuenschwander</h3>
                                    <p class="officer-title">Construction Business Representative</p>
                                </div>
                            </div>
                        </div>
                        <div class="officer-contact">
                            <div class="contact-item">
                                <span class="material-icons contact-icon">phone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Work</span>
                                    <span class="contact-value">(816) 421-5464 (Ext 214)</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <span class="material-icons contact-icon">smartphone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Cell</span>
                                    <span class="contact-value">(816) 489-2533</span>
                                </div>
                            </div>
                            <div class="contact-item contact-email">
                                <span class="material-icons contact-icon">email</span>
                                <a href="mailto:Kneuenschwander@ibewlocal53.org" class="contact-value">Kneuenschwander@ibewlocal53.org</a>
                            </div>
                        </div>
                        <div class="officer-committees">
                            <p class="committee-text">Dispatcher, Outside Construction, Telecommunications, City of Butler, Osage Valley Electric Coop, Southwest Electric Coop, Benefits, Veterans Committee</p>
                        </div>
                    </article>

                    <article class="officer-card">
                        <div class="officer-photo" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/officers/chad-mcgregor.png');">
                            <div class="officer-photo-overlay">
                                <div>
                                    <h3 class="officer-name">Chad McGregor</h3>
                                    <p class="officer-title">Business Representative</p>
                                </div>
                            </div>
                        </div>
                        <div class="officer-contact">
                            <div class="contact-item">
                                <span class="material-icons contact-icon">phone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Work</span>
                                    <span class="contact-value">(816) 421-5464 (Ext 205)</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <span class="material-icons contact-icon">smartphone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Cell</span>
                                    <span class="contact-value">(816) 699-9437</span>
                                </div>
                            </div>
                            <div class="contact-item contact-email">
                                <span class="material-icons contact-icon">email</span>
                                <a href="mailto:Cmcgregor@ibewlocal53.org" class="contact-value">Cmcgregor@ibewlocal53.org</a>
                            </div>
                        </div>
                        <div class="officer-committees">
                            <p class="committee-text">Dispatcher, Outside Construction, Telecommunications, City of Butler, Osage Valley Electric Coop, Southwest Electric Coop, Benefits, Veterans Committee</p>
                        </div>
                    </article>

                    <article class="officer-card">
                        <div class="officer-photo" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/officers/allen-dixon.png');">
                            <div class="officer-photo-overlay">
                                <div>
                                    <h3 class="officer-name">Allen Dixon</h3>
                                    <p class="officer-title">Business Representative</p>
                                </div>
                            </div>
                        </div>
                        <div class="officer-contact">
                            <div class="contact-item">
                                <span class="material-icons contact-icon">phone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Work</span>
                                    <span class="contact-value">(816) 421-5464 (Ext 207)</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <span class="material-icons contact-icon">smartphone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Cell</span>
                                    <span class="contact-value">(816) 384-4818</span>
                                </div>
                            </div>
                            <div class="contact-item contact-email">
                                <span class="material-icons contact-icon">email</span>
                                <a href="mailto:Adixon@ibewlocal53.org" class="contact-value">Adixon@ibewlocal53.org</a>
                            </div>
                        </div>
                        <div class="officer-committees">
                            <p class="committee-text">Dispatcher, Outside Construction, Telecommunications, City of Butler, Osage Valley Electric Coop, Southwest Electric Coop, Benefits, Veterans Committee</p>
                        </div>
                    </article>

                    <article class="officer-card">
                        <div class="officer-photo" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/officers/steve-burkhart.png');">
                            <div class="officer-photo-overlay">
                                <div>
                                    <h3 class="officer-name">Steve Burkhart</h3>
                                    <p class="officer-title">Business Representative</p>
                                </div>
                            </div>
                        </div>
                        <div class="officer-contact">
                            <div class="contact-item">
                                <span class="material-icons contact-icon">phone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Work</span>
                                    <span class="contact-value">(816) 421-5464 (Ext 212)</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <span class="material-icons contact-icon">smartphone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Cell</span>
                                    <span class="contact-value">(816) 382-7465</span>
                                </div>
                            </div>
                            <div class="contact-item contact-email">
                                <span class="material-icons contact-icon">email</span>
                                <a href="mailto:Sburkhart@ibewlocal53.org" class="contact-value">Sburkhart@ibewlocal53.org</a>
                            </div>
                        </div>
                        <div class="officer-committees">
                            <p class="committee-text">Associated Electric Cooperative, LaClede Electric Cooperative, New Mac Electric Cooperative, Se-Ma-No Electric Cooperative, Sho-Me Electric Cooperative, Webster Electric Cooperative</p>
                        </div>
                    </article>

                    <article class="officer-card">
                        <div class="officer-photo" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/officers/andre-tinoco.png');">
                            <div class="officer-photo-overlay">
                                <div>
                                    <h3 class="officer-name">Andre Tinoco</h3>
                                    <p class="officer-title">Business Representative</p>
                                </div>
                            </div>
                        </div>
                        <div class="officer-contact">
                            <div class="contact-item">
                                <span class="material-icons contact-icon">phone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Work</span>
                                    <span class="contact-value">(816) 421-5464 (Ext 211)</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <span class="material-icons contact-icon">smartphone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Cell</span>
                                    <span class="contact-value">(816) 446-0008</span>
                                </div>
                            </div>
                            <div class="contact-item contact-email">
                                <span class="material-icons contact-icon">email</span>
                                <a href="mailto:Atinoco@ibewlocal53.org" class="contact-value">Atinoco@ibewlocal53.org</a>
                            </div>
                        </div>
                        <div class="officer-committees">
                            <p class="committee-text">Line Clearance and Tree Trimming (Asplundh), (City Utilities), (Royer Brothers), (Shade Tree), (Thorne Tree), (Wright Tree)</p>
                        </div>
                    </article>

                    <article class="officer-card">
                        <div class="officer-photo" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/officers/johnny-whitaker.png');">
                            <div class="officer-photo-overlay">
                                <div>
                                    <h3 class="officer-name">Johnny Whitaker</h3>
                                    <p class="officer-title">Business Representative/Organizer</p>
                                </div>
                            </div>
                        </div>
                        <div class="officer-contact">
                            <div class="contact-item">
                                <span class="material-icons contact-icon">phone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Work</span>
                                    <span class="contact-value">(816) 421-5464 (Ext 210)</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <span class="material-icons contact-icon">smartphone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Cell</span>
                                    <span class="contact-value">(816) 548-5502</span>
                                </div>
                            </div>
                            <div class="contact-item contact-email">
                                <span class="material-icons contact-icon">email</span>
                                <a href="mailto:Jwhitaker@ibewlocal53.org" class="contact-value">Jwhitaker@ibewlocal53.org</a>
                            </div>
                        </div>
                        <div class="officer-committees">
                            <p class="committee-text">IBEW Local 53 Organizer</p>
                        </div>
                    </article>

                    <article class="officer-card">
                        <div class="officer-photo" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/officers/matthew-day.png');">
                            <div class="officer-photo-overlay">
                                <div>
                                    <h3 class="officer-name">Matthew Day</h3>
                                    <p class="officer-title">Business Representative/Organizer</p>
                                </div>
                            </div>
                        </div>
                        <div class="officer-contact">
                            <div class="contact-item">
                                <span class="material-icons contact-icon">phone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Work</span>
                                    <span class="contact-value">(816) 421-5464 (Ext 202)</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <span class="material-icons contact-icon">smartphone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Cell</span>
                                    <span class="contact-value">(816) 337-6461</span>
                                </div>
                            </div>
                            <div class="contact-item contact-email">
                                <span class="material-icons contact-icon">email</span>
                                <a href="mailto:Mday@ibewlocal53.org" class="contact-value">Mday@ibewlocal53.org</a>
                            </div>
                        </div>
                        <div class="officer-committees">
                            <p class="committee-text">Dispatcher, Outside Construction, West Central Electric Cooperative</p>
                        </div>
                    </article>

                    <article class="officer-card">
                        <div class="officer-photo" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/officers/sharniece-lewis.png');">
                            <div class="officer-photo-overlay">
                                <div>
                                    <h3 class="officer-name">Sharniece Lewis</h3>
                                    <p class="officer-title">Organizer</p>
                                </div>
                            </div>
                        </div>
                        <div class="officer-contact">
                            <div class="contact-item">
                                <span class="material-icons contact-icon">phone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Work</span>
                                    <span class="contact-value">(816) 421-5464 (Ext 206)</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <span class="material-icons contact-icon">smartphone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Cell</span>
                                    <span class="contact-value">(816) 614-6169</span>
                                </div>
                            </div>
                            <div class="contact-item contact-email">
                                <span class="material-icons contact-icon">email</span>
                                <a href="mailto:Slewis@ibewlocal53.org" class="contact-value">Slewis@ibewlocal53.org</a>
                            </div>
                        </div>
                        <div class="officer-committees">
                            <p class="committee-text">IBEW Local 53 Organizer, Renew VP, Scholarship Committee, Women's Committee.</p>
                        </div>
                    </article>

                    <article class="officer-card">
                        <div class="officer-photo" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/officers/savanna-zeller.png');">
                            <div class="officer-photo-overlay">
                                <div>
                                    <h3 class="officer-name">Savanna Zeller</h3>
                                    <p class="officer-title">Executive Assistant</p>
                                </div>
                            </div>
                        </div>
                        <div class="officer-contact">
                            <div class="contact-item">
                                <span class="material-icons contact-icon">phone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Work</span>
                                    <span class="contact-value">(816) 421-5464 (Ext 213)</span>
                                </div>
                            </div>
                            <div class="contact-item contact-email">
                                <span class="material-icons contact-icon">email</span>
                                <a href="mailto:Szeller@ibewlocal53.org" class="contact-value">Szeller@ibewlocal53.org</a>
                            </div>
                        </div>
                    </article>

                    <article class="officer-card">
                        <div class="officer-photo" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/officers/jessica-looney.png');">
                            <div class="officer-photo-overlay">
                                <div>
                                    <h3 class="officer-name">Jessica Looney</h3>
                                    <p class="officer-title">Executive Assistant</p>
                                </div>
                            </div>
                        </div>
                        <div class="officer-contact">
                            <div class="contact-item">
                                <span class="material-icons contact-icon">phone</span>
                                <div class="contact-details">
                                    <span class="contact-label">Work</span>
                                    <span class="contact-value">(816) 421-5464 (Ext 201)</span>
                                </div>
                            </div>
                            <div class="contact-item contact-email">
                                <span class="material-icons contact-icon">email</span>
                                <a href="mailto:Jlooney@ibewlocal53.org" class="contact-value">Jlooney@ibewlocal53.org</a>
            </div>
        </div>
    </article>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="about-cta-section">
            <div class="section-container">
                <div class="about-cta-card">
                    <div class="about-cta-content">
                        <div class="about-cta-pill">Join the Movement</div>
                        <h2 class="cta-title">Ready to Strengthen Your Future?</h2>
                        <p class="cta-description">Contact us today to learn more about membership benefits, apprenticeship programs, and how Local 53 can work for you.</p>
                        <div class="cta-buttons">
                            <a href="<?php echo home_url('/contact'); ?>" class="btn btn-cta-primary">
                                Contact Us
                                <span class="material-icons">arrow_forward</span>
                            </a>
                            <a href="#" class="btn btn-cta-secondary">Member Resources</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php endwhile; ?>

<?php
get_footer();

