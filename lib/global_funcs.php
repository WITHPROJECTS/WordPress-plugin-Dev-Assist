<?php

use dev_assist\WP_Path as Path;

function wpda_get_src( $param ) {
	return Path::get_src( $param );
}

function wpda_src( $param ) {
	Path::src( $param );
}
