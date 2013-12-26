<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));

//Load required libraries and models
Loader::library('view');
Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');

//Set form and fields variables
$form = sixeightForm::getByID(intval($_POST['sem-form-id']));
$fields = $form->getFields();

$response = array();
$response['hasErrors'] = 0;

//Validate the data
$keepGoing = 1;
foreach($fields as $field) { //Loop through fields
	if($field->ffID == intval($_GET['sectionID'])) {
		$keepGoing = 0;
	}

	if($keepGoing == 1) {
		if($field->isRequired()) { //Determine if it's required
			if($_POST[$field->ffID] == '') { //See if the actual value is blank
				$response['hasErrors'] = 1;
				$response['errorType'] = 'validation';
				$response['errors'][] = array('ffID'=>$field->ffID,'label'=>$field->label,'type'=>'required'); //If it is blank, add a validation error
			}
		}
		
		switch($field->type) {
			case 'Number':
				if((!is_numeric($_POST[$field->ffID])) && ($_POST[$field->ffID] != '')) { //See if the actual value is blank
					$response['hasErrors'] = 1;
					$response['errorType'] = 'validation';
					$response['errors'][] = array('ffID'=>$field->ffID,'label'=>$field->label,'type'=>'numeric');
				}
				break;
			case 'Email Address':
				if((!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", $_POST[$field->ffID])) && ($_POST[$field->ffID] != '')) {
					$response['hasErrors'] = 1;
					$response['errorType'] = 'validation';
					$response['errors'][] = array('ffID'=>$field->ffID,'label'=>$field->label,'type'=>'email');
				}
				break;
		}
	}
}

echo json_encode($response);