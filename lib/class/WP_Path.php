<?php
// /////////////////////////////////////////////////////////////////////////////
//
// ブログの指定は、そのブログでアクティブになっているテーマのディレクトリ名で指定する
//
//
namespace dev_assist;

include_once( __dir__.'/Path.php' );
include_once( __dir__.'/WP_Blogs.php');

class WP_Path extends Path{
	private static $THEME_ROOT              = null;  // 「/」から始まるテーマディレクトリへのURL
	private static $THEME_ROOT_URI          = null;  // 「http」から始まるテーマディレクトリへのURL
	private static $PROTOCOL                = null;  // プロトコル「https://」か「http://」
	private static $EXT_PATH_FROM_THEME_DIR = null;  // 外部ファイルを置いているディレクトリ (アクティブなテーマ内から相対パス)
	private static $USER_EXT_PATH           = null;  // 「/」から始まるユーザー用ディレクトリへのURL
	private static $USER_EXT_URI            = null;  // 「http」から始まるユーザー用ディレクトリへのURL
	private static $SITE_ROOT_FROM_WEB      = null;
	private static $WEB_ROOT_URI            = null;
	private static $REQUEST_URI             = null;  // リクエストされたページのURL
	private static $REQUEST_URI_FROM_BLOG   = null;  // リクエストされたページのURL (ブログルートから)
	private static $QUERY_STRING            = null;
	private static $IMAGE_DIR_NAME          = 'img';  // 画像ディレクトリ名
	private static $JS_DIR_NAME             = 'js';   // JSディレクトリ名
	private static $CSS_DIR_NAME            = 'css';  // CSSディレクトリ名
	private static $PHP_DIR_NAME            = 'php';  // PHPディレクトリ名
	private static $FONT_DIR_NAME           = 'font'; // フォントディレクトリ名
	private static $blogs                   = null;
	private static $setend                  = false;
	/**
	 *
	 * setupが完了していない場合エラーを出力
	 *
	 * @access  private
	 * @version 0.0.1
	 * @todo
	 *
	 * @return void
	 */
	private static function check_setup() {
		if ( !self::$setend ) {
			try {
				throw new \LogicException( 'WP_Pathのメソッドを利用するにはWP_Path::setup($param)を初めに呼んで下さい。', 1 );
			} catch ( \LogicException $e ) {
				die( "WP_Path / Logic Exception / code : {$e->getCode()}/ msg : {$e->getMessage()}" );
			}
		}
	}
	/**
	 *
	 * WP_Pathの初期化
	 * 設定値の変更などを行う
	 *
	 * @access  public
	 * @version 0.0.1
	 * @todo
	 *
	 * @param  mixed[] $param {
	 *   @type string 'site_path'     サイト
	 *   @type string 'ext_path'      開発者用外部ファイルディレクトリ
	 *   @type string 'user_ext_path' ユーザー用外部ファイルディレクトリ
	 *   @type int    'parent_id'     親ブログのID
	 * }
	 * @return void
	 */
	public static function setup( $param ) {
		$site_path     = $param['site_path'];
		$user_ext_path = $param['user_ext_path'];
		$ext_path      = $param['ext_path'];
		$parent_id     = $param['parent_id'];
		$domain        = $_SERVER['SERVER_NAME'];

		// プロトコル
		self::$PROTOCOL = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https://' : 'http://';

		// WordPress wp-content/themesへのパス
		self::$THEME_ROOT = parent::join( get_theme_root() );

		// WordPress wp-content/themesへのURL
		self::$THEME_ROOT_URI = parent::join( get_theme_root_uri() );

		// 開発者用外部ファイルディレクトリパス (テーマディレクトリからの相対パス)
		self::$EXT_PATH_FROM_THEME_DIR = $ext_path;

		// WEBルートURL
		self::$WEB_ROOT_URI = parent::join( self::$PROTOCOL, $domain );

		// ユーザー用外部ファイルディレクトリパス
		self::$USER_EXT_PATH = parent::join( $_SERVER['DOCUMENT_ROOT'], $site_path, $user_ext_path );

		// ユーザー用外部ファイルディレクトリへのURL
		self::$USER_EXT_URI = parent::join( self::$WEB_ROOT_URI, $site_path, $user_ext_path );

		// ブログ情報取得
		self::$blogs = WP_Blogs::get_instance( $parent_id );
		// self::$blogs->get_blog_data();

		// リクエストされたURLの設定
		$request_uri        = $_SERVER["REQUEST_URI"];
		self::$QUERY_STRING = $_SERVER['QUERY_STRING'];
		if( !empty( self::$QUERY_STRING ) ) $request_uri = str_replace( "?{self::$QUERY_STRING}", '', $request_uri );
		self::$REQUEST_URI = parent::join( self::$PROTOCOL, $domain, $request_uri );
		$blog = self::$blogs->get_blog_data();
		self::$REQUEST_URI_FROM_BLOG = str_replace( $blog['url'], '', self::$REQUEST_URI );

		// ディレクトリ名設定
		self::$IMAGE_DIR_NAME = $param['dir_names']['img'];
		self::$JS_DIR_NAME    = $param['dir_names']['js'];
		self::$CSS_DIR_NAME   = $param['dir_names']['css'];
		self::$PHP_DIR_NAME   = $param['dir_names']['php'];
		self::$FONT_DIR_NAME  = $param['dir_names']['font'];

		self::$setend = true;
	}
	/**
	 *
	 * ディレクトリ名前を返す
	 *
	 * @access  public
	 * @version 0.0.1
	 * @todo
	 *
	 * @param  string $key
	 * @return string
	 */
	public static function get_dir_name( $key ) {
		self::check_setup();
		if ( $key === 'image' ) $result = self::$IMAGE_DIR_NAME;
		if ( $key === 'js' )    $result = self::$JS_DIR_NAME;
		if ( $key === 'css' )   $result = self::$CSS_DIR_NAME;
		if ( $key === 'php' )   $result = self::$PHP_DIR_NAME;
		if ( $key === 'font' )  $result = self::$FONT_DIR_NAME;
		return $result;
	}
	/**
	 *
	 * パスを返す
	 *
	 * @access  public
	 * @version 0.0.1
	 * @todo
	 *
	 * @param mixed[] $param {
	 *   @var boolean 'uri'   trueで「http」で返す。falseで「/」で返す
	 *   @var string  'where' 参照するディレクトリの指定。"theme" か "user"
	 *   @var string  'blog'  参照するテーマの名前(ディレクトリ名)
	 *   @var string  'path'  上記引数で指定されたパスからの相対パス
	 * }
	 * @return string パスかURL
	 */
	private static $path_cache_arr = [];
	public static function get_src( $param ) {
		self::check_setup();
		extract($param, EXTR_OVERWRITE);

		// キャッシュされたデータがあれば返す
		$cache_key = ($uri ? 'true' : 'false')."-{$where}-{$blog}-{$path}";
		if( isset( self::$path_cache_arr[ $cache_key ] ) ) return self::$path_cache_arr[ $cache_key ];

		$dest  = null;

		// ベースパスの設定
		if ( $blog === 'active' ) {
			$blog = get_stylesheet_directory_uri();
			$blog = parent::basename( $blog );
		}
		elseif( is_string( $blog ) ) {
			$blog = self::$blogs->get_blog_data( $blog );
			$blog = $blog['theme_name'];
		}

		// URLを返す場合
		if ( $uri ) {
			if ( $where === 'theme' ) {
				$dest = parent::join( self::$THEME_ROOT_URI, $blog, self::$EXT_PATH_FROM_THEME_DIR, $path );
			}elseif ($where === 'user') {
				$dest = parent::join( self::$USER_EXT_URI, 'themes', $blog, $path );
			}
		}
		// パスを返す場合
		else {
			if ( $where === 'theme' ) {
				$dest = parent::join( self::$THEME_ROOT, $blog, self::$EXT_PATH_FROM_THEME_DIR, $path );
			} elseif ($where === 'user' ) {
				$dest = parent::join( self::$USER_EXT_PATH, 'themes', $blog, $path );
			}
		}
		self::$path_cache_arr[ $cache_key ] = $dest;
		return $dest;
	}
	/**
	 *
	 * パスを出力する
	 *
	 * @access  public
	 * @version 0.0.1
	 * @todo
	 * @see WP_Path::src
	 *
	 * @return void
	 */
	public static function src( $param ) {
		echo self::get_src( $param );
	}

