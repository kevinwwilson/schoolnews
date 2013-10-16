<?php 
	defined('C5_EXECUTE') or die(_("Access Denied."));
	$textHelper = Loader::helper("text"); 
	// now that we're in the specialized content file for this block type, 
	// we'll include this block type's class, and pass the block to it, and get
	// the content
	
	$items_num = 3;
	$scrollDuration = 4000;
	$scrollHeight = 125;
	$scrollWidth = 540;
	$num_posts = count($cArray);
	?>

<script type="text/javascript" language="JavaScript">
var headline_count;
var headline_interval;
var old_headline = 0;
var current_headline = 0;
$(document).ready(function(){
  headline_count = $("div.newsset").size();
  $("div.newsset:eq("+current_headline+")").css('top', '5px');
 
  headline_interval = setInterval(headline_rotate,<?php  echo $scrollDuration; ?>);
  $('#scrollup').hover(function() {
    clearInterval(headline_interval);
  }, function() {
    headline_interval = setInterval(headline_rotate,<?php  echo $scrollDuration; ?>);
    headline_rotate();
  });
});
function headline_rotate() {
  current_headline = (old_headline + 1) % headline_count;
  $("div.newsset:eq(" + old_headline + ")")
    .animate({top: -<?php  echo ($scrollHeight * $items_num)+5 ; ?>},"slow", function() {
      $(this).css('top', '<?php  echo ($scrollHeight * $items_num)+10;?>px');
    });
  $("div.newsset:eq(" + current_headline + ")")
    .animate({top: 5},"slow");  
  old_headline = current_headline;
}
</script>


	<style>
		.newsset{top: <?php  echo ($scrollHeight * $items_num)+10;?>px ; height: <?php  echo $scrollHeight-5 ; ?>px ; width: <?php  echo $scrollWidth-10 ; ?>px ; }
		.headline{top: <?php  echo $scrollHeight+10;?>px ; height: <?php  echo $scrollHeight-5 ; ?>px ; width: <?php  echo $scrollWidth-10 ; ?>px ; }
		#scrollup{height: <?php  echo $scrollHeight * $items_num; ?>px ; width: <?php  echo $scrollWidth ; ?>px ; }
	</style>
	
	<div id="scrollnews">
    	<div id="scrollup">
    		<div class="newsset">
			<?php 
			for ($i = 0; $i < $num_posts; $i++ ) {
				$t++;
				$cobj = $cArray[$i]; 
				$title = $cobj->getCollectionName();
				$newsDate = $cobj->getCollectionDatePublic(DATE_APP_GENERIC_MDY_FULL);
				$imgHelper = Loader::helper('image'); 
				$imageF = $cobj->getAttribute('thumbnail');
				if (!empty($imageF)) { 
		    	//$image = $cobj->getAttribute('thumbnail'); 
		    	$image = $imgHelper->getThumbnail($imageF, 150,90)->src; 
				} 
				if($t>$items_num){
					echo '</div><div class="newsset">';
					$t=1;
				}
				?>
				
				<div class="headline news_<?php echo $i?>">
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
				if($t<$items_num && $i == $num_posts){
					$n = ($items_num - $t);
					for($n=$n;$n<$items_num;$n++){
						echo '<div class="headline"></div>';
					}
				}
				?>
			<?php 
			}
			?>
			</div>
		</div>
		<br style="clear: both;"/>
	</div>