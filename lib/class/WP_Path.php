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

	public static function prop( $name ) {
		return self::${$name};
	}
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
		if( !empty( self::$QUERY_STRING ) ) $request_uri = str_replace( '?'.self::$QUERY_STRING, '', $request_uri );

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
}