	/**
	 *
	 * 親ブログで利用しているテーマのへのパス
	 *
	 * @access  public
	 * @version 0.0.1
	 * @todo
	 *
	 * @param string $path
	 * @return string
	 */
	// public static function get_root_blog_src( $path='' ){
	// 	return self::get_src([
	// 		'uri'   => true,
	// 		'where' => 'theme',
	// 		'blog'  => self::$blogs->get_root_blog_data()['theme_name'],
	// 		'path'  => $path
	// 	]);
	// }
	// // -------------------------------------------------------------------------
	// // 親ブログでアクティブになっているテーマ内のパス
	// // -------------------------------------------------------------------------
	// public static function get_root_blog_src($path=''){
	// 	return self::_src([
	// 		'uri'   => true,
	// 		'where' => 'theme',
	// 		'blog'  => self::$blogs->get_root_blog_data()['theme_name'],
	// 		'path'  => $path
	// 	]);
	// }
	// // 出力
	// public static function root_blog_src($path=''){ echo self::get_root_blog_src($path); }
	// // -------------------------------------------------------------------------
	// // ユーザーディレクトリのパス
	// // -------------------------------------------------------------------------
	// public static function get_user_src($path='',$blog='active'){
	// 	return self::_src([
	// 		'uri'   => true,
	// 		'where' => 'user',
	// 		'blog'  => $blog,
	// 		'path'  => $path
	// 	]);
	// }
	// // 出力
	// public static function user_src($path='',$blog='active'){ echo self::get_user_src($path,$blog); }
	// // -------------------------------------------------------------------------
	// // 親ブログでアクティブになっているテーマのユーザーディレクトリのパス
	// // -------------------------------------------------------------------------
	// public static function get_user_root_blog_src($path=''){
	// 	return self::_src([
	// 		'uri'   => true,
	// 		'where' => 'user',
	// 		'blog'  => self::$blogs->get_root_blog_data()['theme_name'],
	// 		'path'  => $path
	// 	]);
	// }
	// // 出力
	// public static function user_root_blog_src($path=''){ echo self::get_user_root_blog_src($path); }
	// // =========================================================================
	// // ページ用パスを出力
	// // =========================================================================
	// private static function _get_page($uri,$where,$path,$blog){
	// 	return self::_src([
	// 		'uri'   => $uri,
	// 		'where' => $where,
	// 		'path'  => $path,
	// 		'blog'  => $blog
	// 	]);
	// }
	// // -------------------------------------------------------------------------
	// // テーマ内のページ用画像
	// // -------------------------------------------------------------------------
	// public static function get_page_img($path=''){
	// 	if(is_front_page() || is_home()) $path = parent::join('toppage',$path);
	// 	$path  = path::join(self::$IMAGE_DIR_NAME,'page',self::$REQUEST_URI_FROM_BLOG,$path);
	// 	if(is_404()) $path = path::join(self::$IMAGE_DIR_NAME,'page','404');
	// 	$dest = self::_get_page(true,'theme',$path,'active');
	// 	$dest = apply_filters('WP_Path_get_page_img',$dest);
	// 	return $dest;
	// }
	// // 出力
	// public static function page_img($path=''){ echo self::get_page_img($path); }
	// // -------------------------------------------------------------------------
	// // テーマ内のページ用CSS
	// // -------------------------------------------------------------------------
	// public static function get_page_css($path=''){
	// 	if(is_front_page() || is_home()) $path = parent::join('toppage',$path);
	// 	$path  = path::join(self::$CSS_DIR_NAME,'page',self::$REQUEST_URI_FROM_BLOG,$path);
	// 	if(is_404()) $path = path::join(self::$CSS_DIR_NAME,'page','404');
	// 	$dest = self::_get_page(true,'theme',$path,'active');
	// 	$dest = apply_filters('WP_Path_get_page_css',$dest);
	// 	return $dest;
	// }
	// // 出力
	// public static function page_css($path=''){ echo self::get_page_img($path); }
	// // -------------------------------------------------------------------------
	// // テーマ内のページ用JS
	// // -------------------------------------------------------------------------
	// public static function get_page_js($path=''){
	// 	if(is_front_page() || is_home()) $path = parent::join('toppage',$path);
	// 	$path = path::join(self::$JS_DIR_NAME,'page',self::$REQUEST_URI_FROM_BLOG,$path);
	// 	if(is_404()) $path = path::join(self::$JS_DIR_NAME,'page','404');
	// 	$dest = self::_get_page(true,'theme',$path,'active');
	// 	$dest = apply_filters('WP_Path_get_page_js',$dest);
	// 	return $dest;
	// }
	// // 出力
	// public static function page_js($path=''){ echo self::get_page_img($path); }
	// // -------------------------------------------------------------------------
	// // テーマ内のページ用PHP
	// // -------------------------------------------------------------------------
	// public static function get_page_php($path=''){
	// 	if(is_front_page() || is_home()) $path = parent::join('toppage',$path);
	// 	$path = path::join(self::$PHP_DIR_NAME,'page',self::$REQUEST_URI_FROM_BLOG,$path);
	// 	if(is_404()) $path = path::join(self::$PHP_DIR_NAME,'page','404');
	// 	$dest = self::_get_page(false,'theme',$path,'active');
	// 	$dest = apply_filters('WP_Path_get_page_php',$dest);
	// 	return $dest;
	// }
	// // 出力
	// public static function page_php($path=''){ echo self::get_page_img($path); }
	// // -------------------------------------------------------------------------
	// // ユーザーディレクトリ内のページ用画像
	// // -------------------------------------------------------------------------
	// public static function get_user_page_img($path=''){
	// 	if(is_front_page() || is_home()) $path = parent::join('toppage',$path);
	// 	$path = parent::join(self::$IMAGE_DIR_NAME,'page',self::$REQUEST_URI_FROM_BLOG,$path);
	// 	if(is_404()) $path = path::join(self::$IMAGE_DIR_NAME,'page','404');
	// 	$dest = self::_get_page(true,'user',$path,'active');
	// 	$dest = apply_filters('WP_Path_get_user_page_img',$dest);
	// 	return $dest;
	// }
	// // 出力
	// public static function user_page_img($path=''){ echo self::get_user_page_img($path); }
	// // -------------------------------------------------------------------------
	// // ユーザーディレクトリ内のページ用CSS
	// // -------------------------------------------------------------------------
	// public static function get_user_page_css($path=''){
	// 	if(is_front_page() || is_home()) $path = parent::join('toppage',$path);
	// 	$path = parent::join(self::$CSS_DIR_NAME,'page',self::$REQUEST_URI_FROM_BLOG,$path);
	// 	if(is_404()) $path = path::join(self::$CSS_DIR_NAME,'page','404');
	// 	$dest = self::_get_page(true,'user',$path,'active');
	// 	$dest = apply_filters('WP_Path_get_user_page_css',$dest);
	// 	return $dest;
	// }
	// // 出力
	// public static function user_page_css($path=''){ echo self::get_user_page_img($path); }
	// // -------------------------------------------------------------------------
	// // ユーザーディレクトリ内のページ用JS
	// // -------------------------------------------------------------------------
	// public static function get_user_page_js($path=''){
	// 	if(is_front_page() || is_home()) $path = parent::join('toppage',$path);
	// 	$path = parent::join(self::$JS_DIR_NAME,'page',self::$REQUEST_URI_FROM_BLOG,$path);
	// 	if(is_404()) $path = path::join(self::$JS_DIR_NAME,'page','404');
	// 	$dest = self::_get_page(true,'user',$path,'active');
	// 	$dest = apply_filters('WP_Path_get_user_page_js',$dest);
	// 	return $dest;
	// }
	// // 出力
	// public static function user_page_js($path=''){ echo self::get_user_page_img($path); }
	// // -------------------------------------------------------------------------
	// // ユーザーディレクトリ内のページ用php
	// // -------------------------------------------------------------------------
	// public static function get_user_page_php($path=''){
	// 	if(is_front_page() || is_home()) $path = parent::join('toppage',$path);
	// 	$path = parent::join(self::$PHP_DIR_NAME,'page',self::$REQUEST_URI_FROM_BLOG,$path);
	// 	if(is_404()) $path = path::join(self::$PHP_DIR_NAME,'page','404');
	// 	$dest = self::_get_page(false,'user',$path,'active');
	// 	$dest = apply_filters('WP_Path_get_user_page_php',$dest);
	// 	return $dest;
	// }
	// // 出力
	// public static function user_page_php($path=''){ echo self::get_user_page_img($path); }
	//
	//
	// // =========================================================================
	// // テーマディレクトリを返す
	// // =========================================================================
	// public static function get_theme_dir($uri=false,$blog='active'){
	// 	if($blog == 'active'){
	// 		$blog = get_stylesheet_directory_uri();
	// 		$blog = parent::basename($blog);
	// 	}
	// 	if($uri) return parent::join(self::$THEME_ROOT_URI,$blog);
	// 	else return parent::join(self::$THEME_ROOT,$blog);
	// }
	// // =========================================================================
	// // ユーザーディレクトリを返す
	// // =========================================================================
	// public static function get_user_dir($uri=false){
	// 	if($uri) return self::$USER_EXT_URI;
	// 	else return self::$USER_EXT_PATH;
	// }
	// // =========================================================================
	// // URLを返す
	// // =========================================================================
	// public static function get_url($blog='active'){
	// 	if($blog == 'active') return get_bloginfo('url');
	// 	else return self::$blogs->get_blog_data($blog)['url'];
	// }
	// // -------------------------------------------------------------------------
	// // URLを出力
	// // -------------------------------------------------------------------------
	// public static function url($blog='active'){
	// 	echo self::get_url($blog);
	// }
	// // -------------------------------------------------------------------------
	// // ルートURLを返す
	// // -------------------------------------------------------------------------
	// public static function get_root_url(){
	// 	return self::get_url('root');
	// }
	// // -------------------------------------------------------------------------
	// // ルートURLを出力
	// // -------------------------------------------------------------------------
	// public static function root_url(){
	// 	echo self::get_root_url();
	// }
	// // =========================================================================
	// // 呼ばれた箇所からのURLを返す
	// // =========================================================================
	// public static function get_url_from_page($path=''){
	// 	return parent::join(self::$REQUEST_URI,$path);
	// }
	// // =========================================================================
	// // ルートURLを出力
	// // =========================================================================
	// public static function url_from_page($path=''){
	// 	echo self::get_url_from_page($path);
	// }
}


