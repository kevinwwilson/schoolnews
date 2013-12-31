<?php  
	defined('C5_EXECUTE') or die(_("Access Denied."));
	$textHelper = Loader::helper("text"); 
        Loader::model("attribute/categories/collection");
        // now that we're in the specialized content file for this block type, 
	// we'll include this block type's class, and pass the block to it, and get
	// the content
	global $c;
	if (count($cArray) > 0) { ?>
	<?php 
        
        
	for ($i = 0; $i < count($cArray); $i++ ) {
		$cobj = $cArray[$i]; 
                
		$status = $cobj->getAttribute('group_status');
		$title = $cobj->getCollectionName();
		$author = $cobj->getAttribute('author');
		$dateline = $cobj->getAttribute('dateline');
                $blocks = $cobj->GetBlocks();
                foreach ($blocks as $block) {
                   $controller = $block->getInstance();
                   $block->display();
                }
  
 } 
	
 } 
?>