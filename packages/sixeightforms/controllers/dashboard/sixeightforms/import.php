<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');
Loader::block('form');

class DashboardSixeightformsImportController extends Controller {
	
	function view() {
		$forms = sixeightForm::getAll();
		$this->set('forms',$forms);
		
		$db = Loader::db();
		$tempMiniSurvey = new MiniSurvey();
		
		//load surveys
		$surveysRS=FormBlockStatistics::loadSurveys($tempMiniSurvey);
		
		//index surveys by question set id
		$surveys=array();
		while($survey=$surveysRS->fetchRow()){
			//get Survey Answers
			$survey['answerSetCount'] = MiniSurvey::getAnswerCount( $survey['questionSetId'] );
			$surveys[ $survey['questionSetId'] ] = $survey;			
		}
		$this->set('surveys',$surveys);
	}
	
	public function startImport() {
		Loader::library('file/importer');
		$fi = new FileImporter();
		$resp = $fi->import($_FILES['data_file']['tmp_name'], $_FILES['data_file']['name'], $fr);

		if (!($resp instanceof FileVersion)) {
			switch($resp) {
				case FileImporter::E_FILE_INVALID_EXTENSION:
					$this->set('error', array(t('Invalid file extension.')));
					break;
				case FileImporter::E_FILE_INVALID:
					$this->set('error', array(t('Invalid file.')));
					break;
			}
		} else {
		
			$fileID = $resp->getFileID();
			$filepath=$resp->getPath();
			$rows = 0;
			if (($handle = fopen($filepath, "r")) !== FALSE) {
				 $data = fgetcsv($handle, 1000, ",");
			}
			$this->set('fileID',$fileID);
			$this->set('csvNumRows',count($data));
			$this->set('cols',$data[0]);
			$this->set('csvData',$data);
			
			if(intval($_POST['fID']) == 0) {
				$fData = array();
				$fData['name'] = $resp->getFileName();
				$fData['sendMail'] = 0;
				$fData['afterSubmit'] = 'thankyou';
				$fData['thankyouMsg'] = t('The record has been added.');
				$fData['submitLabel'] = 'Submit';
				
				$f = sixeightForm::create($fData);
			} else {
				$f = sixeightForm::getByID(intval($_POST['fID']));
			}
			
			$fields = $f->getFields();
			$this->set('f',$f);
			$this->set('fields',$fields);
		}
	}
	
	public function processImport() {
		$fID = intval($_POST['formID']);
		$fileID = intval($_POST['fileID']);
		$f = sixeightForm::getByID($fID);
		
		$csvFilePath = File::getByID($fileID)->getApprovedVersion()->getPath();
		$rows=0;
		
		if (($handle = fopen($csvFilePath, "r")) !== FALSE) {
			
			$csvData = fgetcsv($handle, 0, ',','"');
		
			//Find timestamp and approval columns
			foreach($_POST['col'] as $key => $column) {
				if($column == 'timestamp') {
					$timestampKey = $key;
				}
				if($column == 'isApproved') {
					$approvalKey = $key;
				}
				if($column == 'owner') {
					$ownerKey = $key;
				}
				if($column == 'createdOwner') {
					$createdOwnerKey = $key;
				}
				
				if($column == 'new') {
					$ffData = array();
					$ffData['label'] = $csvData[$key];
					$ffData['type'] = 'Text (Single-line)';
					$ff = $f->addField($ffData);
					
					$_POST['col'][$key] = $ff->ffID;
				}
			}
			
			$fields = $f->getFields();
		
			while(($data = fgetcsv($handle, 0, ',','"')) !== FALSE) {
				$numCols = count($data);
				
				unset($dateYear,$dateMonth,$dateDay,$timeHour,$timeMinute,$timeSecond);
				if(strpos($data[$timestampKey],'-') !== false) {
					//There is a dash, so it looks like it could be a timestamp.  Let's split up the date and time
					$timestamp = explode(' ',$data[$timestampKey]);
					if($timestamp[0] != '') {
						//Setup the date portion of the timestamp
						$date = explode('-',$timestamp[0]);
						$dateYear = $date[0];
						$dateMonth = $date[1];
						$dateDay = $date[2];
					}
					
					if($timestamp[1] != '') {
						//Setup the time portion of the timestamp
						$time = explode(':',$timestamp[1]);
						$timeHour = $time[0];
						$timeMinute = $time[1];
						$timeSecond = $time[2];
					}
					$timestamp = mktime($timeHour,$timeMinute,$timeSecond,$dateMonth,$dateDay,$dateYear);
				} else {
					$timestamp = '';
				}
				
				$as = $f->createAnswerSet(0,$timestamp,$data[$approvalKey]);
				
				if($ownerKey !== FALSE) {
					if($ownerInfo = UserInfo::getByUserName($data[$ownerKey])) {
						$ownerID = $ownerInfo->getUserID();
						if(intval($ownerID) != 0) {
							$as->setOwner($ownerID);
						}
					}
				}
				
				if($createdOwnerKey !== FALSE) {
					if($ownerInfo = UserInfo::getByUserName($data[$createdOwnerKey])) {
						$ownerID = $ownerInfo->getUserID();
						if(intval($ownerID) != 0) {
							$as->setOwner($ownerID);
						}
					} elseif($data[$createdOwnerKey] != '') {
						$uData['uName'] = $data[$createdOwnerKey];
						$uData['uPassword'] = '';
						$uData['uPasswordConfirm'] = '';
						$uData['uEmail'] = $data[$createdOwnerKey];
						$newUserInfo = UserInfo::register($uData);
						echo $newUserInfo->getUserID();
						$as->setOwner($newUserInfo->getUserID());
					}
				}
				
				foreach($_POST['col'] as $key => $column) {
					if(($column != 0) && ($column != 'timestamp') && ($column != 'isApproved')) {
						if($_POST['nl2br'][$key] == 1) {
							$data[$key] = nl2br($data[$key]);
						}
						
						if($_POST['isFile'][$key] == 1) {
							Loader::model('file_list');
							$fl = new FileList();
							$fl->filterByKeywords($data[$key]);
							$files = $fl->getPage();
							if (count($files) > 0) {
								$fileID = $files[0]->getApprovedVersion()->getFileID();
								$as->addAnswer($column,$fileID);
							}
						} else {
							$data[$key] = str_replace('\"','"',$data[$key]);
							$as->addAnswer($column,$data[$key]);
						}
					}
				}
				
				//Reset $as in order to populate the answers into the object
				$as = sixeightAnswerSet::getByID($as->asID);
				
				//Auto-create page
				if($f->properties['autoCreatePage'] == 1) {
					$as->createPage();
				}
				
				$rows++;
			} //End loop through CSV
		}
		$this->redirect('dashboard/sixeightforms/import','importSuccess?rows=' . $rows);
	}
	
