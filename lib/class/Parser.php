<?php

namespace dev_assist;

class Parser {
	/**
	 * 
	 * parse DB data to HTML
	 * 
	 * @return [*]
	 */
	public static function to_html( $key, $data = null ) {
		if ( is_array($key) && $data === null ) {
			$data = [];
			foreach( $key as $k => $v ) $data[ $k ] = Parser::to_html( $k, $v );
			return $data;
		}
		
		return $data;
	}
	/**
	 *
	 * parse form data to php data
	 * 
	 */
	public static function to_data( $key, $data = null ) {
		return $data;
	}
}
