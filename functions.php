<?php
/**
 * Simplish functions and configuration routines
 *
 * @package WordPress
 * @subpackage Simplish
 * @since 2.0
 */

/* Only define it if some child theme hasn't already */
if(!function_exists('sp_setup')){
	/*
	 * Setup: textdomain, nav_menu support.
	 */
	function sp_setup()
	{
		/*
		 * Gettext i18n.
		 * load_theme_textdomain( $domain, $path )
		 * $path relative from / of URL space.
		 */
		load_theme_textdomain( 'simplish', get_template_directory() . '/languages' );

		/*
		 * Make search form, comment form, and comments HTML5.
		 */
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', ) );

		/* @since WP4.1 we don't write the title tag in html head. */
		add_theme_support( 'title-tag' );

		/* Generate default RSS/Atom feed links in output head. */
		add_theme_support( 'automatic-feed-links' );

		/* Offer Custom Background admin options. */
		add_theme_support( 'custom-background' );

		/* Current use in search results view. */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 50, 50, true );

		/* Default library block styles CSS for structural elements. */
		add_theme_support( 'wp-block-styles' );

		/* Allow img and some other block types to define alignwide/alignfull classes. */
		add_theme_support( 'align-wide' );

		/* Add wp-embed-reponsive class to <body> so embedded blocks resize at aspect ratio. */
		add_theme_support( 'responsive-embeds' );

		/* Menu - wp_nav_menu() in sidebar.php - new in WP3. */
		register_nav_menus( array( 'nowidget-right-sidebar' => __( '(Non-widget) Sidebar Menu', 'simplish' ), ) );
	}
}

/* The after_setup_theme hook fires before the theme init hook. */
add_action( 'after_setup_theme', 'sp_setup' );

if(!function_exists('sp_header_style')):
	function sp_header_style()
	{
	?>
		<style type="text/css">
		#header{
			background: url(<?php header_image(); ?>);
		}
		<?php switch(get_header_textcolor()):
			/*_Display text: No_ in theme hdr admin. Hide hdr text. */
			case 'blank':
				echo '#header h1, #header h2{
					display: none;
				}';
				break;
			/* Default hdr text color set. Use traditional burgundy a:active/hover. */
			case '000':
				echo '/* Duplicates style.css:/^#header a:hover */
				#header a:active, #header a:hover{
					color: #760909;
				}';
				break;
			/* Use hdr text color set in theme hdr admin. */
			default:
				echo '/* Must spec <a> or else style.css is more specific & wins. */
				#header a:link, #header a:visited, #header h1, #header h2{
					color: #' . get_header_textcolor() . '
				}';
		endswitch; ?>
		</style>
	<?php
	}
endif;

if(!function_exists('sp_admin_header_style')):
	/* Styles the Appearance->Header image preview. */
	function sp_admin_header_style()
	{
	?>
		<style type="text/css">
		#headimg {
			width: <?php echo $image_header_defaults['width']; ?>px;
			height: <?php echo $image_header_defaults['height']; ?>px;
		}
		</style>
	<?php
	}
endif;

$sp_image_header_defaults = array(
    'default-image'          => '',
    'width'                  => 900,
    'height'                 => 62,
    'flex-height'            => true,
    'flex-width'             => true,
    'uploads'                => true,
    'random-default'         => false,
    'header-text'            => true,
    'default-text-color'     => '000',
    'wp-head-callback'       => 'sp_header_style',
    'admin-head-callback'    => 'sp_admin_header_style',
    'admin-preview-callback' => '',
);
add_theme_support( 'custom-header', $sp_image_header_defaults );

/* Widget Sidebar */
function sp_widgets_init()
{
	register_sidebar( array(
		'name' => __('Right Sidebar', 'simplish' ),
		'id' => 'widget-right-sidebar',
		'description' => __('The right-hand sidebar widget container.', 'simplish'),
	) );
}
add_action('widgets_init', 'sp_widgets_init');

/**
 * Set content width in pixels.
 * Equal to width in style.css:/^#content (662 pixels).
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width Content width.
 */
