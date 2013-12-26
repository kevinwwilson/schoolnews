<?php   

defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');
Loader::model('answer_set_list','sixeightforms');

class DashboardSixeightformsFormsController extends Controller {
	
	function view() {
		$forms = sixeightForm::getAll();
		$this->set('forms',$forms);
		$this->loadHeaderItems();
	}
	
	function on_start() {
		$this->addHeaderItem(Loader::helper('html')->css('forms.css', 'sixeightforms'));
	}
	
	function results() {
	
		if($_GET['index'] == 1) {
			$f = sixeightForm::getByID(intval($_GET['fID']));
			$f->indexAnswerSets();
			$this->redirect('/dashboard/sixeightforms/forms','results/?fID=' . intval($_GET['fID']) . '&indexSuccess=1');
		}
		
		if($_GET['clearCache'] == 1) {
			$f = sixeightForm::getByID(intval($_GET['fID']));
			$f->clearAnswersCache();
			$this->redirect('/dashboard/sixeightforms/forms','results/?fID=' . intval($_GET['fID']) . '&clearCacheSuccess=1');
		}
		
		if($_GET['clearCacheSuccess'] == 1) {
			$this->set('message', t('The cache has been emptied.'));
		}
	
		if($_GET['pageSize'] != '') {
			$pageSize = intval($_GET['pageSize']);
			setcookie('semPageSize',$pageSize);
		} elseif(isset($_COOKIE['pageSize'])) {
			$pageSize = $_COOKIE['pageSize'];
		} else {
			$pageSize = 25;
		}
		
		if(intval($_GET['pageNum']) == 0) {
			$pageNum = 1;
		} else {
			$pageNum = intval($_GET['pageNum']);
		}
		
		
		if($_GET['sortOrder'] == 'ASC') {
			$sortOrder = 'ASC';
			$this->set('newSortOrder','DESC');
			$this->set('sortOrder','ASC');
		} else {
			$sortOrder = 'DESC';
			$this->set('newSortOrder','ASC');
			$this->set('sortOrder','DESC');
		}
		
		$this->loadHeaderItems();
		
		$f = sixeightForm::getByID(intval($_GET['fID']));
		$fields = $f->getFields();
		
		if($_GET['indexSuccess'] == 1) {
			$msg = t('The following fields have been indexed:');
			$iFieldCount = 0;
			foreach($fields as $ff) {
				if($ff->indexable) {
					if($iFieldCount > 0) {
						$msg .= ', ';
					}
					$msg .= $ff->shortLabel;
					$iFieldCount++;
				}
			}
			
			if($iFieldCount > 0) {
				$this->set('message',$msg);
			} else {
				$this->set('message',t('Error: No fields are set to searchable.  Check field settings.'));
			}
		}
		
		if(intval($_GET['sortffID']) == 0) {
			$this->set('sortffID',current($fields)->ffID);
		} else {
			$this->set('sortffID',intval($_GET['sortffID']));
		}
		
		$indexedFields = array();
		foreach($fields as $field) {
			$indexedFields[] = $field;
		}
		$this->set('f',$f);
		$this->set('fields',$fields);
		$this->set('indexedFields',$indexedFields);
		$this->set('action','results');
		$asl = sixeightAnswerSetList::get($f->fID);
		$asl->setSortOrder('DESC');
		$asl->setPageNum($pageNum);
		$asl->setPageSize($pageSize);
		$asl->includeExpired();
		if($_GET['q'] != '') {
			$asl->setSearchQuery($_GET['q']);
		}
		$answerSets = $asl->getAnswerSets();
		
		$numPages = intval($f->getTotalAnswerSetCount() / $pageSize) + 1;
		$this->set('numPages',$numPages);
		$this->set('pageSize',$pageSize);
		$this->set('answerSets',$answerSets);
		if($_GET['sortOrder'] == 'DESC') {
			$this->set('arrowDirection','down');
		} else {
			$this->set('arrowDirection','up');
		}
	}
	
