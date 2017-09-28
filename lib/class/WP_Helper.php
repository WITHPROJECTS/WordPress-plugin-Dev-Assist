<?php
namespace dev_assist;


class WP_Helper{
	// =========================================================================
	// スラッグを渡すとそのスラッグのIDを返す
	// =========================================================================
	public static function slug2id($val){
		$page = get_page_by_path($val);
		return $page ? $page->ID : false;
	}
	// =========================================================================
	// 固定ページの一番上のページ(ルートとなるページ)のオブジェクトを返す
	// 引数にはページオブジェクトか、ページID
	// =========================================================================
	public static function get_root_page($id){
		$page = get_page($id);
		while($page->post_parent) $page = get_page($page->post_parent);
		return $page;
	}
	// =========================================================================
	// 固定ページの一番上のページ(ルートとなるページ)のスラッグを返す
	// 引数にはページオブジェクトか、ページID
	// =========================================================================
	public static function get_root_page_slug($id){
		$page = get_page($id);
		while($page->post_parent) $page = get_page($page->post_parent);
		return $page->post_name;
	}
	// =========================================================================
	// ルートカテゴリを返す
	// 引数にはカテゴリオブジェクトか、カテゴリオブジェクトの配列
	// =========================================================================
	// Array / Object = get_root_category( $cat : Array / Object )
	public static function get_root_category($cat){
		// 配列の場合
		if(is_array($cat)){
			$cnt = count($cat);
			for($i = 0;$i<$cnt;$i++){
				while($cat[$i]->parent) $cat[$i] = get_category($cat[$i]->parent);
			}
		}
		// カテゴリーオブジェクトの場合
		elseif(is_object($cat)){
			while($cat->parent) $cat = get_category($cat->parent);
		}
		return $cat;
	}
	// =========================================================================
	// 引数で指定したページからそのページのルートとなるページまでの情報を配列で返す
	// デフォルトでは最後に配列を反転させるのでルートページ->指定したページの順の配列になる
	// =========================================================================
	public static function get_page_history($id,$reverse=true){
		$setArr = function($child){
			$result_arr = array(
				'id'         => $child->ID,
				'post_name'  => $child->post_name,
				'post_title' => $child->post_title,
				'permalink'  => get_permalink($child->ID)
			);
			return apply_filters('get_page_history_format',$result_arr,$child);
		};
		// ---------------------------------------------------------------------
		$page  = get_page($id);
		$arr   = array($setArr($page));
		while($page->post_parent){
			$page  = get_page($page->post_parent);
			$arr[] = $setArr($page);
		}
		return $reverse ? array_reverse($arr) : $arr;
	}
	// =========================================================================
	// 引数で指定したタームからそのタームのルートとなるタームまでの情報を配列で返す
	// デフォルトでは最後に配列を反転させるのでルートターム->指定したタームの順の配列になる
	// =========================================================================
	public static function get_term_history($tax,$term,$reverse=true){
		$term   = is_int($term) ? get_term($term,$tax) : $term;
		$setArr = function($term,$tax){
			$result_arr = array(
				'id'        => $term->term_id,
				'name'      => $term->name,
				'slug'      => $term->slug,
				'tax'       => $tax,
				'permalink' => get_term_link($term->term_id,$tax)
			);
			return apply_filters('get_term_history_format',$result_arr,$term);
		};
		$arr = array($setArr($term,$tax));
		while($term->parent){
			$term  = get_term($term->parent,$tax);
			$arr[] = $setArr($term,$tax);
		}
		return $reverse ? array_reverse($arr) : $arr;
	}
	// =========================================================================
	// 引数で指定したカテゴリーからそのカテゴリーのルートとなるカテゴリーまでの情報を配列で返す
	// デフォルトでは最後に配列を反転させるのでルートカテゴリー->指定したカテゴリーの順の配列になる
	// =========================================================================
	public static function get_category_history($cat,$reverse=true){
		$cat   = !is_object($cat) ? get_term((int) $cat,'category') : $cat;
		return Helper::get_term_history('category',$cat,$reverse);
		//$setArr = function($cat){
		//	$result_arr = array(
		//		'id'        => $cat->term_id,
		//		'name'      => $cat->name,
		//		'slug'      => $cat->slug,
		//		'permalink' => get_category_link($cat->term_id)
		//	);
		//	return apply_filters('get_category_history_format',$result_arr,$cat);
		//};
		//// ---------------------------------------------------------------------
		//$cat = get_category($id);
		//$arr = array($setArr($cat));
		//while($cat->parent){
		//	$cat   = get_category($cat->parent);
		//	$arr[] = $setArr($cat);
		//}
		//return $reverse ? array_reverse($arr) : $arr;
	}
	// =========================================================================
	// ポストタイプの判定
	// =========================================================================
	// String = is_post_type( $name : String )
	public static function is_post_type($name){
		if(get_post_type() == $name) return true;
		return false;
	}
	// =========================================================================
	// シングルページのポストタイプの判定
	// =========================================================================
	// String = is_single_type( $name : String )
	public static function is_single_type($name){
		if(is_single() && Helper::is_post_type($name)) return true;
		return false;
	}
	// =========================================================================
	// アーカイブページのポストタイプの判定
	// =========================================================================
	// String = is_archive_type( $name : String )
	public static function is_archive_type($name){
		if(is_archive() && Helper::is_post_type($name)) return true;
		return false;
	}
	// =========================================================================
	// 指定された親カテゴリに属するか否か
	// 引数には親となるカテゴリのオブジェクトかカテゴリーID、スラッグを指定する
	// =========================================================================
	// Boolean = is_parent_cateogory($parent : Object | Int | String)
	public static function is_parent_cateogory($parent){
		if(is_string($parent)){
			$parent = get_category_by_slug($parent);
		}elseif(is_int($parent)){
			$parent = get_category($parent);
		}

		if(is_object($parent)){
			$parnet    = $parent->term_id;
			$post_cats = get_the_category();
			foreach($post_cats as $post) if( cat_is_ancestor_of( $parent, $post->term_id ) ){ return true; }
		}
		return false;
	}
	// =========================================================================
	// 指定された固定ページの親子関係か否か
	// 引数には親となる固定ページのURIをルートから指定する
	// 例えば、/sample/child/child-in-child で使用した場合
	// Helper::in_page('sample')       // -> true
	// Helper::in_page('sample/child') // -> true
	// Helper::in_page('child')        // -> false
	// =========================================================================
	// Boolean = in_page($val : String)
	public static function in_page($val = ''){
		if(!is_page()) return false;
		global $post;
		$val     = preg_replace('/\/$/', '', $val);
		$val     = preg_replace('/^\//', '', $val);
		$valArr  = preg_split("/\//", $val);
		$slugArr = array();
		$not     = false;
		$page    = get_page($post->ID);
		while($page->post_parent){
			$page      = get_page($page->post_parent);
			$slugArr[] = $page->post_name;
		}
		$slugArr = array_reverse($slugArr);
		for($i=0,$l=count($valArr); $i<$l ;$i++){
			if(!isset($slugArr[$i]) || $slugArr[$i] != $valArr[$i]) $not = true;
		}
		if($not){
			return false;
		}else{
			return true;
		}
	}
	// =========================================================================
	// 子ページか否か
	// =========================================================================
	// Boolean = in_subpage()
	public static function is_subpage(){
		global $post;
		if(is_page() && $post->post_parent){
			$parentID   = $post->post_parent;
			$parentSlug = get_page_uri($parentID);
			return true;
		}else{
			return false;
		}
	}

