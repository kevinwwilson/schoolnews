<?php
use \FeedWriter\ATOM;

class RssFeedController extends Controller  { 

    
     public function view() {
         
        Loader::helper('get_news_info');
        
        
//        $newsArticleList = GetNewsInfoHelper::getRecentNews();

//        var_dump($newsArticleList); die();       
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
$newsArticleList = GetNewsInfoHelper::getNewsFromJson();

        foreach ($newsArticleList as $article) {
            //Create an empty FeedItem
            $newItem = $Feed->createNewItem();                  
            //Add elements to the feed item
            //Use wrapper functions to add common feed elements
            $newItem->setTitle(strip_tags($article->Headline));
            $newItem->setLink($article->URL);
            
            $newItem->setDate($article->Date);
                        
            $desc = "<em>" . $this->parseDistrict($article->District) . ", MI &#151; </em>";
            $desc .= strip_tags($article->Summary, "<br>,<em>,<strong>");
            
            $newItem->setDescription($desc);
            $image_html = '<p><img src="' .$article->Thumbnail .'"></p>';
            $newItem->setContent($image_html);  
                       
            //Now add the feed item
            $Feed->addItem($newItem);

        }

        //OK. Everything is done. Now generate the feed.
        $this->set('feed',$Feed);
    }
    
    /*
     * Business rule that if the number of districts assigned to the article is greater than 1, then
     * set the dateline to be "Kent ISD".
     */
    public function parseDistrict($districts)
    {
        $arrDist = explode(',', $districts);
        
        if (count($arrDist) == 1) {
            return $arrDist[0];
        } else {
            return 'Kent ISD';
        }
    }
}
?>