function sp_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'sp_content_width', 662 );
}
add_action( 'after_setup_theme', 'sp_content_width', 0 );

/**
 * Enqueue styles and js.
 */
function sp_scripts() {
	wp_enqueue_style( 'sp-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );

	wp_style_add_data( 'sp-style', 'rtl', 'replace' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'sp_scripts' );

/**
 * Set post excerpt length.
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * All excerpt handling borrows heavily from the 2010 theme.
 *
 * @since Simplish 2.4.2 (Twenty Ten 1.0)
 * @return int
 */
function sp_excerpt_length($length)
{
	return 36;
}
add_filter('excerpt_length', 'sp_excerpt_length');

if(!function_exists('sp_readmore_text')){
	/**
	 * "More" text (no link) for More tags in the_content().
	 *
	 * @since Simplish 2.5
	 * @return string "More ->"
	 */
	function sp_readmore_text()
	{
		return __( 'More', 'simplish' ) . '<span class="meta-nav">&rarr;</span>';
	}
}

/**
 * "More" link for excerpts
 *
 * @since Simplish 2.4.2 (Twenty Ten 1.0)
 * @uses sp_readmore_text
 * @return string "More ->" wrapped in a link
 */
function sp_readmore_link()
{
	return ' <a class="more-link" href="'. get_permalink() . '">' . sp_readmore_text() . '</a>';
}

/**
 * Replaces "[...]" (appended to auto-generated excerpts) with an ellipsis and sp_readmore_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Simplish 2.4.2 (Twenty Ten 1.0)
 * @return string An ellipsis and a link
 */
function sp_auto_excerpt_more($more)
{
	return ' &hellip;' . sp_readmore_link();
}
add_filter('excerpt_more', 'sp_auto_excerpt_more');

/**
 * Add "More" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Simplish 2.4.2 (Twenty Ten 1.0)
 * @return string The user-specified (not auto-generated) excerpt with a "More" link
 */
function sp_custom_excerpt_more($output)
{
	if ( has_excerpt() && ! is_attachment() ){
		$output .= sp_readmore_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'sp_custom_excerpt_more' );

/**
 * Zero the default gallery shortcode style, so it isn't injected into output body.
 * Simplish gallery styles are in style.css.
 *
 * @return string The emptied gallery style filter
 */
function sp_zero_gallery_css($css){
	return preg_replace("#<style type='text/css'>(.*?)</style>#s", '', $css);
}
add_filter('gallery_style', 'sp_zero_gallery_css');

/*
 * hCard producers based on blog.txt,
 * http://www.plaintxt.org/themes/blogtxt/
 */
if(!function_exists('sp_byline_hcard')){
	/* Echo hCard for post author, with URL of author's archive. */
	function sp_byline_hcard()
	{
		global $wpdb, $authordata;

		echo '<span class="entry-author author vcard"><a class="url fn" href="' .
			get_author_posts_url($authordata->ID, $authordata->user_nicename) .
			'" title="' . __('More posts by', 'simplish') . ' ' .
			$authordata->display_name .
			'">' .
			get_the_author() .
			'</a></span>';
	}
}

if(!function_exists('sp_author_hcard')){
	/*
	 * Echo hCard for post author, with (from author's profile):
	 * display name, avatar image for email addr, bio info, URL.
	 * Takes integer option for img square size in pixels.
	 * Default size from wp's get_avatar() is 96.
	 */
	function sp_author_hcard($size)
	{
		global $wpdb, $authordata;

		$email = get_the_author_meta('email');
		$avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar($email, $size) );
		$note = '';
		if(isset($authordata->user_description)){
			$note = '<span class="note">' .
			apply_filters('archive_meta', $authordata->user_description) .
			'</span>';
		}
		echo '<span class="author vcard">' .
			$avatar .
			'<a class="url fn" rel="me" title="' .
			get_the_author() . ' ' . __('home page', 'simplish') . '" href="' . get_the_author_meta('url') . '">'
			. get_the_author() .
			'</a>' .
			$note .
			'</span>';
	}
}
?>
