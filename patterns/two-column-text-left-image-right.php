<?php
/**
 * Two Column: Text Left, Image Right
 * Block pattern content – heading and body on the left, image on the right.
 *
 * @package IBEW_Local_53
 */

$image_url = get_template_directory_uri() . '/assets/images/lineman-working.jpg';
?>
<!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"2rem"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center">
	<!-- wp:column {"verticalAlignment":"center","width":"50%"} -->
	<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%">
		<!-- wp:heading {"level":2} -->
		<h2 class="wp-block-heading"><?php esc_html_e('Section Heading', 'ibew-local-53'); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph -->
		<p><?php esc_html_e('Add your introductory text here. This block layout places your title and body copy on the left with an image on the right. Replace the image and edit the text to match your content.', 'ibew-local-53'); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:column -->
	<!-- wp:column {"verticalAlignment":"center","width":"50%"} -->
	<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%">
		<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
		<figure class="wp-block-image size-large"><img src="<?php echo esc_url($image_url); ?>" alt="<?php esc_attr_e('Section image', 'ibew-local-53'); ?>"/></figure>
		<!-- /wp:image -->
	</div>
	<!-- /wp:column -->
</div>
<!-- /wp:columns -->
