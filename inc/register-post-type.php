<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( 'Do not open this file directly.' );
}

class Dev_Docs_Type {

    const DOCUMENT_TYPE = 'dev-docs';
	const CPT = 'e-dev-docs';
	const ADMIN_PAGE_SLUG = 'edit.php?post_type=' . self::CPT;

    private $posts;
	private $trashed_posts;
	private $new_lp_url;
	private $permalink_structure;

    public function get_name() {
		return 'dev-docs';
	}

    /**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var $_instance The single instance of the class.
	 */
	private static $_instance = null;

    /**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return An instance of this class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

    /**
	 * Get Landing Pages Posts
	 *
	 * Returns the posts property of a WP_Query run for posts with the Landing Pages CPT.
	 *
	 * @since 3.1.0
	 *
	 * @return array posts
	 */
	private function get_dev_docs_posts() {
		if ( $this->posts ) {
			return $this->posts;
		}

		// `'posts_per_page' => 1` is because this is only used as an indicator to whether there are any docs.
		$posts_query = new \WP_Query( [
			'post_type' => self::CPT,
			'post_status' => 'any',
			'posts_per_page' => 1,
			'meta_key' => '_dev_docs_type',
			'meta_value' => self::DOCUMENT_TYPE,
		] );

		$this->posts = $posts_query->posts;

		return $this->posts;
	}

    /**
	 * Add Submenu Page
	 *
	 * Adds the 'Landing Pages' submenu item to the 'Templates' menu item.
	 *
	 * @since 3.1.0
	 */
	private function add_submenu_page() {
		$posts = $this->get_dev_docs_posts();

		// If there are no Landing Pages, show the "Create Your First Landing Page" page.
		// If there are, show the pages table.
		if ( ! empty( $posts ) ) {
			$dev_docs_page_menu_slug = self::ADMIN_PAGE_SLUG;
			$dev_docs_page_menu_callback = null;
		} else {
			$dev_docs_page_menu_slug = self::CPT;
			//$dev_docs_page_menu_callback = [ $this, 'print_empty_landing_pages_page' ];
		}

		$dev_docs_pages_title = __( 'Dev Docs', 'dev-docs' );

		add_submenu_page(
			self::ADMIN_PAGE_SLUG,
			$dev_docs_pages_title,
			$dev_docs_pages_title,
			'manage_options',
			$dev_docs_page_menu_slug,
			$dev_docs_page_menu_callback
		);
	}

    // Register dev_docs Post Type
    private function dev_docs_post_type() {

        $labels = array(
            'name'                  => _x( 'Documentations', 'Post Type General Name', 'dev-docs' ),
            'singular_name'         => _x( 'Documentation', 'Post Type Singular Name', 'dev-docs' ),
            'menu_name'             => __( 'Dev Docs', 'dev-docs' ),
            'name_admin_bar'        => __( 'Docs', 'dev-docs' ),
            'archives'              => __( 'Doc Archives', 'dev-docs' ),
            'attributes'            => __( 'Doc Attributes', 'dev-docs' ),
            'parent_item_colon'     => __( 'Parent Doc:', 'dev-docs' ),
            'all_items'             => __( 'All Docs', 'dev-docs' ),
            'add_new_item'          => __( 'Add New Doc', 'dev-docs' ),
            'add_new'               => __( 'Add New Doc', 'dev-docs' ),
            'new_item'              => __( 'New Doc', 'dev-docs' ),
            'edit_item'             => __( 'Edit Doc', 'dev-docs' ),
            'update_item'           => __( 'Update Doc', 'dev-docs' ),
            'view_item'             => __( 'View Doc', 'dev-docs' ),
            'view_items'            => __( 'View Docs', 'dev-docs' ),
            'search_items'          => __( 'Search doc', 'dev-docs' ),
            'not_found'             => __( 'Not found', 'dev-docs' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'dev-docs' ),
            'featured_image'        => __( 'Featured Image', 'dev-docs' ),
            'set_featured_image'    => __( 'Set featured image', 'dev-docs' ),
            'remove_featured_image' => __( 'Remove featured image', 'dev-docs' ),
            'use_featured_image'    => __( 'Use as featured image', 'dev-docs' ),
            'insert_into_item'      => __( 'Insert into doc', 'dev-docs' ),
            'uploaded_to_this_item' => __( 'Uploaded to this doc', 'dev-docs' ),
            'items_list'            => __( 'Docs list', 'dev-docs' ),
            'items_list_navigation' => __( 'Docs list navigation', 'dev-docs' ),
            'filter_items_list'     => __( 'Filter docs list', 'dev-docs' ),
        );
        $args = array(
            'label'                 => __( 'Documentation', 'dev-docs' ),
            'description'           => __( 'This post type is dedicated for help/documentation articles.', 'dev-docs' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields' ),
            'taxonomies'            => array( 'category', 'post_tag' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 20,
            'menu_icon'             => 'dashicons-media-document',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rest_base'             => 'docs',
        );
        register_post_type( 'dev-docs', $args );

    }

    public function __construct() {
        
        
        add_action( 'init', function() {
            $this->dev_docs_post_type();
        }, 30 );
        add_action( 'admin_menu', function() {
			$this->add_submenu_page();
		}, 30 );
    }

}

Dev_Docs_Type::instance();