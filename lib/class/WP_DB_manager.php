<?php
namespace dev_assist;

class WP_DB_manager {
	/**
	 *
	 * @param boolean $mutisite
	 * @param string  $name
	 *
	 */
	public function __construct( $multisite, $name ) {
		$this->multisite  = $multisite;
		$this->table_name = $name;
	}
	/**
	 *
	 * データの追加
	 *
	 * @access  public
	 * @version 0.0.1
	 * @todo
	 *
	 * @param  mixed[] $value
	 * @return void
	 */
	public function add( $value ) {
		if( $this->multisite ) {
			add_site_option( $this->table_name, $value );
		} else {
			add_option( $this->table_name, $value, '', 'no' );
		}
	}
	/**
	 *
	 * データの取得
	 *
	 * @access  public
	 * @version 0.0.1
	 * @todo
	 *
	 * @return false | mixed[]
	 */
	public function get() {
		if( $this->multisite ) {
			return get_site_option( $this->table_name );
		} else {
			return get_option( $this->table_name );
		}
	}
	/**
	 *
	 * データのアップデート
	 *
	 * @access  public
	 * @version 0.0.1
	 * @todo
	 *
	 * @param  mixed[] $value
	 * @return void
	 */
	public function update( $value ) {
		if( $this->multisite ) {
			update_site_option( $this->table_name, $value );
		} else {
			update_option( $this->table_name, $value );
		}
	}
	/**
	 *
	 * データの削除
	 *
	 * @access  public
	 * @version 0.0.1
	 * @todo
	 *
	 * @return void
	 */
	public function delete() {
		if( $this->multisite ) {
			return delete_site_option( $this->table_name );
		} else {
			return delete_option( $this->table_name );
		}
	}
}
