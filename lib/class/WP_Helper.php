<?php
namespace dev_assist;

require_once(__dir__.'/WP_DB_manager.php');

class WP_Helper{
	/**
	 * パスを渡すとそのスラッグのIDを返す
	 * @access public
	 * @version 0.0.1
	 * 
	 * @param  string $slug
	 * @param  string $type
	 * @return int|boolean
	 */
	public static function path2id( $path, $type = 'page' ) {
		$page = get_page_by_path( $path, null, $type );
		return $page ? $page->ID : false;
	}
	/**
	 * 引数で指定したページからそのページのルートとなるページまでの情報を配列で返す
	 * デフォルトでは最後に配列を反転させるのでルートページ->指定したページの順の配列になる
	 * @access public
	 * @version 0.0.1
	 * 
	 * @param  int|WP_Post $id
	 * @param  boolean     $reverse 結果を反転させるか
	 * @return array[]|false
	 */
	public static function get_post_history($id, $reverse=true ) {
		$setArr = function( $child ) {
			$result_arr = [
				'id'         => $child->ID,
				'post_name'  => $child->post_name,
				'post_title' => $child->post_title,
				'permalink'  => get_permalink( $child->ID )
			];
			return $result_arr;
		};

		$page  = get_post( $id );

		if(!$page || $page->post_type === 'revision') return false;

		$arr   = [ $setArr( $page ) ];

		while( $page->post_parent ){
			$page  = get_post( $page->post_parent );
			$arr[] = $setArr( $page );
		}
		return $reverse ? array_reverse($arr) : $arr;
	}
	/**
	 * 固定ページや投稿タイプの一番上のページ(ルートとなるページ)のオブジェクトを返す
	 * @access public
	 * @version 0.0.1
	 * 
	 * @param  int|WP_Post $id
	 * @return WP_Post|false
	 */
	public static function get_root_post( $id ) {
		$root = self::get_post_history( $id, true );
		if ( !$root ) return false;
		return get_post($root[0]['id']);
	}
	/**
	 * 引数で指定したタームからそのタームのルートとなるタームまでの情報を配列で返す
	 * デフォルトでは最後に配列を反転させるのでルートターム->指定したタームの順の配列になる
	 * @access public
	 * @version 0.0.1
	 * 
	 * @param  string             $tax
	 * @param  string|int|WP_Term $term
	 * @param  boolean            $reverse 結果を反転させるか
	 * @return array[]|false
	 */
	public static function get_term_history( $tax, $term, $reverse=true ) {
		if ( is_int( $term ) ) {
			$term = get_term_by( 'term_id', $term, $tax );
		}
		elseif ( is_string( $term ) ) {
			$term = get_term_by( 'slug', $term, $tax );
		}

		if( !$term ) return false;

		$setArr = function( $term, $tax ) {
			$result_arr = [
				'id'        => $term->term_id,
				'name'      => $term->name,
				'slug'      => $term->slug,
				'tax'       => $tax,
				'permalink' => get_term_link( $term->term_id, $tax )
			];
			return $result_arr;
		};

		$arr = [ $setArr( $term, $tax ) ];
		while ( $term->parent ) {
			$term  = get_term( $term->parent, $tax );
			$arr[] = $setArr( $term, $tax );
		}
		return $reverse ? array_reverse( $arr ) : $arr;
	}
	/**
	 * タームの一番上の親タームのオブジェクトを返す
	 * @access public
	 * @version 0.0.1
	 * 
	 * @param  string             $tax
	 * @param  string|int|WP_Term $term
	 * @return false
	 */
	public static function get_root_term( $tax, $term ) {
		$root = self::get_term_history( $tax, $term, true );
		if( !$root ) return false;
		return get_term( $root[0]['id'] );
	}
	/**
	 * タームが他のタームの「先祖」タームであるかどうかをチェック
	 * @access public
	 * @version 0.0.1
	 * 
	 * @param WP_Term|int|string $descendant 祖先
	 * @param WP_Term|int|string $ancestor   先祖
	 * @param string             $tax
	 * @return boolean
	 */
	public static function is_tax_ancestor_of( $descendant, $ancestor, $tax ) {
		if( is_int( $descendant ) ) {
			$descendant = get_term_by( 'term_id', $descendant, $tax );
		}
		elseif( is_string( $descendant ) ) {
			$descendant = get_term_by( 'slug', $descendant, $tax );
		}
		if( is_int( $ancestor ) ) {
			$ancestor = get_term_by( 'term_id', $ancestor, $tax );
		}
		elseif( is_string( $ancestor ) ) {
			$ancestor = get_term_by( 'slug', $ancestor, $tax );
		}
		
		if ( !$ancestor || !$descendant ) return false;
		
		$list   = self::get_term_history( $tax, $descendant, true );
		$result = false;
		for ( $i = 0, $l = count( $list ); $i < $l; $i++ ) {
			if ( $list[$i]['id'] === $ancestor->term_id ) {
				$result = true;
				break;
			}
		}
		return $result;
	}
	/**
	 * 投稿が他の投稿の「先祖」投稿であるかどうかをチェック
	 * @access public
	 * @version 0.0.1
	 * 
	 * @param  WP_Post|int|string  $descendant 祖先 stringの場合はパス
	 * @param  WP_Post|int|string  $ancestor   先祖 stringの場合はパス
	 * @param  string              $type       投稿タイプ $descendant,$ancestorにstring型を与えた場合のみ結果に影響
	 * @return boolean
	 */
	public static function is_post_ancestor_of( $descendant, $ancestor, $type = 'page' ) {
		if ( is_int( $descendant ) ) {
			$descendant = get_post( $descendant );
		}
		elseif( is_string( $descendant ) ) {
			$descendant = get_page_by_path( $descendant, null, $type );
		}
		if ( is_int( $ancestor ) ) {
			$ancestor = get_post( $ancestor );
		}
		elseif( is_string( $ancestor ) ) {
			$ancestor = get_page_by_path( $ancestor, null, $type );
		}

		if ( !$ancestor || !$descendant ) return false;

		$list   = self::get_post_history( $descendant, true );
		$result = false;
		for ( $i = 0, $l = count( $list ); $i < $l; $i++ ) {
			if ( $list[$i]['id'] === $ancestor->ID ) {
				$result = true;
				break;
			}
		}
		return $result;
	}
	/**
	 * 子ページかチェック
	 * @access public
	 * @version 0.0.1
	 *
	 * @param  WP_Post|int|string $post
	 * @param  string             $type 投稿タイプ $postにstring型を与えた場合のみ結果に影響
	 * @return boolean
	 */
	public static function is_child_post( $post, $type='page' ) {
		if ( !$post ) global $post;
		if ( is_int( $post ) ){
			$post = get_post( $post );
		}
		elseif ( is_string( $post ) ) {
			$post = get_page_by_path( $post, null, $type );
		}
		return $post->post_parent > 0 ? true : false;
	}
	/**
	 * 子ページを持っているかチェック
	 * @access public
	 * @version 0.0.1
	 * 
	 * @param WP_Post|int|string $post
	 * @param string             $type
	 * @return boolean
	 */
	public static function has_child_page( $post, $type ) {
		global $wpdb;
		if ( !$post ) global $post;
		if ( is_int( $post ) ){
			$post = get_post( $post );
		}
		elseif ( is_string( $post ) ) {
			$post = get_page_by_path( $post, null, $type );
		}
		if( !$post || !isset($post->ID) || !is_int($post->ID) ) return false;
		$query = "SELECT post_parent FROM {$wpdb->posts} WHERE post_parent = {$post->ID} AND post_status = 'publish'";
		return $wpdb->get_var($query) ? true : false;
	}


