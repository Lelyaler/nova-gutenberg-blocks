/**
 * Nova Blocks - Frontend Interactive Controller
 * Lightweight Vanilla JS with accessible patterns and zero external dependencies
 */

(function () {
	'use strict';

	/**
	 * Formats number with commas and specified decimals
	 */
	function formatNumber(num, decimals = 2) {
		return num.toLocaleString('en-US', {
			minimumFractionDigits: decimals,
			maximumFractionDigits: decimals,
		});
	}

	/**
	 * 1. Initialize Yield & ROI Calculator
	 */
	function initYieldCalculators() {
		const calcs = document.querySelectorAll('[data-nova-calc="true"]');

		calcs.forEach((calc) => {
			const slider = calc.querySelector('[data-calc-slider]');
			const amountText = calc.querySelector('[data-calc-amount-text]');
			const termText = calc.querySelector('[data-calc-term-text]');
			const totalText = calc.querySelector('[data-calc-total]');
			const profitText = calc.querySelector('[data-calc-profit]');
			const monthlyText = calc.querySelector('[data-calc-monthly]');
			const growthBar = calc.querySelector('[data-calc-growth-bar]');
			const chips = calc.querySelectorAll('[data-preset-val]');
			const termBtns = calc.querySelectorAll('[data-term]');

			if (!slider) return;

			let apy = parseFloat(calc.dataset.apy) || 14.8;
			let months = parseInt(calc.dataset.months, 10) || 12;
			const compounding = calc.dataset.compounding || 'monthly';
			const minVal = parseFloat(calc.dataset.min) || 500;
			const maxVal = parseFloat(calc.dataset.max) || 100000;

			function calculate() {
				const amount = parseFloat(slider.value) || 0;

				// Update slider track gradient fill
				const pct = ((amount - minVal) / (maxVal - minVal)) * 100;
				slider.style.background = `linear-gradient(to right, var(--nova-accent) 0%, var(--nova-accent) ${pct}%, rgba(255, 255, 255, 0.12) ${pct}%, rgba(255, 255, 255, 0.12) 100%)`;

				// Compound Interest: A = P * (1 + r/n)^(n*t)
				const n = compounding === 'daily' ? 365 : (compounding === 'monthly' ? 12 : 1);
				const t = months / 12;
				const r = apy / 100;
				const total = amount * Math.pow(1 + r / n, n * t);
				const profit = total - amount;
				const monthly = profit / Math.max(1, months);

				// Update UI
				if (amountText) amountText.textContent = formatNumber(amount, 0);
				if (termText) termText.textContent = `${months} Months`;
				if (totalText) totalText.textContent = formatNumber(total, 2);
				if (profitText) profitText.textContent = formatNumber(profit, 2);
				if (monthlyText) monthlyText.textContent = formatNumber(monthly, 2);

				if (growthBar) {
					const growthPct = Math.min(100, (total / Math.max(1, amount)) * 50);
					growthBar.style.width = `${growthPct.toFixed(1)}%`;
				}
			}

			// Slider input
			slider.addEventListener('input', () => {
				// Clear chip highlights if slider is moved manually
				chips.forEach((c) => c.classList.remove('is-active'));
				calculate();
			});

			// Preset chips
			chips.forEach((chip) => {
				chip.addEventListener('click', () => {
					const val = parseFloat(chip.dataset.presetVal);
					if (!isNaN(val)) {
						slider.value = val;
						chips.forEach((c) => c.classList.remove('is-active'));
						chip.classList.add('is-active');
						calculate();
					}
				});
			});

			// Term buttons
			termBtns.forEach((btn) => {
				btn.addEventListener('click', () => {
					const m = parseInt(btn.dataset.term, 10);
					if (!isNaN(m)) {
						months = m;
						termBtns.forEach((b) => b.classList.remove('is-active'));
						btn.classList.add('is-active');
						calculate();
					}
				});
			});

			// Initial run
			calculate();
		});
	}

	/**
	 * 2. Initialize Pricing & Tier Matrix
	 */
	function initPricingMatrices() {
		const matrices = document.querySelectorAll('[data-nova-pricing="true"]');

		matrices.forEach((matrix) => {
			const toggleBtns = matrix.querySelectorAll('[data-billing]');
			const cards = matrix.querySelectorAll('.nova-pricing__card');

			toggleBtns.forEach((btn) => {
				btn.addEventListener('click', () => {
					const mode = btn.dataset.billing;
					toggleBtns.forEach((b) => b.classList.remove('is-active'));
					btn.classList.add('is-active');

					cards.forEach((card) => {
						const amountEl = card.querySelector('[data-pricing-amount]');
						const periodEl = card.querySelector('[data-pricing-period]');
						const monthlyPrice = parseFloat(card.dataset.priceMonthly) || 0;
						const annualPrice = parseFloat(card.dataset.priceAnnual) || 0;

						if (mode === 'annual') {
							if (amountEl) amountEl.textContent = formatNumber(annualPrice, 0);
							if (periodEl) periodEl.textContent = '/month, billed annually';
						} else {
							if (amountEl) amountEl.textContent = formatNumber(monthlyPrice, 0);
							if (periodEl) periodEl.textContent = '/month';
						}
					});
				});
			});
		});
	}

	/**
	 * 3. Initialize Accessible FAQ Accordions
	 */
	function initFaqAccordions() {
		const accordions = document.querySelectorAll('[data-nova-faq="true"]');

		accordions.forEach((acc) => {
			const allowMultiple = acc.dataset.allowMultiple === 'true';
			const items = acc.querySelectorAll('[data-faq-item]');

			items.forEach((item) => {
				const btn = item.querySelector('.nova-faq__question-btn');
				const panel = item.querySelector('.nova-faq__answer-panel');

				if (!btn || !panel) return;

				btn.addEventListener('click', () => {
					const isExpanded = btn.getAttribute('aria-expanded') === 'true';

					// If single mode, collapse others
					if (!allowMultiple && !isExpanded) {
						items.forEach((other) => {
							if (other !== item) {
								const otherBtn = other.querySelector('.nova-faq__question-btn');
								const otherPanel = other.querySelector('.nova-faq__answer-panel');
								if (otherBtn && otherPanel) {
									otherBtn.setAttribute('aria-expanded', 'false');
									other.classList.remove('is-open');
									otherPanel.hidden = true;
								}
							}
						});
					}

					// Toggle current
					if (isExpanded) {
						btn.setAttribute('aria-expanded', 'false');
						item.classList.remove('is-open');
						panel.hidden = true;
					} else {
						btn.setAttribute('aria-expanded', 'true');
						item.classList.add('is-open');
						panel.hidden = false;
					}
				});
			});
		});
	}

	// Auto-init on DOMContentLoaded or immediate if already ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', () => {
			initYieldCalculators();
			initPricingMatrices();
			initFaqAccordions();
		});
	} else {
		initYieldCalculators();
		initPricingMatrices();
		initFaqAccordions();
	}
})();
