<?php

use dev_assist\WP_Path   as Path;
use dev_assist\WP_Helper as Helper;
use dev_assist\UA        as UA;

// =============================================================================
// 
// Path
// 
// =============================================================================
/**
 * 引数として与えられたパスを結合
 * 最後の引数のみオプションとして連想配列を渡せる
 * @version 0.0.1
 *
 * @var   mixed[] ...$opt (optional) {
 *   @var boolean 'last_slash' パスの最後に/をつける
 * }
 * @return string
 *
 */
function wpda_join() {
	$args = func_get_args();
	return forward_static_call_array( [ '\dev_assist\WP_Path' , 'join'] , $args);
}
// -----------------------------------------------------------------------------
/**
 * 指定されたパスかurlを返す
 * @version 0.0.1
 *
 * @var   mixed[] $param {
 *   @var boolean 'uri'  uriで出力する。falseの場合はパスを返す
 *   @var string 'where' 検索先を指定。"theme"か"user"
 *   @var string 'blog'  検索するブログディレクトリを指定。特殊なものとしてactive(利用中のテーマ), root(親ブログのテーマ)が利用可能
 *   @var string 'path'  検索先からの相対パス
 * }
 * @return string
 */
function wpda_get_src( $param ) {
	return Path::get_src( $param );
}
/**
 * 指定されたパスかurlを出力
 * @version 0.0.1
 * @see wpda_get_src()
 */
function wpda_src( $param ) {
	Path::src( $param );
}
// -----------------------------------------------------------------------------
/**
 * テーマのURLを返す
 * @version 0.0.1
 *
 * @var    string $blog
 * @var    string $path
 * @return string
 */
function wpda_get_theme_url( $path = '',  $blog = 'active' ) {
	return Path::get_src( [
		'uri'   => true,
		'where' => 'theme',
		'blog'  => $blog,
		'path'  => $path
	] );
}
/**
 * テーマのURLを出力
 * @version 0.0.1
 * @see wpda_get_theme_url()
 */
function wpda_theme_url( $path = '',  $blog = 'active' ) {
	echo wpda_get_theme_url( $path, $blog );
}
// -----------------------------------------------------------------------------
/**
 * テーマのパスを返す
 * @version 0.0.1
 *
 * @var    string $blog
 * @var    string $path
 * @return string
 */
function wpda_get_theme_path( $path = '',  $blog = 'active' ) {
	return Path::get_src( [
		'uri'   => false,
		'where' => 'theme',
		'blog'  => $blog,
		'path'  => $path
	] );
}
/**
 * テーマへのパスを出力
 * @version 0.0.1
 *
 * @see wpda_get_theme_path()
 */
function wpda_theme_path( $path = '',  $blog = 'active' ) {
	echo wpda_get_theme_path( $path, $blog );
}
// -----------------------------------------------------------------------------
/**
 * ユーザーディレクトリのURLを返す
 * @version 0.0.1
 *
 * @var    string $blog
 * @var    string $path
 * @return string
 */
function wpda_get_user_url( $path = '',  $blog = 'active' ) {
	return Path::get_src( [
		'uri'   => true,
		'where' => 'user',
		'blog'  => $blog,
		'path'  => $path
	] );
}
/**
 * ユーザーディレクトリのURLを出力
 * @version 0.0.1
 *
 * @see wpda_get_user_url()
 */
function wpda_user_url( $path = '',  $blog = 'active' ) {
	echo wpda_get_user_url( $path, $blog );
}
// -----------------------------------------------------------------------------
/**
 * ユーザーディレクトリのパスを返す
 * @version 0.0.1
 *
 * @var    string $blog
 * @var    string $path
 * @return string
 */
function wpda_get_user_path( $path = '',  $blog = 'active' ) {
	return Path::get_src( [
		'uri'   => false,
		'where' => 'user',
		'blog'  => $blog,
		'path'  => $path
	] );
}
/**
 * ユーザーディレクトリのパスを出力
 * @version 0.0.1
 *
 * @see wpda_get_user_path()
 */
function wpda_user_path( $path = '',  $blog = 'active' ) {
	echo wpda_get_user_path( $path, $blog );
}
// -----------------------------------------------------------------------------
/**
 * ページ用素材のパスかurlを返す
 * @version 0.0.1
 *
 * @var    string  $dir_name ディレクトリ名
 * @var    boolean $url      trueでurl falseでパス
 * @var    string  $from      検索先の指定"theme"か"user"
 * @var    string  $path      検索先からのパス
 * @return string
 */
function wpda_get_page( $dir_name, $url, $from, $path = '' ) {
	$request_uri_from_blog = Path::prop( 'REQUEST_URI_FROM_BLOG' );
	if ( is_front_page() || is_home() ) $path = Path::join( 'toppage', $path );
	$path = path::join( $dir_name, 'page', $request_uri_from_blog, $path );
	if ( is_404() ) $path = path::join( $dir_name, 'page', '404' );
	$dest = Path::get_src([
		'uri'   => $url,
		'where' => $from,
		'blog'  => 'active',
		'path'  => $path
	]);
	return $dest;
}
/**
 * ページ用素材のパスかurlを出力する
 * @version 0.0.1
 *
 * @see wpda_get_page()
 */
