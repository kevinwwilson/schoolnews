<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');
Loader::model('form_style','sixeightforms');

class DashboardSixeightformsStylesController extends Controller { 
	function view() {
		$styles = sixeightformstyle::getAll();
		$this->set('styles',$styles);
	}
	
	public function createStyle() {
		$style = sixeightformstyle::create($_POST['name']);
		
		if($_POST['useTemplate'] == '1') {
			$style->setSelector('form.sem-form','','The form element',true);
			$style->setSelector('div.sem-field-container','padding: 5px 0;','Element that is wrapped around each label and field',true);
			$style->setSelector('span.sem-required-indicator','','Wrapper for required indicator',true);
			$style->setSelector('label.sem-label',"display:block;\nmargin-top:4px;",'Form labels (except for checkboxes and radio button)',true);
			$style->setSelector('fieldset.sem-fieldset',"border:0;\npadding:0;\nmargin:0;",'Checkbox/radio button fieldset',true);
			$style->setSelector('legend.sem-legend',"display:block;\npadding:0;\nmargin:0;\nvertical-align:top;\nmargin-top:4px;\nwhite-space:normal;",'Legend (field label) for checkbox/radio button fieldsets',true);
			$style->setSelector('input.sem-text','margin:2px;','Text input boxes',true);
			$style->setSelector('textarea.sem-textarea','margin:2px;','Textarea input boxes',true);
			$style->setSelector('select.sem-select','margin:2px;','Select (dropdown) boxes',true);
			$style->setSelector('option.sem-option','','Option within select boxes',true);
			$style->setSelector('label.sem-radio-button-label',"display:block;\nclear:both;\nmargin-left:15px;",'Radio button option labels',true);
			$style->setSelector('input.sem-radio-button','margin:2px;','Radio buttons',true);
			$style->setSelector('label.sem-checkbox-label',"display:block;\nclear:both;\nmargin-left:15px;",'Checkbox option labels',true);
			$style->setSelector('input.sem-checkbox','margin:2px;','Checkboxes',true);
			$style->setSelector('input.sem-date','margin:2px;','Date input boxes',true);
			$style->setSelector('div.sem-file','','Browse button for file uploader',true);
			$style->setSelector('a.sem-file-selector','','Link to file manager',true);
			$style->setSelector('textarea.sem-wysiwyg','','WYSIWYG texatrea',true);
			$style->setSelector('input.sem-submit','margin:2px;','Submit Button',true);
		} else {
			$style->setSelector('form.sem-form','','The form element',true);
			$style->setSelector('div.sem-field-container','','Element that is wrapped around each label and field',true);
			$style->setSelector('span.sem-required-indicator','','Wrapper for required indicator',true);
			$style->setSelector('label.sem-label','','Form labels (except for checkboxes and radio button)',true);
			$style->setSelector('fieldset.sem-fieldset','','Checkbox/radio button fieldset',true);
			$style->setSelector('legend.sem-legend','','Legend (field label) for checkbox/radio button fieldsets',true);
			$style->setSelector('input.sem-text','','Text input boxes',true);
			$style->setSelector('textarea.sem-textarea','','Textarea input boxes',true);
			$style->setSelector('select.sem-select','','Select (dropdown) boxes',true);
			$style->setSelector('option.sem-option','','Option within select boxes',true);
			$style->setSelector('label.sem-radio-button-label','','Radio button option labels',true);
			$style->setSelector('input.sem-radio-button','','Radio buttons',true);
			$style->setSelector('label.sem-checkbox-label','','Checkbox option labels',true);
			$style->setSelector('input.sem-checkbox','','Checkboxes',true);
			$style->setSelector('input.sem-date','','Date input boxes',true);
			$style->setSelector('div.sem-file','','Browse button for file uploader',true);
			$style->setSelector('a.sem-file-selector','','Link to file manager',true);
			$style->setSelector('textarea.sem-wysiwyg','','WYSIWYG texatrea',true);
			$style->setSelector('input.sem-submit','','Submit Button',true);
		}
		
		$style->setSelector('','','Custom classes or IDs',true);

		$this->redirect('/dashboard/sixeightforms/styles/-/loadStyle?sID=' . $style->sID);
	}
	
	public function loadStyle() {
		$style = sixeightformstyle::getByID(intval($_GET['sID']));
		$this->set('style',$style);
	}
	
	public function saveStyle() {
		foreach($_POST['selector-css'] as $ssID => $css) {
			sixeightformstyle::setSelectorByID($ssID,$css);
		}
		$this->redirect('/dashboard/sixeightforms/styles/-/loadStyle?sID=' . intval($_POST['sID']));
	}
	
	public function deleteStyle() {
		sixeightformstyle::delete(intval($_GET['sID']));
		$this->redirect('/dashboard/sixeightforms/styles/-/deleteStyleSuccess');
	}
	
	public function deleteStyleSuccess() {
		$this->set('message',t('The style has been deleted.'));
		$this->view();
	}
	
	public function duplicateStyle() {
		$style = sixeightformstyle::getByID(intval($_GET['sID']));
		$style->duplicate();
		$this->redirect('/dashboard/sixeightforms/styles/-/duplicateStyleSuccess');
	}
	
	public function duplicateStyleSuccess() {
		$this->set('message',t('The style has been duplicated.'));
		$this->view();
	}
	
}