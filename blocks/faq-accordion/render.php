<?php
/**
 * Server-Side Render template for SEO FAQ Accordion Block.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block inner content.
 * @var WP_Block $block      Block instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title           = ! empty( $attributes['title'] ) ? esc_html( $attributes['title'] ) : '';
$subtitle        = ! empty( $attributes['subtitle'] ) ? esc_html( $attributes['subtitle'] ) : '';
$generate_schema = ! empty( $attributes['generateSchema'] );
$allow_multiple  = ! empty( $attributes['allowMultiple'] );
$items           = ! empty( $attributes['items'] ) && is_array( $attributes['items'] ) ? $attributes['items'] : [];

// If schema generation is enabled, register items with Schema_Generator
if ( $generate_schema && ! empty( $items ) && class_exists( '\\NovaBlocks\\Plugin' ) ) {
	\NovaBlocks\Plugin::instance()->get_schema_generator()->add_faq_items( $items );
}

$block_id = 'nova-faq-' . wp_unique_id();
$wrapper_attributes = get_block_wrapper_attributes( [
	'class' => 'nova-faq-block',
	'id'    => $block_id,
] );
?>

<div <?php echo $wrapper_attributes; ?> data-nova-faq="true" data-allow-multiple="<?php echo $allow_multiple ? 'true' : 'false'; ?>">
	<div class="nova-faq__header">
		<?php if ( $title ) : ?>
			<h3 class="nova-faq__title"><?php echo $title; ?></h3>
		<?php endif; ?>
		<?php if ( $subtitle ) : ?>
			<p class="nova-faq__subtitle"><?php echo $subtitle; ?></p>
		<?php endif; ?>
	</div>

	<div class="nova-faq__accordion" role="region" aria-label="<?php echo esc_attr( $title ? $title : __( 'FAQ List', 'nova-blocks' ) ); ?>">
		<?php foreach ( $items as $index => $item ) :
			$q = ! empty( $item['question'] ) ? esc_html( $item['question'] ) : '';
			$a = ! empty( $item['answer'] ) ? wp_kses_post( $item['answer'] ) : '';
			if ( empty( $q ) ) {
				continue;
			}
			$item_id = $block_id . '-item-' . $index;
			$ans_id  = $block_id . '-ans-' . $index;
			$is_first = ( 0 === $index );
		?>
			<div class="nova-faq__item <?php echo $is_first ? 'is-open' : ''; ?>" data-faq-item>
				<button
					type="button"
					class="nova-faq__question-btn"
					id="<?php echo esc_attr( $item_id ); ?>"
					aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>"
					aria-controls="<?php echo esc_attr( $ans_id ); ?>"
				>
					<span class="nova-faq__question-text"><?php echo $q; ?></span>
					<span class="nova-faq__icon-wrapper" aria-hidden="true">
						<svg class="nova-faq__arrow-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<polyline points="6 9 12 15 18 9"></polyline>
						</svg>
					</span>
				</button>

				<div
					id="<?php echo esc_attr( $ans_id ); ?>"
					class="nova-faq__answer-panel"
					role="region"
					aria-labelledby="<?php echo esc_attr( $item_id ); ?>"
					<?php echo $is_first ? '' : 'hidden'; ?>
				>
					<div class="nova-faq__answer-content">
						<?php echo $a; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