	public function createForm() {
		$data = array();
		
		if($_POST['doNotSend'] == 1) {
			$sendMail = 0;
		} else {
			$sendMail = 1;
		}
		
		$data['name'] = $_POST['name'];
		$data['sendMail'] = $sendMail;
		$data['sendData'] = $_POST['sendData'];
		$data['mailFrom'] = $_POST['mailFrom'];
		$data['mailFromAddress'] = $_POST['mailFromAddress'];
		$data['mailTo'] = $_POST['mailTo'];
		$data['mailCc'] = $_POST['mailCc'];
		$data['mailBcc'] = $_POST['mailBcc'];
		$data['mailSubject'] = $_POST['mailSubject'];
		$data['afterSubmit'] = $_POST['afterSubmit'];
		$data['thankyouCID'] = $_POST['thankyouCID'];
		$data['thankyouMsg'] = $_POST['thankyouMsg'];
		$data['thankyouURL'] = $_POST['thankyouURL'];
		$data['captcha'] = $_POST['captcha'];
		$data['submitLabel'] = $_POST['submitLabel'];
		$data['maxSubmissions'] = $_POST['maxSubmissions'];
		$data['fieldLabelLocation'] = $_POST['fieldLabelLocation'];
		$data['sendApprovalNotification'] = $_POST['sendApprovalNotification'];
		$data['requiredIndicator'] = $_POST['requiredIndicator'];
		$data['requiredColor'] = $_POST['requiredColor'];
		$data['gateway'] = $_POST['gateway'];
		$data['currencySymbol'] = $_POST['currencySymbol'];
		$data['ecommerceConfirmation'] = $_POST['ecommerceConfirmation'];
		$data['defaultAnswerSetStatus'] = $_POST['defaultAnswerSetStatus'];
		$data['autoCreatePage'] = $_POST['autoCreatePage'];
		$data['parentCID'] = $_POST['parentCID'];
		$data['ctID'] = $_POST['ctID'];
		$data['cName'] = $_POST['cName'];
		$data['cHandle'] = $_POST['cHandle'];
		$data['cDescription'] = $_POST['cDescription'];
		$data['meta_title'] = $_POST['meta_title'];
		$data['meta_description'] = $_POST['meta_description'];
		$data['meta_keywords'] = $_POST['meta_keywords'];
		$data['exclude_nav'] = $_POST['exclude_nav'];
		$data['detailTemplateID'] = $_POST['detailTemplateID'];
		$data['disableCache'] = $_POST['disableCache'];
		$data['maximumPrice'] = $_POST['maximumPrice'];
		$data['autoIndex'] = $_POST['autoIndex'];
		$data['useHTML5'] = $_POST['useHTML5'];
		$data['passRecordID'] = $_POST['passRecordID'];
		$data['recordIDParameter'] = $_POST['recordIDParameter'];
		if($f = sixeightform::create($data)) {
			$this->redirect('/dashboard/sixeightforms/forms','createFormSuccess?newfID=' . $f->fID);
		}
	}
	
	public function createFormSuccess() {
		$fieldsUrl = View::url("dashboard/sixeightforms/forms", "manageFields?fID=" . intval($_GET['newfID'])); 
		$this->set('message', t('The form has been created. Click Settings for full configuration options.'));
		$this->view();
	}

