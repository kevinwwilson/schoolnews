<?php  
	Loader::model('field','sixeightforms');
	class sixeightAnswerSet extends Object {
	
		public function getByID($asID,$sampleFFID=0) {
			$db = Loader::db();
			$asRow = $db->getRow("SELECT * FROM sixeightformsAnswerSets WHERE recordID = ? AND isDeleted != 1",array($asID));
			
			if(!$asRow) {
				$asRow = $db->getRow("SELECT * FROM sixeightformsAnswerSets WHERE asID = ? AND isDeleted != 1",array($asID));
			}
			
			if(!$asRow) {
				$recordIDrow = $db->getRow("SELECT newASID FROM sixeightformsAnswerSets WHERE asID = ?",array($asID));
				if($recordIDrow) {
					return sixeightAnswerSet::getByID($recordIDrow['newASID']);
				}
			}
			
			if(intval($asRow['asID']) == 0) {
				return false;
			} else {
				$as = new sixeightAnswerSet;
				$as->asID = $asRow['asID'];
				if(intval($asRow['recordID']) == 0) {
					$as->recordID = $asRow['asID'];
				} else {
					$as->recordID = $asRow['recordID'];
				}
				$as->fID = $asRow['fID'];
				$as->dateSubmitted = $asRow['dateSubmitted'];
				$as->dateUpdated = $asRow['dateUpdated'];
				$as->ipAddress = $asRow['ipAddress'];
				$as->creator = $asRow['creator'];
				$as->editCode = $asRow['editCode'];
				$as->amountCharged = $asRow['amountCharged'];
				$as->amountPaid = $asRow['amountPaid'];
				$as->isApproved = $asRow['isApproved'];
				$as->isDeleted = $asRow['isDeleted'];
				$as->expiration = $asRow['expiration'];
				$as->cID = $asRow['cID'];
				$as->searchIndex = $asRow['searchIndex'];
				$as->matchingFilter = $asRow['matchingFilter'];
				$as->gatewayResponse = $asRow['gatewayResponse'];
				
				if($sampleFFID == 0) {
					$as->answers = $as->getAnswers();
				} else {
					$as->sample = $as->getAnswer($sampleFFID);
				}
				return $as;
			}
		}
		
		public function getByCID($cID) {
			$db = Loader::db();
			$asRow = $db->getRow("SELECT asID FROM sixeightformsAnswerSets WHERE cID= ? AND isDeleted != 1 ORDER BY asID DESC",array($cID));
			$as = sixeightAnswerSet::getByID($asRow['asID']);
			return $as;
		}
		
		public function getByUID($fID,$uID) {
			$db = Loader::db();
			$asRow = $db->getRow("SELECT asID FROM sixeightformsAnswerSets WHERE fID = ? AND creator= ? AND isDeleted != 1 ORDER BY asID DESC",array($fID,$uID));
			$as = sixeightAnswerSet::getByID($asRow['asID']);
			return $as;
		}
		
		public function getFromArray($asArray) {
			$as = new sixeightAnswerSet;
			$as->asID = $asArray['asID'];
			$as->recordID = $asArray['recordID'];
			$as->fID = $asArray['fID'];
			$as->dateSubmitted = $asArray['dateSubmitted'];
			$as->dateUpdated = $asArray['dateUpdated'];
			$as->ipAddress = $asArray['ipAddress'];
			$as->creator = $asArray['creator'];
			$as->editCode = $asArray['editCode'];
			$as->amountCharged = $asArray['amountCharged'];
			$as->amountPaid = $asArray['amountPaid'];
			$as->isApproved = $asArray['isApproved'];
			$as->isDeleted = $asArray['isDeleted'];
			$as->expiration = $asArray['expiration'];
			$as->cID = $asArray['cID'];
			$as->searchIndex = $asArray['searchIndex'];
			$as->matchingFilter = $asArray['matchingFilter'];
			$as->gatewayResponse = $asRow['gatewayResponse'];
			$as->answers = $asArray['answers'];
			return $as;
		}
		
		public function getByIDAndEditCode($asID,$editCode) {
			$db = Loader::db();
			$asRow = $db->getRow("SELECT * FROM sixeightformsAnswerSets WHERE asID = ? AND editCode = ? AND isDeleted != 1",array($asID,$editCode));
			$as = sixeightAnswerSet::getByID($asRow['asID']);
			return $as;
		}
		
		public function getPrevious() {
			Loader::model('answer_set_list','sixeightforms');
			$db = Loader::db();
			$next = false;
			$asl = sixeightAnswerSetList::get($this->fID);
			$asl->setPageSize(0);
			foreach($asl->getAnswerSetIDs() as $asID) {
				if($asID == $this->asID) {
					return sixeightAnswerSet::getByID($previousAnswerSetID);
				}
				$previousAnswerSetID = $asID;
			}
		}
		
		public function getNext() {
			Loader::model('answer_set_list','sixeightforms');
			$db = Loader::db();
			$next = false;
			$asl = sixeightAnswerSetList::get($this->fID);
			$asl->setPageSize(0);
			foreach($asl->getAnswerSetIDs() as $asID) {
				if($next) {
					return sixeightAnswerSet::getByID($asID);
				}
				if($asID == $this->asID) {
					$next = true;
				}
			}
		}
		
		public function create($fID,$totalPrice='',$timestamp='',$approvalStatus=0,$cID=0,$recordID=0,$uID=0) {
			$db = Loader::db();
			sixeightform::clearAnswersCache($fID);
			if($timestamp == '') {
				$timestamp = time();
			}
			
			if($uID == 0 ) {
				$u = new User();
				$uID = $u->getUserID();
				if(!isset($uID)) {
					$uID = 0;
				}
			}
			
			$editCode = sixeightAnswerSet::generateEditCode();
			
			$ipAddress = $_SERVER['REMOTE_ADDR'];
			$db->execute("INSERT INTO sixeightformsAnswerSets (asID,fID,dateSubmitted,dateUpdated,ipAddress,creator,editCode,amountCharged,isApproved,cID,recordID) VALUES (0,?,?,?,?,?,?,?,?,?,?)",array($fID,$timestamp,time(),$ipAddress,$uID,$editCode,$totalPrice,$approvalStatus,$cID,$recordID));
			if($recordID == 0) {
				$db->execute("UPDATE sixeightformsAnswerSets SET recordID = ? WHERE asID = ?",array($db->Insert_ID(),$db->Insert_ID()));
			}
			
			$as = sixeightAnswerSet::getByID($db->Insert_ID());
			
			return $as;
		}
		
		public function addAnswer($ffID,$value) {
			$db = Loader::db();
			if(is_array($value)) {
				$value = implode("\r\n",$value);
			}
			$db->execute("INSERT INTO sixeightformsAnswers (aID,asID,ffID,value) VALUES (0,?,?,?)",array($this->asID,$ffID,$value));
			
			$this->answers[$db->Insert_ID()] = $this->getAnswer($ffID);
		}
		
		public function delete($deletePage = FALSE,$force = FALSE,$newASID=0) {
			$db = Loader::db();
			if(($this->userCanDelete()) || ($force)) {
				
				sixeightForm::clearAnswersCache($this->fID);
				$db->execute("UPDATE sixeightformsAnswerSets SET isDeleted = 1, newASID = ? WHERE asID = ?",array($newASID,$this->asID));
				
				if($deletePage) {
					$asc = Page::getByID($this->cID);
					$asc->delete();
				}
				
				return true;
			} else {
				return false;
			}
		}
		
		public function changeApprovalStatus($status='') {
			$db = Loader::db();
			sixeightform::clearAnswersCache($this->fID);
			
			$f = sixeightForm::getByID($this->fID);
			if($f->userCanApprove()) {
				if($status == 'approve') {
					$db->execute("UPDATE sixeightformsAnswerSets SET isApproved = 1 WHERE asID = ?",array($this->asID));
					return 1;
				} elseif($status == 'unapprove') {
					$db->execute("UPDATE sixeightformsAnswerSets SET isApproved = 2 WHERE asID = ?",array($this->asID));
					return 0;
				} elseif($this->isApproved == '1') {
					$db->execute("UPDATE sixeightformsAnswerSets SET isApproved = 2 WHERE asID = ?",array($this->asID));
					return 0;
				} else {
					$db->execute("UPDATE sixeightformsAnswerSets SET isApproved = 1 WHERE asID = ?",array($this->asID));
					return 1;
				}
			} else {
				//User does not have permission to change the approval status
				return intval($this->isApproved);
			}
		}
		
		public function setApprovalStatus($status) {
			$db = Loader::db();
			sixeightform::clearAnswersCache($this->fID);
			
			$f = sixeightForm::getByID($this->fID);
			if($f->userCanApprove()) {
				$db->execute("UPDATE sixeightformsAnswerSets SET isApproved = ? WHERE asID = ?",array(intval($status),$this->asID));
			}
		}
		
		public function updateAmountPaid($amount) {
			$db = Loader::db();
			$db->execute("UPDATE sixeightformsAnswerSets SET amountPaid=? WHERE asID=?",array($amount,$this->asID));
		}
		
		public function setExpiration($timestamp) {
			$db = Loader::db();
			$db->execute("UPDATE sixeightformsAnswerSets SET expiration=? WHERE asID=?",array($timestamp,$this->asID));
			$this->expiration = $timestamp;
		}
		
		public function setCID($cID) {
			$db = Loader::db();
			$db->execute("UPDATE sixeightformsAnswerSets SET cID = ? WHERE asID=?",array($cID,$this->asID));
		}
		
		public function generateEditCode() {
			$db = Loader::db();
			$random = "";
			srand((double)microtime()*1000000);
			$data = "AbcDE123IJKLMN67QRSTUVWXYZ";
			$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
			$data .= "0FGH45OP89";
			for($i = 0; $i < 50; $i++) {
				$random .= substr($data, (rand()%(strlen($data))), 1);
			}
			
			$existingCodes = $db->getAll("SELECT count('editCode') as count from sixeightformsAnswerSets WHERE editCode = ?",array($random));
			
			if($existingCodes['count'] > 0) {
				$random = sixeightform::generateEditCode();
			}
			return $random;
		}
		
		public function getAnswers() {
			$db = Loader::db();
			$answers = $db->getAll("SELECT * FROM sixeightformsAnswers WHERE asID = ?",array($this->asID));
			$answersByID = array();
			foreach($answers as $a) {
				$field = sixeightfield::getByID($a['ffID']);
				if(($field->type == 'File Upload') || ($field->type == 'File from File Manager')) {
					$file=File::getByID($a['value']);
					if(($file) && (is_numeric($a['value']))) {
						$fv=$file->getApprovedVersion();
						$a['shortValue'] = $fv->getFileName();
					} else {
						$a['shortValue'] = '';
					} 
				}
				$a['label'] = $field->label;
				$a['shortValue'] = sixeightAnswerSet::shortenText(strip_tags($a['value']),50);
				$answersByID[$a['ffID']] = $a;
			}
			return $answersByID;
		}
		
		public function getCommerceAnswers() {
			$db = Loader::db();
			$f = sixeightform::getByID($this->fID);
			$fields = $f->getFields();
			$answers = array();
			foreach($fields as $field) {
				if($field->eCommerceName != '') {
					$answers[$field->eCommerceName] = $this->answers[$field->ffID];
				}
			}
			return $answers;
		}
		
		public function sortAnswers($a, $b) {
			return strcasecmp($a['sortValue'],$b['sortValue']);
		}
		
		public function getAnswer($identifier) {
			$db = Loader::db();
			if(is_int($identifier)) {
				return $db->getRow("SELECT aID, value FROM sixeightformsAnswers WHERE asID=? AND ffID=?",array($this->asID,$identifier));
			} else {
				return $this->getAnswerByHandle($identifier);
			}
		}
		
		public function getAnswerByLabel($label) {
			foreach($this->answers as $answer) {
				if($answer['label'] == $label) {
					return $answer['value'];
				}
			}
		}
		
		public function getAnswerByHandle($handle) {
			foreach($this->answers as $answer) {
				if($answer['handle'] == $handle) {
					return $answer['value'];
				}
			}
		}
		
		function sendApprovalNotification() {
			if($asUI = UserInfo::getByID($this->creator)) {
				$to = $asUI->getUserEmail();
				$f = sixeightform::getByID($this->fID);
				$from = $f->properties['mailFrom'];
				$fromAddress = $f->properties['mailFromAddress'];
				$subject = 'Approval Notification';
				$body = "The request you submitted on " . date('F j, Y',$this->dateSubmitted) . " at " . date('g:i a',$this->dateSubmitted) . " has been approved.\n\n";
				foreach($this->answers as $a) { 
					$field = sixeightField::getByID($a['ffID']);
					$body .= $field->shortLabel . "\n" .  $a['value'] . "\n\n";
				}	
				sixeightForm::sendMail($to,$from,$fromAddress,$subject,$body);
			}
		}
		
		public function shortenText($strString, $nLength = 15, $strTrailing = "...") {
			$nLength -= strlen($strTrailing);
			if (strlen($strString) > $nLength) {
				return substr($strString, 0, $nLength) . $strTrailing;
			} else {
				return $strString;
			}
		}
		
		public function createPage() {
			$th = Loader::helper('text');
			Loader::model('collection_types');
			
			$asID = $this->asID;
			$form = sixeightForm::getByID($this->fID);
			
			//Create the Page Type to use for the new page
			$asCT = CollectionType::getByID($form->properties['ctID']);
			
			//Setup attributes for new page
			$asCData = array();
			$asCData['cName'] = $this->answers[$form->properties['cName']]['value']; //Page name
			
			
			if(intval($form->properties['cHandle']) == 0) { //If alias is not set in form settings, use Name as alias
				$asCData['cHandle'] = $th->sanitizeFileSystem($asCData['cName']);
			} else {
				$handle = $th->sanitizeFileSystem($this->answers[$form->properties['cHandle']]['value']);
				if($handle == '') { //If the form value for the handle is blank, use Name 
					$asCData['cHandle'] = $th->sanitizeFileSystem($asCData['cName']);
				} else {
					$asCData['cHandle'] = $handle;
				}
			}
			
			$parentPage = Page::getByID($form->properties['parentCID']);
			
			//Create the actual page
			$asc = $parentPage->add($asCT,$asCData);
			
			//Set Page Description
			$cDescription = $this->answers[$form->properties['cDescription']]['value'];
			if($cDescription != '') {
				$asc->update(array('cDescription'=>$cDescription));
			}
			
			//Set Meta Title
			$meta_title = $this->answers[$form->properties['meta_title']]['value'];
			if($meta_title != '') {
				$asc->setAttribute('meta_title',$meta_title);
			}
			
			//Set Meta Keywords
			$meta_keywords = $this->answers[$form->properties['meta_keywords']]['value'];
			if($meta_keywords != '') {
				$asc->setAttribute('meta_keywords',$meta_keywords);
			}
			
			//Set Meta Description
			$meta_description = $this->answers[$form->properties['meta_description']]['value'];
			if($meta_description != '') {
				$asc->setAttribute('meta_description',$meta_description);
			}
			
			//Set Exclude Nav
			if($form->properties['exclude_nav'] == 1) {
				$asc->setAttribute('exclude_nav',1);
			}
			
			//Set the cID for the answer set
			$this->setAnswerSetCID($asc->getCollectionID());
					
			//Add block to Main area on page
			$bt = BlockType::getByHandle('sixeightdatadisplaydetail');
			$data = array('fID'=>$form->properties['fID'],'detailTemplateID'=>$form->properties['detailTemplateID'],'method'=>'inherit');
			$asc->addBlock($bt,'Main',$data);
			
			$form->clearAnswersCache($form->properties['fID']);
		}
		
		public function setAnswerSetCID($cID) {
			$db = Loader::db();
			$db->execute("UPDATE sixeightformsAnswerSets SET cID = ? WHERE asID = ?",array($cID,$this->asID));
		}
		
		public function userCanEdit() {
			$u = new User();
			
			if($u->isSuperUser()) { //Super user can always edit
				return true;
			}
			
			//Check whether users can either their own records
			$f = sixeightForm::getByID(intval($this->fID));
			if($f->ownerCanEdit()) {
				if($u->isRegistered() === false) { //Guest user cannot own records, so it can't edit a specific record
					return false;
				} else {
					if(intval($u->getUserID()) == intval($this->creator)) { //If the user owns the record, they can edit it
						return true;
					}
				}
			} else { //If users cannot edit their own records, they must be part of a group that can edit records
				foreach($u->uGroups as $gID => $gName) { //Loop through the groups
					if($f->groupCanEditRecords($gID)) { //If user is part of a group that can edit, they can edit
						return true;
					}
				}
			}
			
			return false; //Deny access by default
		}
		
		public function userCanDelete() {
			$u = new User();
			
			if($u->isSuperUser()) { //Super user can always delete
				return true;
			}
			
			//Check whether users can delete their own records
			$f = sixeightForm::getByID(intval($this->fID));
			if($f->ownerCanDelete()) {
				if($u->isRegistered() === false) { //Guest user cannot own records, so it can't delete a specific record
					return false;
				} else {
					if(intval($u->getUserID()) == intval($this->creator)) { //If the user owns the record, they can delete it
						return true;
					}
				}
			} else { //If users cannot delete their own records, they must be part of a group that can delete records
				foreach($u->uGroups as $gID => $gName) { //Loop through the groups
					if($f->groupCanDeleteRecords($gID)) { //If user is part of a group that can delete, they can edit
						return true;
					}
				}
			}
			
			return false; //Deny access by default
		}
		
		public function logGatewayResponse($response) {
			$db = Loader::db();
			$db->execute("UPDATE sixeightformsAnswerSets SET gatewayResponse = ? WHERE asID = ?",array($response,$this->asID));
		}
		
		public function setOwner($uID) {
			$db = Loader::db();
			$db->execute("UPDATE sixeightformsAnswerSets SET creator = ? WHERE asID = ?",array($uID,$this->asID));
			sixeightform::clearAnswersCache($this->fID);
		}
		
		public function getOwnerUserName() {
			$uID = $this->creator;
			$u = User::getByUserID($uID);
			return $u->getUserName();
		}
		
		public function sendNotification($to) {
		
			$f = sixeightform::getByID($this->fID);
			$fields = $f->getFields();
			
			$emailData = '';
					
			if($f->properties['sendData']) {
				foreach($fields as $field) {
					if(!$field->excludeFromEmail) {
						if($field->type == 'Credit Card') {
							$emailData .= $field->label . "\nXXXX\n\n";
						} elseif(($field->type == 'File Upload') || ($field->type == 'File from File Manager')) {
							$file = File::getByID($this->answers[$field->ffID]['value']);
							$fv = $file->getApprovedVersion();
							$emailData .= strip_tags($field->label) . "\n";
							$emailData .= BASE_URL . $fv->getRelativePath() . "\n\n";
						} else {
							$emailData .= strip_tags($field->label) . "\n";
							$emailData .= strip_tags($this->answers[$field->ffID]['value']) . "\n\n";
						}
					}
				}
			} else {
				$emailData = t('The form "' . $f->properties['name'] . '" has received a response.');
			}
			
			$toAddress = trim($to);
			
			if(is_numeric($f->properties['mailFrom'])) {
				$fromName = $this->answers[$f->properties['mailFrom']]['value'];
			} else {
				$fromName = $f->properties['mailFrom'];
			}
			
			if(is_numeric($f->properties['mailFromAddress'])) {
				$fromAddress = $this->answers[$f->properties['mailFromAddress']]['value'];
			} else {
				$fromAddress = $f->properties['mailFromAddress'];
			}
			
			sixeightform::sendMail($toAddress,$fromName,$fromAddress,$f->properties['mailSubject'],$emailData );
		}
		
		public function sendConfirmation() {
			$f = sixeightform::getByID($this->fID);
			if(Package::getByHandle('sixeightdatadisplay')) {
				$ddBT = BlockType::getByHandle('sixeightdatadisplay');
				$body = $ddBT->controller->generateTemplateContent($f->properties['confirmationEmail'],$f->getFields(),$this);
				$body = str_replace('<?php  xml version="1.0"?>','',$body);
				$body = str_replace('<?php xml version="1.0"?>','',$body);
				$body = str_replace('<root>','',$body);
				$body = str_replace('</root>','',$body);
				$body = str_replace('<root/>','',$body);
				$body = str_replace('<root />','',$body);
			} else {
				$body = $f->properties['confirmationEmail'];
			}
			$toAddress = trim($this->answers[$f->properties['confirmationField']]['value']);
			sixeightForm::sendMail($toAddress,$f->properties['confirmationFrom'],$f->properties['confirmationFromAddress'],$f->properties['confirmationSubject'],$body);
		}	
	}
?>