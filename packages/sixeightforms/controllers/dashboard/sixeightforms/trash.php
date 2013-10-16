<?php   

defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');

class DashboardSixeightformsTrashController extends Controller {
	
	public function view() {
		$trashedForms = array();
		$trashedForms = sixeightForm::getTrashedForms();
		$this->set('trashedForms',$trashedForms);
		
		$trashedFields = sixeightForm::getTrashedFields();
		$this->set('trashedFields',$trashedFields);
		
		$trashedAnswerSets = sixeightForm::getTrashedAnswerSets();
		$this->set('trashedAnswerSets',$trashedAnswerSets);
	}

}