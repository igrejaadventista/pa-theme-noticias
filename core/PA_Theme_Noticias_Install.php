<?php

use Log1x\AcfPhoneNumber\PhoneNumberField;

/**
 *
 * Bootloader Install
 *
 */

class PAThemeNoticiasInstall
{

	public function __construct()
	{
		add_action('after_setup_theme', array($this, 'installRoutines'), 11);
		add_action('admin_enqueue_scripts', array($this, 'enqueueAssets'));
        add_action('enqueue_block_editor_assets', array($this, 'gutenbergCustomAssets') );
		add_action('after_setup_theme', array($this, 'removePostFormats'), 100);
		add_filter('manage_edit-press_columns', array($this, 'removeFakeColumn'));
		add_action('init', array($this, 'addCustomRoles'));
		add_action('widgets_init', array($this, 'setWidgets'), 11);
		add_action('init', array($this, 'changePostObjectLabel'));
		add_action('admin_menu', array($this, 'changePostMenuLabel'));
		add_filter('manage_post_posts_columns', array($this, 'removeColumns'), 10001);
	}

	function installRoutines()
	{
		/**
		 *
		 * SALA DE IMPRENSA
		 *
		 */
		$labels = array(
			'name'                  => __('Press', 'iasd-noticias'),
			'singular_name'         => __('Press', 'iasd-noticias'),
			'menu_name'             => __('Press', 'iasd-noticias'),
			'name_admin_bar'        => __('Add item', 'iasd-noticias'),
			'add_new'               => __('Add New', 'iasd-noticias'),
			'add_new_item'          => __('Add New Item', 'iasd-noticias'),
			'new_item'              => __('New item', 'iasd-noticias'),
			'edit_item'             => __('Edit item', 'iasd-noticias'),
			'view_item'             => __('View item', 'iasd-noticias'),
			'all_items'             => __('All items', 'iasd-noticias'),
			'search_items'          => __('Search item', 'iasd-noticias'),
			'not_found'             => __('No press found.', 'iasd-noticias'),
			'not_found_in_trash'    => __('No press found in Trash.', 'iasd-noticias'),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => ['slug' => sanitize_title(__('press-room-slug', 'iasd-noticias'))],
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 4,
			'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
			// 'taxonomies'         => array( 'category', 'post_tag' ),
			'show_in_rest'       => true,
		);

		register_post_type('press', $args);

		/**
		 *
		 * FORMATO DE POST
		 *
		 */

		$labels = array(
			'name'              => __('Post format', 'iasd-noticias'),
			'singular_name'     => __('Post format', 'iasd-noticias'),
			'search_items'      => __('Search item', 'iasd-noticias'),
			'all_items'         => __('All items', 'iasd-noticias'),
			'edit_item'         => __('Edit item', 'iasd-noticias'),
			'update_item'       => __('Update item', 'iasd-noticias'),
			'add_new_item'      => __('Add new item', 'iasd-noticias'),
			'new_item_name'     => __('New item', 'iasd-noticias'),
			'menu_name'         => __('Post format', 'iasd-noticias'),
		);
		$args   = array(
			'hierarchical'       => true, // make it hierarchical (like categories)
			'labels'             => $labels,
			//'show_ui'            => checkRole('administrator'),
			'show_admin_column'  => true,
			'show_in_quick_edit' => false,
			'query_var'          => true,
			'show_in_rest'       => true, // add support for Gutenberg editor
			'rewrite'            => ['slug' => sanitize_title(__('xtt-pa-format-slug', 'iasd-noticias'))],
			'default_term'		=> array(
				'name' => 'Notícia',
				'slug'	=> 'noticia'
			)
		);

		register_taxonomy('xtt-pa-format', ['post'], $args);

        /**
		 *
		 * Classificação
		 *
		 */

		$labels = array(
			'name'              => __('Classification', 'iasd-noticias'),
			'singular_name'     => __('Classification', 'iasd-noticias'),
			'search_items'      => __('Search item', 'iasd-noticias'),
			'all_items'         => __('All items', 'iasd-noticias'),
			'edit_item'         => __('Edit item', 'iasd-noticias'),
			'update_item'       => __('Update item', 'iasd-noticias'),
			'add_new_item'      => __('Add new item', 'iasd-noticias'),
			'new_item_name'     => __('New item', 'iasd-noticias'),
			'menu_name'         => __('Classification', 'iasd-noticias'),
		);
		$args   = array(
			'hierarchical'       => true, // make it hierarchical (like categories)
			'labels'             => $labels,
			//'show_ui'            => checkRole('administrator'),
			'show_admin_column'  => true,
			'show_in_quick_edit' => false,
			'query_var'          => true,
			'show_in_rest'       => true, // add support for Gutenberg editor
			'rewrite'            => ['slug' => sanitize_title(__('xtt-pa-classification-slug', 'iasd-noticias'))],
		);

		register_taxonomy('xtt-pa-classification', ['post'], $args);

		/**
		 *
		 * FORMATO DE POST
		 *
		 */

		$labels = array(
			'name'              => __('Press type', 'iasd-noticias'),
			'singular_name'     => __('Press type', 'iasd-noticias'),
			'search_items'      => __('Search items', 'iasd-noticias'),
			'all_items'         => __('All items', 'iasd-noticias'),
			'edit_item'         => __('Edit items', 'iasd-noticias'),
			'update_item'       => __('Update item', 'iasd-noticias'),
			'add_new_item'      => __('Add new item', 'iasd-noticias'),
			'new_item_name'     => __('New item', 'iasd-noticias'),
			'menu_name'         => __('Press type', 'iasd-noticias'),
		);
		$args = array(
			'hierarchical'       => true, // make it hierarchical (like categories)
			'labels'             => $labels,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_quick_edit' => false,
			'query_var'          => true,
			'show_in_rest'       => true, // add support for Gutenberg editor
			'rewrite'            => ['slug' => sanitize_title(__('xtt-pa-press-type-slug', 'iasd-noticias'))],
			'capabilities' 		  => array(
				'edit_terms' 	  => false,
				'delete_terms'    => false,
			),
		);

		register_taxonomy('xtt-pa-press-type', ['press'], $args);

		/**
		 *
		 * REGIÃO
		 *
		 */

		$labels = array(
			'name'          => __('Region', 'iasd-noticias'),
			'singular_name' => __('Region', 'iasd-noticias'),
			'search_items'  => __('Search item', 'iasd-noticias'),
			'all_items'     => __('All items', 'iasd-noticias'),
			'edit_item'     => __('Edit item', 'iasd-noticias'),
			'update_item'   => __('Update item', 'iasd-noticias'),
			'add_new_item'  => __('Add new item', 'iasd-noticias'),
			'new_item_name' => __('New item', 'iasd-noticias'),
			'menu_name'     => __('Regions', 'iasd-noticias'),
		);

		$args   = array(
			'hierarchical'       => true, // make it hierarchical (like categories)
			'labels'             => $labels,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_quick_edit' => false,
			'query_var'          => true,
			'show_in_rest'       => true, // add support for Gutenberg editor
			'rewrite'            => ['slug' => sanitize_title(__('xtt-pa-regiao-slug', 'iasd-noticias'))],
			// 'capabilities' 		 => array(
			// 	'edit_terms' 	 => false,
			// 	'delete_terms'   => false,
			// ),
		);

		// register_taxonomy('xtt-pa-regiao', ['post'], $args);

		register_taxonomy_for_object_type('xtt-pa-editorias', 'press');
		register_taxonomy_for_object_type('xtt-pa-owner', 'press');

		foreach (['acf/include_field_types', 'acf/register_fields'] as $hook) {
			add_filter($hook, function () {
				return new PhoneNumberField(
					get_stylesheet_directory_uri() . '/vendor/log1x/acf-phone-number/public',
					get_stylesheet_directory() . '/vendor/log1x/acf-phone-number/public'
				);
			});
		}
	}

