<?php


function __autoload($class_name) {

	if (file_exists($class_name . '.php')) {
		require_once ($class_name . '.php');
		return;
	} else {
		echo class_name . "php doesn't exist";
	}	
}

//sample data
$data = array(	'primary_content' => 		array( 		'name' => 'your name',
														'address' => 'your address',
														'postcode' => 'your postcode'
												),
				'secondary_content' => array( 		'account_id' => 'your account',
													'branch' => array (		'name' => 'your branch name',
																			'code' => 'branch code'
																		)
												),								
				'tertiary_content' => 'other data here');

//serialise it
$serialised_string = serialize($data);												

$consumer = new App\versionDataDecorator($serialised_string);

//simple get/set for single data item
echo $consumer->get('tertiary_content');
$consumer->set('tertiary_content', 'new data goes here');

//simple get/set for single date array
print_r($consumer->get('primary_content'));

//2nd level iterative get/set
echo $consumer->get('secondary_content')->get('account_id');
$consumer->get('secondary_content')->set('account_id', 'new account text');

//3rd level iterative get/set
echo $consumer->get('secondary_content')->get('branch')->get('name');
$consumer->get('secondary_content')->get('branch')->set('code', 'your new code');

//dehydrate updated data 
$serialised_string = $consumer->dehydrate();

