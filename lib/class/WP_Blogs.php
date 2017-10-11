<?php
namespace dev_assist;

trait WP_Blogs_trait {
	private static $instance = null;
	/**
	 *
	 * インスタンスの取得
	 *
	 */
	public static function get_instance( $root_id ) {
		if ( !self::$instance ) self::$instance = new WP_Blogs( $root_id );
		return self::$instance;
	}
}

class WP_Blogs {
	use WP_Blogs_trait;
	private static $multisite = null;
	private static $blogs     = null;
	/**
	 *
	 * コンストラクタ
	 *
	 */
	public function __construct( $root_id ) {
		global $wpdb;
		self::$multisite = defined( 'MULTISITE' ) ? MULTISITE : false;

		// ブログデータの設定
		$blogs_data = [];

		// マルチサイト
		if ( self::$multisite ) {
			$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->base_prefix}blogs ORDER BY blog_id" );
			// array_unshift($blogs,$blogs[0]);
			$i = 0; $l = count( $blogs );
			for(; $i<$l; $i++){
				$blog_id = (int) $blogs[$i]->blog_id;
				switch_to_blog( $blog_id );
				$theme_name = basename( get_stylesheet_directory_uri() );
				// $theme_name = $i == 0 ? 'root' : basename(get_stylesheet_directory_uri());
				$blogs_data[ $theme_name ] = [
					'blog_id'    => $blog_id,
					'theme_name' => basename( get_stylesheet_directory_uri() ),
					'url'        => get_blog_option( $blog_id, 'home' )
				];
				restore_current_blog();
			}
			// ルートの設定
			switch_to_blog( $root_id );
			$blogs_data['root'] = [
				'blog_id'    => $root_id,
				'theme_name' => basename( get_stylesheet_directory_uri() ),
				'url'        => get_blog_option( $root_id, 'home' )
			];
			restore_current_blog();
		}
		// シングルサイト
		else {
			$blogs_data['root'] = [
				'blog_id'    => get_current_blog_id(),
				'theme_name' => basename( get_stylesheet_directory_uri() ),
				'url'        => get_bloginfo( 'url' )
			];
		}
		$blogs_data['active'] = [
			'blog_id'    => get_current_blog_id(),
			'theme_name' => basename( get_stylesheet_directory_uri() ),
			'url'        => get_bloginfo( 'url' )
		];
		define( 'ROOT_BLOG_THEME', $blogs_data['root']['theme_name'] );
		self::$blogs = $blogs_data;
	}
	/**
	 *
	 * マルチサイトの場合trueを返す
	 *
	 * @access  public
	 * @version 0.0.1
	 * @todo
	 *
	 * @return boolean
	 *
	*/
	public function is_multisite() {
		return self::$multisite;
	}
	/**
	 *
	 * ブログ情報を返す
	 *
	 * @access public
	 * @version 0.0.1
	 * @todo
	 *
	 * @param  boolean $blog_name
	 * @return
	 *
	*/
	public function get_blog_data( $blog_name = false ) {
		// マルチサイト
		if ( self::is_multisite() ) {
			if ( !$blog_name ) $blog_name = basename( get_stylesheet_directory_uri() );
			return self::$blogs[$blog_name];
		}
		// シングルサイト
		else {
			return self::$blogs['root'];
		}
	}
	/**
	 *
	 * ルートブログ情報を返す
	 *
	 * @access  public
	 * @version 0.0.1
	 *
	 * @return mixed[] {
	 *   @var int    'blog_id'    ブログID
	 *   @var string 'theme_name' テーマ名
	 *   @var string 'url'        テーマURI
	 * }
	*/
	public function get_root_blog_data() {
		return self::get_blog_data( 'root' );
	}
}
