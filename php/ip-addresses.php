<?php

/**
 * Summary: php file which contains ip-address-related functions
 */


function gmuw_pf_ip_in_mason_range($ip_address) {

	// get the numeric reprisentation of the Mason min and max IP addresses with IP2long
	$min    = ip2long('129.174.0.0');
	$max    = ip2long('129.174.255.255');

	// check whether the ip address falls between the lower and upper ranges
	return (($ip_address >= $min) AND ($ip_address <= $max));

}
