<script type="text/javascript" src="/concrete/js/jquery.js?v=d5e31ee523921b0586bfb6dec5757fdc"></script>
<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));

$addid = $_REQUEST['bID'];

$disart[] = $addid;

        foreach($disart as $articles){
			Loader::model('page_list');
			$pl = new PageList();
			
			if($pl->cID == $articles){
				echo '<pre>';
				print_r($pl);
				die;
				
			}
			}
			
			?>
<script type="text/javascript">

$(document).ready(function(){
	
	$(".loading img").remove();
	$(".col-sm-9 .ccm-page-list .row").hide();
	$(".col-sm-9 .ccm-page-list .row").first().show();
	$('.outer .col-sm-3 .bottom-video-holder img').click(function(){
		//alert('test');		
		var hideval2 = $(this).attr('alt');
		$(".col-sm-9 .ccm-page-list .row").hide();
		$('#hide'+hideval2+'').show();
		

	});
	});

</script> 		