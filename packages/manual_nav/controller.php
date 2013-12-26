<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));

class ManualNavPackage extends Package {

	protected $pkgHandle = 'manual_nav';
	protected $appVersionRequired = '5.5';
	protected $pkgVersion = '1.3';
	
	public function getPackageName() {
		return t('Manual Nav');
	}	
	
	public function getPackageDescription() {
		return t('Manually choose pages for a navigation menu.');
	}
	
	public function install() {
		$pkg = parent::install();
		
		// install block
		BlockType::installBlockTypeFromPackage('manual_nav', $pkg);
	}
	
	public function uninstall() {
		parent::uninstall();
		$db = Loader::db();
		$db->Execute('DROP TABLE btManualNav, btManualNavLinks');
	}


}