	function enqueueAssets()
	{
		global $current_screen;

		if ($current_screen->id == 'user-edit')
			wp_enqueue_media();

		if ($current_screen->id != 'post' && $current_screen->id != 'edit-post')
			return;

		wp_enqueue_script(
			'adventistas-noticias-admin',
			get_stylesheet_directory_uri() . '/assets/scripts/admin.js',
			array('wp-i18n', 'wp-blocks', 'wp-edit-post', 'wp-element', 'wp-editor', 'wp-components', 'wp-data', 'wp-plugins', 'wp-edit-post', 'lodash'),
			null,
			false
		);
	}

	function gutenbergCustomAssets() {
        wp_enqueue_script(
            'adventistas-noticias-admin-metabox',
            get_stylesheet_directory_uri() . '/assets/scripts/admin-metabox.js',
            null,
            true
        );

        wp_enqueue_style(
            'adventistas-noticias-admin-metabox',
            get_stylesheet_directory_uri() . '/assets/css/admin-metabox.css'
        );
    }

	function removePostFormats()
	{
		remove_theme_support('post-formats');
	}

	function addCustomRoles()
	{
		add_role(
			'colunista',
			__('Columnist', 'iasd-noticias'),
			array(
				'level_1' => true,
				'read' => true,
			)
		);
	}

	function setWidgets()
	{
		register_sidebar(array(
			'name'          => __('Archive columnists', 'iasd-noticias'),
			'id'            => 'archive-authors',
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
		));

		register_sidebar(array(
			'name'          => __('Columnist', 'iasd-noticias'),
			'id'            => 'author',
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
		));

		register_sidebar(array(
			'name'          => __('Archive press', 'iasd-noticias'),
			'id'            => 'front-press',
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
		));

		unregister_sidebar('index');
		unregister_sidebar('single');
	}

	function removeFakeColumn($posts_columns)
	{
		unset($posts_columns['fake']);

		return $posts_columns;
	}

	public static function changePostMenuLabel()
	{
		global $menu;
		global $submenu;
		$menu[5][0] = __('News', 'iasd-noticias');
		$submenu['edit.php'][5][0] = __('News', 'iasd-noticias');
		$submenu['edit.php'][10][0] = __('Add news', 'iasd-noticias');
		echo '';
	}

	public static function changePostObjectLabel()
	{
		global $wp_post_types;
		$labels = &$wp_post_types['post']->labels;
		$wp_post_types['post']->label = $labels->name = __('News', 'iasd-noticias');
		$labels->singular_name = __('News', 'iasd-noticias');
		$labels->add_new = __('Add news', 'iasd-noticias');
		$labels->add_new_item = __('Add news', 'iasd-noticias');
		$labels->edit_item = __('Edit', 'iasd-noticias');
		$labels->new_item = __('News', 'iasd-noticias');
		$labels->view_item = __('View news', 'iasd-noticias');
		$labels->search_items = __('Search news', 'iasd-noticias');
		$labels->not_found = __('No news found', 'iasd-noticias');
		$labels->not_found_in_trash = __('No news in the trash', 'iasd-noticias');
	}

	function removeColumns($columns)
	{
		unset($columns['editor']);
		return $columns;
	}
}

$PAThemeNoticiasInstall = new PAThemeNoticiasInstall();
