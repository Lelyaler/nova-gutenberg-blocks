<?php
namespace NovaBlocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin coordinator.
 */
final class Plugin {

	private static ?Plugin $instance = null;
	private Block_Registry $block_registry;
	private Schema_Generator $schema_generator;

	public static function instance(): Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->schema_generator = new Schema_Generator();
		$this->block_registry   = new Block_Registry( $this->schema_generator );

		$this->init_hooks();
	}

	private function init_hooks(): void {
		add_filter( 'block_categories_all', [ $this, 'register_block_category' ], 10, 2 );
		add_action( 'init', [ $this->block_registry, 'register_blocks' ] );
		add_action( 'wp_footer', [ $this->schema_generator, 'render_schema' ], 99 );
	}

	/**
	 * Registers custom Gutenberg block category with custom title and icon.
	 *
	 * @param array $categories Array of categories for block types.
	 * @param \WP_Block_Editor_Context $block_editor_context The current block editor context.
	 * @return array
	 */
	public function register_block_category( array $categories, $block_editor_context ): array {
		$custom_category = [
			'slug'  => 'nova-blocks',
			'title' => esc_html__( 'Nova Interactive Blocks', 'nova-blocks' ),
			'icon'  => 'superhero',
		];

		// Prepend our category to place it at the top of the block inserter.
		array_unshift( $categories, $custom_category );

		return $categories;
	}

	public function get_registry(): Block_Registry {
		return $this->block_registry;
	}

	public function get_schema_generator(): Schema_Generator {
		return $this->schema_generator;
	}
}
