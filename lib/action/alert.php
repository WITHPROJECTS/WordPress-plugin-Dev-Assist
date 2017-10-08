<?php

namespace dev_assist;

function display_alert( $msg ) {
}

function alert_init( $opt ) {
	$msg = [];

	// コメント許可状態
	if ( $opt['comment_alert'] ) {
		if( get_option( 'default_comment_status' ) === 'open' ) {
			$msg[] = 'コメントを受け付ける設定になっています。';
		}
	}
	// ピンバック・トラックバック許可
	if ( $opt['pinback_alert'] ) {
		if( get_option( 'default_ping_status ' ) === 'open' ) {
			$msg[] = 'ピンバック・トラックバックを受け付ける設定になっています。';
		}
	}
	// ファイル権限
	if ( $opt['file_permision_alert'] ) {
		$config = wpda_join( ABSPATH, 'wp-config.php');
		if ( is_writable( $config ) ) $msg[] = 'wp-config.phpが書き込み可能な権限になっています。';
	}

	add_action( 'admin_notices', function() use ( $msg ) {
		if ( !empty( $msg ) ) {
			$msg = implode( '<br>', $msg );
			echo '<div class="notice notice-warning">';
				echo '<h2>警告</h2>';
				echo '<p>'.$msg.'</p>';
			echo '</div>';
		}
	}, 10 );
}

if( $opt['alert'] === 'enable' || ( $opt['alert'] === 'prod_env_enable' && $opt['domain'] === $_SERVER['SERVER_NAME'] ) ) alert_init( $opt );
