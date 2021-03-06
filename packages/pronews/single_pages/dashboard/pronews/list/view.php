<style type="text/css">
a:hover {text-decoration:none;} /*BG color is a must for IE6*/
a.tooltip span {display:none; padding:2px 3px; margin-left:8px; margin-top: -20px;}
a.tooltip:hover span{display:inline; position:absolute; background:#ffffff; border:1px solid #cccccc; color:#6c6c6c;}
th {text-align: left;}
.align_top{vertical-align: top;}
.ccm-results-list tr td{ border-bottom-color: #dfdfdf; border-bottom-width: 1px; border-bottom-style: solid;}
.ccm-ui .ccm-input-text{width:175px;}
.icon {
display: block;
float: left;
height:20px;
width:20px;
background-image:url('<?php  echo ASSETS_URL_IMAGES?>/icons_sprite.png'); /*your location of the image may differ*/
}
.edit {background-position: -22px -2225px;margin-right: 6px!important;}
.copy {background-position: -22px -439px;margin-right: 6px!important;}
.delete {background-position: -22px -635px;}
.gro-select select{width: 110px !important}
.gro-select .greensel{background:#51A351; color: white;}
.gro-select .whitesel{background:#fff;}
.gro-select .redesel{background:#fc0404;}
.gro-select .yellowesel{background:#fdfd00;}
.ccm-ui div.ccm-pagination span {margin-right:4px;}



</style>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('View/Search News'), false, false, false);?>
<div class="ccm-pane-body">
<?php
    if($remove_name){
?>
        <div class="alert-message block-message error">
            <a class="close" href="<?php  echo $this->action('clear_warning');?>">×</a>
            <p><strong><?php  echo t('Holy guacamole! This is a warning!');?></strong></p><br/>
            <p><?php  echo t('Are you sure you want to delete '.$remove_name.'?');?></p>
            <p><?php  echo t('This action may not be undone!');?></p>
            <div class="alert-actions">
                <a class="btn small" href="<?php  echo $this->action('deletethis', $remove_cid, $remove_name)?>"><?php  echo t('Yes Remove This');?></a> <a class="btn small" href="<?php  echo $this->action('clear_warning');?>"><?php  echo t('Cancel');?></a>
            </div>
        </div>
<?php
    }
?>
<?php $form = Loader::helper('form'); ?>
<form method="get" action="<?php  echo $this->action('view')?>">
        <?php
        $sections[0] = '** All';
        asort($sections);
        ?>
        <table class="ccm-results-list">
            <tr>
                <th><strong><?php  echo $form->label('cParentID', t('District'))?></strong></th>
                <th><strong><?php  echo t('by Keyword')?></strong></th>
                <th><strong><?php  echo t('by Slug')?></strong></th>
                <th><strong><?php  echo t('by Author')?></strong></th>
                <th><strong><?php  echo t('by Tag')?></strong></th>
                <th></th>
            </tr>
            <tr>
                <td>
                    <select name="dist" style="width: 130px!important;">
                        <option value=''>--</option>
                        <?php
                        foreach($dist_values as $dist){
                            if($_GET['dist']==$dist['value']){$selected = 'selected="selected"';}else{$selected=null;}
                            echo '<option '.$selected.'>'.$dist['value'].'</option>';
                        }
                        ?>
                    </select>
                </td>
                <td><?php  echo $form->text('keyword', $keyword)?></td>
                <td><?php  echo $form->text('slug', $slug)?></td>
                <td><?php  echo $form->text('author', $author)?></td>
                <td>
                    <select name="tag" style="width: 110px!important;">
                            <option value=''>--</option>
                    <?php
                    foreach($tag_values as $tag){
                        if($_GET['tag']==$tag['value']){$selected = 'selected="selected"';}else{$selected=null;}
                        echo '<option '.$selected.'>'.$tag['value'].'</option>';
                    }
                    ?>
                    </select>
                </td>
                <td>
                    <?php echo $form->submit('submit', 'Search');?>
                </td>
            </tr>
        </table>
    </form>

    <br/>
    <?php
        $nh = Loader::helper('navigation');
        if ($newsList->getTotal() > 0) {
        $newsList->displaySummary();
    ?>
    <table border="0" class="ccm-results-list" cellspacing="0" cellpadding="0">
        <tr>
            <th>Edit</th>
            <th class="<?php  echo $newsList->getSearchResultsClass('cvName')?>">
                <a href="<?php  echo $newsList->getSortByURL('cvName', 'asc')?>">
                    <?php  echo t('Preview')?></a>
            </th>
            <th class="<?php  echo $newsList->getSearchResultsClass('ak_story_slug')?>">
                <a href="<?php  echo $newsList->getSortByURL('ak_story_slug', 'asc')?>">
                <?php echo t('Slug') ?>
            </th>
            <th class="<?php  echo $newsList->getSearchResultsClass('cvDatePublic')?>">
                <a href="<?php  echo $newsList->getSortByURL('cvDatePublic', 'asc')?>">
                    <?php  echo t('Date')?></a>
            </th>
            <th><?php  echo t('District')?></th>
            <th class="<?php  echo $newsList->getSearchResultsClass('ak_author')?>">
                <a href="<?php  echo $newsList->getSortByURL('ak_author', 'asc')?>">
                    <?php  echo t('Author')?></a>
            </th>
            <th><?php  echo t('Group Status')?></th>
            <th></th>
        </tr>
        <?php
            $pkt = Loader::helper('concrete/urls');
            $pkg= Package::getByHandle('pronews');
            Loader::model('attribute/categories/collection');
            foreach($newsResults as $cobj) {
                $akct = CollectionAttributeKey::getByHandle('news_category');
                $news_category = $cobj->getCollectionAttributeValue($akct);

                $akct = CollectionAttributeKey::getByHandle('author');
                $author = $cobj->getCollectionAttributeValue($akct);
                ?>
                <tr>
                    <td width="20px">
                        <a href="<?php  echo $this->url('/dashboard/pronews/add_news', 'edit', $cobj->getCollectionID())?>" class="icon edit"></a>
                    </td>
                    <td>
                        <a href="<?php  echo $nh->getLinkToCollection($cobj)?>"><?php  echo $cobj->getCollectionName()?></a>
                    </td>
                    <td>
                        <?php echo $cobj->getCollectionAttributeValue('story_slug'); ?>
                    </td>
                    <td>
                        <?php
                        if ($cobj->getCollectionDatePublic() > date(DATE_APP_GENERIC_MDYT_FULL) ){
                            echo '<font style="color:green;">';
                            echo $cobj->getCollectionDatePublic();
                            echo '</font>';
                        } else {
                            echo $cobj->getCollectionDatePublic(DATE_APP_GENERIC_MDYT);
                        }
                        ?>
                    </td>
                    <td>
                       <?php
                       $district = $cobj->getCollectionAttributeValue('district');
                       $districtArray = Array();
                       if($district>0){
                           echo $district->current();
                           if ($district->count() > 1) {
                               echo ' <a href="#" class="district-list" data-toggle="tooltip" title="';
                               for ($districtIndex = 1; $districtIndex < $district->count(); $districtIndex++) {
                                   $districtArray[] = $district->get($districtIndex);
                               }
                               echo implode(', ', $districtArray);
                               echo '">[more]</a>';
                           }
                       }
                       ?>
                    </td>
                    <td>
                        <?php  echo $author;?>
                    </td>
                    <td class="gro-select" id = "<?php echo 'cat'.$cobj->cID ?>">
                        <form>
                            <?php echo Loader::helper('form')->hidden('news_id',$cobj->cID); ?>
                            <?php
                                Loader::model("attribute/categories/collection");
                                $akct = CollectionAttributeKey::getByHandle('group_status');
                                if (is_object($cobj)) {
                                    $tcvalue = $cobj->getAttributeValueObject($akct);
                                }
                            ?>
                            <?php  echo $akct->render('form', $tcvalue, true);?>
                        </form>
                        <script type="text/javascript">
                            var id = '<?php echo "cat".$cobj->cID ?>';

                            if($("#"+id+" .ccm-input-select").val() == '93'){
                                $('#'+id+' .ccm-input-select').addClass('greensel');
                            }else if($("#"+id+" .ccm-input-select").val() == '92') {
                                $('#'+id+' .ccm-input-select').addClass('whitesel');
                            }else if($("#"+id+" .ccm-input-select").val() == '94'){
                                $('#'+id+' .ccm-input-select').addClass('yellowesel');
                            }else{
                                $('#'+id+' .ccm-input-select').addClass('redesel');
                            }
                         </script>
                        </td>
                        <?php
                        $scheduled = $cobj->getCollectionAttributeValue('schedule_article');
                        $publishDate = $cobj->getCollectionAttributeValue('publish_date');

                        if ($scheduled == '1' && $publishDate >= date('Y-m-d H:i:s')  ) {
                        ?>
                            <td><a class="schedule" data-toggle="tooltip" title="<?php echo $publishDate; ?>"> <i style="margin-bottom: 20px;" class="icon-time" ></i></a></td>
                        <?php
                        }
                        ?>
                </tr>
    <?php  } ?>
    </table>
        <br/>
        <?php
        $newsList->displayPaging();
        } else {
            print t('No news entries found.');
        }
        ?>
</div>
<div class="ccm-pane-footer">

</div>
</form>

<script type="text/javascript">
$(document).ready(function(){

$(".gro-select select option[value='']").remove();

    $('.gro-select select').change(function(){
        var crid = $(this).parent().parent().attr('id');

        var data=$(this).parent().serialize();
     $.ajax({
        type: "POST",
        url: "<?php echo Loader::helper('concrete/urls')->getToolsURL('change_status');  ?>",
        data: data,
        success: function(data) {
            if($.trim(data) == 'whitesel'){
                $("#"+crid+" .ccm-input-select").removeClass('redesel');
                $("#"+crid+" .ccm-input-select").removeClass('greensel');
                $("#"+crid+" .ccm-input-select").removeClass('yellowesel');
                $("#"+crid+" .ccm-input-select").addClass('whitesel');

            }else if($.trim(data) == 'greensel'){
                $("#"+crid+" .ccm-input-select").removeClass('redesel');
                $("#"+crid+" .ccm-input-select").removeClass('whitesel');
                $("#"+crid+" .ccm-input-select").removeClass('yellowesel');
                $("#"+crid+" .ccm-input-select").addClass('greensel');

            }else if($.trim(data) == 'redesel'){
                $("#"+crid+" .ccm-input-select").removeClass('greensel');
                $("#"+crid+" .ccm-input-select").removeClass('whitesel');
                $("#"+crid+" .ccm-input-select").removeClass('yellowesel');
                $("#"+crid+" .ccm-input-select").addClass('redesel');
            }

            else if($.trim(data) == 'yellowesel'){
                $("#"+crid+" .ccm-input-select").removeClass('greensel');
                $("#"+crid+" .ccm-input-select").removeClass('whitesel');
                $("#"+crid+" .ccm-input-select").removeClass('redesel');
                $("#"+crid+" .ccm-input-select").addClass('yellowesel');
            }
        }
    });
});

$(".district-list").tooltip();
$(".schedule").tooltip();
});

</script>
