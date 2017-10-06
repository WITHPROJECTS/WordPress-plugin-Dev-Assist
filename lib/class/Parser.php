<?php

namespace dev_assist;

class Parser {
	/**
	 * 
	 * parse DB data to HTML
	 * 
	 * @return [*]
	 */
	public static function data2html( $key, $data = null ) {
		if ( is_array($key) && $data === null ) {
			$data = [];
			foreach( $key as $k => $v ) $data[ $k ] = Parser::data2html( $k, $v );
			return $data;
		}

		if ( $key === 'parent_id' ) {
			if ( !preg_match( '/^[0-9]+$/s', $data ) || (int) $data <= 0 ) {
				$data = '';
			}
		}
		elseif ( $key === 'media_query' ) {
			$data = esc_textarea( $data );
		}
		else {
			$data = sanitize_text_field( $data );
		}
		return $data;
	}
	/**
	 *
	 * parse form data to php data
	 * 
	 */
	public static function form2data( $key, $data = null ) {
		if ( is_array($key) && $data === null ) {
			$data = [];
			foreach( $key as $k => $v ) $data[ $k ] = Parser::form2data( $k, $v );
			return $data;
		}

		if ( $key === 'parent_id' ) {
			if ( !preg_match( '/^[0-9]+$/s', $data ) || (int) $data <= 0 ) {
				$data = 0;
			}
			$data = (int) $data;
		}
		return $data;
	}
}
