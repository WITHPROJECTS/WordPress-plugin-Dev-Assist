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
 * ソースパスを返す
 * @version 0.2.0
 *
 * @var string $where    "theme"か"user" 初期値 "theme"
 * @var string $dir_name 動作設定で設定したディレクトリ名 初期値
 * @var string $add_path 追加パス
 * @var string $blog     対象のブログ 初期値 "active"
 * @return string
 */
function wpda_get_src_path( $where = 'theme', $dir_name = '', $add_path = '', $blog = 'active' ) {
   return Path::join( Path::get_src([
	   'uri'   => false,
	   'where' => $where,
	   'blog'  => $blog,
	   'path'  => $dir_name
   ]), $add_path );
}
/**
 * ソースパスを出力する
 * @version 0.2.0
 * @see wpda_get_src_path()
 */
function wpda_src_path( $where = 'theme', $dir_name = '', $add_path = '', $blog = 'active' ) {
	echo wpda_get_src_path( $where, $dir_name, $add_path );
}
// -----------------------------------------------------------------------------
/**
 * ソースURLを返す
 * @version 0.2.0
 *
 * @var string $where    "theme"か"user" 初期値 "theme"
 * @var string $dir_name 動作設定で設定したディレクトリ名 初期値
 * @var string $add_path 追加パス
 * @var string $blog     対象のブログ 初期値 'active'
 * @return string
 */
function wpda_get_src_url( $where = 'theme', $dir_name = '', $add_path = '', $blog = 'active' ) {
   return Path::join( Path::get_src([
	   'uri'   => true,
	   'where' => $where,
	   'blog'  => $blog,
	   'path'  => $dir_name
   ]), $add_path );
}
/**
 * ソースURLを出力する
 * @version 0.2.0
 * @see wpda_get_src_url()
 */
function wpda_src_url( $where = 'theme', $dir_name = '', $add_path = '', $blog = 'active' ) {
	echo wpda_get_src_url( $where, $dir_name, $add_path, $blog );
}

// -----------------------------------------------------------------------------
/**
 * テーマのURLを返す
 * @version 0.0.1
 *
 * @var    string $blog
 * @return string
 */
function wpda_get_theme_url( $blog = 'active' ) {
	$root  = get_theme_root_uri();
	$blogs = Path::prop( 'blogs' );
	$blog  = $blogs->get_blog_data( $blog );
	return wpda_join($root, $blog['theme_name'] );
}
/**
 * テーマのURLを出力
 * @version 0.0.1
 * @see wpda_get_theme_url()
 */
function wpda_theme_url( $blog = 'active' ) {
	echo wpda_get_theme_url( $blog );
}
// -----------------------------------------------------------------------------
/**
 * テーマのパスを返す
 * @version 0.0.1
 *
 * @var    string $blog
 * @return string
 */
function wpda_get_theme_path( $blog = 'active' ) {
	$root  = get_theme_root();
	$blogs = Path::prop( 'blogs' );
	$blog  = $blogs->get_blog_data( $blog );
	return wpda_join($root, $blog['theme_name'] );
}
/**
 * テーマへのパスを出力
 * @version 0.0.1
 *
 * @see wpda_get_theme_path()
 */
function wpda_theme_path( $blog = 'active' ) {
	echo wpda_get_theme_path( $blog );
}
// -----------------------------------------------------------------------------
/**
 * ユーザーディレクトリのURLを返す
 * @version 0.0.1
 *
 * @var    string $blog
 * @return string
 */
function wpda_get_user_url( $blog = 'active' ) {
	return Path::get_src( [
		'uri'   => true,
		'where' => 'user',
		'blog'  => $blog,
		'path'  => ''
	] );
}
/**
 * ユーザーディレクトリのURLを出力
 * @version 0.0.1
 *
 * @see wpda_get_user_url()
 */
function wpda_user_url( $blog = 'active' ) {
	echo wpda_get_user_url( $blog );
}
// -----------------------------------------------------------------------------
/**
 * ユーザーディレクトリのパスを返す
 * @version 0.0.1
 *
 * @var    string $blog
 * @return string
 */
function wpda_get_user_path( $blog = 'active' ) {
	return Path::get_src( [
		'uri'   => false,
		'where' => 'user',
		'blog'  => $blog,
		'path'  => ''
	] );
}
/**
 * ユーザーディレクトリのパスを出力
 * @version 0.0.1
 *
 * @see wpda_get_user_path()
 */