	// =========================================================================
	// 子ページを持っているか否か
	// =========================================================================
	public static function has_subpage($other=false,$post=false){
		if(!$post) global $post;
		$temp     = $post;
		$post     = $other ? $other : $post;
		$children = get_pages('child_of='.$post->ID);
		$post     = $temp;
		if(count($children) != 0){
			return true;
		}else{
			return false;
		}
	}
	// =========================================================================
	// wp_enqueue_styleのヘルパー
	// =========================================================================
	private static $break_points = false;
	public static function set_break_point($arr=false){
		self::$break_points = $arr;
	}
	public static function custom_enqueue_style($handle=false,$src=false,$deps=[],$ver=false,$media=false,$size=false){
		if(self::$break_points && $size && isset(self::$break_points[$size])){
			$media = self::$break_points[$size];
		}else{
			$media = 'all';
		}
		wp_enqueue_style($handle,$src,$deps,$ver,$media);
	}
	// =========================================================================
	// metaデータのセット
	// =========================================================================
	private static function set_meta_data(){
		global $meta_data;
		global $post;
		$meta_data['setup'] = true;
	}
	public static function get_meta_keywords(){
		global $meta_data;
		if(!isset($meta_data['setup'])) Helper::set_meta_data();
		return $meta_data['keyworkds'];
	}
	public static function get_meta_description(){
		global $meta_data;
		if(!isset($meta_data['setup'])) Helper::set_meta_data();
		return $meta_data['description'];
	}
	// =========================================================================
	// 空ページのリダイレクト
	// =========================================================================
	public static function when_blank_page_redirect(){
		global $post;
		if(is_page() && get_field('empty',$post->ID)){
			$url = Helper::get_root_url();
			header("Location: {$url}");
			exit;
		}
	}
	public static function redirect_to_top(){
		$url = Helper::get_root_url();
		header("Location: {$url}");
	}
	public static function redirect_to($url){
		header("Location: {$url}");
	}
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
