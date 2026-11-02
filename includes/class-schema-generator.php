<?php
namespace NovaBlocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manages automated Schema.org JSON-LD generation for blocks (e.g. FAQPage).
 */
class Schema_Generator {

	/**
	 * @var array<int, array{question: string, answer: string}>
	 */
	private array $faq_entities = [];

	/**
	 * Registers FAQ entities to be output in the page footer schema.
	 *
	 * @param array $items Array of items with 'question' and 'answer' keys.
	 */
	public function add_faq_items( array $items ): void {
		foreach ( $items as $item ) {
			if ( empty( $item['question'] ) || empty( $item['answer'] ) ) {
				continue;
			}

			$this->faq_entities[] = [
				'question' => wp_strip_all_tags( (string) $item['question'] ),
				'answer'   => wp_strip_all_tags( (string) $item['answer'] ),
			];
		}
	}

	/**
	 * Renders the Schema.org JSON-LD graph in the footer.
	 */
	public function render_schema(): void {
		if ( empty( $this->faq_entities ) ) {
			return;
		}

		$main_entities = [];

		foreach ( $this->faq_entities as $faq ) {
			$main_entities[] = [
				'@type'          => 'Question',
				'name'           => $faq['question'],
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text'  => $faq['answer'],
				],
			];
		}

		$schema = [
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => $main_entities,
		];

		$json = wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );

		if ( $json ) {
			echo "\n<!-- Nova Blocks - Automated Schema.org FAQPage Rich Snippet -->\n";
			echo '<script type="application/ld+json">' . "\n" . $json . "\n" . '</script>' . "\n";
		}
	}
}
