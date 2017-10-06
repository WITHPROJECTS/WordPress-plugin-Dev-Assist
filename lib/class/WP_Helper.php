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

	/**
	 * メディアクエリの設定
	 * @access public
	 * @version 0.0.1
	 * 
	 * @param  string $multisite
	 * @param  string $name
	 * @return void
	 */
	private static $media_queries = [];
	public static function set_media_queries( $multisite, $name ) {
		$db_manager        = new WP_DB_manager( $multisite, $name );
		$row_media_queries = $db_manager->get()['media_query'];
		if ( !$row_media_queries ) {
			self::$media_queries = false;
		} else {
			$_media_queries = explode( "\n", $row_media_queries );
			array_map( function( $val ) {
				$val = explode( '=>', $val );
				if ( count( $val ) === 2 ) {
					$val = array_map( "trim", $val );
					self::$media_queries[ $val[0] ] = $val[1];
				}
			}, $_media_queries );
		}
	}
	/**
	 * wp_enque_styleのラッパー　メディアクエリの設定を簡単にする
	 * @access public
	 * @version 0.0.1
	 * 
	 * @param  string           $handle ハンドル名
	 * @param  string           $src    パス
	 * @param  string[]|boolean $deps   依存ファイル(ハンドル名で指定)
	 * @param  string|boolean   $ver    バージョン
	 * @param  string           $media  メディアクエリ デフォルトはall
	 * @return void
	 */
	public static function enqueue_style( $handle, $src, $deps=false, $ver=false, $media='all' ) {
		if ( !self::$media_queries ) {
			$media = $media ? $media : 'all';
		} else {
			if ( isset( self::$media_queries[ $media ] ) ) {
				$media = self::$media_queries[ $media ];
			} else {
				$media = 'all';
			}
		}
		wp_enqueue_style($handle, $src, $deps, $ver, $media);
	}
}
