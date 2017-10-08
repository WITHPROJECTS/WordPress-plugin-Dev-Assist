<?php

namespace dev_assist;

// -----------------------------------------------------------------------------
// リダイレクト
//
if( $opt['author_page_redirect'] ) {
	add_action( 'template_redirect', function(){
			if ( is_author() ){
					wp_redirect( home_url() );
					exit;
			}
	});
}
