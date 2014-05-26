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
		 
    public function import_news() {
	ini_set('auto_detect_line_endings',TRUE);
	$file = $_FILES['cvFile'];
	
	
	
	if($file['name'] != '' && ($file['type'] == 'text/csv' || $file['type'] == 'application/vnd.ms-excel' || $file['type'] == 'application/octet-stream')){		 	
            move_uploaded_file($_FILES["cvFile"]["tmp_name"], DIR_BASE."/files/news/news.csv");
            $this->newsAdded();
	    $this->set('message','News Added'); 	   		    
        }
        else {
            $this->set('message','Please Upload CSV File'); 
	 }
	
            $this->view();
    }
	
    public function newsAdded() {
	
	Loader::library("file/importer");
        Loader::model('collection_attributes');

        $docRoot = $_SERVER['DOCUMENT_ROOT'];
    function cleanValues( $s ) {
	$replaces = array();
	$replaces["E'"] = chr(195).chr(131).chr(198).chr(146).chr(195).chr(130).chr(203).chr(134); 
	$replaces['"'] = chr(195).chr(131).chr(194).chr(162).chr(195).chr(130).chr(226).chr(130).chr(172).chr(195).chr(130).chr(197).chr(147);
	$replaces["'"] = chr(195).chr(131).chr(194).chr(162).chr(195).chr(130).chr(226).chr(130).chr(172).chr(195).chr(130).chr(226).chr(132).chr(162);
	$replaces[141] = chr(195).chr(131).chr(198).chr(146).chr(195).chr(130).chr(194).chr(172);
	$replaces[133] = chr(195).chr(131).chr(198).chr(146).chr(195).chr(130).chr(194).chr(160);
	$replaces[151] = chr(195).chr(131).chr(198).chr(146).chr(195).chr(130).chr(194).chr(185);
	$replaces[138] = chr(195).chr(131).chr(198).chr(146).chr(195).chr(130).chr(194).chr(168);
	$replaces[149] = chr(195).chr(131).chr(198).chr(146).chr(195).chr(130).chr(194).chr(178);
	$replaces[176] = chr(195).chr(131).chr(226).chr(128).chr(154).chr(195).chr(130).chr(194).chr(176);
	$out[141] = "�";
	$out[133] = "�";
	$out[151] = "�";
	$out[138] = "�";
	$out[149] = "�";
	$out[176] = "�";
	
	foreach( $replaces as $k => $v ){
      if( is_numeric( $k ) ){
        $s = str_replace( $v, chr( $k ), $s );
      } else {
        $s = str_replace( $v, $k, $s );
      }
    }
	
	$s = str_replace( chr(195).chr(131).chr(194).chr(162).chr(195).chr(130).chr(226).chr(130).chr(172).chr(195).chr(130).chr(194).chr(157), '"', $s );
        $s = str_replace( chr(195).chr(131).chr(194).chr(162).chr(195).chr(130).chr(226).chr(130).chr(172).chr(195).chr(130).chr(203).chr(156), "'", $s );
        $s = str_replace( chr(195).chr(131).chr(198).chr(146).chr(195).chr(130).chr(194).chr(169), chr(138), $s );
        $s = str_replace( chr(195).chr(131).chr(194).chr(162).chr(195).chr(130).chr(226).chr(130).chr(172).chr(195).chr(130).chr(194).chr(166), "", $s );

    
        $s1 = "";
        for($i=0; $i < strlen( $s ); $i++ ){
        $car = substr( $s, $i, 1 );
        $ord = ord( $car );
        if( $ord > 127 && ( $replaces[$ord] == "" ) ){
          
          $s1 .= $car;
        } else {
          
          if( $out[$ord] ){
            $s1 .= $out[$ord];  
          } else {
            $s1 .= $car;
          }
        }
    }    
    return $s1;    
    }

    function readCsv($csvFile) {
	$row = 1;
	$csvData = array();
	if (($handle = fopen($csvFile, "r")) !== FALSE) {
	    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	        $num = count($data);
	       // echo "<p> $num fields in line $row: <br /></p>\n";
	        $row++;
	        array_push($csvData, $data);
	      /*  for ($c=0; $c < $num; $c++) {
	            echo $data[$c] . "<br />\n";
	        }*/
	    }
	    fclose($handle);
	}	
	
	return $csvData;
}


    function addPage($data, $cHandle, $parent=125 /* Fito1 */, $attributes, $mainAreaContent){

	//check the page Exists or not	
	$cuur_page_path = strtolower( str_replace( '/', '-', $data['name'] ) );
	$cuur_page = Page::getByPath($cuur_page_path);
	 
		 
        if($cuur_page->cID){
        return $cuur_page;
        }

        $parent = Page::getByID( $parent );
	
	
	
	
	$pt = CollectionType::getByHandle($cHandle);
	$article = $parent->add( $pt, $data );
	foreach($attributes as $k=>$v){
		$article->setAttribute( $k,  $v);		
	}
	if($mainAreaContent !=''){
    	$bt = BlockType::getByHandle('content');
    	$article->addBlock( $bt, 'Main', array( 'content' =>$mainAreaContent ) );
	}
	return $article;
}

    function cleanValuesInArray($contents){

	$data = array();
	foreach($contents as $k){
            array_push($data, cleanValues( $k));
	}
	return $data;
    }

    function getFormattedDate($date){
	return date("m/d/Y", strtotime($date));
    }

    function importImages($images, $Names){
	
	$img = explode(',', $images);
	$nam = explode(',', $Names);
	$docRoot = $_SERVER['DOCUMENT_ROOT'];
	$fIDs = array();
	$incr = 0;
	$imageID = 0;
	foreach($img as $f){
            $fls = explode('/', $f);

            $file = basename($f);

            if($file !=''){
                $fileLoc = $docRoot."/tools/attachments/".$file;	

                if(file_exists($fileLoc)){
                   $file_name = $file;
                   if(isset($nam[$incr])){
                           $file_name = $nam[$incr];
                   }	   			   
                        $fi = new FileImporter();
                        $fileObject = $fi->import( $fileLoc);	
                            if(is_object($fileObject)){
                                $fid = $fileObject->getFileID();
                                array_push($fIDs, $fid);
                                    if(!preg_match('/pdf/', $file)){
                                        $imageID = $fid;
                                    }
                                }
                    }	
			
                    $incr++;	
		}
	}
	
	return array('img_ID'=>$imageID, 'fIDs'=>$fIDs);
	
    }

    function getOrCreatePage(){
	
    }

