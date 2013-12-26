<?php   defined('C5_EXECUTE') or die(_("Access Denied."));

class sixeightformsClearCache extends Job {

	public function getJobName() {
		return 'Advanced Forms - Clear Cache';
	}
	
	public function getJobDescription() {
		return t('Clear the advanced forms records cache.');
	}
	
	public function run() {
		Loader::model('form','sixeightforms');
		Loader::model('field','sixeightforms');
		Loader::model('answer_set','sixeightforms');
		$forms = sixeightForm::getAll();
		$i = 0;
		foreach($forms as $form) {
			$form->clearAnswersCache();
			$i++;
		}
		return t('The cache was successfully cleared.');
	}

}

?>