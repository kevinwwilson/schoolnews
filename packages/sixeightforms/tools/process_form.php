<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));

//Load required libraries and models
Loader::library('view');
Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');

//Loader required helpers
$uh = Loader::helper('concrete/urls');

//Set form and fields variables
$form = sixeightForm::getByID(intval($_POST['sem-form-id']));
$fields = $form->getFields();

//Are we updating a record or adding a record?
if(($_POST['editCode'] != '') && ($_POST['asID'] != '')) {

	//Get the original answer set
	$originalAnswerSet = sixeightAnswerSet::getByIDAndEditCode($_POST['asID'],$_POST['editCode']);
	
	if(is_object($originalAnswerSet)) {
		$editRecord = 1;	
	}
}

/*** Error Types: Permissions, Validation, and CAPTCHA ***/
/*** Check for them here.  If any of them occur, don't process the data ***/

$response = array();
$response['hasErrors'] = 0;

//Make sure the user has permission to add a new record
if($editRecord != 1) {
	if($form->userCanAdd() === false) {
		$response['hasErrors'] = 1;
		$response['errorType'] = 'permissions';
		$response['permissionsError'] = 'add';
	}
} else {
	if($originalAnswerSet->userCanEdit() === false) {
		$response['hasErrors'] = 1;
		$response['errorType'] = 'permissions';
		$response['permissionsError'] = 'edit';
	}
}


//Check the CAPTCHA field
$captchaError = 0;
if (($form->properties['captcha'] == '1') && (!$editRecord)) {
	$captcha = Loader::helper('validation/captcha');
	if (!$captcha->check()) {
		$response['hasErrors'] = 1;
		$response['errorType'] = 'captcha';
		$_REQUEST['ccmCaptchaCode']='';
	}
}

