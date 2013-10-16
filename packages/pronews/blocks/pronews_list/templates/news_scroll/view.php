<?php 
	defined('C5_EXECUTE') or die(_("Access Denied."));
	$textHelper = Loader::helper("text"); 
	// now that we're in the specialized content file for this block type, 
	// we'll include this block type's class, and pass the block to it, and get
	// the content
	
	$scrollDuration = 4000;
	$scrollHeight = 295;
	$scrollWidth = 240;
	
?>

<script type="text/javascript" language="JavaScript">
var headline_count;
var headline_interval;
var old_headline = 0;
var current_headline = 0;
$(document).ready(function(){
  headline_count = $("#scrollnews div.headline").size();
  $("#scrollnews div.headline:eq("+current_headline+")").css('top', '5px');
 
  headline_interval = setInterval(headline_rotate,<?php  echo $scrollDuration; ?>);
  $('#scrollnews #scrollup').hover(function() {
    clearInterval(headline_interval);
  }, function() {
    headline_interval = setInterval(headline_rotate,<?php  echo $scrollDuration; ?>);
    headline_rotate();
  });
});
function headline_rotate() {
  current_headline = (old_headline + 1) % headline_count;
  $("#scrollnews div.headline:eq(" + old_headline + ")")
    .animate({top: -<?php  echo $scrollHeight+5 ; ?>},"slow", function() {
      $(this).css('top', '<?php  echo $scrollHeight+10;?>px');
    });
  $("#scrollnews div.headline:eq(" + current_headline + ")")
    .animate({top: 5},"slow");  
  old_headline = current_headline;
}
</script>



	<style>.headline{top: <?php  echo $scrollHeight+10;?>px ; height: <?php  echo $scrollHeight-5 ; ?>px ; width: <?php  echo $scrollWidth-10 ; ?>px ; }</style>
	<style>#scrollup{height: <?php  echo $scrollHeight ; ?>px ; width: <?php  echo $scrollWidth ; ?>px ; }</style>
	
	<div id="scrollnews" style="height: <?php  echo $scrollHeight+10;?>px;">
    	<div id="scrollup">
			<?php 
			for ($i = 0; $i < count($cArray); $i++ ) {
				$cobj = $cArray[$i]; 
				$title = $cobj->getCollectionName();
				$newsDate = $cobj->getCollectionDatePublic(DATE_APP_GENERIC_MDY_FULL);
				$imgHelper = Loader::helper('image'); 
				$imageF = $cobj->getAttribute('thumbnail');
				if (!empty($imageF)) { 
		    	//$image = $cobj->getAttribute('thumbnail'); 
		    	$image = $imgHelper->getThumbnail($imageF, 150,90)->src; 
				} 
				?>
				
				<div class="headline">
					<h2 class="ccm-page-list-title"><a href="<?php echo $nh->getLinkToCollection($cobj)?>"><?php echo $title ?></a></h2>
					<h4><?php  echo $newsDate ; ?></h4>
						<?php 
						if(isset($image)){
							echo '<img src="'.$image.'" style="float:left; padding: 0px 10px 10px 0px;" alt="news_image"/>';
						 }
					if($use_content > 0){
						$block = $cobj->getBlocks('Main');
						foreach($block as $b) {
							if($b->getBlockTypeHandle()=='content'){
								$content = $b->getInstance()->getContent();
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
						unset($image);
						?>
				</div>
			<?php 
			}
			?>
		</div>
	</div>