<?php
use \FeedWriter\ATOM;

class RssFeedController extends Controller  { 

    
     public function view() {
        $districtAbbrev = filter_input(INPUT_GET, 'district', FILTER_DEFAULT);
        $districtPagesHelper = Loader::helper('district_pages');
        $districtName = $districtPagesHelper->getByAbbrev($districtAbbrev);
         
        $feed = $this->createFeed($districtName);

        //OK. Everything is done. Now generate the feed.
        $this->set('feed',$feed);
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
    
    public function createFeed($district) {
        Loader::helper('get_news_info');
        Loader::library('FeedWriter/Item');
        Loader::library('FeedWriter/Feed');
        Loader::library('FeedWriter/RSS2');
        //Creating an instance of RSS1 class.

        date_default_timezone_set('UTC');

        $feed = new \FeedWriter\RSS2();

        //Setting the channel elements
        //Use wrapper functions for common elements
        //For other optional channel elements, use setChannelElement() function
        $feed->setTitle(SITE);
        $feed->setLink(BASE_URL);
//        $feed->setDate(new DateTime());
        //Set feed description to the home page description
        $home = Page::getByID(1);
        $feed->setDescription($home->getAttribute('meta_description'));
        $feed->addNamespace('media', 'http://search.yahoo.com/mrss/');
        //It's important for RSS 1.0 
        $feed->setChannelAbout(BASE_URL . '/about');

        //Adding a feed. Generally this portion will be in a loop and add all feeds.

        //Get featured district articles, if necessary 
        if ($district) {
            $newsArticleList = GetNewsInfoHelper::buildFeaturedList($district);
        } else {
            $newsArticleList = GetNewsInfoHelper::getRecentNews();
        }
        
        foreach ($newsArticleList as $article) {
            $this->addFeedItem($feed, $article);
        }
        return $feed;
    }
    
    public function addFeedItem($feed, $article) {
        //Create an empty FeedItem
        $newItem = $feed->createNewItem();                  
        //Add elements to the feed item
        //Use wrapper functions to add common feed elements
        $newItem->setTitle(strip_tags($article->title));
         $newItem->setAuthor($article->author, '');
        $newItem->setLink($article->link);

        $newItem->setDate($article->date);

        $desc = "<em>" . $this->parseDistrict($article->dateline) . ", MI &#151; </em>";
        $desc .= strip_tags($article->summary, "<br>,<em>,<strong>");

        $newItem->setDescription($desc);

        $newItem->addElement('media:content', null, array('url'=>$article->thumbnail, 'medium'=>'image'));
        //Now add the feed item
        $feed->addItem($newItem);
    }
}
?>
