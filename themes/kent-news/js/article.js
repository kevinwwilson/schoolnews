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

    $articleImages = jQuery('#article_content img');
    $articleImages.each(function(i) {
        //if the image is wrapped in a link then don't apply caption.  This is 
        //mostly here so that the in-article ad doesn't get a caption added
        //to it.
        if(jQuery(this).parent().get(0).tagName !== 'A'){
                jQuery(this).jcaption({
                    captionElement: 'span',
                    wrapperClass: 'img-caption',
                    copyStyle: true});
        }
        
        if (parseInt(jQuery(this).attr('width')) < 300) {
            if (jQuery(this).parent().hasClass('img-caption') === true) {
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
});