	public function updateForm() {
		$data['name'] = $_POST['name'];
		$data['handle'] = $_POST['handle'];
		$data['sendMail'] = $_POST['sendMail'];
		$data['sendData'] = $_POST['sendData'];
		if($_POST['mailFromType'] == 'dynamic') {
			$data['mailFrom'] = intval($_POST['dynamicMailFrom']);
		} else {
			$data['mailFrom'] = $_POST['mailFrom'];
		}
		if($_POST['mailFromAddressType'] == 'dynamic') {
			$data['mailFromAddress'] = intval($_POST['dynamicMailFromAddress']);
		} else {
			$data['mailFromAddress'] = $_POST['mailFromAddress'];
		}
		$data['mailTo'] = $_POST['mailTo'];
		$data['mailCc'] = $_POST['mailCc'];
		$data['mailBcc'] = $_POST['mailBcc'];
		$data['mailSubject'] = $_POST['mailSubject'];
		$data['afterSubmit'] = $_POST['afterSubmit'];
		$data['thankyouCID'] = $_POST['thankyouCID'];
		$data['thankyouMsg'] = $_POST['thankyouMsg'];
		$data['thankyouURL'] = $_POST['thankyouURL'];
		$data['captcha'] = $_POST['captcha'];
		$data['submitLabel'] = $_POST['submitLabel'];
		$data['maxSubmissions'] = $_POST['maxSubmissions'];
		$data['fieldLabelLocation'] = $_POST['fieldLabelLocation'];
		$data['sendApprovalNotification'] = $_POST['sendApprovalNotification'];
		$data['requiredIndicator'] = $_POST['requiredIndicator'];
		$data['requiredColor'] = $_POST['requiredColor'];
		$data['gateway'] = $_POST['gateway'];
		$data['currencySymbol'] = $_POST['currencySymbol'];
		$data['ecommerceConfirmation'] = $_POST['ecommerceConfirmation'];
		$data['defaultAnswerSetStatus'] = $_POST['defaultAnswerSetStatus'];
		$data['autoCreatePage'] = $_POST['autoCreatePage'];
		$data['parentCID'] = $_POST['parentCID'];
		$data['ctID'] = $_POST['ctID'];
		$data['cName'] = $_POST['cName'];
		$data['cHandle'] = $_POST['cHandle'];
		$data['cDescription'] = $_POST['cDescription'];
		$data['meta_title'] = $_POST['meta_title'];
		$data['meta_description'] = $_POST['meta_description'];
		$data['meta_keywords'] = $_POST['meta_keywords'];
		$data['exclude_nav'] = $_POST['exclude_nav'];
		$data['detailTemplateID'] = $_POST['detailTemplateID'];
		$data['disableCache'] = $_POST['disableCache'];
		$data['maximumPrice'] = $_POST['maximumPrice'];
		$data['autoIndex'] = $_POST['autoIndex'];
		$data['useHTML5'] = $_POST['useHTML5'];
		$data['passRecordID'] = $_POST['passRecordID'];
		$data['recordIDParameter'] = $_POST['recordIDParameter'];
		$data['autoExpire'] = $_POST['autoExpire'];
		$data['fID'] = $_POST['fID'];
		$f = sixeightForm::getByID(intval($_POST['fID']));
		$f->update($data);
		$this->redirect('/dashboard/sixeightforms/forms','updateFormSuccess');
	}
	
	public function updateFormSuccess() {
		$this->set('message', t('The form has been updated.'));
		$this->view();	
	}
	
	public function duplicateForm() {
		$f = sixeightForm::getByID(intval($_GET['fID']));
		$f->duplicate();
		$this->redirect('/dashboard/sixeightforms/forms');
	}
	
	public function manageFields() {
		$this->loadHeaderItems();
		$f = sixeightForm::getByID(intval($_GET['fID']));
		$fields = $f->getFields();
		$this->set('f',$f);
		$this->set('fields',$fields);
		$html = Loader::helper('html');
	}
	
	public function deleteForm() {
		$f = sixeightForm::getByID(intval($_GET['fID']));
		$f->delete();
		$this->redirect('/dashboard/sixeightforms/forms','deleteFormSuccess');
	}
	
	public function deleteFormSuccess() {
		$this->set('message', t('The form has been deleted.'));
		$this->view();
	}
	
	public function addField() {
		$data = array();
		$data['fID'] = $_POST['fID'];
		$data['label'] = str_replace("\r\n","",nl2br($_POST['label']));
		$data['text'] = str_replace("\r\n","",nl2br($_POST['text']));
		$data['type'] = $_POST['type'];
		$data['defaultValue'] = $_POST['defaultValue'];
		$data['width'] = $_POST['width'];
		$data['height'] = $_POST['height'];
		$data['maxLength'] = $_POST['maxLength'];
		$data['layout'] = $_POST['layout'];
		$data['format'] = $_POST['format'];
		$data['toolbar'] = $_POST['toolbar'];
		$data['groupWithPrevious'] = $_POST['groupWithPrevious'];
		$data['required'] = $_POST['required'];
		$data['price'] = $_POST['price'];
		$data['qtyStart'] = $_POST['qtyStart'];
		$data['qtyEnd'] = $_POST['qtyEnd'];
		$data['qtyIncrement'] = $_POST['qtyIncrement'];
		$data['eCommerceName'] = $_POST['eCommerceName'];
		$data['handle'] = $_POST['handle'];
		$data['isExpirationField'] = $_POST['isExpirationField'];
		$data['dateFormat'] = $_POST['dateFormat'];
		$data['indexable'] = $_POST['indexable'];
		$data['requireUnique'] = $_POST['requireUnique'];
		$data['urlParameter'] = $_POST['urlParameter'];
		$data['populateWith'] = $_POST['populateWith'];
		$data['cssClass'] = $_POST['cssClass'];
		$data['containerCssClass'] = $_POST['containerCssClass'];
		$data['minYear'] = $_POST['minYear'];
		$data['maxYear'] = $_POST['maxYear'];
		$data['validateSection'] = $_POST['validateSection'];
		$data['fsID'] = $_POST['fsID'];
		
		$options = explode("\n",$_POST['options']);
		$f = sixeightForm::getByID(intval($_POST['fID']));
		$f->addField($data,$options);
		$this->redirect('/dashboard/sixeightforms/forms','addFieldSuccess?fID=' . intval($_POST['fID']));
	}
	
