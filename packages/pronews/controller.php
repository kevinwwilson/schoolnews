<?php  

defined('C5_EXECUTE') or die(_("Access Denied."));

class PronewsPackage extends Package {

	protected $pkgHandle = 'pronews';
	protected $appVersionRequired = '5.6.0';
	protected $pkgVersion = '5.0.2';
	
	public function getPackageDescription() {
		return t("A professional News package");
	}
	
	public function getPackageName() {
		return t("Pro News");
	}
	
	public function install() {
		$pkg = parent::install();
		
		//install blocks
	  	BlockType::installBlockTypeFromPackage('pronews_list', $pkg);	
		
		$this->load_required_models();
		
		$this->install_news_attributes($pkg);
		
		$this->add_sn_pages();
 
      // install pages
      $iak = CollectionAttributeKey::getByHandle('icon_dashboard');
      
      $cp = SinglePage::add('/dashboard/pronews/', $pkg);
      $cp->update(array('cName'=>t('Pro News'), 'cDescription'=>t('News Management for C5')));
      
      $pnl = SinglePage::add('/dashboard/pronews/list/', $pkg);
      $pnl->setAttribute($iak,'icon-list-alt');
      
      $pns = SinglePage::add('/dashboard/pronews/settings/', $pkg);
      $pns->setAttribute($iak,'icon-wrench');
      
      $png = SinglePage::add('/dashboard/pronews/shedule_news_group/', $pkg);
      $png->setAttribute($iak,'icon-clock');
      
      $pnag = SinglePage::add('/dashboard/pronews/shedule_news_group/add_group', $pkg);
      $pnag->setAttribute($iak,'icon-clock');

      
      $an = SinglePage::add('/dashboard/pronews/add_news', $pkg);
      $an->update(array('cName'=>t('Add/Edit')));
      $an->setAttribute($iak,'icon-edit');
	}
	
	public function update(){
		$iak = CollectionAttributeKey::getByHandle('icon_dashboard');
		
		$pns = Page::getByPath('/dashboard/pronews/list');
		$pns->setAttribute($iak,'icon-wrench');
		
		$pnl = Page::getByPath('/dashboard/pronews/list');
		$pnl->setAttribute($iak,'icon-list-alt');
		
		$an = Page::getByPath('/dashboard/pronews/add_news');
		$an->setAttribute($iak,'icon-edit');
	}
	

