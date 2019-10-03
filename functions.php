<?php
/**
 * Ichabod functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ichabod
 */
if ( ! function_exists( 'ichabod_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the afterichabodetup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function ichabod_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on ichabod, use a find and replace
		 * to change 'ichabod' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'ichabod', get_template_directory() . '/languages' );
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );
		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary', 'ichabod' ),
		) );
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
	}
endif;
add_action( 'after_setup_theme', 'ichabod_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function ichabod_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'ichabod_content_width', 768 );
}
add_action( 'after_setup_theme', 'ichabod_content_width', 0 );


/* https://github.com/u12206050/react-gutenberg */
add_action(
    'rest_api_init',
    function () {

        if ( ! function_exists( 'use_block_editor_for_post_type' ) ) {
            require ABSPATH . 'wp-admin/includes/post.php';
        }

        // Surface all Gutenberg blocks in the WordPress REST API
        $post_types = get_post_types_by_support( [ 'editor' ] );
        foreach ( $post_types as $post_type ) {
            if ( use_block_editor_for_post_type( $post_type ) ) {
                register_rest_field(
                    $post_type,
                    'blocks',
                    [
                        'get_callback' => function ( array $post ) {
                            return parse_blocks( $post['content']['raw'] );
                        },
                    ]
                );
            }
        }
    }
);
