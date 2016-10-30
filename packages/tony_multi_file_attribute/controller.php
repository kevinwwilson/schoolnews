<?php

defined('C5_EXECUTE') or die(_("Access Denied."));


class TonyMultiFileAttributePackage extends Package {

	protected $pkgHandle = 'tony_multi_file_attribute';
	protected $appVersionRequired = '5.4';
	protected $pkgVersion = '1.0';

	//these are only used for installs  
	protected $installSamplePages = 1;

	public function getPackageDescription() {
		return t("A custom attribute for multiple image or files");
	}

	public function getPackageName() {
		return t("Multi-File Attribute");
	}

	public function upgrade(){
		$result = parent::upgrade();
		$this->configure();
		return $result;
	}

	public function install() {
		$pkg = parent::install();
		$this->configure();
	}

	public function configure() {
		$pkg = Package::getByHandle('tony_multi_file_attribute');

		Loader::model('collection_types');
		Loader::model('collection_attributes');
		$db = Loader::db();

		//install new multiple files attribute type
		$multiFileAttrType = AttributeType::getByHandle('multiple_files');
		if(!is_object($multiFileAttrType) || !intval($multiFileAttrType->getAttributeTypeID()) ) {
			$multiFileAttrType = AttributeType::add('multiple_files', t('Multiple Files'), $pkg);
		}

		//check that the multi-files attribute type is associate with pages
		$collectionAttrCategory = AttributeKeyCategory::getByHandle('collection');
		$catTypeExists = $db->getOne('SELECT count(*) FROM AttributeTypeCategories WHERE atID=? AND akCategoryID=?', array( $multiFileAttrType->getAttributeTypeID(), $collectionAttrCategory->getAttributeKeyCategoryID() ));
		if(!$catTypeExists) $collectionAttrCategory->associateAttributeKeyType($multiFileAttrType);


		$multiFileAttrKey=CollectionAttributeKey::getByHandle('files');
		if( !$multiFileAttrKey || !intval($multiFileAttrKey->getAttributeKeyID()) )
			$multiFileAttrKey = CollectionAttributeKey::add( $multiFileAttrType, array('akName'=>t("Images/Files"),'akHandle'=>'files','akIsSearchable'=>0), $pkg) ;//null, 'MULTIPLE_FILES');



	}

}

?>
