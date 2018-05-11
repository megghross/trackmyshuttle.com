<?php




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


?>