//End of Functions

/*$db=Loader::db();
$addeIDs=$db->getRow("select distinct ak_old_event_id from CollectionSearchIndexAttributes where ak_old_event_id>0");
foreach($addeIDs as $addeID){
	$addedeventIDs[]=$addeID['ak_old_event_id'];
}
print_r($addedeventIDs);*/
//die;
    ini_set('auto_detect_line_endings',TRUE);
    $csvData = readCsv($docRoot."/files/news/news.csv");




    $fields = $csvData[0];
    unset($csvData[0]);
    $addedParents = array();
    $addednewsIDs=array();
       foreach($csvData as $k){
         $timestamps=array();

     $NW_STORY_SLUG = $k[0];
	 $NW_REPORTERNAME = $k[1];
	 $NW_DATE = $k[2];
	 $NW_PRIMARY_HEADLINE = $k[3];
	 $NW_SECONDARY_HEADLINE = $k[4];
	 $NW_DATELINE = $k[5];
	 $NW_ARTICLE = $k[6];
	 $NW_SUMMARY = $k[7];	 
	 $NW_DISTRICT = $k[8];
	 $NW_REGIONS = $k[9];
	 $NW_PROMOTION = $k[10];
	 $NW_REGIONAL_FEATURE=$k[11];
	 $NW_TAGS=$k[12];
	 $NW_MEDIA=$k[13];
	 $NW_PHOTO1=$k[14];
	 $NW_CAPTION1=$k[15];
	 $NW_PHOTO2=$k[16];
	 $NW_CAPTION2=$k[17];
	 $NW_PHOTO3=$k[18];
	 $NW_CAPTION3=$k[19];
	 $NW_PHOTO4=$k[20];
	 $NW_CAPTION4=$k[21];
	 $NW_PHOTO5=$k[22];
	 $NW_CAPTION5=$k[23];
	
	 
	 $carticle = str_replace(',', '', $NW_ARTICLE);
	 $cdiscription = strip_tags($NW_SUMMARY);
	 
	 
	 
	 $cdistrict = explode(',',$NW_DISTRICT);
	 $ctag = explode(',',$NW_TAGS);
	 $cregion = explode(',',$NW_REGIONS);
	 
	 
	 
	 
	 $parent = Page::getByID(Config::get('SECTIONS_ID'));
		$ct = CollectionType::getByID(Config::get('PAGE_TYPE_ID'));
		sort($timestamps,SORT_NUMERIC);	
		
		$start_date = date("Y-m-d", strtotime($NW_DATE));
		
		$handle = preg_replace('/[^a-z]+/i', '-', $NW_PRIMARY_HEADLINE); 
                
		$data = array('cName' => $NW_PRIMARY_HEADLINE, 'cHandle' => $handle, 'cDescription' => $cdiscription, 'cDatePublic' => $start_date);
		$p = $parent->add($ct, $data);
		$timings=array();
				
		$blocks = $p->getBlocks('Main');
		foreach($blocks as $b) {
			if($b->getBlockTypeHandle()=='content'){
				$b->deleteBlock();
			}
		}
		$bt = BlockType::getByHandle('content');
		$data = array('content' => $carticle);			
		$block=$p->addBlock($bt, 'Main', $data);
		$b=Block::getByID($block->bID);
		$b->setCustomTemplate('news_post');
		$p->reindex();
		
		if($NW_STORY_SLUG!=''){
		$p->setAttribute('story_slug',$NW_STORY_SLUG);
		}
		if($NW_REPORTERNAME!=''){
		$p->setAttribute('author',$NW_REPORTERNAME);
		}		
		if($NW_SECONDARY_HEADLINE!=''){
		$p->setAttribute('secondary_headline',$NW_SECONDARY_HEADLINE);
		}
		if($NW_DATELINE!=''){
		$p->setAttribute('dateline',$NW_DATELINE);
		}		
		if($NW_DISTRICT!=''){
		$p->setAttribute('district',$cdistrict);
		}
		if($NW_REGIONS!=''){
		$p->setAttribute('news_category',$cregion);
		}
		if($NW_TAGS!=''){
		$p->setAttribute('news_tag',$ctag);
		}
		if($NW_REGIONAL_FEATURE!='' && $NW_REGIONAL_FEATURE!= 'Not Regional Feature'){
		$p->setAttribute('regional_feature',1);
		}
		if($NW_MEDIA!='' && $NW_MEDIA == 'Single Photo'){		
		$file = File::getByID($NW_PHOTO1);		
		$p->setAttribute('main_photo',$file);
		$p->setAttribute('single_multiple_photo_status','1');
		
		
		if($NW_CAPTION1!=''){
		$p->setAttribute('photo_caption',$NW_CAPTION1);
		}
		
		}
		
		if($NW_MEDIA!='' && $NW_MEDIA == 'Multiple Photos'){
		
		
		
    //$sliderimages=$NW_PHOTO1.','.$NW_PHOTO2.','.$NW_PHOTO3.','.$NW_PHOTO4.','.$NW_PHOTO5.'||'.$NW_CAPTION1.','.$NW_CAPTION2.','.$NW_CAPTION3.','.$NW_CAPTION4.','.$NW_CAPTION5;
      $sliderimages = $NW_PHOTO1 . "^" . $NW_PHOTO2 . "^" .  $NW_PHOTO3 . "^" . $NW_PHOTO4 .  "^" . $NW_PHOTO5 . "||" . htmlspecialchars($NW_CAPTION1) . "^" . htmlspecialchars($NW_CAPTION2) . "^" . htmlspecialchars($NW_CAPTION3) . "^" . htmlspecialchars($NW_CAPTION4) . "^" . htmlspecialchars($NW_CAPTION5);     
	
		$p->setAttribute('files',$sliderimages);
		$p->setAttribute('single_multiple_photo_status','2');
	
		
		
		}
		if($NW_MEDIA!='' && $NW_MEDIA == 'None'){
		$p->setAttribute('single_multiple_photo_status','3');
		}
		
		
			       
	 
   
   }
	
}
	
	
		
	
}