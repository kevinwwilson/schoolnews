<?php   

defined('C5_EXECUTE') or die(_("Access Denied."));
class SixeightformsPackage extends Package {

	protected $pkgHandle = 'sixeightforms';
	protected $pkgName = "Advanced Forms";
	protected $pkgDescription = 'Create advanced forms for your site';
	protected $appVersionRequired = '5.6';
	protected $pkgVersion = '1.9.2.2';
	
	public function install() {
		$pkg = parent::install();
		
		// Install Block		
		BlockType::installBlockTypeFromPackage('sixeightforms', $pkg);
		
		// Create Single Pages
		Loader::model('single_page');
		$dataDisplayPage = SinglePage::add('/dashboard/sixeightforms', $pkg);
	    $dataDisplayPage->update(array('cName' => 'Advanced Forms', 'cDescription' => 'Create and manage forms')); 
	    
	    $formsPage = SinglePage::add('/dashboard/sixeightforms/forms', $pkg);
	    $formsPage->update(array('cName' => 'Forms'));
	    
	    $stylesPage = SinglePage::add('/dashboard/sixeightforms/styles', $pkg);
	    $stylesPage->update(array('cName' => 'Styles'));
		
		$importPage = SinglePage::add('/dashboard/sixeightforms/import', $pkg);
	    $importPage->update(array('cName' => 'Tools'));
	    
	    $helpPage = SinglePage::add('/dashboard/sixeightforms/help', $pkg);
	    $helpPage->update(array('cName' => 'Help'));
	    
	    //Install Jobs
	    Loader::model('job');
	    Job::installByPackage('sixeightforms_clear_cache',$pkg);
	    Job::installByPackage('sixeightforms_index_records',$pkg);
	}

}

?>
