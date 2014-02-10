<?php  
	defined('C5_EXECUTE') or die(_("Access Denied."));
	$textHelper = Loader::helper("text"); 
	// now that we're in the specialized content file for this block type, 
	// we'll include this block type's class, and pass the block to it, and get
	// the content
	
	if (count($cArray) > 0) { ?>

        <div class="news-block">
	<?php  
	for ($i = 0; $i < count($cArray); $i++ ) {
		$cobj = $cArray[$i]; 
		$title = $cobj->getCollectionName();
		$secondaryheadline = $cobj->getAttribute('secondary_headline');
		$author = $cobj->getAttribute('author');
		$photo_caption = $cobj->getAttribute('photo_caption');
		$dateline = $cobj->getAttribute('dateline');
		$newsDate = $cobj->getCollectionDatePublic('F jS Y');
		$slideimage = $cobj->getAttribute('files');	
	    $sliderimages = explode('^',$slideimage);
		$pid = $cobj->cID;
		?>
		
		<div class="article guest">
<h2><?php echo $title; ?></h2>
    <h4><?php echo $secondaryheadline; ?></h4>
    <strong class="date">by <?php echo $author; ?></strong>

       <?php if($cobj->getAttribute('main_photo') != '' && $cobj->getAttribute('single_multiple_photo_status') == 1){ ?>
        <div class="image-holder">
       <?php $CatImage = $cobj->getAttribute('main_photo');
                   if($CatImage){
	               $ih= Loader::helper('image');
	               $image_arr['realimg'] = $CatImage->getRelativePath();
	               $thumb = $ih->getThumbnail($CatImage, 400, 283);                                
				    $image = '';			       
				    $image = '<img alt="" src="'.$thumb->src.'">';	
					echo $image;
					}
					?>
       <strong class="title"><?php echo $photo_caption ?></strong>
       </div>
       <?php } elseif($slideimage != '' && $cobj->getAttribute('single_multiple_photo_status') == 2) {?>
    
    <div class="slideshow-holder">
        <ul class="news-slideshow">
           <?php foreach($sliderimages as $simages){ 
		     
              $sliders = explode('||',$simages)?>
           
            <li>
                <?php 
				   $f = File::getByID($sliders[0]);
				   $ih= Loader::helper('image');	
				   $image_arr['realimg'] = $f->getRelativePath();
				   $thumb = $ih->getThumbnail($f, 400, 283);                                
				    $image = '';			       
				    $image = '<img alt="" src="'.$thumb->src.'">';	
					echo $image;						
					
					?>                
                
                <strong class="title"><?php echo $sliders[1] ?></strong>
            </li>
              <?php } ?>      
        </ul>
    <nav>
        <ul class="switcher">
        <?php foreach($sliderimages as $simages){ ?>
            <li class=""><a href="#"></a></li>
			<?php } ?>            
        </ul>
    </nav>
    </div>
   <?php  } ?> 

    
    
    <div id="article_content">
    <p><span class="dateline"><?php echo $dateline ?>, MI — </span>
      <?php $block = $cobj->getBlocks('Main');
                           foreach($block as $bi) {
                                   if($bi->getBlockTypeHandle()=='content'){
                                           $content = $bi->getInstance()->getContent();
                                   }
                           } 

                   echo $content;	
                           ?>
        </div>
           <strong class="date">Submitted on: <span id="pub_date"><?php echo $newsDate ?></span></strong>
       </div>

     <?php  } 	?>
    </div>			
		
<?php } ?>
<div class="article-fade"> </div>
<div class="more-guest">
    <a class="more" href="<?php echo $nh->getLinkToCollection($cobj) ?>">Read Full Article  »</a>
</div>
<script type="text/javascript">


</script>

