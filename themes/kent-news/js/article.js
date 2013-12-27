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
	