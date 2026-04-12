<?php
/**
 * Template Name: Member Register
 *
 * Simple registration: first name, last name, email (PMPro free level applied via user_register when configured).
 *
 * @package IBEW_Local_53
 */

get_header();

$register_page_id = is_singular( 'page' ) ? get_queried_object_id() : 0;

$login_url   = function_exists( 'pmpro_login_url' ) ? pmpro_login_url() : wp_login_url( get_permalink() );
$levels_url  = function_exists( 'pmpro_url' ) ? pmpro_url( 'levels' ) : '';
$reg_status  = isset( $_GET['ibew_reg'] ) ? sanitize_text_field( wp_unslash( $_GET['ibew_reg'] ) ) : '';
$can_register = (bool) get_option( 'users_can_register' );
?>

<div class="member-register-page-wrapper">
	<section class="contact-hero member-register-hero">
		<div class="contact-hero-card">
			<div class="hero-pill"><?php esc_html_e( 'New members', 'ibew-local-53' ); ?></div>
			<h1 class="hero-title">
				<?php esc_html_e( 'Create your', 'ibew-local-53' ); ?> <span class="gold-text"><?php esc_html_e( 'member account', 'ibew-local-53' ); ?></span>
			</h1>
			<p class="hero-subtext">
				<?php esc_html_e( 'Enter your name and email below. We will send you a link to set your password and log in.', 'ibew-local-53' ); ?>
			</p>
		</div>
	</section>

	<div class="member-register-container">
		<div class="member-register-card reveal-fade-up">
			<?php if ( 'success' === $reg_status ) : ?>
				<div class="ibew-register-notice ibew-register-notice--success" role="status">
					<p><?php esc_html_e( 'Check your email for a link to set your password and access your account.', 'ibew-local-53' ); ?></p>
				</div>
			<?php elseif ( 'duplicate' === $reg_status ) : ?>
				<div class="ibew-register-notice ibew-register-notice--error" role="alert">
					<p><?php esc_html_e( 'An account with this email already exists. Try logging in or reset your password.', 'ibew-local-53' ); ?></p>
				</div>
			<?php elseif ( 'invalid' === $reg_status ) : ?>
				<div class="ibew-register-notice ibew-register-notice--error" role="alert">
					<p><?php esc_html_e( 'Please enter your first name, last name, and a valid email address.', 'ibew-local-53' ); ?></p>
				</div>
			<?php elseif ( 'closed' === $reg_status ) : ?>
				<div class="ibew-register-notice ibew-register-notice--error" role="alert">
					<p><?php esc_html_e( 'Registration is closed. Please contact the hall for assistance.', 'ibew-local-53' ); ?></p>
				</div>
			<?php elseif ( 'logged_in' === $reg_status ) : ?>
				<div class="ibew-register-notice ibew-register-notice--error" role="alert">
					<p><?php esc_html_e( 'You are already logged in.', 'ibew-local-53' ); ?></p>
				</div>
			<?php elseif ( 'error' === $reg_status ) : ?>
				<div class="ibew-register-notice ibew-register-notice--error" role="alert">
					<p><?php esc_html_e( 'Something went wrong. Please try again or contact the hall.', 'ibew-local-53' ); ?></p>
				</div>
			<?php endif; ?>

			<?php if ( is_user_logged_in() ) : ?>
				<p class="member-register-logged-in">
					<?php esc_html_e( 'You are signed in.', 'ibew-local-53' ); ?>
					<?php if ( function_exists( 'pmpro_url' ) ) : ?>
						<a href="<?php echo esc_url( pmpro_url( 'account' ) ); ?>"><?php esc_html_e( 'My account', 'ibew-local-53' ); ?></a>
					<?php endif; ?>
				</p>
			<?php elseif ( 'success' === $reg_status ) : ?>
				<p class="member-register-after-success">
					<a class="btn btn-tertiary" href="<?php echo esc_url( $login_url ); ?>"><?php esc_html_e( 'Go to login', 'ibew-local-53' ); ?></a>
				</p>
			<?php elseif ( ! $can_register ) : ?>
				<p class="member-register-fallback">
					<?php esc_html_e( 'Online registration is not available right now. Please contact the hall for assistance.', 'ibew-local-53' ); ?>
				</p>
				<p class="member-register-fallback-actions">
					<?php
					$contact_pg   = get_page_by_path( 'contact', OBJECT, 'page' );
					$contact_href = ( $contact_pg && 'publish' === $contact_pg->post_status )
						? get_permalink( $contact_pg )
						: home_url( '/contact/' );
					?>
					<a class="btn btn-cta-outline" href="<?php echo esc_url( $contact_href ); ?>"><?php esc_html_e( 'Contact us', 'ibew-local-53' ); ?></a>
				</p>
			<?php else : ?>
				<form class="ibew-member-register-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="ibew_member_register" />
					<input type="hidden" name="ibew_register_page_id" value="<?php echo esc_attr( (string) $register_page_id ); ?>" />
					<?php wp_nonce_field( 'ibew_member_register', 'ibew_member_register_nonce' ); ?>
					<input type="hidden" name="form_time" value="<?php echo esc_attr( (string) time() ); ?>" />
					<p class="ibew-register-honeypot" aria-hidden="true">
						<label for="ibew_reg_website"><?php esc_html_e( 'Website', 'ibew-local-53' ); ?></label>
						<input type="text" name="website" id="ibew_reg_website" value="" tabindex="-1" autocomplete="off" />
					</p>

					<div class="ibew-register-fields">
						<div class="ibew-register-field">
							<label for="ibew_reg_first_name"><?php esc_html_e( 'First name', 'ibew-local-53' ); ?> <span class="req">*</span></label>
							<input type="text" name="ibew_reg_first_name" id="ibew_reg_first_name" required autocomplete="given-name" maxlength="60" />
						</div>
						<div class="ibew-register-field">
							<label for="ibew_reg_last_name"><?php esc_html_e( 'Last name', 'ibew-local-53' ); ?> <span class="req">*</span></label>
							<input type="text" name="ibew_reg_last_name" id="ibew_reg_last_name" required autocomplete="family-name" maxlength="60" />
						</div>
						<div class="ibew-register-field ibew-register-field--full">
							<label for="ibew_reg_email"><?php esc_html_e( 'Email', 'ibew-local-53' ); ?> <span class="req">*</span></label>
							<input type="email" name="ibew_reg_email" id="ibew_reg_email" required autocomplete="email" maxlength="100" />
						</div>
					</div>

					<p class="ibew-register-submit-wrap">
						<button type="submit" class="btn btn-primary ibew-register-submit"><?php esc_html_e( 'Register now', 'ibew-local-53' ); ?></button>
					</p>
				</form>
			<?php endif; ?>
			<p class="member-register-secondary reveal-fade-up">
			<?php esc_html_e( 'Already a member?', 'ibew-local-53' ); ?>
			<a href="<?php echo esc_url( $login_url ); ?>"><?php esc_html_e( 'Log in', 'ibew-local-53' ); ?></a>
		</p>
		</div>

		<?php
		while ( have_posts() ) :
			the_post();
			if ( trim( get_post()->post_content ) !== '' ) :
				?>
				<div class="member-register-editor reveal-fade-up">
					<div class="page-content-wrapper">
						<?php the_content(); ?>
					</div>
				</div>
				<?php
			endif;
		endwhile;
		?>
	</div>
</div>

<?php
get_footer();