function wpda_user_path( $blog = 'active' ) {
	echo wpda_get_user_path( $blog );
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
	return wpda_join( wpda_get_page( $img_dir_name, true, $from, $path ), [ 'last_slash' => false ] );
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
	return wpda_join( wpda_get_page( $css_dir_name, true, $from, $path ), [ 'last_slash' => false ] );
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
	return wpda_join( wpda_get_page( $js_dir_name, true, $from, $path ), [ 'last_slash' => false ] );
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
	return wpda_join( wpda_get_page( $php_dir_name, false, $from, $path ), [ 'last_slash' => false ] );
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
/**
 * アクティブになっているテーマの名前が引数のものか
 * @version 0.1.0
 * 
 * @var    string  $name
 * @return boolean
 */
function wpda_is_active_theme_name( $name ) {
	// var_dump(get_template());
	$blogs = Path::prop( 'blogs' );
	$blog  = $blogs->get_blog_data( 'active' );
	return $blog['theme_name'] === $name;
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
 * パスを渡すとIDを返す
 * @version 0.0.1
 * 
 * @var    string $slug
 * @var    string $type 投稿タイプ
 * @return int|boolean
 */
function wpda_path2id( $path, $type = 'page' ) {
	return Helper::path2id( $path, $type );
}
// -----------------------------------------------------------------------------
/**
 * 引数で指定したページからそのページのルートとなるページまでの情報を配列で返す
 * デフォルトでは最後に配列を反転させるのでルートページ->指定したページの順の配列になる
 * @version 0.0.1
 * 
 * @var    int|WP_Post $id
 * @var    boolean     $reverse 結果を反転させるか
 * @return array[]|false
 */
function wpda_get_post_history( $id, $reverse = true ) {
	return Helper::get_post_history( $id, $reverse );
}
// -----------------------------------------------------------------------------
/**
 * 固定ページや投稿タイプの一番上のページ(ルートとなるページ)のオブジェクトを返す
 * @version 0.0.1
 * 
 * @var    int|WP_Post $id
 * @return WP_Post
 */
function wpda_get_root_post( $id ) {
	return Helper::get_root_post( $id );
}
// -----------------------------------------------------------------------------
/**
 * 引数で指定したタームからそのタームのルートとなるタームまでの情報を配列で返す
 * デフォルトでは最後に配列を反転させるのでルートターム->指定したタームの順の配列になる
 * @version 0.0.1
 * 
 * @var    string             $tax
 * @var    string|int|WP_Term $term
 * @var    boolean            $reverse 結果を反転させるか
 * @return array[]|false
 */
function wpda_get_term_history( $tax, $term, $reverse = true ) {
	return Helper::get_term_history( $tax, $term, $reverse );
}
// -----------------------------------------------------------------------------
/**
 * タームの一番上の親タームのオブジェクトを返す
 * @version 0.0.1
 * 
 * @var    string             $tax
 * @var    string|int|WP_Term $term
 * @return false
 */
function wpda_get_root_term( $tax, $term ) {
	return Helper::get_root_term( $tax, $term );
}
// -----------------------------------------------------------------------------
/**
 * タームが他のタームの「先祖」タームであるかどうかをチェック
 * @version 0.0.1
 * 
 * @var    WP_Term|int|string $descendant 祖先
 * @var    WP_Term|int|string $ancestor   先祖
 * @var    string             $tax
 * @return boolean
 */
function wpda_is_tax_ancestor_of( $descendant, $ancestor, $tax ) {
	return Helper::is_tax_ancestor_of( $descendant, $ancestor, $tax );
}
// -----------------------------------------------------------------------------
/**
 * 投稿が他の投稿の「先祖」投稿であるかどうかをチェック
 * @access public
 * @version 0.0.1
 * 
 * @var    WP_Post|int|string  $descendant 祖先 stringの場合はパス
 * @var    WP_Post|int|string  $ancestor   先祖 stringの場合はパス
 * @var    string              $type       投稿タイプ $descendant,$ancestorにstring型を与えた場合のみ結果に影響
 * @return boolean
 */
function wpda_is_post_ancestor_of( $descendant, $ancestor, $type = 'page' ) {
	return Helper::is_post_ancestor_of( $descendant, $ancestor, $type );
}
// -----------------------------------------------------------------------------
/**
 * 子ページかチェック
 * @version 0.0.1
 *
 * @param  WP_Post|int|string $post
 * @param  string             $type 投稿タイプ $postにstring型を与えた場合のみ結果に影響
 * @return boolean
 */
function wpda_is_child_post( $post, $type='page' ) {
	return Helper::is_child_post( $post, $type );
}
// -----------------------------------------------------------------------------
/**
 * 子ページを持っているかチェック
 * @version 0.0.1
 * 
 * @param WP_Post|int|string $post
 * @param string             $type
 * @return boolean
 */
function wpda_has_child_page( $post, $type ) {
   return Helper::has_child_page( $post, $type );
}
// -----------------------------------------------------------------------------
/**
 * wp_enque_styleのラッパー　メディアクエリの設定を簡単にする
 * @version 0.0.1
 * 
 * @param  string           $handle ハンドル名
 * @param  string           $src    パス
 * @param  string[]|boolean $deps   依存ファイル(ハンドル名で指定)
 * @param  string|boolean   $ver    バージョン
 * @param  string           $media  メディアクエリ デフォルトはall
 * @return void
 */
function wpda_enqueue_style( $handle, $src, $deps=false, $ver=false, $media='all' ) {
	Helper::enqueue_style( $handle, $src, $deps, $ver, $media );
}
