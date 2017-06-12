<?php

defined('C5_EXECUTE') or die("Access Denied.");
$docRoot = $_SERVER['DOCUMENT_ROOT'];

class JsonExportFile extends Job {
	   public function getJobName() {
	   return t('News article export');
	   }

	   public function getJobDescription() {
	   return t("Creating Json export of all news articles");
	   }


	   public function run() {
		   Loader::model('article');
			$newsResults = $this->getAllNews();
			$newsData = article::buildFromPageList($newsResults);
			$this->writeDataFile($newsData);
	   }

	   private function getAllNews() {
		   Loader::model('page_list');
		   $nh = Loader::helper('navigation');
		   $newsSectionList = new PageList();
		   $newsSectionList->setItemsPerPage(15);
		   $newsSectionList->filterByNewsSection(1);
		   $newsSectionList->sortBy('cvName', 'asc');
		   $tmpSections = $newsSectionList->get();

		   $sections = array();
		   $newsData = array();

		   foreach($tmpSections as $_c) {
			   $sections[$_c->getCollectionID()] = $_c->getCollectionName();
		   }
				$newsList = new PageList();

				if(!empty($sections)){
					//$sections = $this->get('sections');
					$keys = array_keys($sections);
					$keys[] = -1;
					$newsList->filterByParentID($keys);
					$newsList->filter(false,"ak_group_status like '%Published%'");
				}
			$newsList -> sortByPublicDateDescending();
			$newsResults=$newsList->get();

			return $newsResults;
	   }

	   private function parsePageObject($page) {
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

			$pageData =
			array(
				'District' => $disimplode,
				'Date' => $date,
				'URL' => $full_url,
				'Headline' => $primary_headline,
				'Summary' => $summary,
				'Thumbnail' => $full_thumb_path
			);
			return $pageData;
	   }

	   private function writeDataFile($data) {
		   $json = json_encode($newsData);
		   $date = date('Y-m-d');
		   $filename = fopen(DIR_BASE.'/files/export' . $date .'.json',"w+")or die("can't open file");
		   fwrite($filename, $json);
		   fclose($filename);
	   }

}

?>
