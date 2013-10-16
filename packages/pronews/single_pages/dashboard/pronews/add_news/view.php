<?php  
$df = Loader::helper('form/date_time');

if (is_object($news)) { 
    $secondaryheadline = $news->getCollectionAttributeValue('secondary_headline');
	$author = $news->getCollectionAttributeValue('author');
	$mainphoto = $news->getCollectionAttributeValue('main_photo');
	$photocaption = $news->getCollectionAttributeValue('photo_caption');
	$dateline = $news->getCollectionAttributeValue('dateline');
	$district = $news->getCollectionAttributeValue('district');
	$regional_feature = $news->getCollectionAttributeValue('regional_feature');	
	$news_tag = $news->getCollectionAttributeValue('news_tag');	
	$long_summary = $news->getCollectionAttributeValue('long_summary');	
	$files = $news->getCollectionAttributeValue('files');
	$newsTitle = $news->getCollectionName();
	$newsDescription = $news->getCollectionDescription();
	$newsDate = $news->getCollectionDatePublic();
	$cParentID = $news->getCollectionParentID();
	$ctID = $news->getCollectionTypeID();
	$newsBody = '';
	$eb = $news->getBlocks('Main');
	foreach($eb as $b) {
		if($b->getBlockTypeHandle()=='content'){
			$newsBody = $b->getInstance()->getContent();
		}
	}
	$task = 'edit';
	$buttonText = t('Update News Item');
	$title = 'Update';
} else {
	$task = 'add';
	$buttonText = t('Add News Item');
	$title= 'Add';
}
?>
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t($title.' News').'<span class="label" style="position:relative;top:-3px;left:12px;">'.t('* required field').'</span>', false, false, false);?>
	<div class="ccm-pane-body">
		<!--
		<ul class="breadcrumb">
		  <li><a href="/index.php/dashboard/pronews/list/">List</a> <span class="divider">|</span></li>
		  <li class="active">Add/Edit </li>
		</ul>
		-->
	<?php  if ($this->controller->getTask() == 'edit') { ?>
		<form method="post" action="<?php  echo $this->action($task,$news->getCollectionID())?>" id="news-form">
		<?php  echo $form->hidden('newsID', $news->getCollectionID())?>
	<?php  }else{ ?>
		<form method="post" action="<?php  echo $this->action($task)?>" id="news-form">
	<?php  } ?>
	
			<ul class="tabs">
				<li class="active"><a href="javascript:void(0)" onclick="$('ul.tabs li').removeClass('active'); $(this).parent().addClass('active'); $('.pane').hide(); $('div.post').show();"><?php echo t('Post')?></a>
				</li>
				<li><a href="javascript:void(0)" onclick="$('ul.tabs li').removeClass('active'); $(this).parent().addClass('active'); $('.pane').hide(); $('div.options').show();"><?php echo t('Options')?></a>
				</li>
				<li><a href="javascript:void(0)" onclick="$('ul.tabs li').removeClass('active'); $(this).parent().addClass('active'); $('.pane').hide(); $('div.meta').show();"><?php echo t('Meta')?></a>
				</li>
			</ul>
			<div class="pane post">
            	<div class="clearfix">
					<?php  echo $form->label('storySlug', t('Story Slug'))?>
					<div class="input">
						<?php  
						Loader::model("attribute/categories/collection");
						$akct = CollectionAttributeKey::getByHandle('story_slug');
						if (is_object($news)) {
							$tcvalue = $news->getAttributeValueObject($akct);
						}
						?>
						<?php  echo $akct->render('form', $tcvalue, true);?>
					</div>
				</div>
                
                <div class="clearfix">
					<?php  echo $form->label('author', t('Author'))?>
					<div class="input">
						<?php  
						Loader::model("attribute/categories/collection");
						$akct = CollectionAttributeKey::getByHandle('author');
						if (is_object($news)) {
							$tcvalue = $news->getAttributeValueObject($akct);
						}
						?>
						<?php  echo $akct->render('form', $tcvalue, true);?>
					</div>
				</div>
                
                <div class="clearfix">
					<?php  echo $form->label('newsDate', t('Date/Time'))?>
					<div class="input">
						<?php  echo $df->datetime('newsDate', $newsDate)?>
					</div>
				</div>
                
				<div class="clearfix">
					<?php  echo $form->label('newsTitle', t('Primary Headline'))?> *
					<div class="input">
						<?php  echo $form->text('newsTitle', $newsTitle, array('style' => 'width: 230px'))?>
					</div>
				</div>
				<div class="clearfix">
					<?php  echo $form->label('secondaryHeadline', t('Secondary Headline'))?>
					<div class="input">
						<?php  
						Loader::model("attribute/categories/collection");
						$akct = CollectionAttributeKey::getByHandle('secondary_headline');
						if (is_object($news)) {
							$tcvalue = $news->getAttributeValueObject($akct);
						}
						?>
						<?php  echo $akct->render('form', $tcvalue, true);?>
					</div>
				</div>
                
                <div class="clearfix">
					<?php  echo $form->label('dateline', t('Dateline'))?>
					<div class="input">
						<?php  
						Loader::model("attribute/categories/collection");
						$akct = CollectionAttributeKey::getByHandle('dateline');
						if (is_object($news)) {
							$tcvalue = $news->getAttributeValueObject($akct);
						}
						?>
						<?php  echo $akct->render('form', $tcvalue, true);?>
					</div>
				</div>
                
                
                
                
                
				
                <div class="clearfix">
					<?php  echo $form->label('newsDescription', t('Summary'))?>
					<div class="input">
						<div><?php  echo $form->textarea('newsDescription', $newsDescription, array('style' => 'width: 98%; height: 90px; font-family: sans-serif;'))?></div>
					</div>
				</div>
                
                <div class="clearfix">
					<?php  echo $form->label('longSummary', t('Long Summary'))?>
					<div class="input lsummary">
						<?php  
						Loader::model("attribute/categories/collection");
						$akct = CollectionAttributeKey::getByHandle('long_summary');
						if (is_object($news)) {
							$tcvalue = $news->getAttributeValueObject($akct);
						}
						?>
						<?php  echo $akct->render('form', $tcvalue, true);?>
					</div>
				</div>
				
				<div class="clearfix">
					<?php  echo $form->label('newsBody', t('Article'))?>
					<div class="input">
					<?php  Loader::Element('editor_init'); ?>
					<?php  Loader::Element('editor_config'); ?>
					<?php  //Loader::element('editor_controls', array('mode'=>'full')); ?>
					<?php  Loader::Element('editor_controls',array('mode'=>'full'));?>
					<?php  echo $form->textarea('newsBody', $newsBody, array('style' => 'width: 100%; font-family: sans-serif;', 'class' => 'ccm-advanced-editor'))?>
					</div>
				</div>
                 <div class="clearfix">
                	 <?php  echo  t('Single Image/Slide Show')?>
                     <div class="input" >
                     <?php
                      	//echo $from->radio('image',1,1); echo $from->radio('image',0,1);?>
                        <input type="radio" name="image" value="1" checked="checked" /><?php  echo  t('Single Image')?>
                        <input type="radio" name="image" value="2"/><?php  echo  t('Slide Show')?>
                    </div>
                 </div>
                
                <div class="clearfix" id="single_image">
					<?php  echo $form->label('mainPhoto', t('Main Photo'))?>
					<div class="input">
						<?php  
						Loader::model("attribute/categories/collection");
						$akct = CollectionAttributeKey::getByHandle('main_photo');
						if (is_object($news)) {
							$tcvalue = $news->getAttributeValueObject($akct);
						}
						?>
						<?php  echo $akct->render('form', $tcvalue, true);?>
					</div>
				</div>
                <div class="clearfix" id="slideshow" style="display:none">
					<?php  echo $form->label('files', t('Slideshow Images'))?>
					<div class="input">
						<?php  
						Loader::model("attribute/categories/collection");
						$akct = CollectionAttributeKey::getByHandle('files');
						if (is_object($news)) {
							$tcvalue = $news->getAttributeValueObject($akct);
						}
						?>
						<?php  echo $akct->render('form', $tcvalue, true);?>
					</div>
				</div>
                <div class="clearfix">
					<?php 
					Loader::model("attribute/categories/collection");
					$akt = CollectionAttributeKey::getByHandle('thumbnail');
					if (is_object($news)) {
						$tvalue = $news->getAttributeValueObject($akt);
					}
					?>
					<?php  echo $akt->render('label');?>
					<div class="input">
						<?php  echo $akt->render('form', $tvalue, true);?>
					</div>
				</div>
                
                <div class="clearfix" id="photoCaption">
					<?php  echo $form->label('photoCaption', t('Photo Caption'))?>
					<div class="input">
						<?php  
						Loader::model("attribute/categories/collection");
						$akct = CollectionAttributeKey::getByHandle('photo_caption');
						if (is_object($news)) {
							$tcvalue = $news->getAttributeValueObject($akct);
						}
						?>
						<?php  echo $akct->render('form', $tcvalue, true);?>
					</div>
				</div>
                
                
			</div>
			<div class="pane options" style="display: none;">
			
				<div class="clearfix">
					<?php  echo $form->label('cParentID', t('Section'))?> *
					<div class="input">
						<?php  if (count($sections) == 0) { ?>
							<div><?php  echo t('No sections defined. Please create a page with the attribute "news_section" set to true.')?></div>
						<?php  } else { ?>
							<div><?php  echo $form->select('cParentID', $sections, $cParentID)?></div>
						<?php  } ?>
					</div>
				</div>
                
                <div class="clearfix">
					<?php  echo $form->label('district', t('District'))?>
					<div class="input">
						<?php  
						Loader::model("attribute/categories/collection");
						$akct = CollectionAttributeKey::getByHandle('district');
						if (is_object($news)) {
							$tcvalue = $news->getAttributeValueObject($akct);
						}
						?>
						<?php  echo $akct->render('form', $tcvalue, true);?>
					</div>
				</div>
	
				<div class="clearfix">
					<?php  echo $form->label('newsCategory', t('Region'))?>
					<div class="input">
						<?php  
						Loader::model("attribute/categories/collection");
						$akct = CollectionAttributeKey::getByHandle('news_category');
						if (is_object($news)) {
							$tcvalue = $news->getAttributeValueObject($akct);
						}
						?>
						<?php  echo $akct->render('form', $tcvalue, true);?>
					</div>
				</div>
                
                <div class="clearfix">
					<?php  echo $form->label('regionalFeature', t('Regional Feature'))?>
					<div class="input">
						<?php  
						Loader::model("attribute/categories/collection");
						$akct = CollectionAttributeKey::getByHandle('regional_feature');
						if (is_object($news)) {
							$tcvalue = $news->getAttributeValueObject($akct);
						}
						?>
						<?php  echo $akct->render('form', $tcvalue, true);?>
					</div>
				</div>
                
                <div class="clearfix">
					<?php  echo $form->label('newsTag', t('News Tag'))?>
					<div class="input">
						<?php  
						Loader::model("attribute/categories/collection");
						$akct = CollectionAttributeKey::getByHandle('news_tag');
						if (is_object($news)) {
							$tcvalue = $news->getAttributeValueObject($akct);
						}
						?>
						<?php  echo $akct->render('form', $tcvalue, true);?>
					</div>
				</div>
                
				<div class="clearfix">
					<?php  echo $form->label('ctID', t('Page Type'))?> *
					<div class="input">
						<?php  echo $form->select('ctID', $pageTypes, $ctID)?>
					</div>
				</div>
				
				<div class="clearfix">
					<?php 
					Loader::model("attribute/categories/collection");
					$aku = CollectionAttributeKey::getByHandle('news_url');
					if (is_object($news)) {
						$uvalue = $news->getAttributeValueObject($aku);
					}
					?>
					<?php  echo $aku->render('label');?>
					<div class="input">
						<?php  echo $aku->render('form', $uvalue, array('size'=>'50'));?>
					</div>
				</div>
                
                
			</div>
			<div class="pane meta" style="display: none;">
				<div class="clearfix">
					<?php  echo $form->label('akID[1][value]', t('Meta Title'))?>
					<div class="input">
						<?php 
						if(is_object($news)){
							$metaTitle = $news->getAttribute('meta_title');
						}
						?>
						<?php  echo $form->text('akID[1][value]', $metaTitle, array('style' => 'width: 230px'))?>
					</div>
				</div>
				
				<div class="clearfix">
					<?php  echo $form->label('akID[2][value]', t('Meta Description'))?>
					<div class="input">
						<?php 
						if(is_object($news)){
							$metaDescription = $news->getAttribute('meta_description');
						}
						?>
						<?php  echo $form->textarea('akID[2][value]', $metaDescription, array('style' => 'width: 98%; height: 90px; font-family: sans-serif;'))?>
					</div>
				</div>
				
				<div class="clearfix">
					<?php  echo $form->label('akID[3][value]', t('Meta Tags'))?>
					<div class="input">
						<?php 
						if(is_object($news)){
							$metaKeywords = $news->getAttribute('meta_keywords');
						}
						?>
						<?php  echo $form->textarea('akID[3][value]', $metaKeywords, array('style' => 'width: 98%; height: 90px; font-family: sans-serif;'))?>
					</div>
				</div>
			</div>
	</div>
	<div class="ccm-pane-footer">
    	<?php  $ih = Loader::helper('concrete/interface'); ?>
        <?php  print $ih->submit(t($title.' News Item'), 'news-form', 'right', 'primary'); ?>
        <?php  print $ih->button(t('Cancel'), $this->url('/dashboard/pronews/list/'), 'left'); ?>
    </div>
	</form>
    <style type="text/css">
	.lsummary textarea{width: 747px; height: 300px;}
	</style>
<script>
$("input:radio[name=image]").click(function() {
    var value = $(this).val();
	if(value==1){
		$('#slideshow').hide();
		$('#single_image').show();
		$('#photoCaption').show();
	}
	else if(value==2){
		$('#slideshow').show();
		$('#single_image').hide();
		$('#photoCaption').hide();
	}
	
});
</script>