	public function uninstall(){
			
		$results= Page::getByPath('/news');
		$results->delete();
		
		parent::uninstall();
	}
	
	
  function install_news_attributes($pkg) {
	  $eaku = AttributeKeyCategory::getByHandle('collection');
	  $eaku->setAllowAttributeSets(AttributeKeyCategory::ASET_ALLOW_SINGLE);
	  $newsset = $eaku->addSet('pxl_products', t('News'),$pkg);
	  
    $checkn = AttributeType::getByHandle('boolean'); 
  	$newssec=CollectionAttributeKey::getByHandle('news_section'); 
	if( !is_object($newssec) ) {
     	CollectionAttributeKey::add($checkn, 
     	array('akHandle' => 'news_section', 
     	'akName' => t('News Section'),
     	'akIsSearchable' => '1', 
     	'akIsSearchableIndexed' => '1'
     	),$pkg)->setAttributeSet($newsset); 
  	}
  	
  	$checkn = AttributeType::getByHandle('boolean'); 
  	$approve=CollectionAttributeKey::getByHandle('approve'); 
	if( !is_object($approve) ) {
     	CollectionAttributeKey::add($checkn, 
     	array('akHandle' => 'approve', 
     	'akName' => t('Approve'),
     	'akIsSearchable' => '1', 
     	'akIsSearchableIndexed' => '1'
     	),$pkg)->setAttributeSet($newsset); 
  	}
  	
  	$dt = AttributeType::getByHandle('date_time'); 
  	$group_date=CollectionAttributeKey::getByHandle('group_date'); 
	if( !is_object($group_date) ) {
     	CollectionAttributeKey::add($dt, 
     	array('akHandle' => 'group_date', 
     	'akName' => t('Group Date'),
     	'akIsSearchable' => '1', 
     	'akIsSearchableIndexed' => '1'
     	),$pkg)->setAttributeSet($newsset); 
  	}
  	
    $pulln = AttributeType::getByHandle('select'); 
  	$newscat=CollectionAttributeKey::getByHandle('news_category'); 
	if( !is_object($newscat) ) {
     	CollectionAttributeKey::add($pulln, 
     	array('akHandle' => 'news_category', 
     	'akName' => t('News Category'), 
     	'akIsSearchable' => '1', 
     	'akIsSearchableIndexed' => '1', 
		/*'akSelectAllowOtherValues' => true,*/
		'akSelectAllowMultipleValues' => true,  
     	),$pkg)->setAttributeSet($newsset); 
  	}
  	$newstag=CollectionAttributeKey::getByHandle('news_tag'); 
	if( !is_object($newstag) ) {
     	CollectionAttributeKey::add($pulln, 
     	array('akHandle' => 'news_tag', 
     	'akName' => t('News Tags'), 
     	'akIsSearchable' => '1', 
     	'akIsSearchableIndexed' => '1', 
		'akSelectAllowMultipleValues' => true, 
		'akSelectAllowOtherValues' => true, 
     	),$pkg)->setAttributeSet($newsset); 
  	}
     $imagen = AttributeType::getByHandle('image_file'); 
  	 $newsthum=CollectionAttributeKey::getByHandle('thumbnail'); 
	if( !is_object($newsthum) ) {
     	CollectionAttributeKey::add($imagen, 
     	array('akHandle' => 'thumbnail', 
     	'akName' => t('Thumbnail Image'), 
     	),$pkg)->setAttributeSet($newsset); 
  	}
	
	
	
	$pulln = AttributeType::getByHandle('select'); 
  	$newsreg=CollectionAttributeKey::getByHandle('regional_feature'); 
	if( !is_object($newsreg) ) {
     	CollectionAttributeKey::add($pulln, 
     	array('akHandle' => 'regional_feature', 
     	'akName' => t('Regional Feature'), 
     	'akIsSearchable' => '1', 
     	'akIsSearchableIndexed' => '1', 
		/*'akSelectAllowOtherValues' => true,*/
		/*'akSelectAllowMultipleValues' => true,*/  
     	),$pkg)->setAttributeSet($newsset); 
  	}
	
	$textarea = AttributeType::getByHandle('textarea');
	$long_summary=CollectionAttributeKey::getByHandle('long_summary'); 
		if( !is_object($long_summary) ) {
			CollectionAttributeKey::add($textarea, 
			array('akHandle' => 'long_summary', 
			'akName' => t('Long Summary'), 
			'akIsSearchable' => '1',
			'akIsSearchableIndexed' => '1',
			),$pkg)->setAttributeSet($newsset); 
		}
	
     $textn = AttributeType::getByHandle('text'); 
  	$newsurl=CollectionAttributeKey::getByHandle('news_url'); 
	if( !is_object($newsurl) ) {
     	CollectionAttributeKey::add($textn, 
     	array('akHandle' => 'news_url', 
     	'akName' => t('News URL'), 
     	),$pkg)->setAttributeSet($newsset); 
  	}
	
	$textn = AttributeType::getByHandle('text'); 
  	$story_slug=CollectionAttributeKey::getByHandle('story_slug'); 
	if( !is_object($story_slug) ) {
     	CollectionAttributeKey::add($textn, 
     	array('akHandle' => 'story_slug', 
     	'akName' => t('Story Slug'), 
     	),$pkg)->setAttributeSet($newsset); 
  	}
	
	 $textn = AttributeType::getByHandle('text'); 
  	$secondaryheadline=CollectionAttributeKey::getByHandle('secondary_headline'); 
	if( !is_object($secondaryheadline) ) {
     	CollectionAttributeKey::add($textn, 
     	array('akHandle' => 'secondary_headline', 
     	'akName' => t('Secondary Headline'), 
     	),$pkg)->setAttributeSet($newsset); 
  	}
	
	$textn = AttributeType::getByHandle('text'); 
  	$author=CollectionAttributeKey::getByHandle('author'); 
	if( !is_object($author) ) {
     	CollectionAttributeKey::add($textn, 
     	array('akHandle' => 'author', 
     	'akName' => t('Author'), 
     	),$pkg)->setAttributeSet($newsset);  
  	}
	
	$imagen = AttributeType::getByHandle('image_file'); 
  	 $mainphoto=CollectionAttributeKey::getByHandle('main_photo'); 
	if( !is_object($mainphoto) ) {
     	CollectionAttributeKey::add($imagen, 
     	array('akHandle' => 'main_photo', 
     	'akName' => t('Main Photo'), 
     	),$pkg)->setAttributeSet($newsset); 
  	}
	
	$textn = AttributeType::getByHandle('text'); 
  	$photocaption=CollectionAttributeKey::getByHandle('photo_caption'); 
	if( !is_object($photocaption) ) {
     	CollectionAttributeKey::add($textn, 
     	array('akHandle' => 'photo_caption', 
     	'akName' => t('Photo Caption'), 
     	),$pkg)->setAttributeSet($newsset);  
  	}
	
	$pulln = AttributeType::getByHandle('select'); 
		$dateline=CollectionAttributeKey::getByHandle('dateline'); 
		if( !is_object($dateline) ) {
			CollectionAttributeKey::add($pulln, 
			array('akHandle' => 'dateline', 
			'akName' => t('Dateline'), 
			'akIsSearchable' => '1', 
			'akIsSearchableIndexed' => '1', 
			'akSelectAllowOtherValues' => false, 
			),$pkg)->setAttributeSet($newsset); 
		}
		
		$pulln = AttributeType::getByHandle('select'); 
		$district=CollectionAttributeKey::getByHandle('district'); 
		if( !is_object($district) ) {
			CollectionAttributeKey::add($pulln, 
			array('akHandle' => 'district', 
			'akName' => t('District'), 
			'akIsSearchable' => '1', 
			'akIsSearchableIndexed' => '1', 
			'akSelectAllowOtherValues' => false, 
			'akSelectAllowMultipleValues' => true
			),$pkg)->setAttributeSet($newsset);  
		}
	
	
  }
  
