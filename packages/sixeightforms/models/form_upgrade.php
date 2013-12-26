<?php  
	Loader::model('form','sixeightforms');
	Loader::model('field','sixeightforms');
	Loader::model('answer_set','sixeightforms');
	
	class sixeightformUpgrade extends Object {
		
		function upgradeForms($oldForms) {
			$oldForms = sixeightformUpgrade::getOldForms();
			if(is_array($oldForms)) {
				foreach($oldForms as $oldForm) {
					sixeightformUpgrade::updateForm($bID);					
				} //End foreach on $oldForms
			} // End if on $oldForms
		}
		
		function getOldForms() {
			$db = Loader::db();
			$oldForms = $db->getAll("SELECT bID FROM btForm");
			return $oldForms;			
		}
		
		function upgradeForm($bID) {
			$db = Loader::db();
			$oldForm = $db->getRow("SELECT * FROM btForm WHERE bID = ?", array($bID));
			
			$data = array();
			$data['name'] = $oldForm['surveyName'];
			$data['sendMail'] = $oldForm['notifyMeOnSubmission'];
			$data['sendData'] = 1;
			$data['mailFrom'] = SITE;
			$data['mailTo'] = $oldForm['recipientEmail'];
			$data['mailCc'] = '';
			$data['mailBcc'] = '';
			$data['mailSubject'] = $oldForm['surveyName'] . t(' Form Submission');
			if(intval($oldForm['redirectCID']) != 0) {
				$data['afterSubmit'] = 'redirect';
			} else {
				$data['afterSubmit'] = 'thankyou';
			}
			$data['thankyouCID'] = intval($oldForm['redirectCID']);
			$data['thankyouMsg'] = $oldForm['thankyouMsg'];
			$data['thankyouURL'] = '';
			$data['captcha'] = intval($oldForm['displayCaptcha']);
			$data['submitLabel'] = t('Submit');
			$data['fieldLabelLocation'] = 'beside';
			$data['sendApprovalNotification'] = 0;
			$data['requiredIndicator'] = '*';
			$data['requiredColor'] = 'ff0000';
			$data['gateway'] = $oldForm['gateway'];
			$data['ecommerceConfirmation'] = '';
			$data['defaultAnswerSetStatus'] = 0;
			$data['autoCreatePage'] = 0;
			$data['parentCID'] = 0;
			$data['ctID'] = 0;
			$data['cName'] = '';
			$data['cHandle'] = '';
			$data['cDescription'] = '';
			$data['meta_title'] = '';
			$data['meta_description'] = '';
			$data['meta_keywords'] = '';
			$data['exclude_nav'] = '';
			$data['detailTemplateID'] = '';
			$data['disableCache'] = 0;
			$data['maximumPrice'] = '';
			$f = sixeightform::create($data);
			
			// Copy fields
			$oldFields = sixeightformUpgrade::getOldFields($bID);
			$msqID = array();
			if(is_array($oldFields)) {
				foreach($oldFields as $oldField) {
					$fieldData['label'] = $oldField['question'];
					$fieldData['text'] = '';
					switch($oldField['inputType']) {
						case 'field':
							$fieldData['type'] = 'Text (Single-line)';
							break;
						case 'text':
							$fieldData['type'] = 'Text (Multi-line)';
							break;
						case 'radios':
							$fieldData['type'] = 'Radio Button';
							break;
						case 'select':
							$fieldData['type'] = 'Dropdown';
							break;
						case 'checkboxlist':
							$fieldData['type'] = 'Checkbox';
							break;
						case 'fileupload':
							$fieldData['type'] = 'File Upload';
							break;
					}
					$fieldData['defaultValue'] = '';
					$fieldData['maxLength'] = '';
					$fieldData['layout'] = 'horizontal';
					$fieldData['format'] = 'basic';
					$fieldData['toolbar'] = 0;
					$fieldData['groupWithPrevious'] = 0;
					$fieldData['required'] = $oldField['required'];
					$fieldData['price'] = 0;
					$fieldData['qtyStart'] = 0;
					$fieldData['qtyEnd'] = 0;
					$fieldData['qtyIncrement'] = 0;
					$fieldData['eCommerceName'] = '';
					$fieldData['isExpirationField'] = 0;
					$fieldData['dateFormat'] = '';
					$fieldData['indexable'] = 1;
					$fieldData['urlParameter'] = '';
					$fieldData['class'] = '';
					
					$options = explode("%%",$oldField['options']);
					$ff = $f->addField($fieldData,$options);
					$msqID[$oldField['msqID']] = $ff->ffID;
				} // End foreach on $oldFields
			} // End if on $oldFields
			
			//Copy answer sets
			$oldAnswerSets = sixeightformUpgrade::getOldAnswerSets($oldForm['questionSetId']);
			if(is_array($oldAnswerSets)) {
				foreach($oldAnswerSets as $oldAnswerSet) {
					$as = $f->createAnswerSet(0,strtotime($oldAnswerSet['created']));
					$oldAnswers = sixeightformUpgrade::getOldAnswers($oldAnswerSet['asID']);
					if(is_array($oldAnswers)) {
						foreach($oldAnswers as $oldAnswer) {
							if($oldAnswer['answer'] == '') {
								$answer = $oldAnswer['answerLong'];
							} else {
								$answer = $oldAnswer['answer'];
							}
							$as->addAnswer($msqID[$oldAnswer['msqID']],$answer); 
						}
					}
				} // End foreach on $oldAnswerSets
			} // End if on $oldAnswerSets
		}
		
		function getOldFields($bID) {
			$db = Loader::db();
			$oldFields = $db->getAll("SELECT * FROM btFormQuestions WHERE bID = ?", array($bID));
			return $oldFields;
		}
		
		function getOldAnswerSets($questionSetId) {
			$db = Loader::db();
			$oldAnswerSets = $db->getAll("SELECT * FROM btFormAnswerSet WHERE questionSetId = ?", array($questionSetId));
			return $oldAnswerSets;
		}
		
		function getOldAnswers($asID) {
			$db = Loader::db();
			$oldAnswers = $db->getAll("SELECT * FROM btFormAnswers WHERE asID = ?", array($asID));
			return $oldAnswers;
		}
	}
	
?>