//Validate the data
foreach($fields as $field) { //Loop through fields
	if($field->isRequired()) { //Determine if it's required
		if($_POST[$field->ffID] == '') { //See if the actual value is blank
			$response['hasErrors'] = 1;
			$response['errorType'] = 'validation';
			$response['errors'][] = array('ffID'=>$field->ffID,'label'=>$field->label,'type'=>'required'); //If it is blank, add a validation error
		}
	}
	
	if($field->isUnique()) {
		if(!($field->validateUniqueness($_POST[$field->ffID]))) {
			$response['hasErrors'] = 1;
			$response['errorType'] = 'validation';
			$response['errors'][] = array('ffID'=>$field->ffID,'label'=>$field->label,'type'=>'unique'); //If it is blank, add a validation error
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

//If there are no errors, save the data and start preparing email
if ($response['hasErrors'] != 1) {
	
	//Loop through fields to determine total price
	$totalPrice = 0;
	foreach($fields as $field) {
		if($field->type == 'Sellable Item') {
			if($field->price > 0) {
				$totalPrice += $_POST[$field->ffID] * $field->price;
			} else {
				$totalPrice += $_POST[$field->ffID];
			}
		}
	}
	
	if((floatval($form->properties['maximumPrice']) > 0) && ($totalPrice > floatval($form->properties['maximumPrice']))) {
		$totalPrice = $form->properties['maximumPrice'];
	}
	
	//Create answer set
	if($editRecord == 1) { //If we're editing, we want the new record to have the same timestamp, approval status, and cID as the original
		if(intval($originalAnswerSet->recordID) == 0) {
			$originalRecordID = $originalAnswerSet->asID;
		} else {
			$originalRecordID = $originalAnswerSet->recordID;
		}
		$as = $form->createAnswerSet($totalPrice,$originalAnswerSet->dateSubmitted,$originalAnswerSet->isApproved,$originalAnswerSet->cID,$originalRecordID,$originalAnswerSet->creator);
	} else {
		$as = $form->createAnswerSet($totalPrice);
		
		if(intval($form->properties['autoExpire']) > 0) {
			$expiration = (24 * 60 * 60 * intval($form->properties['autoExpire'])) + time();
			$as->setExpiration($expiration);
		}
	}
	$emailData = '';
	
	//Process form data
	foreach($fields as $field) {
		//Ignore the "Text (no user input)" field when processing data
		if($field->type != 'Text (no user input)') {
			if(($field->type == 'File Upload') && (intval($field->fsID) != 0)) {
				//Put the file in the proper file set
				Loader::model('file_set');
				$fs = FileSet::getByID(intval($field->fsID));
				$fs->addFileToSet(intval($_POST[$field->ffID]));
			}
			if($field->type == 'Credit Card') {
				//Add it to the response to send it back to the form
				$response['cc'] = $_POST[$field->ffID];
			
				//Save data
				$as->addAnswer($field->ffID,'XXXX');
			} else if ($field->type == 'Time') {
				$as->addAnswer($field->ffID,$_POST[$field->ffID][0] . ':' . $_POST[$field->ffID][1] . ' ' . $_POST[$field->ffID][2]);
			} else {
				//Save data
				$as->addAnswer($field->ffID,$_POST[$field->ffID]);
				
				if($field->isExpirationField) {
					$as->setExpiration(strtotime($_POST[$field->ffID]));
				}
			}
		}
	}
	
	//Reset $as in order to populate the answers into the object
	$as = sixeightAnswerSet::getByID($as->asID);
	
	//Auto-create page
	if(($form->properties['autoCreatePage'] == 1) && ($editRecord != 1)) {
		$as->createPage();
	}
	
	//Send main notification email
	if((($form->properties['mailTo'] != '') && ($form->properties['sendMail'] == '1')) || (($form->properties['mailTo'] != '') && ($form->properties['sendMail'] == '2') && ($editRecord != 1))) {
		$toAddresses = explode(',',$form->properties['mailTo']);
		foreach($toAddresses as $toAddress) {
			$toAddress = trim($toAddress);
			$adminUserInfo=UserInfo::getByID(USER_SUPER_ID);
			$as->sendNotification($toAddress);
		}
	}
	

	//Send confirmation email to user
	if(($form->properties['confirmationField'] != '') && ($_POST[$form->properties['confirmationField']] != '') && ($form->properties['confirmationEmail'] != '') && ($editRecord != 1)) {
		$as->sendConfirmation();
	}
	
	//Send conditional notifications
	$notifications = $form->getNotifications();
	foreach($notifications as $n) {
		if(is_array($_POST[$n['ffID']])) {
			$nField = implode(',',$_POST[$n['ffID']]);
		} else {
			$nField = $_POST[$n['ffID']];
		}
		switch($n['conditionType']) {
			case 'is equal to':
				if($nField == $n['value']) {
					$as->sendNotification($n['email']);
				}
				break;
			case 'is not equal to':
				if($nField != $n['value']) {
					$as->sendNotification($n['email']);
				}
				break;
			case 'contains':
				if(stripos($nField,$n['value']) !== FALSE) {
					$as->sendNotification($n['email']);
				}
				break;
			case 'does not contain':
				if(stripos($nField,$n['value']) === FALSE) {
					$as->sendNotification($n['email']);
				}
				break;
		}
	}
	
	if($editRecord == 1) { //Delete the old record
		$originalAnswerSet->delete(false,true,$as->asID);
	}
	
	if($form->hasCommerceField()) {
		$response['hasCommerce'] = 1;
		$response['asID'] = $as->asID;
		$response['asC'] = $as->editCode;
		$response['amountCharged'] = $as->amountCharged;
	}
	
	if($form->properties['autoIndex'] == 1) {
		$form->indexAnswerSets();
	}	
}

//Fancy stuff for those who do not have javascript enabled
if(intval($_GET['js']) == 0) {
	if($response['hasErrors'] == 0) {
		//Check redirect option and redirect accordingly
		switch($form->properties['afterSubmit']) {
			case 'thankyou':
				if(strpos($_SERVER['HTTP_REFERER'],'?') !== false) {
					$separator = '&';
				} else {
					$separator = '?';
				}
				header('location:' . $_SERVER['HTTP_REFERER'] . $separator . 'submittedBID=' . $_POST['bID']);
				break;
			case 'redirect':
				header('location:' . View::url(Page::getCollectionPathFromID($form->properties['thankyouCID'])));	
				break;
			case 'url':
				header('location:' . $form->properties['thankyouURL']);	
				break;
		}
	} else {
		if(strpos($_SERVER['HTTP_REFERER'],'?') !== false) {
			$newQueryString = '?errorBID=' . $_POST['bID'];
		
			$urlParts = explode('?',$_SERVER['HTTP_REFERER']);
			$path = $urlParts[0];
			$queryString = $urlParts[1];
			$qsParts = explode('&',$queryString);
			foreach($qsParts as $param) {
				$pair = explode('=',$param);
				$key = $pair[0];
				if(($key != 'errorBID') && ($key != 'errorType') && ($key != 'errorField[]')) {
					$newQueryString .= '&' . $param;
				}
			}
		} else {
			$path = $_SERVER['HTTP_REFERER'] . '?errorBID=' . $_POST['bID'];
		}

		$newQueryString .= '&errorType=' . $response['errorType'];
		
		foreach($response['errors'] as $errorField) {
			$newQueryString .= '&errorField[]=' . $errorField['ffID'];
		}
		
		header('location:' . $path . $newQueryString);
	}
}


$response['action'] = $form->properties['afterSubmit'];
switch($form->properties['afterSubmit']) {
	case 'thankyou':
		$response['response'] = $form->properties['thankyouMsg'];
		break;
	case 'redirect':
		$response['response'] = View::url(Page::getCollectionPathFromID($form->properties['thankyouCID']));	
		if($form->properties['passRecordID'] == 1) {
			if(strpos($_SERVER['HTTP_REFERER'],'?') !== false) {
				$separator = '&';
			} else {
				$separator = '?';
			}
			$response['response'] .= '?' . $form->properties['recordIDParameter'] . '=' . $as->asID;
		}
		break;
	case 'url':
		$response['response'] = $form->properties['thankyouURL'];	
		if($form->properties['passRecordID'] == 1) {
			if(strpos($_SERVER['HTTP_REFERER'],'?') !== false) {
				$separator = '&';
			} else {
				$separator = '?';
			}
			$response['response'] .= '?' . $form->properties['recordIDParameter'] . '=' . $as->asID;
		}
		break;
}

echo json_encode($response);