	public function addFieldSuccess() {
		$this->set('message', t('Field added.'));
		$this->manageFields();
	}
	
	public function duplicateField() {
		$field = sixeightField::getByID(intval($_GET['ffID']));
		$field->duplicate();
		$this->redirect('/dashboard/sixeightforms/forms','manageFields?fID=' . intval($_GET['fID']));
	}
	
	public function deleteField() {
		$field = sixeightField::getByID(intval($_GET['ffID']));
		$field->delete();
		$this->redirect('/dashboard/sixeightforms/forms','manageFields?fID=' . intval($_GET['fID']));
	}
	
	public function updateField() {
		$data['label'] = $_POST['label'];
		$data['text'] = $_POST['text'];
		$data['type'] = $_POST['type'];
		$data['defaultValue'] = $_POST['defaultValue'];
		$data['width'] = $_POST['width'];
		$data['height'] = $_POST['height'];
		$data['maxLength'] = $_POST['maxLength'];
		$data['layout'] = $_POST['layout'];
		$data['format'] = $_POST['format'];
		$data['toolbar'] = $_POST['toolbar'];
		$data['groupWithPrevious'] = $_POST['groupWithPrevious'];
		$data['required'] = $_POST['required'];
		$data['price'] = $_POST['price'];
		$data['qtyStart'] = $_POST['qtyStart'];
		$data['qtyEnd'] = $_POST['qtyEnd'];
		$data['qtyIncrement'] = $_POST['qtyIncrement'];
		$data['eCommerceName'] = $_POST['eCommerceName'];
		$data['handle'] = $_POST['handle'];
		$data['isExpirationField'] = $_POST['isExpirationField'];
		$data['dateFormat'] = $_POST['dateFormat'];
		$data['indexable'] = $_POST['indexable'];
		$data['requireUnique'] = $_POST['requireUnique'];
		$data['urlParameter'] = $_POST['urlParameter'];
		$data['populateWith'] = $_POST['populateWith'];
		$data['cssClass'] = $_POST['cssClass'];
		$data['containerCssClass'] = $_POST['containerCssClass'];
		$data['minYear'] = $_POST['minYear'];
		$data['maxYear'] = $_POST['maxYear'];
		$data['validateSection'] = $_POST['validateSection'];
		$data['fsID'] = $_POST['fsID'];
		$data['ffID'] = $_POST['ffID'];
		
		$options = explode("\n",$_POST['options']);
		$field = sixeightField::getByID(intval($_POST['ffID']));
		$field->update($data,$options);
		
		$this->redirect('/dashboard/sixeightforms/forms','manageFields/?fID=' . intval($_POST['fID']));
	}
	
	public function saveConfirmation() {
		$f = sixeightForm::getByID(intval($_POST['fID']));
		$f->saveConfirmation($_POST['ffID'],$_POST['confirmationFrom'],$_POST['confirmationFromAddress'],$_POST['confirmationSubject'],$_POST['message']);
		$this->redirect('/dashboard/sixeightforms/forms');
	}
	
	public function saveNotifications() {
		$f = sixeightForm::getByID(intval($_POST['fID']));
		$f->clearNotifications();
		$i = 0;
		if(is_array($_POST['ffID'])) {
			foreach($_POST['ffID'] as $ffID) {
				$f->saveNotification($_POST['ffID'][$i],$_POST['conditionType'][$i],$_POST['value'][$i],$_POST['sendData'][$i],$_POST['email'][$i]);
				$i++;
			}
		}
		$this->redirect('/dashboard/sixeightforms/forms');
	}
	
	public function saveRules() {
		$f = sixeightform::getByID(intval($_POST['fID']));
		$f->clearRules();
		$i = 0;
		if(is_array($_POST['ffID'])) {
			foreach($_POST['ffID'] as $ffID) {
				$field = sixeightField::getByID(intval($ffID));
				$comparison = $_POST['comparison'][$i];
				$action = 'show';
				$actionField = explode(':',$_POST['actionField'][$i]);
				$field->addRule($comparison,$_POST['value'][$i],$action,intval($actionField[0]),intval($actionField[1]));
				$i++;
			}
		}
		$this->redirect('/dashboard/sixeightforms/forms','saveRulesSuccess');
	}
	
