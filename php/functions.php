<?php

/**
 * Summary: php file which contains general functions
 */


function gmuw_pf_format_phone_number($phone_number) {

	// initialize return value
	$return_value='';

	// if phone number is in the right format...
	if (preg_match('/^[0-9]{10}$/', $phone_number)) {
		//format it
		$return_value=substr($phone_number,0,3).'-'.substr($phone_number,3,3).'-'.substr($phone_number,6,4);
	} else {
		$return_value=$phone_number;
	}

	// return value
	return $return_value;

}
