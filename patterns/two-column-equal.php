<?php
/**
 * Two Column: Equal Columns
 * Block pattern content – two equal columns for text or mixed content.
 *
 * @package IBEW_Local_53
 */
?>
<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"2rem"}}}} -->
<div class="wp-block-columns">
	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:heading {"level":2} -->
		<h2 class="wp-block-heading"><?php esc_html_e('Left Column Heading', 'ibew-local-53'); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph -->
		<p><?php esc_html_e('Add content for the left column. You can add paragraphs, lists, images, or buttons.', 'ibew-local-53'); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:column -->
	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:heading {"level":2} -->
		<h2 class="wp-block-heading"><?php esc_html_e('Right Column Heading', 'ibew-local-53'); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph -->
		<p><?php esc_html_e('Add content for the right column. You can add paragraphs, lists, images, or buttons.', 'ibew-local-53'); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:column -->
</div>
<!-- /wp:columns -->
