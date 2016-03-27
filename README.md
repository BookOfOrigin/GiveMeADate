# Give Me A Date
Description: Takes any string and attempts to find a date in that string regardless of format.

GiveMeADate extends PHP's DateTime object. So it has all of the same functions and features you need.

Examples:

$date = new GiveMeADate('my birthday is October 14, 1992.');
$date = new GiveMeADate('some text October 5th 86 some text');
$date = new GiveMeADate('2011-01-11');

You can use this to do some awesome stuff. Example:

if((new GiveMeADate('my birthday is November 11, 2001')) < ((new DateTime())->modify('-21 years'))){
	// Person is unable to drink.
} else {
	/// Person is able to drink.
}

Notes: As with anything please use this responsibly. This class is not fool proof. The universe can always make a better idiot... yada... yada...

License: WTFPL

Like it? Buy me a beer, paypal: admin[at]feathur.com
