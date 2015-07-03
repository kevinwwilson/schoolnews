<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
use \FeedWriter\ATOM;
//Permissions Check
if($_GET['bID']) {
    
        Loader::helper('get_news_info');
        Loader::library('FeedWriter/Item');
        Loader::library('FeedWriter/Feed');
        Loader::library('FeedWriter/ATOM');
       
        $nh = Loader::helper('navigation');
        
        $c = Page::getByID($_GET['cID'], 'ACTIVE');
	$a = Area::get($c, $_GET['arHandle']);
		
	//edit survey mode
	$b = Block::getByID($_GET['bID'],$c, $a);
	
	$controller = new PronewsListBlockController($b);
	$rssUrl = $controller->getRssUrl($b);
        
        $cArray = $controller->getPages();
        
        date_default_timezone_set('UTC');

        //Creating an instance of RSS1 class.
        $Feed = new ATOM;

        //Setting the channel elements
        //Use wrapper functions for common elements
        //For other optional channel elements, use setChannelElement() function
        $Feed->setTitle($controller->rssTitle);
        $Feed->setLink($rssUrl);
        $Feed->setDate(new DateTime());
        
        //It's important for RSS 1.0 
        $Feed->setChannelAbout(BASE_URL . '/about');

        //Adding a feed. Generally this portion will be in a loop and add all feeds.
	for ($i = 0; $i < count($cArray); $i++ ) {
            $cobj = $cArray[$i]; 
                    
            $newItem = $Feed->createNewItem();
            $article_path = BASE_URL.$nh->getLinkToCollection($cobj);
            
            //Add elements to the feed item
            //Use wrapper functions to add common feed elements
            $newItem->setTitle($cobj->getCollectionName());
            $newItem->setLink($article_path);            
            $newItem->setDate(date( 'D, d M Y H:i:s T',strtotime($cobj->getCollectionDatePublic())));
            $newItem->setAuthor($cobj->getAttribute('author'));
            
            $desc = "<em>" . $cobj->getAttribute('dateline') . ", MI &#151; </em>";
            $desc .= strip_tags($cobj->getCollectionDescription(), "<br>,<em>,<strong>");
            
            $newItem->setDescription($desc);
            
            //Now add the feed item
            $Feed->addItem($newItem);
      
                    
 } 
                //output final feed
                $Feed->printFeed();
 } else {  	
		$v = View::getInstance();
		$v->renderError('Permission Denied',"You don't have permission to access this RSS feed");
		exit;
	}
