<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns:fb="http://ogp.me/ns/fb#">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width" />
    <?php Loader::element('header_required'); ?>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700|Open+Sans+Condensed:300,700|Oswald:400,300,700' rel='stylesheet' type='text/css' />
    <!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> -->
    <style type="text/css">@import "/concrete/css/ccm.app.css";</style>
    <link media="all" rel="stylesheet" type="text/css" href="<?php print $this->getThemePath(); ?>/css/all.css" />
    <link rel="stylesheet" type="text/css" href="<?php print $this->getStyleSheet('css/typography.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php print $this->getStyleSheet('css/forms.css') ?>" />
    <script type="text/javascript" src="<?php print $this->getThemePath(); ?>/js/jquery.main.js"></script>
    <script type="text/javascript" src="/js/jquery.hoverIntent.minified.js"></script>
    <script type="text/javascript" src="<?php print $this->getThemePath(); ?>/js/respond.min.js"></script>
    <script type="text/javascript" src="<?php print $this->getThemePath(); ?>/js/moment/moment.js"></script>
    <script type="text/javascript" src="<?php print $this->getThemePath(); ?>/js/jcaption/jcaption.min.js"></script>
    <script type="text/javascript" src="<?php print $this->getThemePath(); ?>/js/ie.js"></script>

    <script type="text/javascript" charset="utf-8" src="/logo/SNN_AnimatedLogo_PNG_edgePreload.js"></script>

    <script type="text/javascript" src="<?php print $this->getThemePath(); ?>/js/article.js"></script>
    <style type="text/css">@import "/concrete/css/ccm.app.css";</style>

    <script>
	$(document).ready(function(){
            $('.slideshow').hoverIntent({
                over: startHover,
                out: endHover,
                timeout: 1500
            });
            //Then define the functions to handle over and out
             function startHover(e){
                    $('.slideshow .caption').slideDown(300);
             }
             function endHover(){
                     $('.slideshow .caption').fadeOut(1500);
             }
            jQuery('.approve-answer-link').each(function(i) {
                    if (jQuery (this).text() == 'Unapprove') {
                            jQuery(this).parent().addClass('Approved-Article');
                    }
            });
        });
    </script>
</head>
<body>
	<!-- Facebook  Include the JavaScript SDK on your page once, ideally right after the opening <body> tag.-->
	<div id="fb-root"></div>
        <script>
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=378843365553326";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>

	<!-- wrapper -->
	<div id="wrapper">
            <div class="wrapper-holder">
                <!-- header -->
                <header id="header">
                    <!-- header-holder - ARD banner -->
                    <div class="header-holder">
                        <?php
                        $stack = Stack::getByName('ARD Banner');
                        if ($stack)
                            $stack->display();
                        ?>
                    </div>
                    <div class="header-frame">
                        <!-- logo -->
                        <strong class="logo">
                            <a href="/">
                                <div id="Stage" class="EDGE-307356695">
                                    <div id="Stage_Rectangle" class="edgeLoad-EDGE-307356695"></div>
                                    <div id="Stage_SNN_rss_curve_32" class="edgeLoad-EDGE-307356695"></div>
                                    <div id="Stage_SNN_rss_curve_22" class="edgeLoad-EDGE-307356695"></div>
                                    <div id="Stage_SNN_rss_curve_12" class="edgeLoad-EDGE-307356695"></div>
                                    <div id="Stage_SNN_rss_circle2" class="edgeLoad-EDGE-307356695"></div>
                                    <div id="Stage_SNN_tagline_text3" class="edgeLoad-EDGE-307356695"></div>
                                    <div id="Stage_SNN_newsnetwork_text4" class="edgeLoad-EDGE-307356695"></div>
                                    <div id="Stage_SNN_school_text3" class="edgeLoad-EDGE-307356695"></div>
                                    <div id="Stage_SNN_bottomrightsquare2" class="edgeLoad-EDGE-307356695"></div>
                                    <div id="Stage_SNN_bottomleftsquare2" class="edgeLoad-EDGE-307356695"></div>
                                    <div id="Stage_SNN_upperleftsquare2" class="edgeLoad-EDGE-307356695"></div>
                                </div>
                            </a>
                        </strong>
                        <!-- navigation -->
                        <nav>
                            <!-- Main Nav -->
                            <?php
                            $stack = Stack::getByName('Main Navigation');
                            if ($stack)
                                $stack->display();
                            ?>
                        </nav>
                    </div>
                </header>