	public function saveRulesSuccess() {
		$this->set('message',t('Rules have been saved.'));
		$this->view();
	}
	
	public function deleteAnswerSet() {
		$as = sixeightAnswerSet::getByID(intval($_GET['asID']));
		$as->delete();
		$this->redirect('/dashboard/sixeightforms/forms','results/?fID=' . intval($_GET['fID']));
	}
	
	public function processAnswerSets() {
		if($_POST['action'] == 'delete') {
			if($_POST['range'] == 'selected') {
				if(is_array($_POST['sem-as'])) {
					foreach($_POST['sem-as'] as $asID) {
						$as = sixeightAnswerSet::getByID($asID);
						$as->delete();
					}
				}
			} else {
				$f = sixeightForm::getByID(intval($_POST['fID']));
				$f->deleteAllAnswerSets();
			}
			$this->redirect('/dashboard/sixeightforms/forms','results/?fID=' . intval($_GET['fID']));
		}
		
		if($_POST['action'] == 'approve') {
			if($_POST['range'] == 'selected') {
				if(is_array($_POST['sem-as'])) {
					foreach($_POST['sem-as'] as $asID) {
						$as = sixeightAnswerSet::getByID($asID);
						$as->changeApprovalStatus('approve');
					}
				}
			} else {
				$f = sixeightForm::getByID(intval($_POST['fID']));
				$asl = sixeightAnswerSetList::get(intval($_POST['fID']));
				$answerSets = $asl->getAnswerSets();
				if(is_array($answerSets)) {
					foreach($answerSets as $as) {
						$as->changeApprovalStatus('approve');
					}
				}
			}
			$this->redirect('/dashboard/sixeightforms/forms','results/?fID=' . intval($_GET['fID']));
		}
		
		if($_POST['action'] == 'export') {
			$uh = Loader::helper('concrete/urls');
			header('location:' . BASE_URL . $uh->getToolsURL('export_data','sixeightforms') . '?fID=' . $_POST['fID']);
			return false;
		}
		
		if($_POST['action'] == 'summary') {
			$uh = Loader::helper('concrete/urls');
			header('location:' . BASE_URL . $uh->getToolsURL('export_summary','sixeightforms') . '?fID=' . $_POST['fID']);
			return false;
		}
		
		//$this->redirect('/dashboard/sixeightforms/forms','results/?fID=' . $_POST['fID']);
	}
	
	public function processFields() {
		if($_POST['action'] == 'delete') {
			if(is_array($_POST['sem-ff'])) {
				foreach($_POST['sem-ff'] as $ffID) {
					$field = sixeightField::getByID($ffID);
					$field->delete();
				}
			}
		}
		
		if($_POST['action'] == 'duplicate') {
			if(is_array($_POST['sem-ff'])) {
				foreach($_POST['sem-ff'] as $ffID) {
					$field = sixeightField::getByID($ffID);
					$field->duplicate();
				}
			}
		}
		
		$this->redirect('/dashboard/sixeightforms/forms','manageFields/?fID=' . $_POST['fID']);
	}
	
	public function loadHeaderItems() {
		$html = Loader::helper('html');
		$uh = Loader::helper('concrete/urls');
		$this->addHeaderItem($html->css($uh->getToolsURL('css?sID=' . $this->sID,'sixeightforms'),'sixeightforms'));
		$this->addHeaderItem($html->css('ccm.base.css'));
		$this->addHeaderItem('<script type="text/javascript" src="' . REL_DIR_FILES_TOOLS_REQUIRED . '/i18n_js"></script>'); 
		$this->addHeaderItem($html->javascript('jquery.ui.js'));
		$this->addHeaderItem($html->javascript('ccm.base.js'));
		$this->addHeaderItem($html->javascript('ccm.ui.js'));
		$this->addHeaderItem($html->javascript('jquery.form.js'));
		$this->addHeaderItem($html->javascript('ccm.dialog.js'));
		$this->addHeaderItem($html->javascript('ccm.filemanager.js'));
		$this->addHeaderItem($html->javascript('ccm.search.js'));
		$this->addHeaderItem($html->javascript('jquery.colorpicker.js'));
		$this->addHeaderItem($html->javascript('tiny_mce/tiny_mce.js'));
		$this->addHeaderItem($html->css('ccm.menus.css'));
		$this->addHeaderItem($html->css('ccm.forms.css')); 
		$this->addHeaderItem($html->css('jquery.ui.css'));
		$this->addHeaderItem($html->css('ccm.dialog.css'));
		$this->addHeaderItem($html->css('ccm.calendar.css')); 
		$this->addHeaderItem($html->css('ccm.filemanager.css'));
		$this->addHeaderItem($html->css('ccm.search.css'));
		$this->addHeaderItem($html->css('ccm.colorpicker.css'));
		$this->addHeaderItem($html->javascript('swfobject.js'));
		$this->addHeaderItem($html->javascript('jquery.maxlength.js','sixeightforms'));
		$this->addHeaderItem($html->javascript('jquery.uploadify.js','sixeightforms'));
		$this->addHeaderItem($html->javascript('jquery.tablednd.js', 'sixeightforms'));
	}
	
