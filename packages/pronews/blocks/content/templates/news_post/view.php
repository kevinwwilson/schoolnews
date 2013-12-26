<?php  
	defined('C5_EXECUTE') or die(_("Access Denied."));
	global $c;
	
	Loader::model("attribute/categories/collection");
	
	$newsTitle = $c->getCollectionName();
	$newsDate = $c->getCollectionDatePublic(DATE_APP_GENERIC_MDYT_FULL);
	
	$ak_g = CollectionAttributeKey::getByHandle('news_tag'); 
	$tags = $c->getCollectionAttributeValue($ak_g); 
	
	$ak_u = CollectionAttributeKey::getByHandle('news_url'); 
	$url = $c->getCollectionAttributeValue($ak_u); 

	?>
	<div class="news-attributes">
		<div>
			<h2><?php  echo $newsTitle; ?> </h2>
			<?php 
			if (!empty($url)){
			
			echo '<i><u><a href="'.$url.'">'.$url.'</a></u></i>';
			
			}
			?>
			<h4><?php  echo $newsDate ?></h4>
		</div>
	</div>

<?php 
	
	$content = $controller->getContent();
	print $content;
?>

	<div>
		Tags: <i><?php  echo $tags; ?></i>
	</div>
