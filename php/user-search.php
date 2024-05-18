<?php

/**
 * Summary: php file which implements user search functionality
 */


function gmuw_pf_user_search_get_users($mode,$search_id){

	switch($mode){
		case 'department':

			//set up user department search
			$args = array(
				'meta_query' => array(
					'relation' => 'OR',
						array(
							'key'     => 'pf_affiliation_approved',
							'value'   => $search_id,
				 			'compare' => '='
						),
						array(
							'key'     => 'pf_affiliation_2_approved',
							'value'   => $search_id,
							'compare' => '='
						),
						array(
							'key'     => 'pf_department_approved',
							'value'   => $search_id,
				 			'compare' => '='
						),
						array(
							'key'     => 'pf_department_2_approved',
							'value'   => $search_id,
							'compare' => '='
						)
				)
			);
			
			break;

		case 'building':

			//set up user building search
			$args = array(
				'meta_query' => array(
					'relation' => 'OR',
						array(
							'key'     => 'pf_building_approved',
							'value'   => $search_id,
				 			'compare' => '='
						),
						array(
							'key'     => 'pf_building_2_approved',
							'value'   => $search_id,
							'compare' => '='
						),
				)
			);

			break;
	}

	//set up user query
	$user_query = new WP_User_Query($args);
	
	//get users
	$myusers = $user_query->get_results();

	//return users
	return $myusers;

}

