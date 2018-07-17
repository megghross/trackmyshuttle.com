<?php


use App\Models\GoogleAPIData;
use Carbon\Carbon;

if (!function_exists('textInitials')) {
    /**
     * textInitials
     * @param  string $menuName
     * @return class name
     */
    function isActive($menuName)
    {
        return \Request::route()->getName() == $menuName ? "menu-active" : "";
    }
}

if (!function_exists('textInitials')) {
    /**
     * textInitials
     * @param  string $text
     * @param  integer $length result length
     * @return string
     */
    function textInitials($text, $length = 1)
    {
        $text = (string)$text;
        $length = (int)$length;

        if (mb_strlen($text) < $length || $length < 1) {
            return $text;
        }

        $parts = explode(" ", $text);
        foreach ($parts as &$p) {
            if (trim($p) == "") {
                unset($p);
            }
        }

        if (count($parts) >= $length) {
            $res = "";
            for ($i = 0; $i < $length; $i++) {
                $res .= mb_substr($parts[$i], 0, 1);
            }
        } else {
            if ($length == 1) {
                $res = mb_substr($text, 0, 1);
            } else if ($length == 2) {
                $res = mb_substr($text, 0, 1) . mb_substr($text, -1, 1);
            } else {
                $res = mb_substr($text, 0, $length);
            }
        }
        return $res;
    }
}
if (!function_exists('readableRandomString')) {
    /**
     * Generate human readable random text
     * @param  integer $length length of the returned string
     * @return string          Random string
     */
    function readableRandomString($length = 6)
    {
        $string = '';
        $vowels = array("a", "e", "i", "o", "u");
        $consonants = array(
            'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm',
            'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z'
        );
        // Seed it
        srand((double)microtime() * 1000000);
        $max = $length / 2;
        for ($i = 1; $i <= $max; $i++) {
            $string .= $consonants[rand(0, 19)];
            $string .= $vowels[rand(0, 4)];
        }
        return $string;
    }
}

function GetJson($url)
{
	$data  = GoogleAPIData::where(['url'=> $url])->first();
	if($data==null){
		return GetHttpDataAndSave($url);
	}
	else{
		$updated_at = $data->updated_at;
		$now = \Carbon\Carbon::now();
		$dif=  $now->diff($updated_at);

		if(to_seconds($dif)>600){
			return GetHttpDataAndSave($url);
		}
		return $data->data;
	}
}


function GetHttpDataAndSave($url){
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($ch);
	curl_close($ch);
	$apiData  = GoogleAPIData::where(['url'=> $url])->first();
	if($apiData==null){
		$apiData = new GoogleAPIData();
	}
	else{
		if($data==null || $data==''){
			return $apiData->data;
		}
		$apiData->exists = true;
	}

	$apiData->data = $data;
	$apiData->url = $url;
	$apiData->updated_at = Carbon::now();
	$apiData->save();
	return $data;
}
 function to_seconds($dif)
{
	return ($dif->y * 365 * 24 * 60 * 60) +
		($dif->m * 30 * 24 * 60 * 60) +
		($dif->d * 24 * 60 * 60) +
		($dif->h * 60 * 60) +
		($dif->i * 60) +
		$dif->s;
}
?>