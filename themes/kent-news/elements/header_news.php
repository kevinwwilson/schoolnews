<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns:fb="http://ogp.me/ns/fb#">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width" />
        <link rel="apple-touch-icon-precomposed" sizes="57x57" href="/favicon/apple-icon-57x57.png" />
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/favicon/apple-icon-114x114.png" />
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/favicon/apple-icon-72x72.png" />
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/favicon/apple-icon-144x144.png" />
        <link rel="apple-touch-icon-precomposed" sizes="120x120" href="/favicon/apple-icon-120x120.png" />
        <link rel="apple-touch-icon-precomposed" sizes="152x152" href="/favicon/apple-icon-152x152.png" />
        <link rel="icon" type="image/png" href="/favicon/favicon-32x32.png" sizes="32x32" />
        <link rel="icon" type="image/png" href="/favicon/favicon-16x16.png" sizes="16x16" />
        <meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png" />
	<?php Loader::element('header_required_news'); ?>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700|Open+Sans+Condensed:300,700|Oswald:400,300,700' rel='stylesheet' type='text/css' />
	<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> -->
	<link media="all" rel="stylesheet" type="text/css" href="<?php print $this->getThemePath(); ?>/css/all.css" />
	<link rel="stylesheet" type="text/css" href="<?php print $this->getStyleSheet('css/typography.css')?>" />
	<link rel="stylesheet" type="text/css" href="<?php print $this->getStyleSheet('css/forms.css')?>" />
        <link rel="stylesheet" type="text/css" href="<?php print $this->getStyleSheet('css/bootstrap-glyphicons.css')?>" />
        <link rel="stylesheet" type="text/css" href="<?php print $this->getStyleSheet('css/print.css')?>" />
        <link rel="stylesheet" type="text/css" href="<?php print $this->getThemePath(); ?>/js/modal/jquery.modal.css "/>
	<script type="text/javascript" src="<?php print $this->getThemePath(); ?>/js/jquery.main.js"></script>
	<script type="text/javascript" src="<?php print $this->getThemePath(); ?>/js/respond.min.js"></script>
	<script type="text/javascript" src="<?php print $this->getThemePath(); ?>/js/moment/moment.js"></script>
	<script type="text/javascript" src="<?php print $this->getThemePath(); ?>/js/jcaption/jcaption.min.js"></script>
	<script type="text/javascript" src="<?php print $this->getThemePath(); ?>/js/ie.js"></script>

	<script>
	//Add padding to the left and right of images floated in the articles

		jQuery(document).ready(function() {


			jQuery("#article_content img").each(function (i) {
				if (jQuery(this).css('float') === 'left') {
					jQuery(this).css('margin-right', '10px')
								.css('margin-top', '6px')
								.css('margin-bottom', '6px')
								.removeAttr('height');
					}
				if (jQuery(this).css('float') === 'right') {
					jQuery(this).css('margin-left', '10px')
								.css('margin-top', '6px')
								.css('margin-bottom', '6px')
								.removeAttr('height');
				}
				});

			jQuery('#article_content img')
                                .jcaption({
                                    captionElement: 'span',
                                    wrapperClass: 'img-caption',
                                    copyStyle: true})
                                .each(function(i) {
                                    console.log (parseInt(jQuery(this).attr('width')));
                                    if (parseInt(jQuery(this).attr('width')) < 300) {
                                            console.log (jQuery(this).parent().hasClass('img-caption'));
                                            if (jQuery(this).parent().hasClass('img-caption') == true) {
                                                    jQuery(this).parent().addClass('img-small-caption');
                                            }
                                            else {
                                                    jQuery(this).addClass("img-small");
                                            }
                                        }
                                    });


//			jQuery("#article_content img").each(function(i) {
//                                console.log (parseInt(jQuery(this).attr('width')));
//				if (parseInt(jQuery(this).attr('width')) < 300) {
//                                        console.log (jQuery(this).parent().hasClass('img-caption'));
//					if (jQuery(this).parent().hasClass('img-caption') == true) {
//						jQuery(this).parent().addClass('img-small-caption');
//					}
//					else {
//						jQuery(this).addClass("img-small");
//					}
//				}
//			});




			var dateline = '<span class="dateline">' +  jQuery(".dateline").html() + '</span>';
			jQuery("#article_content p").first().prepend(dateline);
			jQuery("#article_content .dateline").first().remove();

			jQuery ("#article_content table").each(function(i) {
				jQuery(this).addClass('inline-sidebar');
			});

			//parse date
			var date = jQuery('#pub_date').text();
			if (date != ""){
                            var date_parsed = moment(date, "YYYY-MM-DD").format('MMMM Do YYYY');;
                            jQuery('#pub_date').text(date_parsed);
                        }

			});

	</script>
<?php
	Loader::helper('get_news_info');
	$fId = (int)GetNewsInfoHelper::getInfo('kent-news-prod', 'photo1', $_GET['rID']);
	$f = File::getByID($fId);
	$fv = $f->getApprovedVersion();
	$path = $fv->getRelativePath();

	if ($fId > 0) {
		$f = File::getByID($fId);
		echo '<meta property="og:image" content="' . BASE_URL . $path . '"/>';
		}

?>
</head>
<body>
	<!-- Facebook  Include the JavaScript SDK on your page once, ideally right after the opening <body> tag.-->
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=378843365553326";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>

	<!-- wrapper -->
	<div id="wrapper">
		<div class="wrapper-holder">
			<!-- header -->
			<header id="header">


				<!-- header-holder - ARD banner -->
				<div class="header-holder">


					<?php
					   $stack = Stack::getByName('ARD Banner');
						if ($stack) $stack->display();
						?>


				</div>



				<div class="header-frame">
					<!-- logo -->
					<strong class="logo"><a href="/">school news network</a></strong>
					<!-- navigation -->
					<nav>
						<!-- Main Nav -->
						<?php
						   $stack = Stack::getByName('Main Navigation');
							if ($stack) $stack->display();
							?>
					</nav>
				</div>
			</header>