// global $wpda_path;
// $wpda_src = WP_Path::src;
// $wpda_src = WP_Path::get_src;

// namespace \;

function wpda_src( $param ) {
	WP_Path::src( $param );
}
function wpda_get_src( $param ) {
	return WP_Path::get_src( $param );
}
// =============================================================================
// エイリアス用関数
// =============================================================================
// function path__src           ( $path='', $blog='active' )   { WP_Path::src           ( $path, $blog ); }
// function path__user_src      ( $path='', $blog='active' )   { WP_Path::user_src      ( $path, $blo );  }
// function path__url           ( $blog='active' )             { WP_Path::url           ( $blog );        }
// function path__root_url      ()                             { WP_Path::root_url      ();               }
// function path__page_img      ( $path='' )                   { WP_Path::page_img      ( $path );        }
// function path__user_page_img ( $path='' )                   { WP_Path::user_page_img ( $path );        }
// function path__page_css      ( $path='' )                   { WP_Path::page_css      ( $path );        }
// function path__user_page_css ( $path='' )                   { WP_Path::user_page_css ( $path );        }
// function path__page_js       ( $path='' )                   { WP_Path::page_js       ( $path );        }
// function path__user_page_js  ( $path='' )                   { WP_Path::user_page_js  ( $path );        }
// function path__page_php      ( $path='' )                   { WP_Path::page_php      ( $path );        }
// function path__user_page_php ( $path='' )                   { WP_Path::user_page_php ( $path );        }
// function path__url_from_page ( $path='' )                   { WP_Path::url_from_page ( $path );        }
//
// function path__get_src           ( $path='', $blog='active' )   { return WP_Path::get_src       ( $path,$blog );  }
// function path__get_user_src      ( $path='', $blog='active' )   { return WP_Path::get_user_src  ( $path, $blog ); }
// function path__get_url           ( $blog='active' )             { return WP_Path::get_url       ( $blog );        }
// function path__get_root_url      ()                             { return WP_Path::get_root_url  ();               }
// function path__get_theme_dir     ( $uri=false, $blog='active' ) { return WP_Path::get_theme_dir ( $uri, $blog );  }
// function path__get_user_dir      ( $uri=false )                 { return WP_Path::get_user_dir  ( $uri );         }
// function page__get_page_img      ( $path='' )                   { return WP_Path::get_page_img      ( $path );    }
// function page__get_user_page_img ( $path='' )                   { return WP_Path::get_user_page_img ( $path );    }
// function page__get_page_css      ( $path='' )                   { return WP_Path::get_page_css      ( $path );    }
// function page__get_user_page_css ( $path='' )                   { return WP_Path::get_user_page_css ( $path );    }
// function page__get_page_js       ( $path='' )                   { return WP_Path::get_page_js       ( $path );    }
// function page__get_user_page_js  ( $path='' )                   { return WP_Path::get_user_page_js  ( $path );    }
// function page__get_page_php      ( $path='' )                   { return WP_Path::get_page_php      ( $path );    }
// function page__get_user_page_php ( $path='' )                   { return WP_Path::get_user_page_php ( $path );    }
// function path__get_url_from_page ( $path='' )                   { return WP_Path::get_url_from_page ( $path );    }
