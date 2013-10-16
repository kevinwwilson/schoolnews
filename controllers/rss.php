<?php
use \FeedWriter\ATOM;
class RssController extends Controller  { 

    
     public function view() {
        Loader::helper('get_news_info');
        Loader::library('FeedWriter/Item');
        Loader::library('FeedWriter/Feed');
        Loader::library('FeedWriter/ATOM');
       
        
        date_default_timezone_set('UTC');

        //Creating an instance of RSS1 class.
        $Feed = new ATOM;

        //Setting the channel elements
        //Use wrapper functions for common elements
        //For other optional channel elements, use setChannelElement() function
        $Feed->setTitle(SITE);
        $Feed->setLink(BASE_URL);
        $Feed->setDate(new DateTime());
        
        //Set feed description to the home page description
//        $home = Page::getByPath("/", 'active');
//        $Feed->setDescription($home->getCollectionDescription());
        
        //It's important for RSS 1.0 
        $Feed->setChannelAbout(BASE_URL . '/about');

        //Adding a feed. Generally this portion will be in a loop and add all feeds.

        $articles = GetNewsInfoHelper::getRecentNews(15);

        foreach ($articles as $key => $article) {
            //Create an empty FeedItem
            $newItem = $Feed->createNewItem();
            $article_path = 'http://www.schoolnewsnetwork.org/news/?rID=' .$article['recordID'];
            
            //Add elements to the feed item
            //Use wrapper functions to add common feed elements
            $newItem->setTitle(strip_tags($article['values']['primary_headline']));
            $newItem->setLink($article_path);            
            $newItem->setDate($article['values']['date'] . 'T12:00');
            $newItem->setAuthor($article['values']['Name']);
            
            $desc = "<em>" . $article['values']['dateline'] . ", MI &#151; </em>";
            $desc .= strip_tags($article['values']['summary'], "<br>,<em>,<strong>");
            
            $newItem->setDescription($desc);
            
            //$newItem->setContent($article['values']['article']);
            
//            //Use core addElement() function for other supported optional elements
//            $newItem->addElement('dc:subject', $article['values']['dateline'] . ", MI");
            
            if ((int)$article['values']['photo1'] > 0) {
                $f = File::getByID($article['values']['photo1']);
                $fv = $f->getApprovedVersion();
                $image_path = $fv->getDownloadURL();
                
                //set as feed enclosure as the 'main' image
                //$newItem->setEnclosure($image_path, $fv->getFullSize(), $fv->getMimeType());
                
                //also include in article HTML at top
                //$image_html = '<a href="' . $image_path . '"/></a>' ;
                $image_html = '<p><img src="' .$image_path .'"></p>';
                $content_html = $image_html . $article['values']['article'];
                $newItem->setContent($content_html);
            }
            else {
                 $newItem->setContent($article['values']['article']);
            }
            
            
            //Now add the feed item
            $Feed->addItem($newItem);

        }
        //OK. Everything is done. Now generate the feed.

                $this->set('feed',$Feed);

        }
}
?>
