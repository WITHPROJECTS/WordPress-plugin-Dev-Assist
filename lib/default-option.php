<?php

$default_option = [
	'site_path'     => '',
	'domain'        => '',
	'parent_id'     => 1,
	'delete_option' => false,
	'media_query'   => '',
	'ext_path'             => 'build',
	'user_ext_path'        => 'user-src',
	'img_dir_name'         => 'img',
	'css_dir_name'         => 'css',
	'js_dir_name'          => 'js',
	'php_dir_name'         => 'php',
	'font_dir_name'        => 'font',
	'author_page_redirect' => true,
	'title_tag'            => true,
	'show_admin_bar'       => true,              // 管理バーの表示
	'emoji_block'          => true,              // 絵文字メタタグブロック
	'oembed_block'         => true,              // oEmbedメタタグブロック
	'feed_block'           => true,              // フィードブロック
	'canonical_block'      => true,              // カノニカルタグブロック
	'alert'                => 'prod_env_enable', // アラート出力
	'comment_alert'        => true,              // コメント許可状態時にアラートを出力
	'pinback_alert'        => true,              // ピンバック・トラックバック許可
	'file_permision_alert' => true,              // ファイル権限がおかしいときにアラートを出力
];

$default_option['media_query'] .= "small=>screen and (max-width: 500px)\n";
$default_option['media_query'] .= "large=>screen and (min-width: 501px)";
