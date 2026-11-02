<?php
/**
 * Server-Side Render template for Yield & ROI Calculator Block.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block inner content.
 * @var WP_Block $block      Block instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title          = ! empty( $attributes['title'] ) ? esc_html( $attributes['title'] ) : '';
$subtitle       = ! empty( $attributes['subtitle'] ) ? esc_html( $attributes['subtitle'] ) : '';
$symbol         = ! empty( $attributes['currencySymbol'] ) ? esc_attr( $attributes['currencySymbol'] ) : '$';
$code           = ! empty( $attributes['currencyCode'] ) ? esc_attr( $attributes['currencyCode'] ) : 'USD';
$min_amount     = isset( $attributes['minAmount'] ) ? (float) $attributes['minAmount'] : 500;
$max_amount     = isset( $attributes['maxAmount'] ) ? (float) $attributes['maxAmount'] : 100000;
$step_amount    = isset( $attributes['stepAmount'] ) ? (float) $attributes['stepAmount'] : 500;
$initial_amount = isset( $attributes['defaultAmount'] ) ? (float) $attributes['defaultAmount'] : 10000;
$apy            = isset( $attributes['defaultApy'] ) ? (float) $attributes['defaultApy'] : 14.8;
$months         = isset( $attributes['defaultMonths'] ) ? (int) $attributes['defaultMonths'] : 12;
$compounding    = ! empty( $attributes['compounding'] ) ? esc_attr( $attributes['compounding'] ) : 'monthly';
$accent_color   = ! empty( $attributes['accentColor'] ) ? esc_attr( $attributes['accentColor'] ) : '#fdca40';
$cta_text       = ! empty( $attributes['ctaText'] ) ? esc_html( $attributes['ctaText'] ) : '';
$cta_url        = ! empty( $attributes['ctaUrl'] ) ? esc_url( $attributes['ctaUrl'] ) : '#';
$show_breakdown = ! empty( $attributes['showBreakdown'] );

// Initial server calculations for zero-layout-shift and SEO SSR
$n = ( 'daily' === $compounding ) ? 365 : ( ( 'monthly' === $compounding ) ? 12 : 1 );
$t = $months / 12;
$r = ( $apy / 100 );
$total_return = $initial_amount * pow( 1 + ( $r / $n ), $n * $t );
$earned_profit = $total_return - $initial_amount;
$monthly_rate = $earned_profit / max( 1, $months );

$wrapper_attributes = get_block_wrapper_attributes( [
	'class' => 'nova-calc-block',
	'style' => '--nova-accent: ' . $accent_color . ';',
] );
?>

<div <?php echo $wrapper_attributes; ?>
	data-nova-calc="true"
	data-currency-symbol="<?php echo $symbol; ?>"
	data-currency-code="<?php echo $code; ?>"
	data-min="<?php echo esc_attr( $min_amount ); ?>"
	data-max="<?php echo esc_attr( $max_amount ); ?>"
	data-step="<?php echo esc_attr( $step_amount ); ?>"
	data-default-amount="<?php echo esc_attr( $initial_amount ); ?>"
	data-apy="<?php echo esc_attr( $apy ); ?>"
	data-months="<?php echo esc_attr( $months ); ?>"
	data-compounding="<?php echo esc_attr( $compounding ); ?>"
>
	<div class="nova-calc__header">
		<?php if ( $title ) : ?>
			<h3 class="nova-calc__title"><?php echo $title; ?></h3>
		<?php endif; ?>
		<?php if ( $subtitle ) : ?>
			<p class="nova-calc__subtitle"><?php echo $subtitle; ?></p>
		<?php endif; ?>
	</div>

	<div class="nova-calc__body">
		<!-- Left: Controls -->
		<div class="nova-calc__controls">
			<div class="nova-calc__field">
				<div class="nova-calc__field-head">
					<label class="nova-calc__label" for="nova-amount-input"><?php esc_html_e( 'Initial Deposit', 'nova-blocks' ); ?></label>
					<span class="nova-calc__amount-display">
						<span class="nova-calc__currency"><?php echo $symbol; ?></span>
						<span class="nova-calc__amount-val" data-calc-amount-text><?php echo number_format( $initial_amount, 0, '.', ',' ); ?></span>
					</span>
				</div>
				<div class="nova-calc__slider-wrap">
					<input
						type="range"
						id="nova-amount-input"
						class="nova-calc__slider"
						min="<?php echo esc_attr( $min_amount ); ?>"
						max="<?php echo esc_attr( $max_amount ); ?>"
						step="<?php echo esc_attr( $step_amount ); ?>"
						value="<?php echo esc_attr( $initial_amount ); ?>"
						aria-label="<?php esc_attr_e( 'Deposit Amount Slider', 'nova-blocks' ); ?>"
						data-calc-slider
					/>
				</div>
				<div class="nova-calc__presets">
					<button type="button" class="nova-calc__chip" data-preset-val="2500"><?php echo $symbol; ?>2,500</button>
					<button type="button" class="nova-calc__chip" data-preset-val="10000"><?php echo $symbol; ?>10,000</button>
					<button type="button" class="nova-calc__chip" data-preset-val="25000"><?php echo $symbol; ?>25,000</button>
					<button type="button" class="nova-calc__chip" data-preset-val="50000"><?php echo $symbol; ?>50,000</button>
				</div>
			</div>

			<div class="nova-calc__field">
				<div class="nova-calc__field-head">
					<label class="nova-calc__label"><?php esc_html_e( 'Investment Duration', 'nova-blocks' ); ?></label>
					<span class="nova-calc__term-display" data-calc-term-text><?php echo sprintf( esc_html__( '%d Months', 'nova-blocks' ), $months ); ?></span>
				</div>
				<div class="nova-calc__term-buttons" role="radiogroup" aria-label="<?php esc_attr_e( 'Investment Duration Selector', 'nova-blocks' ); ?>">
					<button type="button" class="nova-calc__term-btn <?php echo ( 3 === $months ) ? 'is-active' : ''; ?>" data-term="3">3M</button>
					<button type="button" class="nova-calc__term-btn <?php echo ( 6 === $months ) ? 'is-active' : ''; ?>" data-term="6">6M</button>
					<button type="button" class="nova-calc__term-btn <?php echo ( 12 === $months ) ? 'is-active' : ''; ?>" data-term="12">12M</button>
					<button type="button" class="nova-calc__term-btn <?php echo ( 24 === $months ) ? 'is-active' : ''; ?>" data-term="24">24M</button>
				</div>
			</div>
		</div>

		<!-- Right: Results Card -->
		<div class="nova-calc__results-card">
			<div class="nova-calc__stat-primary">
				<span class="nova-calc__stat-label"><?php esc_html_e( 'Projected Total Return', 'nova-blocks' ); ?></span>
				<div class="nova-calc__stat-value">
					<span class="nova-calc__currency"><?php echo $symbol; ?></span>
					<span data-calc-total><?php echo number_format( $total_return, 2, '.', ',' ); ?></span>
				</div>
			</div>

			<?php if ( $show_breakdown ) : ?>
				<div class="nova-calc__breakdown">
					<div class="nova-calc__stat-item">
						<span class="nova-calc__stat-item-label"><?php esc_html_e( 'Net Earned Interest', 'nova-blocks' ); ?></span>
						<span class="nova-calc__stat-item-val nova-calc__stat-item-val--profit">
							+<?php echo $symbol; ?><span data-calc-profit><?php echo number_format( $earned_profit, 2, '.', ',' ); ?></span>
						</span>
					</div>
					<div class="nova-calc__stat-item">
						<span class="nova-calc__stat-item-label"><?php esc_html_e( 'Monthly Passive Yield', 'nova-blocks' ); ?></span>
						<span class="nova-calc__stat-item-val">
							~<?php echo $symbol; ?><span data-calc-monthly><?php echo number_format( $monthly_rate, 2, '.', ',' ); ?></span>
						</span>
					</div>
					<div class="nova-calc__stat-item">
						<span class="nova-calc__stat-item-label"><?php esc_html_e( 'Annual APY Rate', 'nova-blocks' ); ?></span>
						<span class="nova-calc__stat-item-val nova-calc__stat-item-val--badge">
							<?php echo esc_html( number_format( $apy, 1 ) ); ?>%
						</span>
					</div>
				</div>

				<div class="nova-calc__growth-bar">
					<div class="nova-calc__growth-progress" data-calc-growth-bar style="width: <?php echo esc_attr( min( 100, round( ( $total_return / $initial_amount ) * 50, 1 ) ) ); ?>%;"></div>
				</div>
			<?php endif; ?>

			<?php if ( $cta_text ) : ?>
				<div class="nova-calc__cta-wrap">
					<a href="<?php echo $cta_url; ?>" class="nova-calc__cta-btn">
						<span><?php echo $cta_text; ?></span>
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="5" y1="12" x2="19" y2="12"></line>
							<polyline points="12 5 19 12 12 19"></polyline>
						</svg>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
