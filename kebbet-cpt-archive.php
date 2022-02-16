<?php
/**
 * Plugin Name: Kebbet plugins - Custom Post Type: Archive
 * Plugin URI: https://github.com/kebbet/kebbet-cpt-archive
 * Description: Registers a Custom Post Type.
 * Version: 20210928.1
 * Author: Erik Betshammar
 * Author URI: https://verkan.se
 *
 * @package kebbet-cpt-archive
 */

namespace kebbet\cpt\archive;

const POSTTYPE  = 'event-archive';
const SLUG      = 'archive';
const ICON      = 'archive';
const MENUPOS   = 10;
const THUMBNAIL = true;

/**
 * Link to ICONS
 *
 * @link https://developer.wordpress.org/resource/dashicons/
 */

/**
 * Hook into the 'init' action
 */
function init() {
	load_textdomain();
	register();
	if ( true === THUMBNAIL ) {
		add_theme_support( 'post-thumbnails' );
	}
}
add_action( 'init', __NAMESPACE__ . '\init', 0 );

/**
 * Flush rewrite rules on registration.
 *
 * @link https://codex.wordpress.org/Function_Reference/register_post_type
 */
function rewrite_flush() {
	// First, we "add" the custom post type via the above written function.
	// Note: "add" is written with quotes, as CPTs don't get added to the DB,
	// They are only referenced in the post_type column with a post entry,
	// when you add a post of this CPT.
	register();

	// ATTENTION: This is *only* done during plugin activation hook in this example!
	// You should *NEVER EVER* do this on every page load!!
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\rewrite_flush' );

/**
 * Load plugin textdomain.
 */
function load_textdomain() {
	load_plugin_textdomain( 'kebbet-cpt-archive', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/**
 * Register Custom Post Type
 */
function register() {

	$labels_args       = array(
		'name'                     => _x( 'Archive', 'Post Type General Name', 'kebbet-cpt-archive' ),
		'singular_name'            => _x( 'Archive', 'Post Type Singular Name', 'kebbet-cpt-archive' ),
		'menu_name'                => __( 'Archive', 'kebbet-cpt-archive' ),
		'name_admin_bar'           => __( 'Archive post', 'kebbet-cpt-archive' ),
		'parent_item_colon'        => __( 'Parent post:', 'kebbet-cpt-archive' ),
		'all_items'                => __( 'All posts', 'kebbet-cpt-archive' ),
		'add_new_item'             => __( 'Add new', 'kebbet-cpt-archive' ),
		'add_new'                  => __( 'Add new post', 'kebbet-cpt-archive' ),
		'new_item'                 => __( 'New post', 'kebbet-cpt-archive' ),
		'edit_item'                => __( 'Edit post', 'kebbet-cpt-archive' ),
		'update_item'              => __( 'Update post', 'kebbet-cpt-archive' ),
		'view_item'                => __( 'View post', 'kebbet-cpt-archive' ),
		'view_items'               => __( 'View posts', 'kebbet-cpt-archive' ),
		'search_items'             => __( 'Search posts', 'kebbet-cpt-archive' ),
		'not_found'                => __( 'Not found', 'kebbet-cpt-archive' ),
		'not_found_in_trash'       => __( 'No posts found in Trash', 'kebbet-cpt-archive' ),
		'featured_image'           => __( 'Featured image', 'kebbet-cpt-archive' ),
		'set_featured_image'       => __( 'Set featured image', 'kebbet-cpt-archive' ),
		'remove_featured_image'    => __( 'Remove featured image', 'kebbet-cpt-archive' ),
		'use_featured_image'       => __( 'Use as featured image', 'kebbet-cpt-archive' ),
		'insert_into_item'         => __( 'Insert into item', 'kebbet-cpt-archive' ),
		'uploaded_to_this_item'    => __( 'Uploaded to this post', 'kebbet-cpt-archive' ),
		'items_list'               => __( 'Items list', 'kebbet-cpt-archive' ),
		'items_list_navigation'    => __( 'Items list navigation', 'kebbet-cpt-archive' ),
		'filter_items_list'        => __( 'Filter items list', 'kebbet-cpt-archive' ),
		'archives'                 => __( 'Archive posts archive', 'kebbet-cpt-archive' ),
		'attributes'               => __( 'Archive post attributes', 'kebbet-cpt-archive' ),
		'item_published'           => __( 'Post published', 'kebbet-cpt-archive' ),
		'item_published_privately' => __( 'Post published privately', 'kebbet-cpt-archive' ),
		'item_reverted_to_draft'   => __( 'Post reverted to Draft', 'kebbet-cpt-archive' ),
		'item_scheduled'           => __( 'Post scheduled', 'kebbet-cpt-archive' ),
		'item_updated'             => __( 'Post updated', 'kebbet-cpt-archive' ),
		// 5.7 + 5.8
		'filter_by_date'           => __( 'Filter posts by date', 'kebbet-cpt-archive' ),
		'item_link'                => __( 'Archive post link', 'kebbet-cpt-archive' ),
		'item_link_description'    => __( 'A link to an archive post', 'kebbet-cpt-archive' ),
	);

	$supports_args = array(
		'author',
		'title',
		'editor',
		'page-attributes',
		'excerpt'
	);

	if ( true === THUMBNAIL ) {
		$supports_args = array_merge( $supports_args, array( 'thumbnail' ) );
	}

	$rewrite_args      = array(
		'slug'       => SLUG,
		'with_front' => false,
		'pages'      => false,
		'feeds'      => true,
	);
	$capabilities_args = array(
		'edit_post'          => 'edit_' . POSTTYPE,
		'edit_posts'         => 'edit_' . POSTTYPE .'s',
		'edit_others_posts'  => 'edit_others_' . POSTTYPE .'s',
		'publish_posts'      => 'publish_' . POSTTYPE .'s',
		'read_post'          => 'read_' . POSTTYPE .'s',
		'read_private_posts' => 'read_private_' . POSTTYPE .'s',
		'delete_post'        => 'delete_' . POSTTYPE,
	);
	$post_type_args    = array(
		'label'               => __( 'Archive post type', 'kebbet-cpt-archive' ),
		'description'         => __( 'Custom post type for archive posts', 'kebbet-cpt-archive' ),
		'labels'              => $labels_args,
		'supports'            => $supports_args,
		'taxonomies'          => array( 'category' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => MENUPOS,
		'menu_icon'           => 'dashicons-' . ICON,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => SLUG,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite_args,
		'capabilities'        => $capabilities_args,
		// Adding map_meta_cap will map the meta correctly.
		'show_in_rest'        => true,
		'map_meta_cap'        => true,
	);
	register_post_type( POSTTYPE, $post_type_args );
}

/**
 * Adds custom capabilities to CPT. Adjust it with plugin URE later with its UI.
 */
function add_custom_capabilities() {

	// Gets the editor and administrator roles.
	$admins = get_role( 'administrator' );
	$editor = get_role( 'editor' );
	$author = get_role( 'author' );

	// Add custom capabilities.
	$admins->add_cap( 'edit_' . POSTTYPE );
	$admins->add_cap( 'edit_' . POSTTYPE .'s' );
	$admins->add_cap( 'edit_others_' . POSTTYPE .'s' );
	$admins->add_cap( 'publish_' . POSTTYPE .'s' );
	$admins->add_cap( 'read_' . POSTTYPE .'s' );
	$admins->add_cap( 'read_private_' . POSTTYPE .'s' );
	$admins->add_cap( 'delete_' . POSTTYPE );

	$editor->add_cap( 'edit_' . POSTTYPE );
	$editor->add_cap( 'edit_' . POSTTYPE .'s' );
	$editor->add_cap( 'edit_others_' . POSTTYPE .'s' );
	$editor->add_cap( 'publish_' . POSTTYPE .'s' );
	$editor->add_cap( 'read_' . POSTTYPE .'s' );
	$editor->add_cap( 'read_private_' . POSTTYPE .'s' );
	$editor->add_cap( 'delete_' . POSTTYPE );

	$author->add_cap( 'edit_' . POSTTYPE );
	$author->add_cap( 'edit_' . POSTTYPE .'s' );
	$author->add_cap( 'publish_' . POSTTYPE .'s' );
	$author->add_cap( 'read_' . POSTTYPE .'s' );
	$author->add_cap( 'read_private_' . POSTTYPE .'s' );
	$author->add_cap( 'delete_' . POSTTYPE );
}
add_action( 'admin_init', __NAMESPACE__ . '\add_custom_capabilities');

/**
 * Post type update messages.
 *
 * See /wp-admin/edit-form-advanced.php
 *
 * @param array $messages Existing post update messages.
 *
 * @return array Amended post update messages with new CPT update messages.
 */
function post_updated_messages( $messages ) {

	$post             = get_post();
	$post_type        = get_post_type( $post );
	$post_type_object = get_post_type_object( $post_type );

	$messages[ POSTTYPE ] = array(
		0  => '',
		1  => __( 'Post updated.', 'kebbet-cpt-archive' ),
		2  => __( 'Custom field updated.', 'kebbet-cpt-archive' ),
		3  => __( 'Custom field deleted.', 'kebbet-cpt-archive' ),
		4  => __( 'Post updated.', 'kebbet-cpt-archive' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Post restored to revision from %s', 'kebbet-cpt-archive' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'Post published.', 'kebbet-cpt-archive' ),
		7  => __( 'Post saved.', 'kebbet-cpt-archive' ),
		8  => __( 'Post submitted.', 'kebbet-cpt-archive' ),
		9  => sprintf(
			/* translators: %1$s: date and time of the scheduled post */
			__( 'Post scheduled for: <strong>%1$s</strong>.', 'kebbet-cpt-archive' ),
			date_i18n( __( 'M j, Y @ G:i', 'kebbet-cpt-archive' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Post draft updated.', 'kebbet-cpt-archive' ),
	);
	if ( $post_type_object->publicly_queryable && POSTTYPE === $post_type ) {

		$permalink         = get_permalink( $post->ID );
		$view_link         = sprintf(
			' <a href="%s">%s</a>',
			esc_url( $permalink ),
			__( 'View Post', 'kebbet-cpt-archive' )
		);
		$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
		$preview_link      = sprintf(
			' <a target="_blank" href="%s">%s</a>',
			esc_url( $preview_permalink ),
			__( 'Preview Post', 'kebbet-cpt-archive' )
		);

		$messages[ $post_type ][1]  .= $view_link;
		$messages[ $post_type ][6]  .= $view_link;
		$messages[ $post_type ][9]  .= $view_link;
		$messages[ $post_type ][8]  .= $preview_link;
		$messages[ $post_type ][10] .= $preview_link;

	}

	return $messages;

}
add_filter( 'post_updated_messages', __NAMESPACE__ . '\post_updated_messages' );

/**
 * Custom bulk post updates messages
 *
 * @param array  $bulk_messages The messages for bulk updating posts.
 * @param string $bulk_counts Number of updated posts.
 */
function bulk_post_updated_messages( $bulk_messages, $bulk_counts ) {

	$bulk_messages[ POSTTYPE ] = array(
		/* translators: %s: singular of posts, %$s: plural of posts.  */
		'updated'   => _n( '%s post updated.', '%s posts updated.', number_format_i18n( $bulk_counts['updated'] ), 'kebbet-cpt-archive' ),
		/* translators: %s: singular of posts, %$s: plural of posts.  */
		'locked'    => _n( '%s post not updated, somebody is editing it.', '%s posts not updated, somebody is editing them.', number_format_i18n( $bulk_counts['locked'] ), 'kebbet-cpt-archive' ),
		/* translators: %s: singular of posts, %$s: plural of posts.  */
		'deleted'   => _n( '%s post permanently deleted.', '%s posts permanently deleted.', number_format_i18n( $bulk_counts['deleted'] ), 'kebbet-cpt-archive' ),
		/* translators: %s: singular of posts, %$s: plural of posts.  */
		'trashed'   => _n( '%s post moved to the Trash.', '%s posts moved to the Trash.', number_format_i18n( $bulk_counts['trashed'] ), 'kebbet-cpt-archive' ),
		/* translators: %s: singular of posts, %$s: plural of posts.  */
		'untrashed' => _n( '%s post restored from the Trash.', '%s posts restored from the Trash.', number_format_i18n( $bulk_counts['untrashed'] ), 'kebbet-cpt-archive' ),
	);

	return $bulk_messages;

}
add_filter( 'bulk_post_updated_messages', __NAMESPACE__ . '\bulk_post_updated_messages', 10, 2 );

/**
 * Add the content to the `At a glance`-widget.
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/at-a-glance.php';

/**
 * Adds and modifies the admin columns for the post type.
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/admin-columns.php';