function wpda_page( $dir_name, $url, $from, $path = '' ) {
	echo wpda_get_page( $dir_name, $url, $from, $path );
}
// -----------------------------------------------------------------------------
/**
 * ページ用画像のurlを返す
 * @version 0.0.1
 *
 * @var    string $from 検索先の指定"theme"か"user"
 * @var    string $path 検索先からのパス
 * @return string
 */
function wpda_get_page_img_url( $from, $path = '' ) {
	$img_dir_name = Path::prop('IMAGE_DIR_NAME');
	return wpda_get_page( $img_dir_name, true, $from, $path );
}
/**
 * ページ用画像のurlを出力する
 * @version 0.0.1
 *
 * @see wpda_get_page_img_url()
 */
function wpda_page_img_url( $from, $path = '' ) {
	echo wpda_get_page_img_url( $from, $path );
}
// -----------------------------------------------------------------------------
/**
 * ページ用CSSのurlを返す
 * @version 0.0.1
 *
 * @var    string $from 検索先の指定"theme"か"user"
 * @var    string $path 検索先からのパス
 * @return string
 */
function wpda_get_page_css_url( $from, $path = '' ) {
	$css_dir_name = Path::prop('CSS_DIR_NAME');
	return wpda_get_page( $css_dir_name, true, $from, $path );
}
/**
 * ページ用画像のurlを出力する
 * @version 0.0.1
 *
 * @see wpda_get_page_css_url()
 */
function wpda_page_css_url( $from, $path = '' ) {
	echo wpda_get_page_css_url( $from, $path );
}
// -----------------------------------------------------------------------------
/**
 * ページ用JSのurlを返す
 * @version 0.0.1
 *
 * @var    string $from 検索先の指定"theme"か"user"
 * @var    string $path 検索先からのパス
 * @return string
 */
function wpda_get_page_js_url( $from, $path = '' ) {
	$js_dir_name = Path::prop('JS_DIR_NAME');
	return wpda_get_page( $js_dir_name, true, $from, $path );
}
/**
 * ページ用画像のurlを出力する
 * @version 0.0.1
 *
 * @see wpda_get_page_js_url()
 */
function wpda_page_js_url( $from, $path = '' ) {
	echo wpda_get_page_js_url( $from, $path );
}
// -----------------------------------------------------------------------------
/**
 * ページ用PHPのurlを返す
 * @version 0.0.1
 *
 * @var    string $from 検索先の指定"theme"か"user"
 * @var    string $path 検索先からのパス
 * @return string
 */
function wpda_get_page_php_path( $from, $path = '' ) {
	$php_dir_name = Path::prop('PHP_DIR_NAME');
	return wpda_get_page( $php_dir_name, false, $from, $path );
}
/**
 * ページ用画像のurlを出力する
 * @version 0.0.1
 *
 * @see wpda_get_page_php_path()
 */
function wpda_page_php_path( $from, $path = '' ) {
	echo wpda_get_page_php_path( $from, $path );
}
// -----------------------------------------------------------------------------
/**
 * ブログのurlを返す
 * @version 0.0.1
 *
 * @var    string $blog_name
 * @return string
 */
function wpda_get_blog_url( $blog_name = 'active' ) {
	if ( $blog_name === 'active' ) $blog_name = basename( get_stylesheet_directory_uri() );
	$blogs = Path::prop( 'blogs' );
	$blog  = $blogs->get_blog_data( $blog_name );
	return $blog['url'];
}
/**
 * ブログのurlを出力する
 * @version 0.0.1
 *
 * @see wpda_get_blog_url
 */
function wpda_blog_url( $blog_name = 'active' ) {
	echo wpda_get_blog_url( $blog_name );
}
// -----------------------------------------------------------------------------
/**
 * ページパスを返す
 * @version 0.0.1
 *
 * @return string
 */
function wpda_get_page_path() {
	$request_uri_from_blog = Path::prop( 'REQUEST_URI_FROM_BLOG' );
	return $request_uri_from_blog;
}
/**
 * ページパスを出力
 * @see wpda_get_page_path()
 */
function wpda_page_path() {
	echo wpda_get_page_path();
}
// =============================================================================
// 
// UA
// 
// =============================================================================
/**
 * UserAgentを返す
 * @version 0.0.1
 * 
 * @return string
 */
function wpda_get_ua() {
	return UA::get_ua();
}
// -----------------------------------------------------------------------------
/**
 * デバイス名を返す
 * @version 0.0.1
 * 
 * @return string
 */
function wpda_get_device_name() {
	return UA::get_device_name();
}
// -----------------------------------------------------------------------------
/**
 * スマホ環境かどうかを返す
 * @version 0.0.1
 * 
 * @return boolean
 */
function wpda_is_smartphone() {
	return UA::is_smartphone();
}
// -----------------------------------------------------------------------------
/**
 * タブレット環境かどうかを返す
 * @version 0.0.1
 * 
 * @return boolean
 */
function wpda_is_tablet() {
	return UA::is_tablet();
}
// -----------------------------------------------------------------------------
/**
 * PC環境かどうかを返す
 * @version 0.0.1
 * 
 * @return boolean
 */
function wpda_is_pc() {
	return UA::is_pc();
}
// =============================================================================
// 
// Helper
// 
// =============================================================================
/**
 * [function description]
 * @var string $slug
 */
function wpda_slug2id( $slug ) {
	return Helper::slug2id( $slug );
}
