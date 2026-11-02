<?php
namespace NovaBlocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles Gutenberg block registration and asset enqueues.
 */
class Block_Registry {

	private Schema_Generator $schema_generator;

	public function __construct( Schema_Generator $schema_generator ) {
		$this->schema_generator = $schema_generator;
	}

	/**
	 * Registers all custom blocks from block.json metadata.
	 */
	public function register_blocks(): void {
		$this->register_assets();

		$blocks = [
			'yield-calculator',
			'pricing-matrix',
			'faq-accordion',
		];

		foreach ( $blocks as $block ) {
			$block_path = NOVA_BLOCKS_DIR . 'blocks/' . $block;

			if ( file_exists( $block_path . '/block.json' ) ) {
				register_block_type( $block_path );
			}
		}
	}

	/**
	 * Pre-registers scripts and styles referenced in block.json files.
	 */
	private function register_assets(): void {
		// Editor JavaScript
		wp_register_script(
			'nova-blocks-editor',
			NOVA_BLOCKS_URL . 'assets/js/blocks.js',
			[ 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ],
			NOVA_BLOCKS_VERSION,
			true
		);

		// Editor Styles
		wp_register_style(
			'nova-blocks-editor-style',
			NOVA_BLOCKS_URL . 'assets/css/editor.css',
			[ 'wp-edit-blocks' ],
			NOVA_BLOCKS_VERSION
		);

		// Frontend JavaScript
		wp_register_script(
			'nova-blocks-frontend',
			NOVA_BLOCKS_URL . 'assets/js/frontend.js',
			[],
			NOVA_BLOCKS_VERSION,
			true
		);

		// Frontend Styles
		wp_register_style(
			'nova-blocks-frontend-style',
			NOVA_BLOCKS_URL . 'assets/css/frontend.css',
			[],
			NOVA_BLOCKS_VERSION
		);

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'nova-blocks-editor', 'nova-blocks' );
		}
	}
}
