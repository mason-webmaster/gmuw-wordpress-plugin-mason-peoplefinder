<?php

/**
 * Summary: php file containing functions related to dates
 */


function gmuw_pf_display_last_modified_date($date_value){

	//set return variable
	$return_value='';

	//if we have a date value
	if (!empty($date_value)) {
		$return_value.=date_format(date_create_from_format('YmdHis', $date_value), 'Y-m-d');
	}

	//return value
	return $return_value;

}