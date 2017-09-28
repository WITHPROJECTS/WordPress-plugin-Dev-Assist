<?php
namespace dev_assist;

class UA{
	private static $ua     = null;
	private static $device = [];
	private static $setend = false;
	// =========================================================================
	// 初期設定
	// =========================================================================
	private static function check_setend(){
		if(UA::$setend) return;
		UA::$setend = true;
		UA::$ua     = strtolower($_SERVER['HTTP_USER_AGENT']);
		$ua = UA::$ua;
		if(strpos($ua,'iphone') !== false){
			UA::$device['type'] = 'mobile';
			UA::$device['name'] = 'iPhone';
		}elseif(strpos($ua,'ipod') !== false){
			UA::$device['type'] = 'mobile';
			UA::$device['name'] = 'iPod';
		}elseif((strpos($ua,'android') !== false) && (strpos($ua, 'mobile') !== false)){
			UA::$device['type'] = 'mobile';
			UA::$device['name'] = 'Android mobile';
		}elseif((strpos($ua,'windows') !== false) && (strpos($ua, 'phone') !== false)){
			UA::$device['type'] = 'mobile';
			UA::$device['name'] = 'Window mobile';
		}elseif((strpos($ua,'firefox') !== false) && (strpos($ua, 'mobile') !== false)){
			UA::$device['type'] = 'mobile';
			UA::$device['name'] = 'Firefox mobile';
		}elseif(strpos($ua,'blackberry') !== false){
			UA::$device['type'] = 'mobile';
			UA::$device['name'] = 'blackberry';
		}elseif(strpos($ua,'ipad') !== false){
			UA::$device['type'] = 'tablet';
			UA::$device['name'] = 'iPad';
		}elseif((strpos($ua,'windows') !== false) && (strpos($ua, 'touch') !== false && (strpos($ua, 'tablet pc') == false))){
			UA::$device['type'] = 'tablet';
			UA::$device['name'] = 'Windows tablet';
		}elseif((strpos($ua,'android') !== false) && (strpos($ua, 'mobile') === false)){
			UA::$device['type'] = 'tablet';
			UA::$device['name'] = 'Android tablet';
		}elseif((strpos($ua,'firefox') !== false) && (strpos($ua, 'tablet') !== false)){
			UA::$device['type'] = 'tablet';
			UA::$device['name'] = 'Firefox tablet';
		}elseif((strpos($ua,'kindle') !== false) || (strpos($ua, 'silk') !== false)){
			UA::$device['type'] = 'tablet';
			UA::$device['name'] = 'kindle';
		}elseif((strpos($ua,'playbook') !== false)){
			UA::$device['type'] = 'tablet';
			UA::$device['name'] = 'playbook';
		}else{
			UA::$device['type'] = 'pc';
			UA::$device['name'] = 'unknown';
		}
	}
	// =========================================================================
	// UserAgentを返す
	// =========================================================================
	public static function get_ua(){
		UA::check_setend();
		return UA::$ua;
	}
	// =========================================================================
	// デバイス名を返す
	// =========================================================================
	public static function get_device_name(){
		UA::check_setend();
		return UA::$device['name'];
	}
	// =========================================================================
	// スマホ環境かどうかを返す
	// =========================================================================
	public static function is_smartphone(){
		UA::check_setend();
		return UA::$device['type'] == 'mobile';
	}
	// =========================================================================
	// タブレット環境かどうかを返す
	// =========================================================================
	public static function is_tablet(){
		UA::check_setend();
		return UA::$device['type'] == 'tablet';
	}
	// =========================================================================
	// タブレット環境かどうかを返す
	// =========================================================================
	public static function is_pc(){
		UA::check_setend();
		return UA::$device['type'] == 'pc';
	}
}
