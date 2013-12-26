<?php   
defined('C5_EXECUTE') or die(_("Access Denied.")); 

Loader::model('sixeightdatadisplay','sixeightdatadisplay');
Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');
Loader::model('answer_set_list','sixeightforms');
Loader::block('sixeightdatadisplay');
Loader::model('attribute/categories/collection');

class SixeightdatadisplaydetailBlockController extends BlockController {
	
	protected $btTable = 'btSixeightdatadisplaydetail';
	protected $btInterfaceWidth = "450";
	protected $btInterfaceHeight = "340";
	
	public function getBlockTypeDescription() {
		return t("Display a single record.");
	}
	
	public function getBlockTypeName() {
		return t("Data Display Single Record");
	}
	
	function __construct($obj = null) {
		parent::__construct($obj);	
		$forms = sixeightForm::getAll();
		$listTemplates = sixeightdatadisplay::getTemplates('list');
		$detailTemplates = sixeightdatadisplay::getTemplates('detail');
		
		if((!count($forms)) || (!count($detailTemplates))) {
			$this->set('isReady',false);
		} else {
			$this->set('isReady',true);
			$this->set('forms',$forms);
			$this->set('detailTemplates',$detailTemplates);
		}
	}
	
	function view() {
		$c = Page::getCurrentPage();
		$cID = $c->getCollectionID();
		
		$detailTemplate = sixeightdatadisplay::getTemplate($this->detailTemplateID);
		$this->set('detailTemplateContent',$detailTemplate['templateContent']);
		$this->set('detailTemplateEmpty',$detailTemplate['templateEmpty']);
		$f = sixeightForm::getByID($this->fID);
		$this->set('f',$f);
		$this->set('questions',$f->getFields());
		if($this->method == 'parameter') {
			$parameter = $_GET[$this->parameterName];
			
			if($this->ffID == '0') {
				$answerSet = sixeightAnswerSet::getByID($parameter);
			} else {
				$asl = sixeightAnswerSetList::get($this->fID);
				$asl->addFilter($this->ffID,$_GET[$this->parameterName]);
				$answerSets = $asl->getAnswerSets();
				$answerSet = reset($answerSets);
			}
		} elseif($this->method == 'inherit') {
			$answerSet = sixeightAnswerSet::getByCID($cID);
		} elseif($this->method == 'username') {
			$u = new User();
			$uName = $u->getUserName();
			$asl = sixeightAnswerSetList::get($this->fID);
			$asl->addFilter($this->ffID,$uName);
			$answerSets = $asl->getAnswerSets();
			foreach($answerSets as $as) {
				if(strtolower($as->answers[$this->ffID]['value']) == strtolower($uName)) {
					$answerSet = $as;
				}
			}
		} elseif($this->method == 'owner') {
			$u = new User();
			$uName = $u->getUserName();
			$asl = sixeightAnswerSetList::get($this->fID);
			$asl->requireOwnership();
			$answerSets = $asl->getAnswerSets();
			$answerSet = reset($answerSets);
		} else { //Match answer set by page attribute
			switch($this->pageAttributeHandle) {
				case 'cName':
					$valueToMatch = $c->getCollectionName();
					break;
				case 'cDescription':
					$valueToMatch = $c->getCollectionDescription();
					break;
				case 'cHandle':
					$valueToMatch = $c->getCollectionHandle();
					break;
				case 'custom':
					$valueToMatch = $c->getAttribute($this->parameterName);
					break;
			}
			
			if($this->ffID == '0') {
				$answerSet = sixeightAnswerSet::getByID(intval($valueToMatch));
			} else {
				$asl = sixeightAnswerSetList::get($this->fID);
				$asl->addFilter($this->ffID,$valueToMath);
				$answerSets = $asl->getAnswerSets();
				$answerSet = reset($answerSets);
			}
		}
		
		$this->set('answerSet',$answerSet);
	}
	
	function on_page_view() {
		$html = Loader::helper('html');
		$f = sixeightForm::getByID($this->fID);
		$fBlock = BlockType::getByHandle('sixeightforms');
		if(($f->userCanEdit()) || ($f->ownerCanEdit())) {
			$fBlock->controller->loadHeaderItems($this->fID);
			$this->addHeaderItem($html->javascript('frontend-actions.js','sixeightdatadisplay'));
		}
	}
	
}
?>