function gmuw_pf_show_admin_users_search_results($myusers){

	/*
	fields:
	pf_name
	pf_name_approved
	pf_pronouns
	pf_pronouns_approved
	pf_email
	pf_email_approved
	pf_phone
	pf_phone_approved
	pf_fax
	pf_fax_approved
	pf_title
	pf_title_approved
	pf_department
	pf_department_approved
	pf_affiliation
	pf_affiliation_approved
	pf_building
	pf_building_approved
	pf_room
	pf_room_approved
	pf_mailstop
	pf_mailstop_approved
	pf_title_2
	pf_title_2_approved
	pf_department_2
	pf_department_2_approved
	pf_affiliation_2
	pf_affiliation_2_approved
	pf_building_2
	pf_building_2_approved
	pf_room_2
	pf_room_2_approved
	pf_mailstop_2
	pf_mailstop_2_approved
	pf_search_key
	*/

	//initialize return variable
	$return_value='';

	//begin table
	$return_value.='<table>';
	
	//table header
	$return_value.='<thead>';
	$return_value.='<tr>';
	$return_value.='<th>user_id</th>';
	//$return_value.='<th>pf_name</th>';
	$return_value.='<th>pf_name_approved</th>';
	//$return_value.='<th>pf_pronouns</th>';
	$return_value.='<th>pf_pronouns_approved</th>';
	//$return_value.='<th>pf_email</th>';
	$return_value.='<th>pf_email_approved</th>';
	//$return_value.='<th>pf_phone</th>';
	$return_value.='<th>pf_phone_approved</th>';
	//$return_value.='<th>pf_fax</th>';
	$return_value.='<th>pf_fax_approved</th>';
	//$return_value.='<th>pf_title</th>';
	$return_value.='<th>pf_title_approved</th>';
	//$return_value.='<th>pf_department</th>';
	$return_value.='<th>pf_department_approved</th>';
	//$return_value.='<th>pf_affiliation</th>';
	$return_value.='<th>pf_affiliation_approved</th>';
	//$return_value.='<th>pf_building</th>';
	$return_value.='<th>pf_building_approved</th>';
	//$return_value.='<th>pf_room</th>';
	$return_value.='<th>pf_room_approved</th>';
	//$return_value.='<th>pf_mailstop</th>';
	$return_value.='<th>pf_mailstop_approved</th>';
	//$return_value.='<th>pf_title_2</th>';
	$return_value.='<th>pf_title_2_approved</th>';
	//$return_value.='<th>pf_department_2</th>';
	$return_value.='<th>pf_department_2_approved</th>';
	//$return_value.='<th>pf_affiliation_2</th>';
	$return_value.='<th>pf_affiliation_2_approved</th>';
	//$return_value.='<th>pf_building_2</th>';
	$return_value.='<th>pf_building_2_approved</th>';
	//$return_value.='<th>pf_room_2</th>';
	$return_value.='<th>pf_room_2_approved</th>';
	//$return_value.='<th>pf_mailstop_2</th>';
	$return_value.='<th>pf_mailstop_2_approved</th>';
	//$return_value.='<th>pf_search_key</th>';
	$return_value.='</tr>';
	$return_value.='</thead>';

	//table body
	$return_value.='<tbody>';

	//loop through users
	foreach($myusers as $myuser){
		$return_value.='<tr>';
		$return_value.='<td>'.$myuser->ID.'</td>';
		//$return_value.='<td>'.$myuser->pf_name.'</td>';
		$return_value.='<td>'.$myuser->pf_name_approved.'</td>';
		//$return_value.='<td>'.$myuser->pf_pronouns.'</td>';
		$return_value.='<td>'.$myuser->pf_pronouns_approved.'</td>';
		//$return_value.='<td>'.$myuser->pf_email.'</td>';
		$return_value.='<td>'.$myuser->pf_email_approved.'</td>';
		//$return_value.='<td>'.$myuser->pf_phone.'</td>';
		$return_value.='<td>'.$myuser->pf_phone_approved.'</td>';
		//$return_value.='<td>'.$myuser->pf_fax.'</td>';
		$return_value.='<td>'.$myuser->pf_fax_approved.'</td>';
		//$return_value.='<td>'.$myuser->pf_title.'</td>';
		$return_value.='<td>'.$myuser->pf_title_approved.'</td>';
		//$return_value.='<td>'.$myuser->pf_department.'</td>';
		//$return_value.='<td>'.$myuser->pf_department_approved.'</td>';
		$return_value.='<td>';
		if (!empty($myuser->pf_department_approved)) {
			$return_value.=get_term_by('id', $myuser->pf_department_approved, 'department')->name;
		}
		$return_value.='</td>';
		//$return_value.='<td>'.$myuser->pf_affiliation.'</td>';
		//$return_value.='<td>'.$myuser->pf_affiliation_approved.'</td>';
		$return_value.='<td>';
		if (!empty($myuser->pf_affiliation_approved)) {
			$return_value.=get_term_by('id', $myuser->pf_affiliation_approved, 'department')->name;
		}
		$return_value.='</td>';
		//$return_value.='<td>'.$myuser->pf_building.'</td>';
		//$return_value.='<td>'.$myuser->pf_building_approved.'</td>';
		$return_value.='<td>';
		if (!empty($myuser->pf_building_approved)) {
			$return_value.=get_term_by('id', $myuser->pf_building_approved, 'building')->name;
		}
		$return_value.='</td>';
		//$return_value.='<td>'.$myuser->pf_room.'</td>';
		$return_value.='<td>'.$myuser->pf_room_approved.'</td>';
		//$return_value.='<td>'.$myuser->pf_mailstop.'</td>';
		$return_value.='<td>'.$myuser->pf_mailstop_approved.'</td>';
		//$return_value.='<td>'.$myuser->pf_title_2.'</td>';
		$return_value.='<td>'.$myuser->pf_title_2_approved.'</td>';
		//$return_value.='<td>'.$myuser->pf_department_2.'</td>';
		//$return_value.='<td>'.$myuser->pf_department_2_approved.'</td>';
		$return_value.='<td>';
		if (!empty($myuser->pf_department_2_approved)) {
			$return_value.=get_term_by('id', $myuser->pf_department_2_approved, 'department')->name;
		}
		$return_value.='</td>';
		//$return_value.='<td>'.$myuser->pf_affiliation_2.'</td>';
		//$return_value.='<td>'.$myuser->pf_affiliation_2_approved.'</td>';
		$return_value.='<td>';
		if (!empty($myuser->pf_affiliation_2_approved)) {
			$return_value.=get_term_by('id', $myuser->pf_affiliation_2_approved, 'department')->name;
		}
		$return_value.='</td>';
		//$return_value.='<td>'.$myuser->pf_building_2.'</td>';
		//$return_value.='<td>'.$myuser->pf_building_2_approved.'</td>';
		$return_value.='<td>';
		if (!empty($myuser->pf_building_2_approved)) {
			$return_value.=get_term_by('id', $myuser->pf_building_2_approved, 'building')->name;
		}
		$return_value.='</td>';
		//$return_value.='<td>'.$myuser->pf_room_2.'</td>';
		$return_value.='<td>'.$myuser->pf_room_2_approved.'</td>';
		//$return_value.='<td>'.$myuser->pf_mailstop_2.'</td>';
		$return_value.='<td>'.$myuser->pf_mailstop_2_approved.'</td>';
		//$return_value.='<td>'.$myuser->pf_search_key.'</td>';
		$return_value.='</tr>';
	}

	$return_value.='</tbody>';

	//end table
	$return_value.='</table>';

	//return value
	return $return_value;

}
