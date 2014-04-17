<?php

defined('C5_EXECUTE') or die("Access Denied.");
$docRoot = $_SERVER['DOCUMENT_ROOT'];

class JsonSummaryFile extends Job {
	   public function getJobName() {
	   return t('Json Summary File for News');
	   }

	   public function getJobDescription() {
	   return t("Creating Json Summary Files for News");
	   }


	   public function run() {
	    Loader::model('page_list');
	    $nh = Loader::helper('navigation');
	    $newsSectionList = new PageList();
		$newsSectionList->setItemsPerPage(15);
		$newsSectionList->filterByNewsSection(1);
		$newsSectionList->sortBy('cvName', 'asc');
		$tmpSections = $newsSectionList->get();
		$sections = array();

		$lastthree_date = date("Y-m-d H:i:s",strtotime("-3 Months"));




		foreach($tmpSections as $_c) {
			$sections[$_c->getCollectionID()] = $_c->getCollectionName();
		}
	         $newsList = new PageList();

	         if(!empty($sections)){
			 //$sections = $this->get('sections');
			 $keys = array_keys($sections);
			 $keys[] = -1;
			 $newsList->filterByParentID($keys);
			 }
			 $newsList -> sortByPublicDateDescending();
			 $newsResults=$newsList->get(2000);

			 $arr = array();
			 foreach($newsResults as $cobj){




			 $district = $cobj->getCollectionAttributeValue('district');

			 $dis = array();

			 foreach($district as $dist){
			    $dis[] = $dist->value;

			 }



			 $disimplode = implode(",",$dis);




			 $date = $cobj->getCollectionDatePublic();
			 $url = $nh->getLinkToCollection($cobj);
			 $base = BASE_URL.DIR_REL;
			 $full_url = $base.''.$url;
			 $primary_headline = $cobj->getCollectionName();
			 $summary = $cobj->getCollectionDescription();
			 $thumbnail = $cobj->getCollectionAttributeValue('thumbnail');
			 if($thumbnail != ''){
			 $thumbnail_path = $thumbnail->getRelativePath();
			 $full_thumb_path = $base.''.$thumbnail_path;
			 } else {

				$full_thumb_path = '';
			 }


			  if($date > $lastthree_date) {


			  $arr[] = array('District' => $disimplode, 'Date' => $date, 'URL' => $full_url, 'Headline' => $primary_headline, 'Summary' => $summary, 'Thumbnail' => $full_thumb_path);

			  }
			 }


			 $json = json_encode($arr);

			 	$filename = fopen(DIR_BASE.'/files/widget/news.json',"w+")or die("can't open file");
				fwrite($filename, $json);
				fclose($filename);

	   }


}

?>