	public function importSuccess() {
		$this->set('message',$_GET['rows'] . t(' row(s) imported.'));
		$this->view();
	}
	
	public function convertForm() {
		Loader::model('form_upgrade','sixeightforms');
		sixeightformUpgrade::upgradeForm(intval($_GET['bID']));
		$this->redirect('dashboard/sixeightforms/import','convertFormSuccess');
	}
	
	public function convertFormSuccess() {
		$this->set('message',t('The form has been converted.'));
		$this->view();
	}
	
	public function exportFormDefinition() {
		$f = sixeightform::getByID(intval($_GET['fID']));
		$formXML = new SimpleXMLElement('<?php xml version="1.0" ?><form></form>');
		
		$formXML->addAttribute('name',$f->properties['name']);
		$formXML->addAttribute('submitLabel',$f->properties['submitLabel']);
		$formXML->addAttribute('captcha',$f->properties['captcha']);
		$formXML->addAttribute('requiredIndicator',$f->properties['requiredIndicator']);
		$formXML->addAttribute('requiredColor',$f->properties['requiredColor']);
		$formXML->addAttribute('defaultAnswerSetStatus',$f->properties['defaultAnswerSetStatus']);
		$formXML->addAttribute('disableCache',$f->properties['disableCache']);
		$formXML->addAttribute('maximumPrice',$f->properties['maximumPrice']);
		$formXML->addAttribute('sendApprovalNotification',$f->properties['sendApprovalNotification']);
		$formXML->addAttribute('ownerCanEdit',$f->properties['ownerCanEdit']);
		$formXML->addAttribute('ownerCanDelete',$f->properties['ownerCanDelete']);
		$formXML->addAttribute('oneRecordPerUser',$f->properties['oneRecordPerUser']);
		
		$formXML->addChild('fields');
		
		$fields = $f->getFields();
		foreach($fields as $field) {
			$fieldXML = $formXML->fields->addChild('field');
			$fieldXML->addAttribute('label',$field->label);
			$fieldXML->addAttribute('text',$field->text);
			$fieldXML->addAttribute('type',$field->type);
			$fieldXML->addAttribute('defaultValue',$field->defaultValue);
			$fieldXML->addAttribute('width',$field->width);
			$fieldXML->addAttribute('height',$field->height);
			$fieldXML->addAttribute('maxLength',$field->maxLength);
			$fieldXML->addAttribute('layout',$field->layout);
			$fieldXML->addAttribute('format',$field->format);
			$fieldXML->addAttribute('toolbar',$field->toolbar);
			$fieldXML->addAttribute('groupWithPrevious',$field->groupWithPrevious);
			$fieldXML->addAttribute('required',$field->required);
			$fieldXML->addAttribute('sortPriority',$field->sortPriority);
			$fieldXML->addAttribute('price',$field->price);
			$fieldXML->addAttribute('qtyStart',$field->qtyStart);
			$fieldXML->addAttribute('qtyEnd',$field->qtyEnd);
			$fieldXML->addAttribute('qtyIncrement',$field->qtyIncrement);
			$fieldXML->addAttribute('eCommerceName',$field->eCommerceName);
			$fieldXML->addAttribute('isExpirationField',$field->isExpirationField);
			$fieldXML->addAttribute('dateFormat',$field->dateFormat);
			$fieldXML->addAttribute('indexable',$field->indexable);
			$fieldXML->addAttribute('fsID',$field->fsID);
			$fieldXML->addAttribute('urlParameter',$field->urlParameter);
			$fieldXML->addAttribute('cssClass',$field->cssClass);
		}
		
		$this->set('xml',$formXML->asXML());
	}
	
	public function createFromHTMLText() {
		$dom = new DOMDocument;
		$dom->loadHTML($this->post('html'));
		foreach($dom->getElementsByTagName('form') as $form) {
			
		}
	}
}

?>