<?php
/**
 * Template Name: Member Dashboard
 *
 * Member home: welcome, quick nav, featured resources, forms, upcoming events.
 *
 * @package IBEW_Local_53
 */

get_header();

$user = wp_get_current_user();
$name = ($user && $user->exists()) ? $user->display_name : '';

// Featured document resources (same rules as Resources page documents section).
$dashboard_resources = new WP_Query(
	array(
		'post_type'      => 'resource',
		'posts_per_page' => 4,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'meta_query'     => array(
			'relation' => 'OR',
			array(
				'key'     => 'resource_type',
				'value'   => 'document',
				'compare' => '=',
			),
			array(
				'key'     => 'resource_type',
				'compare' => 'NOT EXISTS',
			),
		),
	)
);

// Match datetime-local values saved from the event editor (site-local).
$now_meta = current_time( 'Y-m-d' ) . 'T' . current_time( 'H:i' );

$dashboard_events = new WP_Query(
	array(
		'post_type'      => 'event',
		'posts_per_page' => 3,
		'meta_key'       => 'event_start_datetime',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query'     => array(
			array(
				'key'     => 'event_start_datetime',
				'value'   => $now_meta,
				'compare' => '>=',
				'type'    => 'CHAR',
			),
		),
	)
);

/**
 * Resolve permalink for a page slug, or empty if not found.
 *
 * @param string $slug Page slug.
 * @return string
 */
$ibew_dashboard_page_link = static function ( $slug ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );
	return ( $page && 'publish' === $page->post_status ) ? get_permalink( $page ) : '';
};

$resources_hub_url = home_url( '/resources/' );
$resources_pages   = get_posts(
	array(
		'post_type'      => 'page',
		'posts_per_page' => 1,
		'post_status'    => 'publish',
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'page-resources.php',
		'fields'         => 'ids',
	)
);
if ( ! empty( $resources_pages ) ) {
	$resources_hub_url = get_permalink( $resources_pages[0] );
}

$events_archive_url = get_post_type_archive_link( 'event' );
if ( ! $events_archive_url ) {
	$events_archive_url = home_url( '/events/' );
}

$account_url = ( function_exists( 'pmpro_url' ) ) ? pmpro_url( 'account' ) : home_url( '/membership-account/' );

$dashboard_forms = array(
	array(
		'slug'  => 'out-of-work',
		'label' => __( 'Out of Work Form', 'ibew-local-53' ),
	),
	array(
		'slug'  => 'update-contact-info',
		'label' => __( 'Update Contact Info', 'ibew-local-53' ),
	),
	array(
		'slug'  => 'member-inquiry',
		'label' => __( 'Member Inquiry', 'ibew-local-53' ),
	),
	array(
		'slug'  => 'training-request',
		'label' => __( 'Training Request', 'ibew-local-53' ),
	),
);

$contact_page_url = $ibew_dashboard_page_link( 'contact' );
$forms_hub_url    = $ibew_dashboard_page_link( 'forms' );
if ( ! $forms_hub_url ) {
	$forms_hub_url = '#member-dashboard-forms';
}
?>

<section class="archive-hero resources-hero member-dashboard-hero">
	<div class="archive-hero-container reveal-fade-up">
		<h1 class="hero-title">
			<?php if ( $name ) : ?>
				<?php
				printf(
					/* translators: %s: member display name */
					esc_html__( 'Welcome back, %s', 'ibew-local-53' ),
					esc_html( $name )
				);
				?>
			<?php else : ?>
				<?php esc_html_e( 'Welcome back', 'ibew-local-53' ); ?>
			<?php endif; ?>
		</h1>
		<p class="hero-subtext"><?php esc_html_e( 'Access your Local 53 member resources, forms, and upcoming events.', 'ibew-local-53' ); ?></p>
	</div>
</section>

