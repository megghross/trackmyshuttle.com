<?php
	
class timezone extends core
{
    var $connector;
    var $error;
    
    function __construct() {
       // $this->connector = parent::db();
		$core = new core();
        $this->connector = $core->db();
    }
	
	function timezone_list() {
		static $timezones = null;

		if ($timezones === null) {
			$timezones = [];
			$offsets = [];
			$now = new DateTime();

			foreach (DateTimeZone::listIdentifiers() as $timezone) {
				$now->setTimezone(new DateTimeZone($timezone));
				$offsets[] = $offset = $now->getOffset();
				$timezones[$timezone] = '(' . $this->format_GMT_offset($offset) . ') ' . $this->format_timezone_name($timezone);
			}

			array_multisort($offsets, $timezones);
		}

		return $timezones;
	}

	function format_GMT_offset($offset) {
		$hours = intval($offset / 3600);
		$minutes = abs(intval($offset % 3600 / 60));
		return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');
	}

	function format_timezone_name($name) {
		$name = str_replace('/', ', ', $name);
		$name = str_replace('_', ' ', $name);
		$name = str_replace('St ', 'St. ', $name);
		return $name;
	}
	
}

?>
