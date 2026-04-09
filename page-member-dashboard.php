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
		<ul class="member-dashboard-list">
			<?php
			if ( $dashboard_resources->have_posts() ) :
				while ( $dashboard_resources->have_posts() ) :
					$dashboard_resources->the_post();
					$file_info = function_exists( 'ibew_local_53_get_resource_file_info' ) ? ibew_local_53_get_resource_file_info( get_the_ID() ) : null;
					$href      = '';
					if ( $file_info && ! empty( $file_info['url'] ) ) {
						$href = $file_info['url'];
					}
					?>
					<li class="member-dashboard-list-item">
						<?php if ( $href ) : ?>
							<a href="<?php echo esc_url( $href ); ?>" class="member-dashboard-list-link"><?php the_title(); ?></a>
						<?php else : ?>
							<span class="member-dashboard-list-text"><?php the_title(); ?></span>
						<?php endif; ?>
					</li>
					<?php
				endwhile;
				wp_reset_postdata();
			else :
				?>
				<li class="member-dashboard-list-item"><span class="member-dashboard-list-muted"><?php esc_html_e( 'No resources available yet.', 'ibew-local-53' ); ?></span></li>
			<?php endif; ?>
		</ul>
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
