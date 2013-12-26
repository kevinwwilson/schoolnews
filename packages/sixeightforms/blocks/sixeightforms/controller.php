<?php     
defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');
Loader::model('form_style','sixeightforms');

class SixeightformsBlockController extends BlockController {

	protected $btTable = 'btSixeightforms';
	protected $btInterfaceWidth = "300";
	protected $btInterfaceHeight = "100";

	public function getBlockTypeDescription() {
		return t("Display a form created from the backend");
	}
	
	public function getBlockTypeName() {
		return t("Advanced Form");
	}
	
	function getFormID() {
		return $this->fID;
	}
	
	function getStyleID() {
		return $this->sID;
	}
	
	function view() {
		global $c;
		$style = sixeightformstyle::getByID($this->sID);
		$this->set('style',$style);
		$f = sixeightForm::getByID($this->fID);
		$fields = $f->getFields();
		
		if($f->ownerCanEdit() && $f->oneRecordPerUser()) {
			$u = new User();
			if($u->isLoggedIn()) {
				$as = sixeightAnswerSet::getByUID($f->getFormID(),$u->getUserID());
				$this->set('asID',$as->asID);
				$this->set('editCode',$as->editCode);
			}
		}
		
		//If we are editing a record, we will get the editCode and answer set ID from the URL
		if(($_GET['editCode'] != '') && ($_GET['asID'] != '')) {
			//For security, this function only returns the answer set if the asID and the editCode match according to the DB
			$as = sixeightAnswerSet::getByIDAndEditCode($_GET['asID'],$_GET['editCode']);
			$this->set('asID',intval($_GET['asID']));
			$this->set('editCode',htmlspecialchars($_GET['editCode']));
		}
		
		if(is_object($as)) {
			$this->set('editingRecord',true);
			//Populate the default values with the answers for each field
			foreach($fields as $key => $field) {
				$fields[$key]->defaultValue = $as->answers[$field->ffID]['value'];
			}
		}
		
		$this->set('f',$f);
		$this->set('fID',$f->fID);
		$this->set('fields',$fields);
		$this->set('displayInDialog',$this->displayInDialog);
	}
	
	function on_page_view() {
		if($this->requireSSL == 1) {
			global $c;
			$cp = new Permissions($c);
			if (isset($cp)) {
				if (!$cp->canWrite() && !$cp->canAddSubContent() && !$cp->canAdminPage() && !$cp->canApproveCollection()) {	
					if($_SERVER['HTTPS']!="on") {
						$redirect= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
						header("Location:$redirect");
					}
				}
			}
		}
		
		$this->loadHeaderItems();
	}
	
	public function loadHeaderItems($fID = 0) {
		$uh = Loader::helper('concrete/urls');
		$html = Loader::helper('html');
		
		if($fID == 0) {
			$f = sixeightForm::getByID($this->fID);
		} else {
			$f = sixeightForm::getByID($fID);
		}
		
		$headerFiles = array();
		
		$headerFiles[] = $html->css('jquery.ui.css'); //Required for ccm.sitemap.js, ccm.dialog.js, ccm.filemanager.js
		$footerFiles[] = $html->javascript('jquery.ui.js');
		$footerFiles[] = $html->javascript('bootstrap.js');
		$headerFiles[] = $html->css('jquery.ui.css'); //Required for date field
		
		
		$headerFiles[] = $html->css($uh->getToolsURL('css?sID=' . $this->sID,'sixeightforms'),'sixeightforms');
		$footerFiles[] = $html->javascript('ccm.app.js'); //Required for ccm.sitemap.js, ccm.dialog.js, ccm.filemanager.js
		$headerFiles[] = $html->javascript('jquery.maxlength.js','sixeightforms');
		$headerFiles[] = $html->css('ccm.app.css'); //Required to display the file manager (or any other sort of) dialog
		
		
		if($f->hasSiteMapField()) {
			$headerFiles[] = $html->javascript('ccm.sitemap.js'); //Required to display the sitemap
		}
		
		if($f->hasFileManagerField() || $f->hasWYSIWYGField()) {
			$headerFiles[] = $html->javascript('tiny_mce/tiny_mce.js'); //Load this because of some Javascript stuff that requires it in view.php
			$headerFiles[] = $html->javascript('jquery.uploadify.js','sixeightforms');
			$headerFiles[] = $html->javascript('swfobject.js');
			$headerFiles[] = $html->css('ccm.search.css');
			$headerFiles[] = $html->javascript('jquery.form.js');
			$headerFiles[] = '<script type="text/javascript" src="' . REL_DIR_FILES_TOOLS_REQUIRED . '/i18n_js"></script>'; //Required for file manager
		}

		foreach($headerFiles as $hf) {
			$this->addHeaderItem($hf);
		}

		foreach($footerFiles as $ff) {
			$this->addFooterItem($ff);
		}
	}

}

?>