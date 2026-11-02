/**
 * Nova Blocks - Gutenberg Editor Block Registration
 * Built with WordPress Block API v3, React InspectorControls, and Dynamic Server Rendering.
 */

(function (wp) {
	'use strict';

	if (!wp || !wp.blocks || !wp.element) {
		return;
	}

	const { registerBlockType } = wp.blocks;
	const { createElement: el, Fragment, useState } = wp.element;
	const { InspectorControls, RichText } = wp.blockEditor || wp.editor;
	const {
		PanelBody,
		TextControl,
		RangeControl,
		SelectControl,
		ToggleControl,
		Button,
		ColorPalette,
		Notice,
	} = wp.components;
	const { __ } = wp.i18n;

	const PALETTE_COLORS = [
		{ name: 'Gold Accent', color: '#fdca40' },
		{ name: 'Emerald Green', color: '#10b981' },
		{ name: 'Cyber Blue', color: '#38bdf8' },
		{ name: 'Neon Purple', color: '#a855f7' },
		{ name: 'Crimson Red', color: '#ef4444' },
	];

	/* ==========================================================================
	   1. Block: Yield & ROI Calculator
	   ========================================================================== */
	registerBlockType('nova-blocks/yield-calculator', {
		edit: function (props) {
			const { attributes, setAttributes } = props;
			const {
				title,
				subtitle,
				currencySymbol,
				currencyCode,
				minAmount,
				maxAmount,
				stepAmount,
				defaultAmount,
				defaultApy,
				defaultMonths,
				compounding,
				accentColor,
				ctaText,
				ctaUrl,
				showBreakdown,
			} = attributes;

			// Quick preview calculation in editor
			const n = compounding === 'daily' ? 365 : (compounding === 'monthly' ? 12 : 1);
			const t = defaultMonths / 12;
			const r = defaultApy / 100;
			const total = defaultAmount * Math.pow(1 + r / n, n * t);
			const profit = total - defaultAmount;

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __('Calculator Parameters', 'nova-blocks'), initialOpen: true },
						el(TextControl, {
							label: __('Title', 'nova-blocks'),
							value: title,
							onChange: (val) => setAttributes({ title: val }),
						}),
						el(TextControl, {
							label: __('Subtitle', 'nova-blocks'),
							value: subtitle,
							onChange: (val) => setAttributes({ subtitle: val }),
						}),
						el(TextControl, {
							label: __('Currency Symbol', 'nova-blocks'),
							value: currencySymbol,
							onChange: (val) => setAttributes({ currencySymbol: val }),
						}),
						el(RangeControl, {
							label: __('Annual APY Rate (%)', 'nova-blocks'),
							value: defaultApy,
							onChange: (val) => setAttributes({ defaultApy: val }),
							min: 1,
							max: 100,
							step: 0.1,
						}),
						el(SelectControl, {
							label: __('Default Compounding', 'nova-blocks'),
							value: compounding,
							options: [
								{ label: __('Monthly Compounding', 'nova-blocks'), value: 'monthly' },
								{ label: __('Daily Compounding', 'nova-blocks'), value: 'daily' },
								{ label: __('Annual Compounding', 'nova-blocks'), value: 'yearly' },
							],
							onChange: (val) => setAttributes({ compounding: val }),
						}),
						el(SelectControl, {
							label: __('Default Duration', 'nova-blocks'),
							value: defaultMonths,
							options: [
								{ label: '3 Months', value: 3 },
								{ label: '6 Months', value: 6 },
								{ label: '12 Months (1 Year)', value: 12 },
								{ label: '24 Months (2 Years)', value: 24 },
							],
							onChange: (val) => setAttributes({ defaultMonths: parseInt(val, 10) }),
						})
					),
					el(
						PanelBody,
						{ title: __('Deposit Limits', 'nova-blocks'), initialOpen: false },
						el(RangeControl, {
							label: __('Default Deposit', 'nova-blocks'),
							value: defaultAmount,
							onChange: (val) => setAttributes({ defaultAmount: val }),
							min: minAmount,
							max: maxAmount,
							step: stepAmount,
						}),
						el(TextControl, {
							label: __('Minimum Deposit', 'nova-blocks'),
							type: 'number',
							value: minAmount,
							onChange: (val) => setAttributes({ minAmount: parseFloat(val) || 100 }),
						}),
						el(TextControl, {
							label: __('Maximum Deposit', 'nova-blocks'),
							type: 'number',
							value: maxAmount,
							onChange: (val) => setAttributes({ maxAmount: parseFloat(val) || 100000 }),
						})
					),
					el(
						PanelBody,
						{ title: __('Styling & Call to Action', 'nova-blocks'), initialOpen: false },
						el(
							'div',
							{ style: { marginBottom: '1rem' } },
							el('label', { className: 'components-base-control__label' }, __('Accent Color', 'nova-blocks')),
							el(ColorPalette, {
								colors: PALETTE_COLORS,
								value: accentColor,
								onChange: (val) => setAttributes({ accentColor: val || '#fdca40' }),
							})
						),
						el(ToggleControl, {
							label: __('Show Returns Breakdown', 'nova-blocks'),
							checked: showBreakdown,
							onChange: (val) => setAttributes({ showBreakdown: val }),
						}),
						el(TextControl, {
							label: __('CTA Button Text', 'nova-blocks'),
							value: ctaText,
							onChange: (val) => setAttributes({ ctaText: val }),
						}),
						el(TextControl, {
							label: __('CTA Button URL', 'nova-blocks'),
							value: ctaUrl,
							onChange: (val) => setAttributes({ ctaUrl: val }),
						})
					)
				),
				// In-editor live preview card
				el(
					'div',
					{
						className: 'nova-calc-block',
						style: { '--nova-accent': accentColor },
					},
					el('span', { className: 'nova-editor-badge' }, __('Gutenberg Live Preview', 'nova-blocks')),
					el(
						'div',
						{ className: 'nova-calc__header' },
						el('h3', { className: 'nova-calc__title' }, title || __('Investment & Yield Calculator', 'nova-blocks')),
						el('p', { className: 'nova-calc__subtitle' }, subtitle)
					),
					el(
						'div',
						{ className: 'nova-calc__body' },
						el(
							'div',
							{ className: 'nova-calc__controls' },
							el(
								'div',
								{ className: 'nova-calc__field' },
								el(
									'div',
									{ className: 'nova-calc__field-head' },
									el('span', { className: 'nova-calc__label' }, __('Initial Deposit', 'nova-blocks')),
									el(
										'span',
										{ className: 'nova-calc__amount-display' },
										currencySymbol,
										defaultAmount.toLocaleString('en-US')
									)
								),
								el('div', { className: 'nova-calc__slider-wrap' },
									el('input', {
										type: 'range',
										className: 'nova-calc__slider',
										value: defaultAmount,
										min: minAmount,
										max: maxAmount,
										readOnly: true,
										style: {
											background: `linear-gradient(to right, ${accentColor} 0%, ${accentColor} 50%, rgba(255,255,255,0.12) 50%, rgba(255,255,255,0.12) 100%)`
										}
									})
								)
							),
							el(
								'div',
								{ className: 'nova-calc__field' },
								el(
									'div',
									{ className: 'nova-calc__field-head' },
									el('span', { className: 'nova-calc__label' }, __('Term', 'nova-blocks')),
									el('span', { className: 'nova-calc__term-display' }, `${defaultMonths} Months`)
								)
							)
						),
						el(
							'div',
							{ className: 'nova-calc__results-card' },
							el(
								'div',
								{ className: 'nova-calc__stat-primary' },
								el('span', { className: 'nova-calc__stat-label' }, __('Projected Return', 'nova-blocks')),
								el(
									'div',
									{ className: 'nova-calc__stat-value' },
									currencySymbol,
									total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
								)
							),
							el(
								'div',
								{ className: 'nova-calc__breakdown' },
								el(
									'div',
									{ className: 'nova-calc__stat-item' },
									el('span', null, __('Net Profit', 'nova-blocks')),
									el('span', { className: 'nova-calc__stat-item-val nova-calc__stat-item-val--profit' },
										`+${currencySymbol}${profit.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
									)
								),
								el(
									'div',
									{ className: 'nova-calc__stat-item' },
									el('span', null, __('APY Rate', 'nova-blocks')),
									el('span', { className: 'nova-calc__stat-item-val nova-calc__stat-item-val--badge' }, `${defaultApy}%`)
								)
							)
						)
					)
				)
			);
		},
		save: function () {
			return null; // Dynamic SSR via render.php
		},
	});

	/* ==========================================================================
	   2. Block: Pricing & Tier Matrix
	   ========================================================================== */
	registerBlockType('nova-blocks/pricing-matrix', {
		edit: function (props) {
			const { attributes, setAttributes } = props;
			const { title, subtitle, annualDiscount, currencySymbol, plans } = attributes;

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __('Pricing Settings', 'nova-blocks'), initialOpen: true },
						el(TextControl, {
							label: __('Title', 'nova-blocks'),
							value: title,
							onChange: (val) => setAttributes({ title: val }),
						}),
						el(TextControl, {
							label: __('Subtitle', 'nova-blocks'),
							value: subtitle,
							onChange: (val) => setAttributes({ subtitle: val }),
						}),
						el(TextControl, {
							label: __('Currency Symbol', 'nova-blocks'),
							value: currencySymbol,
							onChange: (val) => setAttributes({ currencySymbol: val }),
						}),
						el(RangeControl, {
							label: __('Annual Discount (%)', 'nova-blocks'),
							value: annualDiscount,
							onChange: (val) => setAttributes({ annualDiscount: val }),
							min: 0,
							max: 50,
						})
					)
				),
				el(
					'div',
					{ className: 'nova-pricing-block' },
					el('span', { className: 'nova-editor-badge' }, __('Pricing Matrix Block', 'nova-blocks')),
					el(
						'div',
						{ className: 'nova-pricing__header' },
						el('h3', { className: 'nova-pricing__title' }, title),
						el('p', { className: 'nova-pricing__subtitle' }, subtitle)
					),
					el(
						'div',
						{ className: 'nova-pricing__grid' },
						(plans || []).map((plan, idx) =>
							el(
								'div',
								{
									key: idx,
									className: `nova-pricing__card ${plan.isPopular ? 'nova-pricing__card--featured' : ''}`,
								},
								plan.badge && el('div', { className: 'nova-pricing__card-badge' }, plan.badge),
								el('h4', { className: 'nova-pricing__card-name' }, plan.name),
								el('p', { className: 'nova-pricing__card-tagline' }, plan.tagline),
								el(
									'div',
									{ className: 'nova-pricing__card-price' },
									el('span', { className: 'nova-pricing__currency' }, currencySymbol),
									el('span', { className: 'nova-pricing__amount' }, plan.priceMonthly),
									el('span', { className: 'nova-pricing__period' }, '/mo')
								),
								el(
									'ul',
									{ className: 'nova-pricing__features-list' },
									(plan.features || []).map((feat, fIdx) =>
										el('li', { key: fIdx, className: 'nova-pricing__feature-item' }, `✔ ${feat}`)
									)
								)
							)
						)
					)
				)
			);
		},
		save: function () {
			return null; // Dynamic SSR via render.php
		},
	});

	/* ==========================================================================
	   3. Block: SEO FAQ Accordion & Schema
	   ========================================================================== */
	registerBlockType('nova-blocks/faq-accordion', {
		edit: function (props) {
			const { attributes, setAttributes } = props;
			const { title, subtitle, generateSchema, allowMultiple, items } = attributes;

			const updateItem = (index, field, val) => {
				const next = [...(items || [])];
				next[index] = { ...next[index], [field]: val };
				setAttributes({ items: next });
			};

			const addItem = () => {
				const next = [
					...(items || []),
					{
						question: __('New Question', 'nova-blocks'),
						answer: __('Detailed answer providing helpful context for users and search crawlers.', 'nova-blocks'),
					},
				];
				setAttributes({ items: next });
			};

			const removeItem = (index) => {
				const next = (items || []).filter((_, i) => i !== index);
				setAttributes({ items: next });
			};

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __('Accordion & SEO Settings', 'nova-blocks'), initialOpen: true },
						el(ToggleControl, {
							label: __('Generate FAQPage Schema (JSON-LD)', 'nova-blocks'),
							help: __('Automatically outputs Schema.org microdata in page footer for Google Rich Snippets.', 'nova-blocks'),
							checked: generateSchema,
							onChange: (val) => setAttributes({ generateSchema: val }),
						}),
						el(ToggleControl, {
							label: __('Allow Multiple Open Items', 'nova-blocks'),
							checked: allowMultiple,
							onChange: (val) => setAttributes({ allowMultiple: val }),
						})
					)
				),
				el(
					'div',
					{ className: 'nova-faq-block' },
					el('span', { className: 'nova-editor-badge' }, __('FAQ Accordion (SEO Ready)', 'nova-blocks')),
					el(
						'div',
						{ className: 'nova-faq__header' },
						el(RichText, {
							tagName: 'h3',
							className: 'nova-faq__title',
							value: title,
							onChange: (val) => setAttributes({ title: val }),
							placeholder: __('FAQ Title...', 'nova-blocks'),
						}),
						el(RichText, {
							tagName: 'p',
							className: 'nova-faq__subtitle',
							value: subtitle,
							onChange: (val) => setAttributes({ subtitle: val }),
							placeholder: __('FAQ Subtitle...', 'nova-blocks'),
						})
					),
					el(
						'div',
						{ className: 'nova-editor-repeater-list' },
						(items || []).map((item, idx) =>
							el(
								'div',
								{ key: idx, className: 'nova-editor-repeater-item' },
								el(
									'div',
									{ className: 'nova-editor-repeater-head' },
									el('span', { className: 'nova-editor-repeater-title' }, `${__('Question', 'nova-blocks')} #${idx + 1}`),
									el(
										Button,
										{
											isDestructive: true,
											isSmall: true,
											onClick: () => removeItem(idx),
										},
										__('Delete', 'nova-blocks')
									)
								),
								el(TextControl, {
									label: __('Question Text', 'nova-blocks'),
									value: item.question,
									onChange: (val) => updateItem(idx, 'question', val),
								}),
								el(TextControl, {
									label: __('Answer Text', 'nova-blocks'),
									value: item.answer,
									onChange: (val) => updateItem(idx, 'answer', val),
								})
							)
						),
						el(
							Button,
							{
								isPrimary: true,
								style: { marginTop: '0.75rem' },
								onClick: addItem,
							},
							__('+ Add FAQ Item', 'nova-blocks')
						)
					)
				)
			);
		},
		save: function () {
			return null; // Dynamic SSR via render.php
		},
	});
})(window.wp);
