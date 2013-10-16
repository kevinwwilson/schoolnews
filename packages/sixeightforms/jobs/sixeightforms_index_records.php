<?php   defined('C5_EXECUTE') or die(_("Access Denied."));

class sixeightformsIndexRecords extends Job {

	public function getJobName() {
		return 'Advanced Forms - Index Records';
	}
	
	public function getJobDescription() {
		return t('Index records that were created using Advanced Forms in order to make them searchable using the Data Display search.');
	}
	
	public function run() {
		Loader::model('form','sixeightforms');
		Loader::model('field','sixeightforms');
		Loader::model('answer_set','sixeightforms');
		$forms = sixeightForm::getAll();
		$i = 0;
		foreach($forms as $form) {
			$form->indexAnswerSets();
			$i++;
		}
		return $i . t(' forms were indexed.');
	}

}

?>