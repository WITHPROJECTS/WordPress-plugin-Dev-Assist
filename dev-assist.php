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

$default_options = [
	'site_path'     => '',
	'domain'        => '',
	'parent_id'     => 1,
	'delete_option' => false,
	'break_point'   => '',
	'ext_path'             => 'build',
	'user_ext_path'        => 'user-src',
	'img_dir_name'         => 'img',
	'css_dir_name'         => 'css',
	'js_dir_name'          => 'js',
	'php_dir_name'         => 'php',
	'font_dir_name'        => 'font',
	'emoji_block'          => true,              // 絵文字メタタグブロック
	'oembed_block'         => true,              // oEmbedメタタグブロック
	'alert'                => 'prod_env_enable', // アラート出力
	'comment_alert'        => true,              // コメント許可状態時にアラートを出力
	'file_permision_alert' => true,              // ファイル権限がおかしいときにアラートを出力
];

$default_options['break_point'] .= "small=>screen and (max-width: 500px)\n";
$default_options['break_point'] .= "large=>screen and (min-width: 501px)";

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

// =============================================================================
//
// 管理画面へのページ追加
//
// =============================================================================
// 専用CSS/JS追加
// -----------------------------------------------------------------------------
add_action( 'admin_enqueue_scripts', function(){
	wp_enqueue_style( 'dev-assist', WPDA_PATH.'asset/build/css/dev-assist.css' );
	wp_enqueue_script( 'dev-assist', WPDA_PATH.'asset/build/js/dev-assist.js', ['jquery'], true );
} );
// -----------------------------------------------------------------------------
// マルチサイトの場合の処理
// -----------------------------------------------------------------------------
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
// -----------------------------------------------------------------------------
else {
	// 専用CSS/JS追加
	add_action( 'admin_enqueue_scripts', function(){
		wp_enqueue_style( 'dev-assist', WPDA_PATH.'asset/build/css/dev-assist.css' );
		wp_enqueue_script( 'dev-assist', WPDA_PATH.'asset/build/js/dev-assist.js', ['jquery'], true );
	} );
	// 管理画面へページ追加
	add_action( 'admin_menu', function() {
		add_options_page(
			'Dev Assist',                          // page_title（オプションページのHTMLのタイトル）
			'Dev Assist',                          // menu_title（メニューで表示されるタイトル）
			'administrator',                       // capability
			'dev-assist',                          // menu_slug（URLのスラッグこの例だとoptions-general.php?page=hello-world）
			function() {
				include_once( WPDA_DIR.'lib/page.php' );
			}
		);
	} );
}
// =============================================================================
// 有効化時の処理
// =============================================================================
add_action( 'activated_plugin', function() {
	$db_manager = new WP_DB_manager( WPDA_MULTISITE, WPDA_DB_OPTIONS_NAME );
	if ( !$db_manager->get() ) $db_manager->add( WPDA_DEFAULT_OPTIONS );
} );

// =============================================================================
// 無効化時の処理
// =============================================================================
add_action('deactivated_plugin', function() {
	$db_manager = new WP_DB_manager( WPDA_MULTISITE, WPDA_DB_OPTIONS_NAME );
	$opt        = $db_manager->get();
	if ( $opt['delete_option'] ) $db_manager->delete();
} );
