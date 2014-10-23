<?php
$df = Loader::helper('form/date_time');
$ih = Loader::helper('concrete/interface');

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
	$singlemultiple = $news->getCollectionAttributeValue('single_multiple_photo_status');
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
	$buttonText = t('Save');

} else {
	$task = 'add';
	$buttonText = t('Add News Item');

}
?>
<style>
.ccm-pane-footer .ccm-button-v2-left{color:#fff; background: #ef3939;}
.ccm-pane-footer .ccm-button-v2-left:hover{color:#fff; background: #e02e2e;}
.statushidden{display: none;}
.ccm-ui input, .ccm-ui input-text {width: 743px;}

.top-buttons {
    position: absolute;
    right: 20px;
}

.top-buttons .btn {
    margin-left: 10px;
}

</style>


<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t($title.' News').'<span class="label" style="position:relative;top:-3px;left:12px;">'.t('* required field').'</span>', false, false, false);?>
	<div class="ccm-pane-body">



<?php
    if($remove_name){
?>
    <div class="alert-message block-message error">
        <a class="close" href="<?php  echo $this->action('clear_warning');?>">�</a>
        <p><strong><?php  echo t('Holy guacamole! This is a warning!');?></strong></p><br/>
       <p><?php  echo t('Are you sure you want to delete '.$remove_name.'?');?></p>
       <p><?php  echo t('This action may not be undone!');?></p>
       <div class="alert-actions">
         <a class="btn small" href="<?php  echo $this->action('delete_news', $remove_cid)?>"><?php  echo t('Yes Remove This');?></a> <a class="btn small" href="<?php  echo $this->action('clear_warning');?>"><?php  echo t('Cancel');?></a>
        </div>
    </div>
<?php
    }
?>
<?php  if ($this->controller->getTask() == 'edit') { ?>
    <form method="post" action="<?php  echo $this->action($task,$news->getCollectionID())?>" id="news-form">
<?php  echo $form->hidden('newsID', $news->getCollectionID())?>
<?php  }else{ ?>
    <form method="post" action="<?php  echo $this->action($task)?>" id="news-form">
<?php  } ?>
        <div class="top-buttons">

        <?php  print $ih->submit(t($buttonText), 'news-form', 'right', 'primary'); ?>

        <?php  print $ih->button(t('Cancel'), $this->url('/dashboard/pronews/list/'), 'dleft'); ?>
        </div>
        <ul class="tabs">
            <li class="active">
                <a href="javascript:void(0)" onclick="$('ul.tabs li').removeClass('active'); $(this).parent().addClass('active'); $('.pane').hide(); $('div.post').show();"><?php echo t('Post')?></a>
            </li>
            <li>
                <a href="javascript:void(0)" onclick="$('ul.tabs li').removeClass('active'); $(this).parent().addClass('active'); $('.pane').hide(); $('div.meta').show();"><?php echo t('Meta')?></a>
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
                <?php  echo $form->text('newsTitle', $newsTitle)?>
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
                <div>
                    <?php  echo $form->textarea('newsDescription', $newsDescription, array('style' => 'width: 98%; height: 90px; font-family: sans-serif;'))?>
                </div>
                <div id="shortCount"></div>
            </div>
        </div>
        <div class="clearfix">
            <?php  echo $form->label('longSummary', t('Long Summary'))?>
            <div class="input">
                <div>
                    <?php echo $form->textarea('longSummary', $long_summary, array('style' => 'width: 98%; height: 90px; font-family: sans-serif;')) ?>
                </div>
                <div id="longCount"></div>
            </div>
        </div>
        <div class="clearfix">
                <?php echo $form->label('newsBody', t('Article')) ?>
                <div class="input">
                    <?php Loader::Element('editor_init'); ?>
                    <?php Loader::Element('editor_config'); ?>
                    <?php Loader::Element('editor_controls', array('mode' => 'full')); ?>
                    <?php echo $form->textarea('newsBody', $newsBody, array('style' => 'width: 100%; font-family: sans-serif;', 'class' => 'ccm-advanced-editor')) ?>
                </div>
            </div>
            <div class="clearfix">
                	 <?php  echo  t('Single Image/Slide Show');

	                 if($singlemultiple == 2){ ?>
		              <div class="input" >
                     <?php
                      	//echo $from->radio('image',1,1); echo $from->radio('image',0,1);?>
                      	<input type="radio" name="image" value="3"/><?php  echo  t('No Photo')?>
                        <input type="radio" name="image" value="1"/><?php  echo  t('Single Image')?>
                        <input type="radio" name="image" value="2"  checked="checked" /><?php  echo  t('Slide Show')?>

                    </div>

	                <?php }	elseif($singlemultiple == 1) { ?>

                     <div class="input" >
                     <?php
                      	//echo $from->radio('image',1,1); echo $from->radio('image',0,1);?>
                      	<input type="radio" name="image" value="3"/><?php  echo  t('No Photo')?>
                        <input type="radio" name="image" value="1" checked="checked" /><?php  echo  t('Single Image')?>
                        <input type="radio" name="image" value="2"/><?php  echo  t('Slide Show')?>

                    </div>
                    <?php } else{ ?>

	                    <div class="input" >
                     <?php
                      	//echo $from->radio('image',1,1); echo $from->radio('image',0,1);?>
                      	<input type="radio" name="image" value="3"  checked="checked" /><?php  echo  t('No Photo')?>
                        <input type="radio" name="image" value="1"/><?php  echo  t('Single Image')?>
                        <input type="radio" name="image" value="2"/><?php  echo  t('Slide Show')?>

                    </div>


                    <?php }?>
                 </div>


                 <div class="clearfix statushidden">
					<?php  echo $form->label('singlemultiple', t('Single/Multiple Photo Status'))?>
					<div class="input lsummary">
						<?php
						Loader::model("attribute/categories/collection");
						$akct = CollectionAttributeKey::getByHandle('single_multiple_photo_status');
						if (is_object($news)) {
							$tcvalue = $news->getAttributeValueObject($akct);
						}
						?>
						<?php  echo $akct->render('form', $tcvalue, true);?>
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




				<div class="clearfix">
					<?php  echo $form->label('cParentID', t('Section'))?> *
					<div class="input">
						<?php  if (count($sections) == 0) { ?>
							<div><?php  echo t('No sections defined. Please create a page with the attribute "news_section" set to true.')?></div>
						<?php  } else { ?>
						      <?php if($page_type_id != ''){
						            $cParentID = $sections_id;

					          } ?>
							<div><?php  echo $form->select('cParentID', $sections, $cParentID)?></div>

						<?php  } ?>
					</div>
				</div>

                <div class="clearfix">
                            <?php  echo $form->label('district', t('Primary and Other Districts'))?>
                        <div class="district-check input">
                            <input type="checkbox" id="ckbCheckAll" /> <strong>All Districts</strong>
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
					<?php
					 if($page_type_id != ''){
						$ctID = $page_type_id;

					} ?>

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
        <?php  print $ih->submit(t($buttonText), 'news-form', 'right', 'primary'); ?>

        <?php  print $ih->button(t('Cancel'), $this->url('/dashboard/pronews/list/'), 'dleft'); ?>

        <?php  if ($this->controller->getTask() == 'edit') { ?>
        <?php  print $ih->button(t('Delete News'), $this->url('/dashboard/pronews/add_news/delete_check/'.$news->getCollectionID().''), 'left'); }?>

    </div>
	</form>
    <style type="text/css">
	.lsummary textarea{width: 747px; height: 300px;}
        #longCount, #shortCount{text-align: right; margin-top: 20px; margin-right: 10px;}
    </style>
<script>
            if($('.statushidden input:text').val() == ''){
$('.statushidden input:text').val(3);
}

if($('.statushidden input:text').val() == '2'){
        $('#slideshow').show();
		$('#single_image').hide();
		$('#photoCaption').hide();
}else if($('.statushidden input:text').val() == '1'){
	    $('#slideshow').hide();
		$('#single_image').show();
		$('#photoCaption').show();

}else{
	    $('#slideshow').hide();
		$('#single_image').hide();
		$('#photoCaption').hide();

}


$("#ckbCheckAll").click(function () {
        $(".district-check input").prop('checked', $(this).prop('checked'));
    });


$("input:radio[name=image]").click(function() {
    var value = $(this).val();
	if(value==1){
	    $('.statushidden input:text').val(1);
		$('#slideshow').hide();
		$('#single_image').show();
		$('#photoCaption').show();
	}
	else if(value==2){
	    $('.statushidden input:text').val(2);
		$('#slideshow').show();
		$('#single_image').hide();
		$('#photoCaption').hide();
	}else{
	$('.statushidden input:text').val(3);
		$('#slideshow').hide();
		$('#single_image').hide();
		$('#photoCaption').hide();

	}

});

$("#newsDescription").keyup(function(){ countCharacters('#newsDescription', '#shortCount', 150); });

$("#newsDescription").click(function(){ countCharacters('#newsDescription', '#shortCount', 150); });

$("#longSummary").keyup(function(){ countCharacters('#longSummary', '#longCount', 400); });

$("#longSummary").click(function(){ countCharacters('#longSummary', '#longCount', 400); });

function countCharacters(count, update, max)
{
    var left = max - parseInt($(count).val().length);

    if(left > -1){
        $(update).text("Characters left: " + (max - $(count).val().length));
    } else {
        $(update).text("Maximum allowed charecters");
    }
}

</script>