	function add_sn_pages() {
 		$pageType= CollectionType::getByHandle('left_sidebar');
     	if(!is_object($pageType)){  
	 		$pageType= CollectionType::getByHandle('right_sidebar');
		}
	 	if(!is_object($pageType)){  
	 		$pageType= CollectionType::getByHandle('news_page');
		}
  		if(!is_object($pageType)){  
     		$NewsPageTypes = array(
        	array('ctHandle' => 'news_page',   'ctName' => t('News Page'),'ctIcon'=>t('template3.png')),
     		 );
      		foreach( $NewsPageTypes as $NewsPageType ) {
        		CollectionType::add($NewsPageType, $pkg);
     	 	}
      		$pageType= CollectionType::getByHandle('news_page');
   		 }

    	$pageNewsParent = Page::getByID(HOME_CID);
    	$setNewsAt = Page::getByPath('/news');

    	if(!is_object($setNewsAt) || $setNewsAt->cID==NULL ){
    		$pageNewsParent->add($pageType, array('cName' => 'News', 'cHandle' => 'news'));
    	}
    	
		$setNewsAt = Page::getByPath('/news');
    	$setNewsAt->setAttribute('news_section','1'); 
    
    	$cIDn = $setNewsAt->getCollectionID();
    
    	$bt = BlockType::getByHandle('pronews_list');
		
		$data = array('num' => '10',
		'cParentID'=>$cIDn,
		'cThis'=>'0',
		'paginate'=>'1',
		'displayAliases'=>'1',
		'ctID'=>'0',
		'rss'=>'1',
		'rssTitle'=>'Latest News',
		'rssDescription'=>'Our latest news feed',
		'truncateSummaries'=>'1',
		'truncateChars'=>'128',
		'category'=>'All Categories',
		'title'=>'Our Latest News'
		);
					
		$setNewsAt->addBlock($bt, 'Main', $data);
		
		$block = $setNewsAt->getBlocks('Main');
		foreach($block as $b) {
			$b->setCustomTemplate('news_list');
		}
  }
  
  
  function load_required_models() {
    Loader::model('single_page');
    Loader::model('collection');
    Loader::model('page');
    loader::model('block');
    Loader::model('collection_types');
    Loader::model('/attribute/categories/collection');
  }		
		
}