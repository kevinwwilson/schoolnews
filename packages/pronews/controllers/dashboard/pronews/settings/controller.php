<?php    
defined('C5_EXECUTE') or die(_("Access Denied."));
class DashboardPronewsSettingsController extends Controller {	

   public $helpers = array('html','form');
	
	public function on_start() {
		Loader::model('page_list');
		$this->error = Loader::helper('validation/error');
	}
	
	public function view() {
	
	$this->loadPageTypes();
	$this->loadnewsSections();
	$this->addHeaderItem(Loader::helper('html')->javascript('tiny_mce/tiny_mce.js'));
	
	}
	
	protected function loadPageTypes() {
		Loader::model("collection_types");
		$ctArray = CollectionType::getList('');		
		$pageTypes = array();
		foreach($ctArray as $ct) {
			$pageTypes[$ct->getCollectionTypeID()] = $ct->getCollectionTypeName();		
		}
		$this->set('pageTypes', $pageTypes);
		
		Loader::model('config');
	    $this->token = Loader::helper('validation/token');
	    $this->set('page_type_id',Config::get('PAGE_TYPE_ID'));
	}
	
	protected function loadnewsSections() {
		$newsSectionList = new PageList();
		$newsSectionList->filterByNewsSection(1);
		$newsSectionList->sortBy('cvName', 'asc');
		$tmpSections = $newsSectionList->get();
		$sections = array();
		foreach($tmpSections as $_c) {
			$sections[$_c->getCollectionID()] = $_c->getCollectionName();
		}
		$this->set('sections', $sections);
		
		Loader::model('config');
	    $this->token = Loader::helper('validation/token');
	    $this->set('sections_id',Config::get('SECTIONS_ID'));
	}
	
	public function save_settings() {
	
	Loader::model('config');
		$this->token = Loader::helper('validation/token');		
		$co = new Config();
		$pkg = Package::getByHandle("pronews");
		$co->setPackageObject($pkg);
		if(isset($_POST['PAGE_TYPE_ID'])){
		$co->save('PAGE_TYPE_ID',$_POST['PAGE_TYPE_ID']);
		}		
		if(isset($_POST['PAGE_TYPE_ID'])){
		$co->save('SECTIONS_ID',$_POST['SECTIONS_ID']);
		}
		$this->set('message', t('Settings has been saved.'));
		$this->view();
		 }
	
	
	
	
}