	public function savePermissions() {
		$f = sixeightForm::getByID(intval($_POST['fID']));
		$f->resetPermissions();
		
		$permissions = array();
		if(count($_POST['addRecords']) > 0) {
			foreach($_POST['addRecords'] as $gID => $val) {
				$permissions[$gID][] = 'addRecords';
			}
		}
		
		if(count($_POST['editRecords']) > 0) {
			foreach($_POST['editRecords'] as $gID => $val) {
				$permissions[$gID][] = 'editRecords';
			}
		}
		
		if(count($_POST['deleteRecords']) > 0) {
			foreach($_POST['deleteRecords'] as $gID => $val) {
				$permissions[$gID][] = 'deleteRecords';
			}
		}
		
		if(count($_POST['approveRecords']) > 0) {
			foreach($_POST['approveRecords'] as $gID => $val) {
				$permissions[$gID][] = 'approveRecords';
			}
		}
		
		if(count($permissions) > 0) {
			foreach($permissions as $gID => $gPermissions) {
				$f->setPermissions($gID,$gPermissions);
			}
		}
		
		if(intval($_POST['ownerCanEdit']) == 1) {
			$f->setOwnerCanEdit(true);
		} else {
			$f->setOwnerCanEdit(false);
		}
		
		if(intval($_POST['ownerCanDelete']) == 1) {
			$f->setOwnerCanDelete(true);
		} else {
			$f->setOwnerCanDelete(false);
		}
		
		if(intval($_POST['oneRecordPerUser']) == 1) {
			$f->setOneRecordPerUser(true);
		} else {
			$f->setOneRecordPerUser(false);
		}
		
		$this->redirect('/dashboard/sixeightforms/forms');
	}
	
	public function savePermissionsSuccess() {
		$this->set('message',t('Permissions have been saved.'));
		$this->view();
	}
	
	public function export() {
		$this->set('action','export');
		$this->loadHeaderItems();
		$f = sixeightForm::getByID(intval($_GET['fID']));
		$this->set('f',$f);
		
		if($_GET['action'] == 'export') {
			$uh = Loader::helper('concrete/urls');
			$ffIDs = '';
			if(count($_GET['ffID']) > 0) {
				$ffIDs = '&ffIDs=';
				foreach($_GET['ffID'] as $ffID) {
					$ffIDs .= $ffID . ',';
				}
			}
			header('location:' . BASE_URL . $uh->getToolsURL('export_data','sixeightforms') . '?fID=' . $_GET['fID'] . '&requireApproval=' . $_GET['requireApproval'] . '&dateRange=' . $_GET['dateRange'] . '&startDate=' . $_GET['startDate'] . '&endDate=' . $_GET['endDate'] . $ffIDs);
		}
	}
	
	public function saveAlternateOptions() {
		$f = sixeightform::getByID(intval($_POST['fID']));
		$ff = sixeightField::getByID(intval($_POST['ffID']));
		$ff->clearAlternateOptions();
		$i = 0;
		if(is_array($_POST['name'])) {
			foreach($_POST['name'] as $ogName) {
				$options = explode("\n",$_POST['options'][$i]);
				$ogID = $ff->addAlternateOptionGroup($ogName,$options);
				$f->updateRuleOptionGroups($_POST['ogID'][$i],$ogID);
				$i++;
			}
		}
		$this->redirect('/dashboard/sixeightforms/forms','manageFields?fID=' . intval($_POST['fID']));
	}
}

?>