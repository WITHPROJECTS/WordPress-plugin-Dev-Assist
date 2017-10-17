<?php
/**
 * URLを出力する
 * @version 0.0.1
 * 
 * @var    string $blog
 * @var    string $path
 * @return string
 */
add_shortcode( 'url', function( $arg ){
	extract( shortcode_atts([
		'blog' => 'active',
		'path' => ''
	],$arg));
	return wpda_join( wpda_get_blog_url( $blog ), $path );
} );
/**
 * ソースパスかurlを出力する
 * @version 0.0.1
 * 
 * @var boolean $url
 * @var string  $blog
 * @var string  $from
 * @var string  $dir_name
 * @var string  $file
 * @return string
 */
add_shortcode( 'src', function( $arg ) {
	extract( shortcode_atts([
		'url'      => true,
		'blog'     => 'active',
		'from'     => 'theme',
		'dir_name' => '',
		'file'     => ''
	], $arg ) );
	$url = is_bool( $url ) ? $rul : $url === 'true' ? true : false;
	$base_path = wpda_get_src([
		'uri'   => $url,
		'where' => $from,
		'blog'  => $blog,
		'path'  => $dir_name
	]);
	return wpda_join( $base_path, $file, [ 'last_slash' => false ] );
} );
/**
 * imgタグを出力する
 * @version 0.0.1
 * 
 * @var    string $blog src|srcset属性で利用
 * @var    string $from src|srcset属性で利用
 * @return string
 */
add_shortcode( 'img', function( $arg ){
	$img_dir      = dev_assist\WP_Path::prop('IMAGE_DIR_NAME');
	$from         = isset( $arg['from'] ) ? $arg['from'] : 'theme';
	$blog         = isset( $arg['blog'] ) ? $arg['blog'] : 'active';
	$base_img_url = wpda_get_src([
		'uri'   => true,
		'where' => $from,
		'blog'  => $blog,
		'path'  => $img_dir
	]);
	$cnt     = '<img';
	foreach ( $arg as $key => $val ) {
		if ( preg_match( '/(blog|from)/', $key ) ) continue;
		
		if( $key === 'src' ) $arg['src'] = wpda_join( $base_img_url, $val );

		if ( $key === 'srcset' ) {
			$val = array_map( 'trim', explode( ',', $val ) );
			for( $i = 0, $l = count($val); $i < $l; $i++ ) $val[ $i ] = wpda_join( $base_img_url, $val[ $i ], [ 'last_slash' => false ] );
			$arg['srcset'] = implode( ',', $val );
		}
		$cnt .= " {$key}=\"{$arg[ $key ]}\"";
	}
	$cnt .= '>';
	return $cnt;
} );
/**
 * 画像urlを出力する
 * @version 0.0.1
 * 
 * @var    string $blog
 * @var    string $from
 * @var    string $file
 * @return string
 */
add_shortcode( 'img-url', function( $arg ) {
	extract( shortcode_atts([
		'blog' => 'active',
		'from' => 'theme',
		'file' => ''
	], $arg ) );
	$img_dir      = dev_assist\WP_Path::prop('IMAGE_DIR_NAME');
	$base_img_url = wpda_get_src([
		'uri'   => true,
		'where' => $from,
		'blog'  => $blog,
		'path'  => $img_dir
	]);
	return wpda_join($base_img_url, $file, [ 'last_slash'=>false ] );
} );
/**
 * ページ用画像を利用したimgタグを出力する
 * @version 0.0.1
 * 
 * @var    string @from
 * @return string
 */
add_shortcode( 'page-img', function( $arg ) {
	$from = isset( $arg['from'] ) ? $arg['from'] : 'theme';
	$cnt  = '<img';
	foreach ( $arg as $key => $val ) {
		if ( $key === 'from' ) continue;
		
		if( $key === 'src' ) $arg['src'] = wpda_get_page_img_url( $from, $val );

		if ( $key === 'srcset' ) {
			$val = array_map( 'trim', explode( ',', $val ) );
			for( $i = 0, $l = count($val); $i < $l; $i++ ) $val[ $i ] = wpda_get_page_img_url( $from, $val[ $i ] );
			$arg['srcset'] = implode( ',', $val );
		}
		$cnt .= " {$key}=\"{$arg[ $key ]}\"";
	}
	$cnt .= '>';
	return $cnt;
} );
/**
 * ページ用画像のurlを出力する
 * @version 0.0.1
 * 
 * @var    string $from
 * @var    string $file
 * @return string
 */
add_shortcode( 'page-img-url', function( $arg ) {
	extract( shortcode_atts([
		'from' => 'theme',
		'file' => ''
	], $arg ) );
	return wpda_get_page_img_url( $from, $file );
} );
/**
 * ページ用PDFファイルのurlを出力する
 * @version 0.3.0
 * 
 * @var    string $from
 * @var    string $file
 * @return string
 */
add_shortcode( 'page-pdf-url', function( $arg ) {	
	extract( shortcode_atts([
		'from'      => 'theme',
		'file'      => ''
	], $arg ) );
	return wpda_get_page( 'pdf', true, $from, $file );
} );
/**
 * ページ用ファイルのurlを出力する
 * @version 0.3.0
 * 
 * @var    string $file_type
 * @var    string $from
 * @var    string $file
 * @return string
 */
add_shortcode( 'page-file-url', function( $arg ) {	
	extract( shortcode_atts([
		'file_type' => '',
		'from'      => 'theme',
		'file'      => ''
	], $arg ) );
	return wpda_get_page( $file_type, true, $from, $file );
} );
/**
 * PHPファイルを読み込む
 * @version 0.0.1 
 *
 * @var string $type "require" or "require_once", "include" or "include_once"
 * @var string $blog
 * @var string $file
 * @var string $from
 * @return string
 * 
 */
add_shortcode( 'php-include', function( $arg ) {
	extract( shortcode_atts([
		'type' => 'require_once',
		'blog' => 'active',
		'file' => '',
		'from' => 'theme'
	], $arg ) );
	
	$base_path = wpda_get_src([
		'uri'   => false,
		'where' => $from,
		'blog'  => $blog,
		'path'  => dev_assist\WP_Path::prop('PHP_DIR_NAME')
	]);
	$file_path = wpda_join( $base_path, $file, [ 'last_slash' => false ] );
	ob_start();
	if ( $type === 'require' ) {
		require( $file_path );
	} elseif ( $type === 'require_once' ) {
		require_once( $file_path );
	} elseif ( $type === 'include' ) {
		include( $file_path );
	} elseif ( $type === 'include_once' ) {
		include_once( $file_path );
	}
	return ob_get_clean();
} );
