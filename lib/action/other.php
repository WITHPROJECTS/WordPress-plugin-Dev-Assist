<?php

namespace dev_assist;

// =============================================================================
// タイトルタグ出力
//
if( $opt['title_tag'] ) {
	add_theme_support('title-tag');
}

// =============================================================================
// 管理バー非表示
//
if ( !$opt['show_admin_bar'] ) {
	add_filter( 'show_admin_bar', '__return_false' );
}
