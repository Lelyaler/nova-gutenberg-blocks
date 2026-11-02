<?php
/**
 * Server-Side Render template for Pricing & Tier Matrix Block.
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
$discount        = isset( $attributes['annualDiscount'] ) ? (int) $attributes['annualDiscount'] : 20;
$currency_symbol = ! empty( $attributes['currencySymbol'] ) ? esc_attr( $attributes['currencySymbol'] ) : '$';
$plans           = ! empty( $attributes['plans'] ) && is_array( $attributes['plans'] ) ? $attributes['plans'] : [];

$wrapper_attributes = get_block_wrapper_attributes( [
	'class' => 'nova-pricing-block',
] );
?>

<div <?php echo $wrapper_attributes; ?> data-nova-pricing="true">
	<div class="nova-pricing__header">
		<?php if ( $title ) : ?>
			<h3 class="nova-pricing__title"><?php echo $title; ?></h3>
		<?php endif; ?>
		<?php if ( $subtitle ) : ?>
			<p class="nova-pricing__subtitle"><?php echo $subtitle; ?></p>
		<?php endif; ?>

		<div class="nova-pricing__billing-toggle" role="radiogroup" aria-label="<?php esc_attr_e( 'Billing Cycle Switcher', 'nova-blocks' ); ?>">
			<button type="button" class="nova-pricing__billing-btn is-active" data-billing="monthly">
				<?php esc_html_e( 'Monthly Billing', 'nova-blocks' ); ?>
			</button>
			<button type="button" class="nova-pricing__billing-btn" data-billing="annual">
				<?php esc_html_e( 'Annual Billing', 'nova-blocks' ); ?>
				<?php if ( $discount > 0 ) : ?>
					<span class="nova-pricing__discount-tag">-<?php echo esc_html( $discount ); ?>%</span>
				<?php endif; ?>
			</button>
		</div>
	</div>

	<div class="nova-pricing__grid">
		<?php foreach ( $plans as $plan ) :
			$name           = ! empty( $plan['name'] ) ? esc_html( $plan['name'] ) : '';
			$tagline        = ! empty( $plan['tagline'] ) ? esc_html( $plan['tagline'] ) : '';
			$price_monthly  = isset( $plan['priceMonthly'] ) ? (float) $plan['priceMonthly'] : 0;
			$price_annual   = isset( $plan['priceAnnual'] ) ? (float) $plan['priceAnnual'] : 0;
			$is_popular     = ! empty( $plan['isPopular'] );
			$badge          = ! empty( $plan['badge'] ) ? esc_html( $plan['badge'] ) : ( $is_popular ? esc_html__( 'Most Popular', 'nova-blocks' ) : '' );
			$features       = ! empty( $plan['features'] ) && is_array( $plan['features'] ) ? $plan['features'] : [];
			$cta_text       = ! empty( $plan['ctaText'] ) ? esc_html( $plan['ctaText'] ) : esc_html__( 'Choose Plan', 'nova-blocks' );
			$cta_url        = ! empty( $plan['ctaUrl'] ) ? esc_url( $plan['ctaUrl'] ) : '#';
			$card_classes   = 'nova-pricing__card' . ( $is_popular ? ' nova-pricing__card--featured' : '' );
		?>
			<div class="<?php echo esc_attr( $card_classes ); ?>" data-price-monthly="<?php echo esc_attr( $price_monthly ); ?>" data-price-annual="<?php echo esc_attr( $price_annual ); ?>">
				<?php if ( $badge ) : ?>
					<div class="nova-pricing__card-badge"><?php echo $badge; ?></div>
				<?php endif; ?>

				<div class="nova-pricing__card-head">
					<h4 class="nova-pricing__card-name"><?php echo $name; ?></h4>
					<?php if ( $tagline ) : ?>
						<p class="nova-pricing__card-tagline"><?php echo $tagline; ?></p>
					<?php endif; ?>
				</div>

				<div class="nova-pricing__card-price">
					<span class="nova-pricing__currency"><?php echo $currency_symbol; ?></span>
					<span class="nova-pricing__amount" data-pricing-amount><?php echo esc_html( number_format( $price_monthly, 0 ) ); ?></span>
					<span class="nova-pricing__period" data-pricing-period><?php esc_html_e( '/month', 'nova-blocks' ); ?></span>
				</div>

				<ul class="nova-pricing__features-list">
					<?php foreach ( $features as $feat ) : ?>
						<li class="nova-pricing__feature-item">
							<svg class="nova-pricing__check-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
								<polyline points="20 6 9 17 4 12"></polyline>
							</svg>
							<span><?php echo esc_html( $feat ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>

				<div class="nova-pricing__cta-box">
					<a href="<?php echo $cta_url; ?>" class="nova-pricing__btn <?php echo $is_popular ? 'nova-pricing__btn--primary' : 'nova-pricing__btn--secondary'; ?>">
						<?php echo $cta_text; ?>
					</a>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
