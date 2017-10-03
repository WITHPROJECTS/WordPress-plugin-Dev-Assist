<?php

// -----------------------------------------------------------------------------
// カテゴリー並び順調整
//
add_action(
		'wp_terms_checklist_args',
		function($args,$post_id = null){
				$args['checked_ontop'] = false;
				return $args;
		}
);
