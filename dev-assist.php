<?php
/*
Plugin Name: Dev Assit
Description: サイト制作をサポートする設定、関数、ショートコードを提供
Version: 0.0.1
Author: WITHPROJECTS inc.
Author URI: http://www.withprojects.co.jp/
*/
/* Copyright 2017 WITHPROJECTS inc. (email : project@withprojects.co.jp) */
namespace dev_assist;

require_once(__dir__.'/lib/default_options.php');

$plgin_data = get_file_data( __FILE__, ['version' => 'Version'] );
$multisite  = defined('MULTISITE') && MULTISITE ? true : false;

define( 'WPDA_VERSION',         '0.0.1' );
define( 'WPDA_DEFAULT_OPTIONS', $default_options );
define( 'WPDA_DB_OPTIONS_NAME', 'dev-assist_options' );
define( 'WPDA_DIR',             plugin_dir_path( __FILE__ ) );
define( 'WPDA_PATH',            plugins_url('dev-assist/') );
define( 'WPDA_MULTISITE',       $multisite );

require_once( WPDA_DIR.'lib/class/Parser.php' );
require_once( WPDA_DIR.'lib/class/UA.php' );
require_once( WPDA_DIR.'lib/class/Path.php' );
require_once( WPDA_DIR.'lib/class/WP_Blogs.php' );
require_once( WPDA_DIR.'lib/class/WP_Helper.php' );
require_once( WPDA_DIR.'lib/class/WP_Path.php' );
require_once( WPDA_DIR.'lib/class/WP_DB_manager.php' );

$db_manager = new WP_DB_manager( WPDA_MULTISITE, WPDA_DB_OPTIONS_NAME );
$opt        = $db_manager->get();

WP_Path::setup([
	'site_path'     => $opt['site_path'],
	'ext_path'      => $opt['ext_path'],
	'user_ext_path' => $opt['user_ext_path'],
	'parent_id'     => $opt['parent_id'],
	'dir_names'     => [
		'img'  => $opt['img_dir_name'],
		'css'  => $opt['css_dir_name'],
		'js'   => $opt['js_dir_name'],
		'php'  => $opt['php_dir_name'],
		'font' => $opt['font_dir_name'],
	]
]);
// -----------------------------------------------------------------------------
// グローバル関数の定義
//
require_once( WPDA_DIR.'lib/global_funcs.php' );

// =============================================================================
//
// 管理画面へのページ追加
//
// -----------------------------------------------------------------------------
// 専用CSS/JS追加
//
add_action( 'admin_enqueue_scripts', function(){
	wp_enqueue_style( 'dev-assist', WPDA_PATH.'asset/build/css/dev-assist.css' );
	wp_enqueue_script( 'dev-assist', WPDA_PATH.'asset/build/js/dev-assist.js', ['jquery'], true );
} );
// -----------------------------------------------------------------------------
// マルチサイトの場合の処理
//
if ( defined('MULTISITE') && MULTISITE ) {
	// 管理画面へページ追加
	add_action( 'network_admin_menu', function() {
		add_submenu_page(
			'settings.php',   // 親メニューのスラッグ
			'Dev Assist',     // サブメニューページのタイトル
			'Dev Assist',     // プルダウンに表示されるメニュー名
			'administrator', // サブメニューの権限
			'dev-assist',     // サブメニューのスラッグ
			function() {
				include_once( WPDA_DIR.'lib/page.php' );
			}
		);
	} );
}
// -----------------------------------------------------------------------------
// シングルサイトの場合の処理
//
else {
	// 専用CSS/JS追加
	add_action( 'admin_enqueue_scripts', function(){
		wp_enqueue_style( 'dev-assist', WPDA_PATH.'asset/build/css/dev-assist.css' );
		wp_enqueue_script( 'dev-assist', WPDA_PATH.'asset/build/js/dev-assist.js', ['jquery'], true );
	} );
	// 管理画面へページ追加
	add_action( 'admin_menu', function() {
		add_options_page(
			'Dev Assist',    // page_title（オプションページのHTMLのタイトル）
			'Dev Assist',    // menu_title（メニューで表示されるタイトル）
			'administrator', // capability
			'dev-assist',    // menu_slug（URLのスラッグこの例だとoptions-general.php?page=hello-world）
			function() {
				include_once( WPDA_DIR.'lib/page.php' );
			}
		);
	} );
}
// =============================================================================
//
// プラグイン有効化/無効化時の処理
//
// =============================================================================
// 有効化時の処理
//
add_action( 'activated_plugin', function() {
	$db_manager = new WP_DB_manager( WPDA_MULTISITE, WPDA_DB_OPTIONS_NAME );
	if ( !$db_manager->get() ) $db_manager->add( WPDA_DEFAULT_OPTIONS );
} );
// -----------------------------------------------------------------------------
// 無効化時の処理
//
add_action('deactivated_plugin', function() {
	$db_manager = new WP_DB_manager( WPDA_MULTISITE, WPDA_DB_OPTIONS_NAME );
	$opt        = $db_manager->get();
	if ( $opt['delete_option'] ) $db_manager->delete();
} );
// =============================================================================
//
//
//
// -----------------------------------------------------------------------------
// リダイレクト
//
if( $opt['author_page_redirect'] ) {
	add_action( 'template_redirect', function(){
			if ( is_author() ){
					wp_redirect( home_url() );
					exit;
			}
	});
}

// -----------------------------------------------------------------------------
// タイトルタグ出力
//
if( $opt['title_tag'] ) {
	add_theme_support('title-tag');
}
// -----------------------------------------------------------------------------
// 管理バー非表示
//
if ( !$opt['show_admin_bar'] ) {
	add_filter( 'show_admin_bar', '__return_false' );
}
// -----------------------------------------------------------------------------
// ブロック
//
remove_action( 'wp_head', 'wp_generator' ); // meta generator
remove_action( 'wp_head', 'wlwmanifest_link' );                       // Windows Live Writer
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 ); // rel=next | rel=prev 別途代替え
remove_action( 'wp_head', 'wp_shortlink_wp_head' );                   // 短いURL
remove_action( 'wp_head', 'rsd_link' );                               // RSD EditURI

// 絵文字ブロック
if ( $opt['emoji_block'] ) {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'emoji_svg_url', '__return_false' );
};
// eEmbedブロック
if ( $opt['oembed_block'] ) {
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	remove_action( 'template_redirect', 'rest_output_link_header', 11 );
}
// Feedブロック
if( $opt['feed_block'] ) {
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
}
// カノニカルタグブロック
if ( $opt['canonical_block'] ) {
	remove_action( 'wp_head', 'rel_canonical' ); // URL正規化 別途代替え
}

// -----------------------------------------------------------------------------
// カテゴリー並び順調整
//
add_action(
		'wp_terms_checklist_args',
		function($args,$post_id = null){
				$args['checked_ontop'] = false;
				return $args;
		}
);
// -----------------------------------------------------------------------------
// alert
//
if ( $opt['alert'] !== 'disable' ) {
	// var_dump($opt['alert'] === 'prod_env_enable' && );
	// var_dump("ddd");
}
