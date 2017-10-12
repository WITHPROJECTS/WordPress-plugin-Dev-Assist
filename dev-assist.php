<?php
/*
Plugin Name: Dev Assist
Description: サイト制作をサポートする設定、関数、ショートコードを提供
Version: 0.2.0
Author: WITHPROJECTS inc.
Author URI: http://www.withprojects.co.jp/
*/
/* Copyright 2017 WITHPROJECTS inc. (email : project@withprojects.co.jp) */
namespace dev_assist;

require_once(__dir__.'/lib/default-option.php');

$plgin_data = get_file_data( __FILE__, ['version' => 'Version'] );
$multisite  = defined('MULTISITE') && MULTISITE ? true : false;

define( 'WPDA_VERSION',         $plgin_data['version'] );
define( 'WPDA_DEFAULT_OPTIONS', $default_option );
define( 'WPDA_DB_OPTIONS_NAME', 'dev-assist_options' );
define( 'WPDA_DIR',             plugin_dir_path( __FILE__ ) );
define( 'WPDA_URL',             plugins_url( basename(__dir__).'/' ) );
define( 'WPDA_MULTISITE',       $multisite );

require_once( WPDA_DIR.'lib/class/Parser.php' );
require_once( WPDA_DIR.'lib/class/UA.php' );
require_once( WPDA_DIR.'lib/class/Path.php' );
require_once( WPDA_DIR.'lib/class/WP_Blogs.php' );
require_once( WPDA_DIR.'lib/class/WP_Helper.php' );
require_once( WPDA_DIR.'lib/class/WP_Path.php' );
require_once( WPDA_DIR.'lib/class/WP_DB_manager.php' );

WP_Helper::set_media_queries( WPDA_MULTISITE, WPDA_DB_OPTIONS_NAME );
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

require_once( WPDA_DIR.'lib/global-funcs.php' ); // グローバル関数の定義
require_once( WPDA_DIR.'lib/shortcode.php' );    // ショートコードの定義

// -----------------------------------------------------------------------------
//
// 管理画面へのページ追加
//
// 専用CSS/JS追加
add_action( 'admin_enqueue_scripts', function(){
	wp_enqueue_style( 'dev-assist', WPDA_URL.'asset/build/css/dev-assist.css' );
	wp_enqueue_script( 'dev-assist', WPDA_URL.'asset/build/js/dev-assist.js', ['jquery'], false, true );
} );
// マルチサイトの場合の処理
if ( defined('MULTISITE') && MULTISITE ) {
	// 管理画面へページ追加
	add_action( 'network_admin_menu', function() {
		add_submenu_page(
			'settings.php',  // 親メニューのスラッグ
			'Dev Assist',    // サブメニューページのタイトル
			'Dev Assist',    // プルダウンに表示されるメニュー名
			'administrator', // サブメニューの権限
			'dev-assist',    // サブメニューのスラッグ
			function() {
				include_once( WPDA_DIR.'lib/page.php' );
			}
		);
	} );
}
// シングルサイトの場合の処理
else {
	// 専用CSS/JS追加
	add_action( 'admin_enqueue_scripts', function(){
		wp_enqueue_style( 'dev-assist', WPDA_URL.'asset/build/css/dev-assist.css' );
		wp_enqueue_script( 'dev-assist', WPDA_URL.'asset/build/js/dev-assist.js', ['jquery'], true );
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

// -----------------------------------------------------------------------------
// Dev Assist 有効化時の処理
//
add_action( 'activated_plugin', function() {
	$db_manager = new WP_DB_manager( WPDA_MULTISITE, WPDA_DB_OPTIONS_NAME );
	if ( !$db_manager->get() ) $db_manager->add( WPDA_DEFAULT_OPTIONS );
} );

// -----------------------------------------------------------------------------
// Dev Assist 無効化時の処理
//
add_action('deactivated_plugin', function() {
	$db_manager = new WP_DB_manager( WPDA_MULTISITE, WPDA_DB_OPTIONS_NAME );
	$opt        = $db_manager->get();
	if ( $opt['delete_option'] ) $db_manager->delete();
} );

// -----------------------------------------------------------------------------
// 各処理
//
require_once( WPDA_DIR.'lib/action/redirect.php' );      // リダイレクト
require_once( WPDA_DIR.'lib/action/metatag-block.php' ); // ブロック
require_once( WPDA_DIR.'lib/action/admin.php' );         // 管理画面
require_once( WPDA_DIR.'lib/action/other.php' );         // その他
// アラート
if( is_admin() && $opt['alert'] !== 'disable' ) require_once( WPDA_DIR.'lib/action/alert.php' );


// =============================================================================
// Auto Update
//
require_once( WPDA_DIR.'vendor/autoload.php' );
add_action( 'init', function(){
	$plugin_slug = plugin_basename( __FILE__ );
	$gh_user     = 'WITHPROJECTS';
	$gh_repo     = 'WordPress-plugin-Dev-Assist';
	new \Miya\WP\GH_Auto_Updater( $plugin_slug, $gh_user, $gh_repo );
} );
