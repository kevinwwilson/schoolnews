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

			$filename = $this->writeDataFile($newsData);
			return ('Created file at ' . $filename);
	   }

	   private function getAllNews() {
			Loader::model('page_list');
			$nh = Loader::helper('navigation');
			$newsSectionList = new PageList();
			$newsSectionList->filterByNewsSection(1);
			$newsSectionList->sortBy('cvName', 'asc');
			$tmpSections = $newsSectionList->get();
			$sections = array();

			foreach($tmpSections as $_c) {
			   $sections[$_c->getCollectionID()] = $_c->getCollectionName();
			}
			$newsList = new PageList();

			if(!empty($sections)){
				$keys = array_keys($sections);
				$keys[] = -1;
				$newsList->filterByParentID($keys);
				$newsList->filter(false,"ak_group_status like '%Published%'");
				$newsList -> sortByPublicDateDescending();
				$newsResults=$newsList->get();
			}

			return $newsResults;
	   	}


	   private function writeDataFile($data) {
		   $json = json_encode($data);
		   $date = date('Y-m-d');
		   $path = DIR_BASE.'/files/export' . $date .'.json';
		   $filename = fopen($path,"w+")or die("can't open file");
		   fwrite($filename, $json);
		   fclose($filename);
		   return $path;
	   }

}

?>
