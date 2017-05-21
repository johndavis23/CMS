<?php
namespace App\Util;

include_once "Config/config.php";


class UrlUtils
{
	function __construct()
	{
	}
	
	static function getControllerUrl($controller)
	{
		global $APP_URL;
		return $APP_URL.$controller."/";
	}
	static function getAssetsUrl() {
        global $APP_URL;
        return str_replace('index.php', 'assets',$APP_URL);
    }
}
