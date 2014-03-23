
<?php

header('Content-Type: application/javascript');

Loader::helper('get_news_info');

//$newsList = file_get_contents('/files/widget/news.json');

////temporary until it can be fed in
//$days = 30;
      
//$newsList = GetNewsInfoHelper::getRecentNews($days);


//update thumbnail to be a path rather than file id
//for($i = 0; $i < count($newsList); $i++) { 
//    if ((int)$newsList[$i]['values']['thumbnail'] > 0) {
//        $f = File::getByID($newsList[$i]['values']['thumbnail']);
//        $fv = $f->getApprovedVersion();
//        $image_path = $fv->getURL();
//        $newsList[$i]['values']['thumbnail']=$image_path;
//    }
//}

echo $_GET['callback']."(";
include '/files/widget/news.json';
echo");";
        
?>