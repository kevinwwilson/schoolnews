<?php  
	class sixeightForm extends Object {
	
		public function getByID($fID) {
			$db = Loader::db();
			$f = new sixeightForm;
			$f->fID = $fID;
			$row = $db->getRow("SELECT * FROM sixeightforms WHERE fID=?", array($fID));
			if ($row['fID'] == $fID) {
				foreach($row as $col => $val) {
					$f->properties[$col] = $val;
				}
			}
			
			//If no payment gateway is set, use paypal by default
			if($f->properties['gateway'] == '') {
				$f->properties['gateway'] = 'paypal';
			}
			
			//If no currency symbol is set, use $ by default
			if($f->properties['currencySymbol'] == '') {
				$f->properties['currencySymbol'] = '$';
			}
			
			return $f;
		}
		
		public function getByHandle($handle) {
			$db = Loader::db();
			$row = $db->getRow("SELECT fID FROM sixeightforms WHERE handle = ?",array($handle));
			if(intval($row['fID']) != 0) {
				return sixeightForm::getByID($row['fID']);
			} else {
				return false;
			}
		}
		
		public function getAll() {
			$db = Loader::db();
			$formsData = $db->getAll("SELECT fID FROM sixeightforms WHERE isDeleted != 1 ORDER BY name ASC");
			if(count($formsData) > 0) {
				$forms = array();
				foreach($formsData as $formRow) {
					$forms[] = sixeightForm::getByID($formRow['fID']);
				}
			}
			return $forms;
		}
		
		public function getFormID() {
			return $this->properties['fID'];
		}
		
		public function create($data) {
			$db = Loader::db();
			$sql = array();
			
			if($data['handle'] == '') {
				$txt = Loader::helper('text');
				$data['handle'] = $txt->handle($data['name']);
			}
			
			$sqlData[] = $data['name'];
			$sqlData[] = $data['handle'];
			$sqlData[] = $data['sendMail'];
			$sqlData[] = $data['sendData'];
			$sqlData[] = $data['mailFrom'];
			$sqlData[] = $data['mailFromAddress'];
			$sqlData[] = $data['mailTo'];
			$sqlData[] = $data['mailCc'];
			$sqlData[] = $data['mailBcc'];
			$sqlData[] = $data['mailSubject'];
			$sqlData[] = $data['afterSubmit'];
			$sqlData[] = $data['thankyouCID'];
			$sqlData[] = $data['thankyouMsg'];
			$sqlData[] = $data['thankyouURL'];
			$sqlData[] = $data['captcha'];
			$sqlData[] = $data['submitLabel'];
			$sqlData[] = $data['maxSubmissions'];
			$sqlData[] = $data['fieldLabelLocation'];
			$sqlData[] = $data['sendApprovalNotification'];
			$sqlData[] = $data['requiredIndicator'];
			$sqlData[] = $data['requiredColor'];
			$sqlData[] = $data['gateway'];
			$sqlData[] = $data['currencySymbol'];
			$sqlData[] = $data['ecommerceConfirmation'];
			$sqlData[] = $data['defaultAnswerSetStatus'];
			$sqlData[] = $data['autoCreatePage'];
			$sqlData[] = $data['parentCID'];
			$sqlData[] = $data['ctID'];
			$sqlData[] = $data['cName'];
			$sqlData[] = $data['cHandle'];
			$sqlData[] = $data['cDescription'];
			$sqlData[] = $data['meta_title'];
			$sqlData[] = $data['meta_description'];
			$sqlData[] = $data['meta_keywords'];
			$sqlData[] = $data['exclude_nav'];
			$sqlData[] = $data['detailTemplateID'];
			$sqlData[] = $data['disableCache'];
			$sqlData[] = $data['maximumPrice'];
			$sqlData[] = $data['autoIndex'];
			$sqlData[] = $data['useHTML5'];
			$sqlData[] = $data['passRecordID'];
			$sqlData[] = $data['recordIDParameter'];
			$sqlData[] = $data['autoExpire'];
			
			$db->execute("INSERT INTO sixeightforms (fID, dateCreated, name, handle, sendMail, sendData, mailFrom, mailFromAddress, mailTo, mailCc, mailBcc, mailSubject, afterSubmit, thankyouCID, thankyouMsg, thankyouURL, captcha, submitLabel, maxSubmissions, fieldLabelLocation, sendApprovalNotification, requiredIndicator, requiredColor, gateway, currencySymbol, ecommerceConfirmation, defaultAnswerSetStatus, autoCreatePage, parentCID, ctID, cName, cHandle, cDescription, meta_title, meta_description, meta_keywords, exclude_nav, detailTemplateID, disableCache, maximumPrice, autoIndex, useHTML5, passRecordID, recordIDParameter, autoExpire) VALUES (0, '" . time() . "',?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",$sqlData);
			$f = sixeightForm::getByID($db->Insert_ID());
			$f->setDefaultPermissions();
			return $f;
		}
		
		public function update($data) {
			$db = Loader::db();
			
			$sqlData[] = $data['name'];
			$sqlData[] = $data['handle'];
			$sqlData[] = $data['sendMail'];
			$sqlData[] = $data['sendData'];
			$sqlData[] = $data['mailFrom'];
			$sqlData[] = $data['mailFromAddress'];
			$sqlData[] = $data['mailTo'];
			$sqlData[] = $data['mailCc'];
			$sqlData[] = $data['mailBcc'];
			$sqlData[] = $data['mailSubject'];
			$sqlData[] = $data['afterSubmit'];
			$sqlData[] = $data['thankyouCID'];
			$sqlData[] = $data['thankyouMsg'];
			$sqlData[] = $data['thankyouURL'];
			$sqlData[] = $data['captcha'];
			$sqlData[] = $data['submitLabel'];
			$sqlData[] = $data['maxSubmissions'];
			$sqlData[] = $data['fieldLabelLocation'];
			$sqlData[] = $data['sendApprovalNotification'];
			$sqlData[] = $data['requiredIndicator'];
			$sqlData[] = $data['requiredColor'];
			$sqlData[] = $data['gateway'];
			$sqlData[] = $data['currencySymbol'];
			$sqlData[] = $data['ecommerceConfirmation'];
			$sqlData[] = $data['defaultAnswerSetStatus'];
			$sqlData[] = $data['autoCreatePage'];
			$sqlData[] = $data['parentCID'];
			$sqlData[] = $data['ctID'];
			$sqlData[] = $data['cName'];
			$sqlData[] = $data['cHandle'];
			$sqlData[] = $data['cDescription'];
			$sqlData[] = $data['meta_title'];
			$sqlData[] = $data['meta_description'];
			$sqlData[] = $data['meta_keywords'];
			$sqlData[] = $data['exclude_nav'];
			$sqlData[] = $data['detailTemplateID'];
			$sqlData[] = $data['disableCache'];
			$sqlData[] = $data['maximumPrice'];
			$sqlData[] = $data['autoIndex'];
			$sqlData[] = $data['useHTML5'];
			$sqlData[] = $data['passRecordID'];
			$sqlData[] = $data['recordIDParameter'];
			$sqlData[] = $data['autoExpire'];
			$sqlData[] = $this->fID;
			
			$db->execute("UPDATE sixeightforms SET name = ?, handle = ?, sendMail = ?, sendData = ?, mailFrom = ?, mailFromAddress = ?, mailTo = ?, mailCc = ?, mailBcc = ?, mailSubject = ?, afterSubmit = ?, thankyouCID = ?, thankyouMsg = ?, thankyouURL = ?, captcha = ?, submitLabel = ?, maxSubmissions = ?, fieldLabelLocation = ?, sendApprovalNotification = ?, requiredIndicator = ?, requiredColor = ?, gateway = ?, currencySymbol=?, ecommerceConfirmation = ?, defaultAnswerSetStatus = ?, autoCreatePage = ?, parentCID = ?, ctID = ?, cName = ?, cHandle = ?, cDescription = ?, meta_title = ?, meta_description = ?, meta_keywords = ?, exclude_nav = ?, detailTemplateID = ?, disableCache = ?, maximumPrice = ?, autoIndex = ?, useHTML5 = ?, passRecordID = ?, recordIDParameter = ?, autoExpire = ? WHERE fID=?",$sqlData);
		}
		
		public function duplicate() {
			$data = array();
			$data['name'] = $this->properties['name'];
			$data['handle'] = $this->properties['handle'];
			$data['sendMail'] = $this->properties['sendMail'];
			$data['sendData'] = $this->properties['sendData'];
			$data['mailFrom'] = $this->properties['mailFrom'];
			$data['mailFromAddress'] = $this->properties['mailFromAddress'];
			$data['mailTo'] = $this->properties['mailTo'];
			$data['mailCc'] = $this->properties['mailCc'];
			$data['mailBcc'] = $this->properties['mailBcc'];
			$data['mailSubject'] = $this->properties['mailSubject'];
			$data['afterSubmit'] = $this->properties['afterSubmit'];
			$data['thankyouCID'] = $this->properties['thankyouCID'];
			$data['thankyouMsg'] = $this->properties['thankyouMsg'];
			$data['thankyouURL'] = $this->properties['thankyouURL'];
			$data['captcha'] = $this->properties['captcha'];
			$data['submitLabel'] = $this->properties['submitLabel'];
			$data['maxSubmissions'] = $this->properties['maxSubmissions'];
			$data['fieldLabelLocation'] = $this->properties['fieldLabelLocation'];
			$data['sendApprovalNotification'] = $this->properties['sendApprovalNotification'];
			$data['requiredIndicator'] = $this->properties['requiredIndicator'];
			$data['requiredColor'] = $this->properties['requiredColor'];
			$data['gateway'] = $this->properties['gateway'];
			$data['currencySymbol'] = $this->properties['currencySymbol'];
			$data['ecommerceConfirmation'] = $this->properties['ecommerceConfirmation'];
			$data['defaultAnswerSetStatus'] = $this->properties['defaultAnswerSetStatus'];
			$data['autoCreatePage'] = $this->properties['autoCreatePage'];
			$data['parentCID'] = $this->properties['parentCID'];
			$data['ctID'] = $this->properties['ctID'];
			$data['cName'] = $this->properties['cName'];
			$data['cHandle'] = $this->properties['cHandle'];
			$data['cDescription'] = $this->properties['cDescription'];
			$data['meta_title'] = $this->properties['meta_title'];
			$data['meta_description'] = $this->properties['meta_description'];
			$data['meta_keywords'] = $this->properties['meta_keywords'];
			$data['exclude_nav'] = $this->properties['exclude_nav'];
			$data['detailTemplateID'] = $this->properties['detailTemplateID'];
			$data['disableCache'] = $this->properties['disableCache'];
			$data['maximumPrice'] = $this->properties['maximumPrice'];
			$data['autoIndex'] = $this->properties['autoIndex'];
			$data['useHTML5'] = $this->properties['useHTML5'];
			$data['passRecordID'] = $this->properties['passRecordID'];
			$data['recordIDParameter'] = $this->properties['recordIDParameter'];
			$data['autoExpire'] = $this->properties['autoExpire'];
			
			$newForm = sixeightForm::create($data);

			$fields = $this->getFields();
			foreach($fields as $field) {
				$field->duplicate($newForm->fID);
			}
			
		}
		
		public function updateName($name) {
			$db = Loader::db();
			$db->execute("UPDATE sixeightforms SET name=? WHERE fID=?",array($name,$this->fID));
		}
		
		public function delete() {
			$db = Loader::db();
			$db->execute("UPDATE sixeightforms SET isDeleted=1 WHERE fID=?",array($this->fID));
		}
		
		public function addField($data,$options='') {
			$data['fID'] = $this->fID;
			$field = sixeightField::create($data,$options);
			return $field;
		}
		
		public function getFieldCount() {
			$db = Loader::db();
			$fieldCount = $db->getRow("SELECT count(ffID) as total FROM sixeightformsFields WHERE fID=? AND isDeleted != 1", array($this->fID));
			return $fieldCount['total'];
		}
		
		public function getFields() {
			Loader::model('field','sixeightforms');
			$db = Loader::db();
			$fieldsData = $db->getAll("SELECT ffID FROM sixeightformsFields WHERE fID = ? AND isDeleted != 1 ORDER by sortPriority ASC, ffID",array($this->fID));
			$fields = array();
			foreach($fieldsData as $fieldData) {
				$fields[$fieldData['ffID']] = sixeightField::getByID($fieldData['ffID']);
			}
			return $fields;
		}
		
		public function getField($ffID) {
			Loader::model('field','sixeightforms');
			return sixeightField::getByID($ffID);
		}
		
		public function getFieldByHandle($ffHandle) {
			Loader::model('field','sixeightforms');
			return sixeightField::getByHandle($ffHandle);
		}
		
		public function getTotalAnswerSetCount() {
			$db = Loader::db();
			$asCount = $db->getRow("SELECT count(asID) as total FROM sixeightformsAnswerSets WHERE fID=? AND isDeleted != 1", array($this->fID));
			return $asCount['total'];
		}
		
		public function saveConfirmation($ffID,$from,$fromAddress,$subject,$message) {
			$db = Loader::db();
			$db->execute("UPDATE sixeightforms SET confirmationField=?, confirmationFrom=?, confirmationFromAddress=?, confirmationSubject=?, confirmationEmail=? WHERE fID=?",array($ffID,$from,$fromAddress,$subject,$message,$this->fID));
		}
		
		public function clearNotifications() {
			$db = Loader::db();
			$db->execute("DELETE FROM sixeightformsNotifications WHERE fID=?",array($this->fID));
		}
		
		public function saveNotification($ffID,$conditionType,$value,$sendData,$email) {
			$db = Loader::db();
			$db->execute("INSERT INTO sixeightformsNotifications (nID,fID,ffID,conditionType,value,sendData,email) VALUES (0,?,?,?,?,?,?)",array($this->fID,$ffID,$conditionType,$value,$sendData,$email));
		}
		
		public function getNotifications() {
			$db = Loader::db();
			$notifications = $db->getAll("SELECT * FROM sixeightformsNotifications WHERE fID = ? ORDER by nID ASC",array($this->fID));
			return $notifications;
		}
		
		public function clearAnswersCache() {
			$db = Loader::db();
			$db->execute("UPDATE sixeightformsAnswerSets SET matchingFilters='' WHERE fID=?",array($this->fID));
		}
		
		public function sendMail($to,$from='',$fromAddress='',$subject,$body) {
			if($to != '') {
				$adminUserInfo=UserInfo::getByID(USER_SUPER_ID);
				if($from == '') {
					$from = SITE;
				}
				if($fromAddress == '') {
					$fromAddress = $adminUserInfo->getUserEmail(); 
				}
				$mh = Loader::helper('mail');
				$mh->to($to); 
				$mh->from($fromAddress, $from);
				$mh->setSubject($subject);
				$mh->setBody($body);
				@$mh->sendMail();
			}
		}
		
		public function getTotalAnswerSets() {
			$db = Loader::db();
			$asCount = $db->getRow("SELECT count(asID) as total FROM sixeightformsAnswerSets WHERE fID=? AND isDeleted != 1", array($this->fID));
			return $asCount['total'];
		}
		
		public function deleteAllAnswerSets() {
			$db = Loader::db();
			$answerSets = $db->execute("SELECT asID FROM sixeightformsAnswerSets WHERE fID=?",array($this->fID));
			foreach($answerSets as $as) {
				$db->execute("DELETE FROM sixeightformsAnswerSets WHERE asID=?",array($as['asID']));
				$db->execute("DELETE FROM sixeightformsAnswers WHERE asID=?",array($as['asID']));
			}
			$this->clearAnswersCache();
		}
		
		public function getAnswerSetList() {
			Loader::model('answer_set_list','sixeightforms');
			return sixeightAnswerSetList::get($this->fID);
		}
		
		public function getAnswerSetSamples($ffID=0) {
			$db = Loader::db();
			$asIDs = $db->getAll("SELECT asID FROM sixeightformsAnswerSets WHERE fID = ? AND isDeleted != 1",array($this->fID));
			
			if($ffID == 0) {
				$fields = $this->getFields();
				$firstFF = reset($fields);
				$ffID = $firstFF->ffID;
			}

			if(count($asIDs) > 0) {
				$answerSets = array();
				foreach($asIDs as $asID) {
					$answerSets[$asID['asID']] = sixeightAnswerSet::getByID($asID['asID'],$ffID);
				}
			}
			
			return $answerSets;
		}
		
		public function hasSiteMapField() {
			$db = Loader::db();
			$fieldCount = $db->getRow("SELECT count(ffID) as total FROM sixeightformsFields WHERE fID=? AND toolbar=1 AND isDeleted != 1", array($this->fID));
			if($fieldCount['total'] > 0) {
				return true;
			} else {
				return false;
			}
		}
		
		public function hasFileManagerField() {
			$db = Loader::db();
			$fieldCount = $db->getRow("SELECT count(ffID) as total FROM sixeightformsFields WHERE fID=? AND (type='File Upload' OR type='File from File Manager') AND isDeleted != 1",array($this->fID));
			if($fieldCount['total'] > 0) {
				return true;
			} else {
				return false;
			}
		}
		
		public function hasWYSIWYGField() {
			$db = Loader::db();
			$fieldCount = $db->getRow("SELECT count(ffID) as total FROM sixeightformsFields WHERE fID=? AND type='WYSIWYG' AND isDeleted != 1",array($this->fID));
			if($fieldCount['total'] > 0) {
				return true;
			} else {
				return false;
			}
		}
		
		public function hasDateField() {
			$db = Loader::db();
			$fieldCount = $db->getRow("SELECT count(ffID) as total FROM sixeightformsFields WHERE fID=? AND type='Date' AND isDeleted != 1",array($this->fID));
			if($fieldCount['total'] > 0) {
				return true;
			} else {
				return false;
			}
		}
		
		public function hasCommerceField() {
			$db = Loader::db();
			$fieldCount = $db->getRow("SELECT count(ffID) as total FROM sixeightformsFields WHERE fID=? AND type='Sellable Item' AND isDeleted != 1", array($this->fID));
			if($fieldCount['total'] > 0) {
				return true;
			} else {
				return false;
			}
		}
		
		public function getPaymentGateways() {
			$fh = Loader::helper('file');
			$templates = array();
			
			if (file_exists(DIR_FILES_ELEMENTS . '/sixeightforms/payment')) {
				$templates = array_merge($templates, $fh->getDirectoryContents(DIR_FILES_ELEMENTS . '/sixeightforms/payment'));
			}
			
			if(file_exists(DIR_BASE . '/' . DIRNAME_PACKAGES . '/sixeightforms/elements/payment')) {
				$templates = array_merge($templates, $fh->getDirectoryContents(DIR_BASE . '/' . DIRNAME_PACKAGES . '/sixeightforms/elements/payment'));
			}
			
			sort($templates);
			
			return $templates;
		}
		
		function indexAnswerSets() {
			$db = Loader::db();
			
			//Clear the cache so that when the user searches, they will get correct results
			$this->clearAnswersCache();
			
			//Get rid of the old search index for this form
			$db->execute("UPDATE sixeightformsAnswerSets SET searchIndex='' WHERE fID=?",array($this->fID));
			
			//Loop through the indexable fields for this form
			$fields = $db->getAll("SELECT ffID FROM sixeightformsFields WHERE fID=? AND indexable=1",array($this->fID));
			foreach($fields as $field) {
				//Loop through the answers for each field
				$answers = $db->getAll("SELECT asID,value FROM sixeightformsAnswers WHERE ffID=?",array($field['ffID']));
				foreach($answers as $answer) {
					$index[$answer['asID']] .= strip_tags($answer['value']) . ' ';
				}
			}
			
			if(count($index) > 0) {
				foreach($index as $asID => $data) {
					$db->execute("UPDATE sixeightformsAnswerSets SET searchIndex=? WHERE asID=?",array($data,$asID));
				}
				$db->execute("UPDATE sixeightforms SET indexTimestamp=? WHERE fID=?",array(time(),$this->fID));
			}
		}
		
		public function createAnswerSet($totalPrice='',$timestamp='',$approvalStatus=0,$cID=0,$recordID=0,$uID=0) {
			$as = sixeightAnswerSet::create($this->fID,$totalPrice,$timestamp,$approvalStatus,$cID,$recordID,$uID);
			return $as;
		}
		
		public function setPermissions($gID,$permissions) {
			$db = Loader::db();
			$this->resetGroupPermissions($gID);
			if(count($permissions) > 0) {
				$db->execute("INSERT INTO sixeightformsPermissions (fpID, fID, gID) VALUES (0,?,?)",array($this->fID,$gID));
				foreach($permissions as $permission) {
					$db->execute("UPDATE sixeightformsPermissions SET $permission = 1 WHERE fID = ? AND gID = ?",array($this->fID,$gID)); 
				}
			}			
		}
		
		public function resetPermissions() {
			$db = Loader::db();
			$db->execute("DELETE FROM sixeightformsPermissions WHERE fID = ?",array($this->fID));
		}
		
		public function resetGroupPermissions($gID) {
			$db = Loader::db();
			$db->execute("DELETE FROM sixeightformsPermissions WHERE fID = ? AND gID = ?",array($this->fID,$gID));
		}
		
		public function setDefaultPermissions() {
			$db = Loader::db();
			$this->resetPermissions();
			
			//Give guests permission to add records - This query assumes guests are group ID 1
			$db->execute("INSERT INTO sixeightformsPermissions (fpID, fID, gID, addRecords) VALUES (0, ?, 1, 1)",array($this->fID));
			
			//Give administrators full permissions - This query assumes guests are group ID 3
			$db->execute("INSERT INTO sixeightformsPermissions (fpID, fID, gID, addRecords, editRecords, deleteRecords, approveRecords) VALUES (0, ?, 3, 1, 1, 1, 1)",array($this->fID));
		}
		
		public function setOwnerCanEdit($status) {
			$db = Loader::db();
			if($status) {
				$db->execute("UPDATE sixeightforms SET ownerCanEdit=1 WHERE fID=?",array($this->fID));
			} else {
				$db->execute("UPDATE sixeightforms SET ownerCanEdit=0 WHERE fID=?",array($this->fID));
			}
		}
		
		public function ownerCanEdit() {
			if(intval($this->properties['ownerCanEdit']) == 1) {
				return true;
			} else {
				return false;
			}
		}
		
		public function setOwnerCanDelete($status) {
			$db = Loader::db();
			if($status) {
				$db->execute("UPDATE sixeightforms SET ownerCanDelete=1 WHERE fID=?",array($this->fID));
			} else {
				$db->execute("UPDATE sixeightforms SET ownerCanDelete=0 WHERE fID=?",array($this->fID));
			}
		}
		
		public function ownerCanDelete() {
			if(intval($this->properties['ownerCanDelete']) == 1) {
				return true;
			} else {
				return false;
			}
		}
		
		public function setOneRecordPerUser($status) {
			$db = Loader::db();
			if($status) {
				$db->execute("UPDATE sixeightforms SET oneRecordPerUser=1 WHERE fID=?",array($this->fID));
			} else {
				$db->execute("UPDATE sixeightforms SET oneRecordPerUser=0 WHERE fID=?",array($this->fID));
			}
		}
		
		public function oneRecordPerUser() {
			if(intval($this->properties['oneRecordPerUser']) == 1) {
				return true;
			} else {
				return false;
			}
		}
		
		public function groupCanAddRecords($gID) {
			$db = Loader::db();
			$gp = $db->getRow("SELECT * FROM sixeightformsPermissions WHERE fID = ? AND gID = ? AND addRecords = 1",array($this->fID,$gID));
			if(count($gp) > 0) {
				return true;
			} else {
				return false;
			}
		}
		
		public function groupCanEditRecords($gID) {
			$db = Loader::db();
			$gp = $db->getRow("SELECT * FROM sixeightformsPermissions WHERE fID = ? AND gID = ? AND editRecords = 1",array($this->fID,$gID));
			if(count($gp) > 0) {
				return true;
			} else {
				return false;
			}
		}
		
		public function groupCanDeleteRecords($gID) {
			$db = Loader::db();
			$gp = $db->getRow("SELECT * FROM sixeightformsPermissions WHERE fID = ? AND gID = ? AND deleteRecords = 1",array($this->fID,$gID));
			if(count($gp) > 0) {
				return true;
			} else {
				return false;
			}
		}
		
		public function groupCanApproveRecords($gID) {
			$db = Loader::db();
			$gp = $db->getRow("SELECT * FROM sixeightformsPermissions WHERE fID = ? AND gID = ? AND approveRecords = 1",array($this->fID,$gID));
			if(count($gp) > 0) {
				return true;
			} else {
				return false;
			}
		}
		
		public function userCanAdd() {
			$u = new User();
			
			//Check whether or not "One Record Per User" is set
			if($this->oneRecordPerUser()) {
				if(intval($u->getUserID()) == 0) { //User is not logged in
					return false;
				} else {
					if($this->getUserAnswerSetCount() > 0) { //User has already submitted once
						return false;
					}
				}
				return true;
			} else { //If "One Record Per User" is not set, the only other limiting factor is standard permissions
				if($u->isSuperUser()) { //Super user can always add
					return true;
				}
				
				foreach($u->uGroups as $gID => $gName) { //Loop through the groups
					if($this->groupCanAddRecords($gID)) { //If user is part of a group that can add, they can add
						return true;
					}
				}
			}
			
			return false; //Deny access by default
		}
		
		public function userCanEdit() {
			$u = new User();
			if($u->isSuperUser()) { //Super user can always add
				return true;
			}
			
			foreach($u->uGroups as $gID => $gName) { //Loop through the groups
				if($this->groupCanEditRecords($gID)) { //If user is part of a group that can add, they can add
					return true;
				}
			}
			
			return false;
		}
		
		public function userCanApprove() {
			$u = new User();
			
			if($u->isSuperUser()) { //Super user can always approve
				return true;
			}
			
			foreach($u->uGroups as $gID => $gName) {
				if($this->groupCanApproveRecords($gID)) {
					return true;
				}
			}
			
			return false; //Deny access by default
		}
		
		public function getUserAnswerSetCount() {
			$db = Loader::db();
			$u = new User();
			$asCount = $db->getRow("SELECT count(asID) AS total FROM sixeightformsAnswerSets WHERE fID=? AND creator=? AND isDeleted != 1",array($this->fID,intval($u->getUserID())));
			return $asCount['total'];
		}
		
		public function getGatewayPath() {
			if($this->properties['gateway'] == '') {
				$gateway = 'paypal';
			} else {
				$gateway = $this->properties['gateway'];
			}
			
			//Check root elements path first
			if (file_exists(DIR_FILES_ELEMENTS . '/sixeightforms/payment/' . $gateway)) {
				return DIR_FILES_ELEMENTS . '/sixeightforms/payment/' . $gateway;
			}
			
			if(file_exists(DIR_BASE . '/' . DIRNAME_PACKAGES . '/sixeightforms/elements/payment/' . $gateway)) {
				return DIR_BASE . '/' . DIRNAME_PACKAGES . '/sixeightforms/elements/payment/' . $gateway;
			}
			
			return false;
		}
		
		public function loadGatewayConfig() {
			$gp = $this->getGatewayPath();
			include($gp . '/config.php');
			$gateway = new semGateway();
			return $gateway;
		}
		
		public function loadGatewayForm($vars) {
			if($this->properties['gateway'] == '') {
				$gateway = 'paypal';
			} else {
				$gateway = $this->properties['gateway'];
			}
			
			if (file_exists(DIR_FILES_ELEMENTS . '/sixeightforms/payment/' . $gateway)) {
				Loader::element('sixeightforms/payment/' . $gateway . '/form',$vars);
			}
			
			if(file_exists(DIR_BASE . '/' . DIRNAME_PACKAGES . '/sixeightforms/elements/payment/' . $gateway)) {
				Loader::packageElement('payment/' . $gateway . '/form','sixeightforms',$vars);
			}
		}
		
		public function loadGatewayProcessor($vars) {
			if($this->properties['gateway'] == '') {
				$gateway = 'paypal';
			} else {
				$gateway = $this->properties['gateway'];
			}
			
			if (file_exists(DIR_FILES_ELEMENTS . '/sixeightforms/payment/' . $gateway)) {
				Loader::element('sixeightforms/payment/' . $gateway . '/processor',$vars);
			}
			
			if(file_exists(DIR_BASE . '/' . DIRNAME_PACKAGES . '/sixeightforms/elements/payment/' . $gateway)) {
				Loader::packageElement('payment/' . $gateway . '/processor','sixeightforms',$vars);
			}
		}
		
		public function getXML() {
			$formXML = new SimpleXMLElement('<form></form>');
			$formXML->addAttribute('name',$this->properties['name']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','sendMail');
			$child->addAttribute('value',$this->properties['sendMail']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','sendData');
			$child->addAttribute('value',$this->properties['sendData']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','mailSubject');
			$child->addAttribute('value',$this->properties['mailSubject']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','thankyouMsg');
			$child->addAttribute('value',$this->properties['thankyouMsg']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','submitLabel');
			$child->addAttribute('value',$this->properties['submitLabel']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','captcha');
			$child->addAttribute('value',$this->properties['captcha']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','requiredIndicator');
			$child->addAttribute('value',$this->properties['requiredIndicator']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','requiredColor');
			$child->addAttribute('value',$this->properties['requiredColor']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','gateway');
			$child->addAttribute('value',$this->properties['gateway']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','ecommerceConfirmation');
			$child->addAttribute('value',$this->properties['ecommerceConfirmation']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','disableCache');
			$child->addAttribute('value',$this->properties['disableCache']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','maximumPrice');
			$child->addAttribute('value',$this->properties['maximumPrice']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','sendApprovalNotification');
			$child->addAttribute('value',$this->properties['sendApprovalNotification']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','ownerCanEdit');
			$child->addAttribute('value',$this->properties['ownerCanEdit']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','ownerCanDelete');
			$child->addAttribute('value',$this->properties['ownerCanDelete']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','oneRecordPerUser');
			$child->addAttribute('value',$this->properties['oneRecordPerUser']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','autoIndex');
			$child->addAttribute('value',$this->properties['autoIndex']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','useHTML5');
			$child->addAttribute('value',$this->properties['useHTML5']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','passRecordID');
			$child->addAttribute('value',$this->properties['passRecordID']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','recordIDParameter');
			$child->addAttribute('value',$this->properties['recordIDParameter']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','autoExpire');
			$child->addAttribute('value',$this->properties['autoExpire']);
			
			$child = $formXML->addChild('property');
			$child->addAttribute('name','currencySymbol');
			$child->addAttribute('value',$this->properties['currencySymbol']);
			
			$formXML->addChild('fields');
			
			$fields = $this->getFields();
			foreach($fields as $field) {
				$fieldXML = $formXML->fields->addChild('field');
				$fieldXML->addAttribute('label',$field->label);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','text');
				$child->addAttribute('value',$field->text);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','type');
				$child->addAttribute('value',$field->type);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','defaultValue');
				$child->addAttribute('value',$field->defaultValue);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','width');
				$child->addAttribute('value',$field->width);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','height');
				$child->addAttribute('value',$field->height);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','maxLength');
				$child->addAttribute('value',$field->maxLength);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','layout');
				$child->addAttribute('value',$field->layout);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','format');
				$child->addAttribute('value',$field->format);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','toolbar');
				$child->addAttribute('value',$field->toolbar);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','groupWithPrevious');
				$child->addAttribute('value',$field->groupWithPrevious);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','required');
				$child->addAttribute('value',$field->required);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','sortPriority');
				$child->addAttribute('value',$field->sortPriority);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','price');
				$child->addAttribute('value',$field->price);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','qtyStart');
				$child->addAttribute('value',$field->qtyStart);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','qtyEnd');
				$child->addAttribute('value',$field->qtyEnd);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','qtyIncrement');
				$child->addAttribute('value',$field->qtyIncrement);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','eCommerceName');
				$child->addAttribute('value',$field->eCommerceName);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','isDeleted');
				$child->addAttribute('value',$field->isDeleted);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','isExpirationField');
				$child->addAttribute('value',$field->isExpirationField);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','dateFormat');
				$child->addAttribute('value',$field->dateFormat);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','indexable');
				$child->addAttribute('value',$field->indexable);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','urlParameter');
				$child->addAttribute('value',$field->urlParameter);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','cssClass');
				$child->addAttribute('value',$field->cssClass);
				
				$child = $fieldXML->addChild('property');
				$child->addAttribute('name','containerCssClass');
				$child->addAttribute('value',$field->containerCssClass);
				
				if(count($field->options) > 0) {
					$options = $fieldXML->addChild('options');
					foreach($field->options as $option) {
						$optionXML = $options->addChild('option',$option['value']);
						$optionXML->addAttribute('value',$option['value']);
					}
				}
			}
			
			return $formXML; 
		}
		
		public function updateExportTimestamp() {
			$db = Loader::db();
			$db->execute("UPDATE sixeightforms SET exportTimestamp = ? WHERE fID=?",array(time(),$this->fID));
		}
		
		public function getRules() {
			$db = Loader::db();
			$rules = $db->getAll("SELECT * FROM sixeightformsRules WHERE fID=? ORDER BY rID",array($this->fID));
			return $rules;
		}
		
		public function clearRules() {
			$db = Loader::db();
			$db->execute("DELETE FROM sixeightformsRules WHERE fID = ?",array($this->fID));
		}
		
		public function updateRuleOptionGroups($oldOGID,$newOGID) {
			$db = Loader::db();
			$db->execute("UPDATE sixeightformsRules SET ogID = ? WHERE ogID = ?",array($newOGID,$oldOGID));
		}
		
		public function getAlternateOptions() {
			$groups = array();
			$i = 0;
			foreach($this->getFields() as $ff) {
				$optionGroups = $ff->getAlternateOptions();
				if(count($optionGroups) > 0) {
					foreach($optionGroups as $og) {
						$groups[$i]['label'] = $ff->shortLabel . ': ' . $og['name'];
						$groups[$i]['ffID'] = $ff->ffID;
						$groups[$i]['ogID'] = $og['ogID'];
						$groups[$i]['options'] = $og['options'];
						$i++;
					}
				}
			}
			return $groups;
		}
		
		public function renderAlternateOptions() {
			foreach($this->getAlternateOptions() as $og) {
				//Render JavaScript variables
				echo 'var optionGroups' . $og['ogID'] . ' = \'';
				$ff = sixeightfield::getByID($og['ffID']);
				$ff->options = $og['options'];
				$ff->render(false);
				echo '\';';
			}
		}
	
	}
?>