	private static $media_queries = [];
	public static function get_media_queries( $multisite, $name ) {
		if ( empty( self::$mediaQueries ) ) {
			$db_manager     = new WP_DB_manager( $multisite, $name );
			$row_breakpoint = $db_manager->get()['break_point'];
			if(empty())
		}
		if ( self::$mediaQueries === false ){
			var_dump("ddd");
		}
		
		// var_dump();
		// var_dump($db_manager);
		// $db_manager = new WP_DB_manager(WPDA_MULTISITE);
		// new dev_assist\WP_DB_manager();
		// var_dump();
	}
	
	/**
	 * [enqueue_style description]
	 * @param  string           $handle ハンドル名
	 * @param  string           $src    パス
	 * @param  string[]|boolean $deps   依存ファイル(ハンドル名で指定)
	 * @param  string|boolean   $ver    バージョン
	 * @param  string|boolean   $size   メディアクエリのサイズ
	 * @param  string           $media  対象メディア デフォルトはall
	 */
	public static function enqueue_style( $handle, $src, $deps=false, $ver=false, $size=false, $media='all' ) {
		if ( empty( self::$mediaQueries ) ) {
			self::get_media_queries();
		}
		
	}


	// // =========================================================================
	// // wp_enqueue_styleのヘルパー
	// // =========================================================================
	// private static $break_points = false;
	// public static function set_break_point($arr=false){
	// 	self::$break_points = $arr;
	// }
	// public static function custom_enqueue_style($handle=false,$src=false,$deps=[],$ver=false,$media=false,$size=false){
	// 	if(self::$break_points && $size && isset(self::$break_points[$size])){
	// 		$media = self::$break_points[$size];
	// 	}else{
	// 		$media = 'all';
	// 	}
	// 	wp_enqueue_style($handle,$src,$deps,$ver,$media);
	// }






	// =========================================================================
	//
	// =========================================================================
	// public static function template($file){
	//	global $PART_PATH;
	//	preg_match('/^.\/([a-z]+.php)/',$file,$match);
	//	if($match){
	//		include_once(THEME_ROOT.'/'.THEME_DIR_NAME.'/'.$match[1]);
	//	}else{
	//		foreach($PART_PATH as $key => $val){
	//			if(file_exists($val.$file)){
	//				include_once($val.$file);
	//				break;
	//			}
	//		}
	//	}
	// }
}
