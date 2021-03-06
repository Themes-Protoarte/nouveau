<?php
/** The \NV\Theme\Utilities\Theme class */

namespace NV\Theme\Utilities;

use NV\Theme\NV;

/**
 * Basic features to be used in theme template files.
 */
class Theme {

	/**
	 * Not yet implemented.
	 * 
	 * Will display Foundation-native pagination for archives.
	 * 
	 * @todo Implement Foundation-friendly markup
	 * @param array $args
	 */
	public static function archive_nav( $args = array() ) {
		echo paginate_links( $args );
	}

	/**
	 * Not yet implemented.
	 * 
	 * Will display Foundation-native pagination for a paginated post/article.
	 *
	 * @todo Implement Foundation-friendly markup
	 * @param array $args
	 */
	public static function article_page_nav( $args = array() ) {
		echo wp_link_pages( $args );
	}


	/**
	 * Can be used to output breadcrumbs. Implements Foundation's breadcrumb structure.
	 *
	 * @todo Evaluate to determine if this can be done in a more clean way
	 * 
	 * @global \WP_Query $wp_query
	 * @global object    $post
	 *
	 * @param array      $args
	 *
	 * @return string
	 */
	public static function breadcrumbs( $args = array() ) {
		global $post, $wp_query;

		$defaults = array(
			'use_prefix'   => true,
			'blog_title'   => __( 'Blog', 'nvLangScope' ), 'before' => '<ul class="breadcrumbs">',
			'after'        => '</ul>',
			'crumb_before' => '<li%>',
			//% represents replacement character for current/active page
			'crumb_after'  => '</li>',
			'echo'         => true,
		);

		$r = wp_parse_args( $args, $defaults );
		$r = apply_filters( 'wp_link_pages_args', $r );
		extract( $r, EXTR_SKIP );

		/** @var $use_prefix */
		/** @var $blog_title */
		/** @var $before */
		/** @var $after */
		/** @var $crumb_before */
		/** @var $crumb_after */
		/** @var $echo */

		$query_obj = $wp_query->get_queried_object();

		//Open tag...
		$output = $before;
		$output .= '<li><a href="' . get_home_url() . '">' . __( 'Home', 'nvLangScope' ) . '</a></li>';

		//Determine content of breadcrumb...
		if ( is_singular() ) {
			$ancestors = get_post_ancestors( $post );

			if ( $use_prefix && ! is_page() ) {
				//Get the url for the archive (get_home_url() must be used for the built-in 'post' post type)
				$archive_url = get_post_type_archive_link( $post->post_type ) ?: get_home_url();
				//What is the NAME of this items archive?
				$ptype_title = ( is_singular( 'post' ) ) ? $blog_title : get_post_type_object( $post->post_type )->label;
				//Add archive to the breadcrumb bar
				$output .= '<li><a href="' . $archive_url . '">' . $ptype_title . '</a></li>';
			}

			foreach ( $ancestors as $ancestor ) {
				//Output each ancestor if a page has them
				$output .= '<li><a href="' . get_permalink( $ancestor ) . '">' . get_the_title( $ancestor ) . '</a></li>';
			}

			//Output current page but mark it as current
			$output .= '<li class="current"><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
		} else if ( WordPress::is_tax_archive() ) {
			$output .= '<li class="unavailable"><a href="">' . get_taxonomy( $query_obj->taxonomy )->label . '</a></li>';
			$output .= '<li class="current"><a href="' . get_pagenum_link() . '">' . $query_obj->name . '</a></li>';
		} else if ( is_post_type_archive() ) {
			$output .= '<li class="current"><a href="' . get_post_type_archive_link( $post->post_type ) . '">' . $query_obj->label . '</a></li>';
		} else if ( is_search() ) {
			$output .= '<li class="current"><a href="/?s=' . $_REQUEST['s'] . '">' . __( 'Search: ', 'nvLangScope' ) . urldecode( $_REQUEST['s'] ) . '</a></li>';
		} else if ( is_year() ) {
			$output .= '<li class="current"><a href="/?m=' . $_REQUEST['m'] . '">' . __( 'Year: ', 'nvLangScope' ) . get_the_date( 'Y' ) . '</a></li>';
		} else if ( is_month() ) {
			$output .= '<li class="current"><a href="/?m=' . $_REQUEST['m'] . '">' . __( 'Month: ', 'nvLangScope' ) . get_the_date( 'F Y' ) . '</a></li>';
		} else if ( is_date() ) {
			$output .= '<li class="current"><a href="/?m=' . $_REQUEST['m'] . '">' . __( 'Date: ', 'nvLangScope' ) . get_the_date( 'F d, Y' ) . '</a></li>';
		}

		//Closing tag...
		$output .= $after;

		if ( $echo ) {
			echo $output;
		}

		return $output;
	}


	/**
	 * This loads the template for individual comments. It is called from within the comments template
	 * ( parts/comments.php ) file like so:
	 *
	 * <code>
	 * <?php
	 *    wp_list_comments( array( 'callback' => array( '\Nv\Utilities\Theme', 'comments' ) ) );
	 * ?>
	 * </code>
	 *
	 * @param       $comment
	 * @param array $args
	 * @param int   $depth
	 */
	public static function comments( $comment, $args = array(), $depth = 1 ) {
		require NV::i()->get_path( 'parts', 'comments/comments.php' );
	}


