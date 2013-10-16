<?php 

defined('C5_EXECUTE') or die("Access Denied.");
class SitemapNewsOnly extends Job {

	/** The end-of-line terminator.
	* @var string
	*/
	const EOL = "\n";

	/** Returns the job name.
	* @return string
	*/
	public function getJobName() {
		return t('Generate the sitemap.xml file for news');
	}

	/** Returns the job description.
	* @return string
	*/
	public function getJobDescription() {
		return t('Generate the sitemap.xml file only for news items');
	}

	/** Executes the job.
	* @return string Returns a string describing the job result in case of success.
	* @throws Exception Throws an exception in case of errors.
	*/
	public function run() {
			try {
			
			//$rsPages = $db->query('SELECT cID FROM Pages WHERE (cID > 1) ORDER BY cID');
			$relName = ltrim(SITEMAPXML_FILE, '\\/');
			$osName = rtrim(DIR_BASE, '\\/') . '/' . $relName;
			$urlName = rtrim(BASE_URL . DIR_REL, '\\/') . '/' . $relName;
			if(!file_exists($osName)) {
				@touch($osName);
			}
			if(!is_writable($osName)) {
				throw new Exception(t('The file %s is not writable', $osName));
			}
			if(!$hFile = fopen($osName, 'w')) {
				throw new Exception(t('Cannot open file %s', $osName));
			}
			if(!@fprintf($hFile, '<'.'?xml version="1.0" encoding="%s"?>' . self::EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', APP_CHARSET)) {
				throw new Exception(t('Error writing header of %s', $osName));
			}
			//add pages here
			Loader::helper('get_news_info');
			$p = 0;
			$pageList = GetNewsInfoHelper::getPageList(); 
			foreach ($pageList as $row) {
				if(!@fprintf($hFile, self::EOL. '<url>', APP_CHARSET)) {
					throw new Exception(t('Error starting page output', $osName));
				}
				if(!@fprintf($hFile, self::EOL. '<loc>' . $row[0] . '</loc>', APP_CHARSET)) {
					throw new Exception(t('Error writing location', $osName));
				}
				if(!@fprintf($hFile, self::EOL. '<lastmod>' . $row[1] . '</lastmod>', APP_CHARSET)) {
					throw new Exception(t('Error writing modified date', $osName));
				}
				if(!@fprintf($hFile, self::EOL. '<changefreq>daily</changefreq>', APP_CHARSET)) {
					throw new Exception(t('Error writing changefreq', $osName));
				}
				if(!@fprintf($hFile, self::EOL. '<priority>0.5</priority>', APP_CHARSET)) {
					throw new Exception(t('Error writing priority', $osName));
				}
				if(!@fprintf($hFile, self::EOL. '</url>', APP_CHARSET)) {
					throw new Exception(t('Error endng page output', $osName));
				}
				$p++;
				
			}
			
			if(!@fwrite($hFile, self::EOL . '</urlset>')) {
				throw new Exception(t('Error writing footer of %s', $osName));
			}
			@fflush($hFile);
			@fclose($hFile);
			unset($hFile);
			return t('%1$s file saved. '.$p. ' pages).', $urlName, $addedPages);
		}
		catch(Exception $x) {
			if(isset($hFile) && $hFile) {
				@fflush($hFile);
				@ftruncate($hFile, 0);
				@fclose($hFile);
				$hFile = null;
			}
			throw $x;
		}
	
	}
}
	?>