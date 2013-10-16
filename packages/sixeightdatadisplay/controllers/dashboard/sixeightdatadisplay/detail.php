<?php     

defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model('sixeightdatadisplay','sixeightdatadisplay');
Loader::model('form','sixeightforms');

class DashboardSixeightdatadisplayDetailController extends Controller {
	
	function view() {
		$c=$this->getCollectionObject();
		$this->set('forms', sixeightForm::getAll());
		$this->set('detailTemplates', sixeightdatadisplay::getTemplates('detail'));
	}

	public function createTemplate() {
		$id = sixeightdatadisplay::createTemplate($_GET,'detail');
		$this->redirect('/dashboard/sixeightdatadisplay/detail/-/getTemplate?tID=' . $id . '&new=true');
	}
	
	public function deleteTemplate() {
		sixeightdatadisplay::deleteTemplate(intval($_GET['tID']));
		$this->redirect('/dashboard/sixeightdatadisplay/detail/-/deleteTemplateSuccess');
	}
	
	public function deleteTemplateSuccess() {
		$this->set('message', 'The template has been deleted.');
		$this->view();
	}
	
	public function getTemplate($tID='') {
		if ($_GET['tID'] != '') {
			$tID = intval($_GET['tID']);
		}
		
		if ($_GET['new'] == 'true') {
			$this->set('message',t('The template has been created.  You may edit it in the section below.'));
		}
		
		if ($_GET['save'] == 'true') {
			$this->set('message',t('The template has been saved.'));
		}
		
		$loadedTemplate = sixeightdatadisplay::getTemplate($tID);
		$this->set('loadedTemplate', $loadedTemplate);
		$this->set('placeholders', sixeightdatadisplay::getFormPlaceholders($loadedTemplate['fID'],$loadedTemplate['templateType']));
		$this->view();
	}
	
	public function duplicateTemplate() {
		if (sixeightdatadisplay::duplicateTemplate($_GET['tID'],$_GET['name'])) {
			$this->redirect('/dashboard/sixeightdatadisplay/detail/-/duplicateTemplateSuccess');
		} else {
			$this->redirect('dashboard/sixeightdatadisplay/detail/-/duplicateTemplateError');
		}
	}
	
	public function duplicateTemplateSuccess() {
		$this->set('message', t('The template has been duplicated.'));
		$this->view();
	}
	
	public function duplicateTemplateError() {
		$this->set('message', t('Error: the template could not be duplicated.'));
		$this->view();
	}
	
	public function saveTemplate() {
		sixeightdatadisplay::saveTemplate($_POST);
		$id = intval($_POST['tID']);
		$this->redirect('/dashboard/sixeightdatadisplay/detail/-/getTemplate?tID=' . $id . '&save=true');
	}

}

?>