<?php       
defined('C5_EXECUTE') or die(_("Access Denied."));
class SixeightdatadisplayPackage extends Package {

	protected $pkgHandle = 'sixeightdatadisplay';
	protected $pkgName = "Data Display";
	protected $pkgDescription = 'Create and display form data.';
	protected $appVersionRequired = '5.4.0';
	protected $pkgVersion = '2.5.2';
	
	public function install() {
		
		$formsPkg = Package::getByHandle('sixeightforms');
		
		if(!is_object($formsPkg) || !$formsPkg->isPackageInstalled()){ 
			throw new Exception('Advanced Forms is required to run Data Display.  Please install Advanced Forms before installing Data Display.');
		} else {
			$pkg = parent::install();
		
			// Install Blocks
			BlockType::installBlockTypeFromPackage('sixeightdatadisplay', $pkg);
			BlockType::installBlockTypeFromPackage('sixeightdatadisplaydetail', $pkg);
			
			// Create Single Pages
			Loader::model('single_page');
			$dataDisplayPage = SinglePage::add('/dashboard/sixeightdatadisplay', $pkg);
		    $dataDisplayPage->update(array('cName' => 'Data Display', 'cDescription' => 'Create and display form data.')); 
		    
		    $listPage = SinglePage::add('/dashboard/sixeightdatadisplay/list', $pkg);
		    $listPage->update(array('cName' => 'Templates'));  
		    
		    $toolsPage = SinglePage::add('/dashboard/sixeightdatadisplay/tools', $pkg);
		    $toolsPage->update(array('cName' => 'Tools')); 
		    
		    $helpPage = SinglePage::add('/dashboard/sixeightdatadisplay/help', $pkg);
		    $helpPage->update(array('cName' => 'Help'));
		    
		    $oldDataDisplayPage = Page::getByPath('/dashboard/datadisplay');
		    if(intval($oldDataDisplayPage->cID) != 0) {
		    	$oldDataDisplayPage->update(array('cName'=>'Data Display (old)'));
		    }
		    
		    //Create Blank Detail Template
		    Loader::model('sixeightdatadisplay','sixeightdatadisplay');
			sixeightdatadisplay::createTemplate(array('fID'=>0,'templateName'=>'Blank'),'detail');
		}
		
	}
	

}