<div class="member-dashboard-wrap resources-page-container">
	<?php ibew_local_53_render_member_dashboard_nav( array( 'show_rule' => true ) ); ?>

	<section id="member-dashboard-resources" class="member-dashboard-section resources-documents-section">
		<div class="member-dashboard-section-head">
			<h2 class="section-title"><?php esc_html_e( 'Resources', 'ibew-local-53' ); ?></h2>
		</div>
		<div class="resources-grid">
			<?php
			if ( $dashboard_resources->have_posts() ) :
				while ( $dashboard_resources->have_posts() ) :
					$dashboard_resources->the_post();
					$file_info     = function_exists( 'ibew_local_53_get_resource_file_info' ) ? ibew_local_53_get_resource_file_info( get_the_ID() ) : null;
					$categories    = get_the_terms( get_the_ID(), 'resource_category' );
					$category_name = ! empty( $categories ) ? $categories[0]->name : '';

					$icon_bg_color = '#fef2f2';
					$icon_color    = '#dc2626';
					if ( ! empty( $categories ) ) {
						$cat_slug = $categories[0]->slug;
						switch ( $cat_slug ) {
							case 'contracts':
								$icon_bg_color = '#fef2f2';
								$icon_color    = '#dc2626';
								break;
							case 'safety':
								$icon_bg_color = '#fef3c7';
								$icon_color    = '#d97706';
								break;
							case 'benefits':
								$icon_bg_color = '#dbeafe';
								$icon_color    = '#2563eb';
								break;
							case 'wage-scales':
								$icon_bg_color = '#dcfce7';
								$icon_color    = '#16a34a';
								break;
							default:
								$icon_bg_color = '#f3f4f6';
								$icon_color    = '#6b7280';
						}
					}
					?>
					<article class="resource-card">
						<div class="resource-card-content">
							<div class="resource-icon" style="background-color: <?php echo esc_attr( $icon_bg_color ); ?>;">
								<svg width="30" height="36" viewBox="0 0 30 36" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M18.75 0H3.75C1.6875 0 0 1.6875 0 3.75V32.25C0 34.3125 1.6875 36 3.75 36H26.25C28.3125 36 30 34.3125 30 32.25V11.25L18.75 0ZM22.5 28.5H7.5V24.75H22.5V28.5ZM22.5 21H7.5V17.25H22.5V21ZM16.875 13.125V2.8125L27.1875 13.125H16.875Z" fill="<?php echo esc_attr( $icon_color ); ?>"/>
								</svg>
							</div>
							<div class="resource-info">
								<?php if ( $category_name ) : ?>
									<span class="resource-category"><?php echo esc_html( $category_name ); ?></span>
								<?php endif; ?>
								<h3 class="resource-title"><?php the_title(); ?></h3>
								<?php if ( $file_info ) : ?>
									<span class="resource-meta"><?php echo esc_html( $file_info['type'] ); ?> • <?php echo esc_html( $file_info['size'] ); ?> • <?php esc_html_e( 'Updated', 'ibew-local-53' ); ?> <?php echo esc_html( $file_info['updated'] ); ?></span>
								<?php endif; ?>
							</div>
						</div>
						<?php if ( $file_info && ! empty( $file_info['url'] ) ) : ?>
							<div class="resource-actions">
								<a href="<?php echo esc_url( $file_info['url'] ); ?>" class="btn btn-download" download>
									<svg width="18" height="22" viewBox="0 0 18 22" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M18 7.75H12.75V0.25H5.25V7.75H0L9 16.75L18 7.75ZM0 19.25V21.75H18V19.25H0Z" fill="currentColor"/>
									</svg>
									<span><?php esc_html_e( 'Download', 'ibew-local-53' ); ?></span>
								</a>
							</div>
						<?php else : ?>
							<div class="resource-actions">
								<span class="member-dashboard-list-muted"><?php esc_html_e( 'File unavailable', 'ibew-local-53' ); ?></span>
							</div>
						<?php endif; ?>
					</article>
					<?php
				endwhile;
				wp_reset_postdata();
			else :
				?>
				<div class="no-resources-message">
					<p><?php esc_html_e( 'No resources available yet.', 'ibew-local-53' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
		<p class="member-dashboard-view-all">
			<a href="<?php echo esc_url( $resources_hub_url ); ?>" class="btn btn-tertiary"><?php esc_html_e( 'View All Resources', 'ibew-local-53' ); ?></a>
		</p>
	</section>

	<hr class="member-dashboard-rule" />

	<section id="member-dashboard-forms" class="member-dashboard-section resources-documents-section">
		<div class="member-dashboard-section-head">
			<h2 class="section-title"><?php esc_html_e( 'Forms', 'ibew-local-53' ); ?></h2>
		</div>
		<ul class="member-dashboard-list">
			<?php foreach ( $dashboard_forms as $form ) : ?>
				<?php
				$form_url = $ibew_dashboard_page_link( $form['slug'] );
				?>
				<li class="member-dashboard-list-item">
					<?php if ( $form_url ) : ?>
						<a href="<?php echo esc_url( $form_url ); ?>" class="member-dashboard-list-link"><?php echo esc_html( $form['label'] ); ?></a>
					<?php else : ?>
						<span class="member-dashboard-list-text"><?php echo esc_html( $form['label'] ); ?></span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<p class="member-dashboard-view-all">
			<a href="<?php echo esc_url( $forms_hub_url ); ?>" class="btn btn-tertiary"><?php esc_html_e( 'View All Forms', 'ibew-local-53' ); ?></a>
		</p>
	</section>

	<hr class="member-dashboard-rule" />

	<section id="member-dashboard-events" class="member-dashboard-section resources-documents-section">
		<div class="member-dashboard-section-head">
			<h2 class="section-title"><?php esc_html_e( 'Upcoming Events', 'ibew-local-53' ); ?></h2>
		</div>
		<ul class="member-dashboard-list">
			<?php
			if ( $dashboard_events->have_posts() ) :
				while ( $dashboard_events->have_posts() ) :
					$dashboard_events->the_post();
					$start = get_post_meta( get_the_ID(), 'event_start_datetime', true );
					$label = get_the_title();
					if ( $start && function_exists( 'ibew_local_53_format_event_date' ) ) {
						$label .= ' – ' . ibew_local_53_format_event_date( $start, 'F j' );
					}
					?>
					<li class="member-dashboard-list-item">
						<a href="<?php the_permalink(); ?>" class="member-dashboard-list-link"><?php echo esc_html( $label ); ?></a>
					</li>
					<?php
				endwhile;
				wp_reset_postdata();
			else :
				?>
				<li class="member-dashboard-list-item"><span class="member-dashboard-list-muted"><?php esc_html_e( 'No upcoming events scheduled.', 'ibew-local-53' ); ?></span></li>
			<?php endif; ?>
		</ul>
		<p class="member-dashboard-view-all">
			<a href="<?php echo esc_url( $events_archive_url ); ?>" class="btn btn-tertiary"><?php esc_html_e( 'View All Events', 'ibew-local-53' ); ?></a>
		</p>
	</section>

	<hr class="member-dashboard-rule" />

	<section class="member-dashboard-help resources-cta-section" aria-labelledby="member-dashboard-help-heading">
		<div class="cta-content reveal-fade-up">
			<h3 id="member-dashboard-help-heading" class="cta-title"><?php esc_html_e( 'Need Help?', 'ibew-local-53' ); ?></h3>
			<p class="cta-text">
				<?php esc_html_e( 'Contact the hall office or visit your account page.', 'ibew-local-53' ); ?>
			</p>
		</div>
		<div class="cta-actions reveal-fade-up reveal-delay-1">
			<?php if ( $contact_page_url ) : ?>
				<a href="<?php echo esc_url( $contact_page_url ); ?>" class="btn btn-cta-gold">
					<?php esc_html_e( 'Contact', 'ibew-local-53' ); ?>
					<span class="material-icons">arrow_forward</span>
				</a>
			<?php else : ?>
				<a href="mailto:localrep@ibewlocal53.org" class="btn btn-cta-gold">
					<?php esc_html_e( 'Email the hall', 'ibew-local-53' ); ?>
					<span class="material-icons">arrow_forward</span>
				</a>
			<?php endif; ?>
			<a href="<?php echo esc_url( $account_url ); ?>" class="btn btn-cta-outline"><?php esc_html_e( 'My Account', 'ibew-local-53' ); ?></a>
		</div>
	</section>

	<?php
	while ( have_posts() ) :
		the_post();
		$content = get_post()->post_content;
		if ( trim( $content ) !== '' ) :
			?>
			<article class="member-dashboard-editor-content page-content">
				<div class="page-container" style="padding-top: 0;">
					<div class="page-content-wrapper">
						<?php the_content(); ?>
					</div>
				</div>
			</article>
			<?php
		endif;
	endwhile;
	?>
</div>

<?php
get_footer();
