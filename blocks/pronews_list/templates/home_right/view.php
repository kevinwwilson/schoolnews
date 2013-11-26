<?php  
	defined('C5_EXECUTE') or die(_("Access Denied."));
	$textHelper = Loader::helper("text"); 
	// now that we're in the specialized content file for this block type, 
	// we'll include this block type's class, and pass the block to it, and get
	// the content
	
	$db = Loader::db();		 
	$row = $db->GetArray('SELECT * FROM btselectProNewsList');
	foreach($row as $data){
			if($data['active'] == 1){			
			$active_artid = $data['atID'];
		}
		
	}
	$articleids = explode('||',$active_artid);		
	$atids = array();
	                             foreach($articleids as $displayid){
	                             Loader::model('page_list');
	                             $pl = new PageList();	                             	                                 
	                             $pl->filter(false, '( cv.cID in('.$displayid.') )');
	                             $pl->filter(false,"ak_group_status like '%Active%'");	                             
	                             $pages = $pl->getPage(); 	                                                       
	                             foreach($pages as $cpage){ 
	                             
	                             $atids[] = $cpage->cID;
	                             
	                             }
	                             }	
	                                                          
	shuffle($atids); 
	$m=0;
	foreach($atids as $displayid){	
	
	Loader::model('page_list');
	$plz = new PageList(); 
	$plz->filter(false,"ak_group_status like '%Active%'"); 
	        
	$pages = $plz->get(); 
	
	foreach($pages as $cobj){ 
    
	     if($cobj->cID == $displayid && $m < 4){
	     
		    
			 $title = $cobj->getCollectionName();
		     $author = $cobj->getAttribute('author');
		     $dateline = $cobj->getAttribute('dateline'); 
	     
		     $cpage->setAttribute('group_status','Active');
		     $nh = Loader::helper('navigation');
		     $url = $nh->getLinkToCollection($cpage);	                             	                             
		     $photo_caption = $cobj->getAttribute('photo_caption');
			 $secondary_headline = $cobj->getAttribute('secondary_headline');
			 $dateline = $cobj->getAttribute('dateline');									
			 $CatImage = $cobj->getAttribute('main_photo');
			 $image = '';
			        if(is_object($CatImage)){
			         $ih= Loader::helper('image');
	                $image_arr['realimg'] = $CatImage->getRelativePath();
	                $thumb = $ih->getThumbnail($CatImage, 469, 331);
			        
				    $image = '<img alt="" src="'.$thumb->src.'">';
				    }
			 
			 if($m == 0){
	
	     ?>



				<div class="content-block-holder">
				
					<!--ASC:-->	
	
<!--?xml version="1.0"?-->



    <div class="slide-block-noborder gallery-js-ready autorotation-active">
        <ul class="slideshow" style="height: 331px; ">
            <li style="display: block; " class="active">
                <?php echo $image; ?>
                <strong class="caption" style="display: none; "><?php echo $photo_caption ?></strong>
            </li>
        </ul>
    </div>


    <article class="post">
        <h1><?php  echo $title?></h1>
        <h2><?php echo $secondary_headline ?></h2>
        
        <strong class="date">by <?php echo $author ?></strong>
        <p><span class="dateline"><?php echo $dateline?> — </span>
<?php  
		if($use_content > 0){
			$block = $cpage->getBlocks('Main');
			foreach($block as $bi) {
				if($bi->getBlockTypeHandle()=='content'){
					$content = $bi->getInstance()->getContent();
				}
			}
		}else{
			$content = $cpage->getCollectionDescription();
		}
		if(!$controller->truncateSummaries){
			echo $content;
		}else{
			echo $textHelper->shorten($content,$controller->truncateChars);
		}
		?>
        <br/>
        </p>
         <a href="<?php  echo BASE_URL.DIR_REL;?>/index.php?cID=<?php echo $cobj->cID?>">READ FULL STORY »</a>
    </article>


									
				</div>
				
				<div class="slide-col">
					<div class="mask">
						<div class="carousel">
							<div class="slide">
				<?php } else { ?>
				<!-- slide-col -->
				
								<!--ASC:-->	
	
<!--ASC:-->	
	
<!--?xml version="1.0"?-->


<div class="box">
            <strong class="title"><?php  echo $title?></strong>
            <strong class="date">by <?php echo $author ?></strong>
    <p><span class="dateline"><?php echo $dateline ?> — </span>
<?php  
		if($use_content > 0){
			$block = $cobj->getBlocks('Main');
			foreach($block as $bi) {
				if($bi->getBlockTypeHandle()=='content'){
					$content = $bi->getInstance()->getContent();
				}
			}
		}else{
			$content = $cobj->getCollectionDescription();
		}
		if(!$controller->truncateSummaries){
			echo $content;
		}else{
			echo $textHelper->shorten($content,$controller->truncateChars);
		}
		?>
        <br/>
        </p>
       <a href="<?php  echo BASE_URL.DIR_REL;?>/index.php?cID=<?php echo $cobj->cID?>">More »</a> 
           
    </div>
<!--?xml version="1.0"?-->


							
				
				
<?php } } } $m++; }?>

                             </div>
						</div>
					</div>

				</div> 
