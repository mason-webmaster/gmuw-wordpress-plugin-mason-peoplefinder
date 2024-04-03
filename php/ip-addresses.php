<?php

/**
 * Summary: php file which contains ip-address-related functions
 */


function gmuw_pf_ip_in_mason_range($ip_address) {

	// get the numeric reprisentation of the Mason min and max IP addresses with IP2long
	$min    = ip2long('129.174.0.0');
	$max    = ip2long('129.174.255.255');
	$needle = ip2long($ip_address);

	// check whether the ip address falls between the lower and upper ranges
	return (($needle >= $min) AND ($needle <= $max));

}
