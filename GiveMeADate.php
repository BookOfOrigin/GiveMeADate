<?php
/*
* Source: https://github.com/BookOfOrigin/GiveMeADate
*/

class GiveMeADate extends \DateTime {
	public function __construct($string = 'now'){
		parent::__construct();
		if($string !== 'now'){
			$this->ConvertString($string);
		}
	}
	
	public function ConvertString($string){
		$return = array();
		
		// Match Y-m-d
		if(preg_match('/(\d{4}-\d{2}-\d{2})/', $string, $matches) === 1){
			if(empty($return) && isset($matches[1])){
				$exploded = explode('-', $matches[1]);
				$return['year'] = $exploded[0];
				$return['month'] = $exploded[1];
				$return['day'] = $exploded[2];
			}
		}
		
		// Match dates: 01/01/2012 or 30-12-11 or 1 2 1985
		if(preg_match('/([0-9]?[0-9])[\.\-\/ ]+([0-1]?[0-9])[\.\-\/ ]+([0-9]{2,4})/', $string, $matches) === 1){
			if(!isset($return['day']) && isset($matches[1])){
				$return['day'] = $matches[1];
			}
			
			if(!isset($return['month']) &&isset($matches[2])){
				$return['month'] = $matches[2];
			}
			
			if(!isset($return['year']) &&isset($matches[3])){
				$return['year'] = $matches[3];
			}
		}
		
		// Attempt to make this at least some what ledgible.
		// Match dates: Sunday 1st March 2015; Sunday, 1 March 2015; Sun 1 Mar 2015; Sun-1-March-2015
		$pattern = '(?:(?:'.implode('|', array_merge($this->days, $this->short_days)).')';
		$pattern .= '[ ,\-_\/]*)?([0-9]?[0-9])[ ,\-_\/]*(?:'.implode('|', $this->number_extensions) . ')';
		$pattern .= '?[ ,\-_\/]*('.implode('|', array_merge($this->months, $this->short_months)).')[ ,\-_\/]+([0-9]{4})';
		if(preg_match('/'.$pattern.'/i', $string, $matches ) === 1){
			if(!isset($return['day']) && isset($matches[1])){
				$return['day'] = $matches[1];
			}
			
			if(!isset($return['month']) && isset($matches[2])){
				if(isset($this->short_months[$matches[2]])){
					$return['month'] = $this->short_months[$matches[2]];
				} elseif(isset($this->months[$matches[2]])){
					$return['month'] = $this->months[$matches[2]];
				}
			}
			
			if(!isset($return['year']) && isset($matches[3])) {
				$return['year'] = $matches[3];
			}
		}
		
		// Match dates: March 1st 2015; March 1 2015; March-1st-2015
		$pattern = '('.implode('|', array_merge($this->months, $this->short_months)) . ')';
		$pattern .= '[ ,\-_\/]*([0-9]?[0-9])[ ,\-_\/]*(?:'.implode('|', $this->number_extensions).')?[ ,\-_\/]+([0-9]{4})';
		if(preg_match('/'.$pattern.'/i', $string, $matches ) === 1){
			if(!isset($return['month']) && isset($matches[1])){
				if(isset($this->short_months[$matches[1]])){
					$return['month'] = $this->short_months[$matches[1]];
				} elseif(isset($this->months[$matches[1]])){
					$return['month'] = $this->months[$matches[1]];
				}
			}
			
			if(!isset($return['day']) && isset($matches[2])){
				$return['day'] = $matches[2];
			}
			
			if(!isset($return['year']) && isset($matches[3])) {
				$return['year'] = $matches[3];
			}
		}
		
		// We've tried a bunch of patterns, but nothing has quite worked yet. So we'll try piece mailing it.
		
		// Match the month.
		if(!isset($return['month'])){
			if(preg_match('/('.implode( '|', array_merge($this->months, $this->short_months)).')/i', $string, $month) === 1){
				if(isset($month[1]) && isset($this->months[$month[1]])){
					$return['month'] = $this->months[$month[1]];
				}
				
				if(isset($month[1]) && isset($this->short_months[$month[1]])){
					$return['month'] = $this->$short_months[$month[1]];
				}
			}
		}
		
		// Match 5tth, 1st, etc...
		if(!isset($return['day'])){
			if(preg_match('/([0-9]?[0-9])('.implode('|', $this->number_extensions).')/', $string, $day) === 1){
				if(isset($day[1])){
					$return['day'] = $day[1];
				}
			}
		}
		
		// Find the year if we don't have one yet.
		if(!isset($return['year'])){
			if(preg_match('/[0-9]{4}/', $string, $year) === 1){
				if(isset($year[1])){
					$year = $year[1];
				}
			}
		}
		
		// If the day and month aren't empty try for a two digit year which can not match the day. (we're getting into some shakey ground here).
		if(isset($return['day']) && isset($return['month']) && !isset($return['year'])){
			if(preg_match( '/[0-9]{2}/', $string, $year ) === 1){
				if(isset($year[1]) && $return['day'] !== $year[1]){
					$return['year'] = $year[1];
				}
			}
		}
		
		// Day prepend a leading zero if single digit.
		if(isset($return['day']) && strlen($return['day']) === 1){
			$return['day'] = '0'.$return['day'];
		}
		
		// Month prepend a leading zero if single digit.
		if(isset($return['month']) && strlen($return['month']) === 1){
			$return['month'] = '0'.$return['month'];
		}
		
		// Two digit year fix.
		if(isset($return['year']) && strlen($return['year']) === 2){
			if($return['year'] < date('y')){
				$return['year'] = '19'.$return['year'];
			} else {
				$return['year'] = '20'.$return['year'];
			}
		}
		
		if(count($return) === 3) {
			$this->createFromFormat('Y-m-d', $return['year'].'-'.$return['month'].'-'.$return['day']);
		}
	}
	
	// Months in a year.
	private $months = array(
		'january' => '1',
		'february' => '2',
		'march' => '3',
    'april' => '4',
    'may' => '5',
    'june' => '6',
    'july' => '7',
    'august' => '8',
    'september' => '9',
    'october' => '10',
    'november' => '11',
    'december' => '12'
	);
	
	// Days in a week.
	private $days = array(
		'sunday',
		'monday',
    'tuesday',
    'wednesday',
    'thursday',
		'friday',
		'saturday'
	);
	
	// Common short months in a year.
	private $short_months = array(
		'jan' => '1',
    'feb' => '2',
    'mar' => '3',
    'apr' => '4',
    'may' => '5',
    'jun' => '6',
    'jul' => '7',
    'aug' => '8',
    'sept' => '9',
		'sep' => '9',
    'oct' => '10',
    'nov' => '11',
    'dec' => '12'
	);
	
	// Common short days in a week.
	private $short_days = array(
		"mon",
    "tues",
		"tue",
    "wed",
    "thur",
		"thu",
    "fri",
    "sat",
    "sun"
	);
	
	// Numbers ending with an extension of some kind. (1st, 3rd...)
	private $number_extensions = array(
		'st',
		'nd',
		'rd',
		'th'
	);
}