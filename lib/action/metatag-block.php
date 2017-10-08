<?php

namespace dev_assist;

remove_action( 'wp_head', 'wp_generator' );                           // meta generator
remove_action( 'wp_head', 'wlwmanifest_link' );                       // Windows Live Writer
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 ); // rel=next | rel=prev 別途代替え
remove_action( 'wp_head', 'wp_shortlink_wp_head' );                   // 短いURL
remove_action( 'wp_head', 'rsd_link' );                               // RSD EditURI

// 絵文字ブロック
if ( $opt['emoji_block'] ) {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'emoji_svg_url', '__return_false' );
};
// eEmbedブロック
if ( $opt['oembed_block'] ) {
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	remove_action( 'template_redirect', 'rest_output_link_header', 11 );
}
// Feedブロック
if( $opt['feed_block'] ) {
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
}
// カノニカルタグブロック
if ( $opt['canonical_block'] ) {
	remove_action( 'wp_head', 'rel_canonical' ); // URL正規化 別途代替え
}
