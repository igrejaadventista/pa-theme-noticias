<?php

if(file_exists($composer = __DIR__ . '/vendor/autoload.php'))
    require_once $composer;

define('PARENT_THEME_URI', get_template_directory_uri() . '/');
define('THEME_URI', get_stylesheet_directory_uri() . '/');
define('THEME_DIR', get_stylesheet_directory() . '/');
define('THEME_CSS', THEME_URI . 'assets/css/');
define('THEME_JS', THEME_URI . 'assets/js/');
define('THEME_IMGS', THEME_URI . 'assets/images/');

new \Blocks\ChildBlocks;

require_once(dirname(__FILE__) . '/classes/controllers/PA_ACF_Leaders.class.php');
require_once(dirname(__FILE__) . '/classes/controllers/PA_ACF_HomeFields.class.php');
require_once(dirname(__FILE__) . '/classes/controllers/PA_ACF_PostFields.class.php');
require_once(dirname(__FILE__) . '/classes/controllers/PA_ACF_Site-ministries.class.php');
require_once(dirname(__FILE__) . '/classes/controllers/PA_CPT_Projects.class.php');
require_once(dirname(__FILE__) . '/classes/controllers/PA_CPT_Leaders.class.php');
require_once(dirname(__FILE__) . '/classes/controllers/PA_CPT_SliderHome.class.php');
require_once(dirname(__FILE__) . '/classes/controllers/PA_Enqueue_Files.class.php');
require_once(dirname(__FILE__) . '/classes/controllers/PA_Page_Lideres.php');
require_once(dirname(__FILE__) . '/classes/controllers/PA_Util.class.php');
require_once(dirname(__FILE__) . '/classes/PA_Helpers.php');

// CORE INSTALL
require_once (dirname(__FILE__) . '/core/PA_Theme_Noticias_Install.php');

/**
* Remove unused taxonomies
*/
add_action('wp_loaded', function() {
    unregister_taxonomy_for_object_type('xtt-pa-colecoes', 'post');
    unregister_taxonomy_for_object_type('post_tag', 'post');
    unregister_taxonomy_for_object_type('category', 'post');
});

add_filter('blade/view/paths', function ($paths) {
    $paths = (array)$paths;

    $paths[] = get_stylesheet_directory();

    return $paths;
});

add_filter('template_include', function ($template) {
    $path = explode('/', $template);
    $template_chosen = basename(end($path), '.php');
    $template_chosen = str_replace('.blade', '', $template_chosen);
    $grandchild_template = dirname(__FILE__) . '/' . $template_chosen . '.blade.php';

    if(file_exists($grandchild_template)):
        blade($template_chosen);
        return '';
    endif;

    return $template;
});

/**
* Filter save post to get video length
*/
add_action('acf/save_post', function($post_id) {
    if(get_post_type($post_id) != 'post')
        return;

    $url = parse_url(get_field('video_url', $post_id, false));
    $host = '';
    $id = '';

    if(empty($url))
        return;

    if(str_contains($url['host'], 'youtube') || str_contains($url['host'], 'youtu.be')):
        $host = 'youtube';

        if(array_key_exists('query', $url)):
            parse_str($url['query'], $params);
            $id = $params['v'];
        else:
            $id = str_replace('/', '', $url['path']);
        endif;
    elseif(str_contains($url['host'], 'vimeo')):
        $host = 'vimeo';
        $id = str_replace('/', '', $url['path']);
    endif;

    if(!empty($host) && !empty($id))
        getVideoLength($post_id, $host, $id);
});

function loadMore() {
    die(var_dump($_POST));
}
add_action('wp_ajax_load_more', 'loadMore');
add_action('wp_ajax_nopriv_load_more', 'loadMore');