	/**
	 * Load footer template.
	 *
	 * Includes the footer template for a theme or if a name is specified then a specialised footer will be included.
	 *
	 * For the parameter, if the file is called "footer-special.php" then specify "special".
	 *
	 * @uses locate_template()
	 * @uses do_action() Calls 'get_footer' action.
	 *
	 * @param string $name The name of the specialised footer.
	 * @param string $path
	 */
	public static function get_footer( $name = null, $path = 'layout/' ) {
		do_action( 'get_footer', $name );

		//Ensure path has closing slash
		$path = trailingslashit( $path );

		$templates = array();
		if ( isset( $name ) ) {
			$templates[] = "{$path}footer-{$name}.php";
		} else {
			$templates[] = "{$path}footer.php";
		}

		// Backward compat code will be removed in a future release
		if ( '' == locate_template( $templates, true ) ) {
			trigger_error( "The specified footer ( {$templates[0]} ) was not found...", E_USER_ERROR );
		}
	}


	/**
	 * Load a header template.
	 *
	 * This allows you to load a header template from any specified path and/or file name. By default, this will look
	 * in the theme's /layout/ folder, unless otherwise specified.
	 *
	 * For the parameter, if the file is called "header-special.php" then specify "special".
	 *
	 * @uses locate_template()
	 * @uses do_action() Calls 'get_header' action.
	 *
	 * @param mixed  $name The name of the specialised header (note: will be prepended with "header-")
	 * @param string $path The theme-relative path to the header file.
	 */
	public static function get_header( $name = null, $path = 'layout/' ) {
		do_action( 'get_header', $name );

		//Ensure path has closing slash
		$path = trailingslashit( $path );

		$templates = array();
		if ( isset( $name ) ) {
			$templates[] = "{$path}header-{$name}.php";
		} else {
			$templates[] = "{$path}header.php";
		}

		// Backward compat code will be removed in a future release
		if ( '' == locate_template( $templates, true ) ) {
			trigger_error( "The specified header ( {$templates[0]} ) was not found...", E_USER_ERROR );
		}
	}


	/**
	 * Shortcut to simplify a standard loop. You simply provide a template part path and the loop is handled for you.
	 *
	 * @param string $part    The template part to load
	 * @param string $no_part The template part to load if there are no results
	 */
	public static function loop( $part, $no_part = '' ) {
		// START the loop
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				get_template_part( $part, get_post_format() );
			}
		} else if ( ! empty( $no_part ) ) {
			get_template_part( $no_part );
		}
		// END the loop
	}


	/**
	 * Shortcut to simplify a loop that uses a custom query. You can provide either a WP_Query object or an array of
	 * query arguments if you want the object created for you. The loop will then be performed and the appropriate
	 * specified template part will be loaded. You can also specify a custom name for the query object variable to
	 * prevent collisions.
	 *
	 * Note that template parts are loaded by include, NOT by get_template_part(). This is because get_template_part()
	 * blocks access to the custom query variable unless a global statement pulls it into the template.
	 * 
	 * @todo Ensure there's a way to use pagination inside the custom loop
	 *
	 * @param mixed  $custom_query Required. Either a WP_Query object or an array of arguments to perform a new query.
	 * @param string $part         Required. The theme-relative template part to load.
	 * @param string $no_part      Optional. The theme-relative template part to load if there are no results.
	 * @param string $var_name     Optional. The variable name (without $) you want to use for accessing the query within template parts. Default: 'query'.
	 *
	 * @return bool Returns false if nothing to show.
	 */
	public static function custom_loop( $custom_query, $part, $no_part = '', $var_name = 'query' ) {

		// Set up the custom named query variable
		if ( is_array( $custom_query ) || is_string( $custom_query ) ) {
			$$var_name = new \WP_Query( $custom_query );
		} else if ( is_a( $custom_query, '\WP_Query' ) ) {
			$$var_name = &$custom_query;
		} else {
			return false;
		}

		// START the loop
		if ( $$var_name->have_posts() ) {
			while ( $$var_name->have_posts() ) {
				$$var_name->the_post();
				do_action( "get_template_part_{$part}", $part, null );
				$file = NV::i()->get_path() . $part . '.php';
				include $file;
			}
		} else if ( ! empty( $no_part ) ) {
			do_action( "get_template_part_{$part}", $part, null );
			$file = NV::i()->get_path() . $no_part . '.php';
			include $file;
		}

		wp_reset_query();
	}


	/**
	 * Outputs the name of the file as an HTML comment for easy-peesy troubleshooting.
	 *
	 * @param string $file This function should always be passed the value of __FILE__
	 */
	public static function output_file_marker( $file ) {
		
		// Don't output this info if WP_DEBUG is off
		if ( ! WP_DEBUG ) {
			return;
		}
		
		// Strip out system path (keeping only site-root-relative path)
		$file = preg_replace( '|' . preg_quote( ABSPATH ) . '|', '', $file );

		// Output an HTML comment with the current template path
		printf( "\n\n<!-- " . __( 'Template file: %s', 'nvLangScope' ) . " -->\n\n", '/' . $file );
	}

	/**
	 * Displays information about a post. Author, post date, etc.
	 *
	 * Example: Posted on July 4, 2012 by Matt
	 */
	public static function posted_on() {
		// Full HTML5 datetime for datetime attr
		$dt_html = esc_attr( get_the_date( 'c' ) );

		// Visible date
		$dt_text = esc_html( get_the_date() );

		// Author archive LINK
		$author_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );

		//Author title attribute
		$author_tooltip = esc_attr( sprintf( __( 'View all posts by %s', 'nvLangScope' ), get_the_author() ) );

		// Author name
		$author_name = get_the_author();

		// OUTPUT THE HTML
		printf(
			'<span class="posted-on">' . __( 'Posted on %s by %s', 'nvLangScope' ) . '</span>',
			// Date and time...
			"<time datetime='{$dt_html}' pubdate>{$dt_text}</time>",
			// Author vcard and link...
			"<span class='author vcard'><a href='{$author_url}' title='{$author_tooltip}' rel='author'>{$author_name}</a></span>"
		);
	}

}