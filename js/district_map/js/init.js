jQuery(function () {

    var stateNames = new Array();
    var stateURLs = new Array();
    var stateModes = new Array();
    var stateSizes = new Array();
    var stateColors = new Array();
    var stateOverColors = new Array();
    var stateClickedColors = new Array();
    var stateText = new Array();


    var offColor;
    var strokeColor;
    var mapWidth;
    var mapHeight;
    var useSideText;
    var textAreaWidth;
    var textAreaPadding;

    var mouseX = 0;
    var mouseY = 0;
    var current = null;

    // Detect if the browser is IE.
    var IE = jQuery.browser.msie ? true : false;

    var UrlRoot = '/js/district_map';
    
    jQuery.ajax({
        type: 'GET',
        url: UrlRoot + '/xml/kentisd.xml',
        dataType: jQuery.browser.msie ? 'text' : 'xml',
        success: function (data) {


            var xml;
            if (jQuery.browser.msie) {
                xml = new ActiveXObject('Microsoft.XMLDOM');
                xml.async = false;
                xml.loadXML(data);

            } else {
                xml = data;
            }

            var $xml = jQuery(xml);

if (jQuery.browser.msie && parseInt(jQuery.browser.version) == 8){

           offColor = '#' + $xml.find('mapSettings').attr('offColor');
            strokeColor = '#' + $xml.find('mapSettings').attr('strokeColor');
            mapWidth = '585';
            mapHeight = '372';
            useSideText = $xml.find('mapSettings').attr('useSideText');
            textAreaWidth = $xml.find('mapSettings').attr('textAreaWidth');
            textAreaPadding = $xml.find('mapSettings').attr('textAreaPadding');

            } else {

            offColor = '#' + $xml.find('mapSettings').attr('offColor');
            strokeColor = '#' + $xml.find('mapSettings').attr('strokeColor');
            mapWidth = $xml.find('mapSettings').attr('mapWidth');
            mapHeight = $xml.find('mapSettings').attr('mapHeight');
            useSideText = $xml.find('mapSettings').attr('useSideText');
            textAreaWidth = $xml.find('mapSettings').attr('textAreaWidth');
            textAreaPadding = $xml.find('mapSettings').attr('textAreaPadding');
 }

            if (useSideText == 'true') {
                jQuery("#text").css({
                    'width': (parseFloat(textAreaWidth) - parseFloat(textAreaPadding * 2)) + 'px',
                    'height': (parseFloat(mapHeight) - parseFloat(textAreaPadding * 2)) + 'px',
                    'display': 'inline',
                    'float': 'right',
                    'padding': textAreaPadding + 'px'
                });

                jQuery('#text').html($xml.find('defaultSideText').text());
            }


            //Parse xml
            $xml.find('stateData').each(function (i) {

                var $node = jQuery(this);

                stateText.push($node.text());
                stateNames.push($node.attr('stateName'));
                stateURLs.push($node.attr('url'));
                stateModes.push($node.attr('stateMode'));
                stateSizes.push($node.attr('stateSize'));
                stateColors.push('#' + $node.attr('initialStateColor'));
                stateOverColors.push('#' + $node.attr('stateOverColor'));
                stateClickedColors.push('#' + $node.attr('stateSelectedColor'));

            });

            createMap();

        }
    });


    function createMap() {
         jQuery('#text').hide();
          jQuery('.close').hide();
        //start map


        var r = new ScaleRaphael('map', 280, 350),
            attributes = {
                fill: '#d9d9d9',
                cursor: 'crosshair',
                stroke: '#0',
                "fill-opacity": 0,
                'stroke-width': 0,
                'stroke-linejoin': 'round'
            },
            arr = new Array();

        for (var state in usamappaths) {

            //Create obj
            var obj = r.path(usamappaths[state].path);
            obj.attr(attributes);
            arr[obj.id] = state;

            //var el = paper.path();
          obj.mouseovered = false;

            if (stateModes[obj.id] == 'OFF') {
                obj.attr({
                    fill: offColor,
                    cursor: 'default'
                });


            } else {




                obj.attr({
                    fill: stateColors[obj.id]
                });




                obj.mouseover(function (e) {
					if(this.mouseovered==false)
  						{
    					this.mouseovered=true;
   						 this.toFront();
  						}
                    //Animate if not already the current state
                    if (this != current) {

                        this.animate({
                             transform:stateSizes[this.id],
                             "fill-opacity": 1,
                             elementId: stateNames[this.id],
                             fill: stateOverColors[this.id]
                        }, 200);
                    }
                    /*

					if(this.mouseovered==false)
					    this.toBack();
  						{
    					this.mouseovered=true;
   						 this.toFront();
  						}
 //Animate if not already the current state


                    if (this != current) {


                          this.animate({
                             transform:stateSizes[this.id],
                             "fill-opacity": 1,
                             elementId: stateNames[this.id],
                             fill: stateOverColors[this.id]
                        }, 200);
                    }
*/


                    //tooltip

                    if (jQuery.browser.msie && parseInt(jQuery.browser.version) > 8){
                       var point = this.getBBox(0);
                       jQuery('#map').next('.point').remove();
                       jQuery('#map').after(jQuery('<div />').addClass('point'));
                       jQuery('.point').html(stateNames[this.id]).css({
                        left: mouseX - 100,
                        top: mouseY - 35
                       }).fadeIn();
                    }else{
                        var point = this.getBBox(0);
                    jQuery('#map').next('.point').remove();
                    jQuery('#map').after(jQuery('<div />').addClass('point'));
                    jQuery('.point').html(stateNames[this.id]).css({
                        left: mouseX - 5,
                        top: mouseY - 5
                    }).fadeIn();


                    }


                });




 obj.mouseout(function (e) {
                    if (this != current) {
                        this.stop().animate({
                            fill: stateColors[this.id]
                        }, 200);

                           if (jQuery.browser.msie && parseInt(jQuery.browser.version) == 9){


                      this.animate({
   							 transform: 's1',
   							  "fill-opacity": 0
						}, 200, 'null');



                    }else{

                        this.animate({
   							 transform: 's1',
   							  "fill-opacity": 0
						}, 200, 'null');
                    }


                     }


                    jQuery('#map').next('.point').remove();
                    this.toBack();
   this.mousedover = false;

                });



 jQuery('.close').mousedown(function () {
      jQuery('#text').fadeOut("slow");

      jQuery(this).fadeOut("slow");

});



  obj.mousedown(function (e) {

                    //Reset scrollbar
                    var t = jQuery('#text')[0];
                    t.scrollLeft = 0;
                    t.scrollTop = 0;
                    //Animate previous state out
                    if (current) {
                                             }
  //Animate previous state out
                    if (current) {
                        current.animate({
                            fill: stateColors[current.id]
                        }, 500);

                    }




                    current = this;

                    if (useSideText == 'true') {
                        jQuery('#text').html(stateText[this.id]);
                    } else {
                         window.open(stateURLs[this.id], '_blank');
                    }
                });




            }

        }

        resizeMap(r);
    }



    // Set up for mouse capture
    if (document.captureEvents && Event.MOUSEMOVE) {
        document.captureEvents(Event.MOUSEMOVE);
    }

    // Main function to retrieve mouse x-y pos.s

    function getMouseXY(e) {

        var scrollTop = jQuery(window).scrollTop();

        if (e && e.pageX) {
            mouseX = e.pageX;
            mouseY = e.pageY-scrollTop;
        } else {
            mouseX = event.clientX + document.body.scrollLeft;
            mouseY = event.clientY + document.body.scrollTop;
        }
        // catch possible negative values
        if (mouseX < 0) {
            mouseX = 0;
        }
        if (mouseY < 0) {
            mouseY = 0;
        }




     jQuery('#map').next('.point').css({
            left: mouseX - 20,
            top: mouseY - 50
         });
 }


    // Set-up to use getMouseXY function onMouseMove
    document.body.onmousemove = getMouseXY;

 function resizeMap(paper) {

        paper.changeSize(mapWidth, mapHeight, true, false);

        if (useSideText == 'true') {
            jQuery(".mapWrapper").css({
                'width': (parseFloat(mapWidth, 10) + parseFloat(textAreaWidth, 10)) + 'px',
                'height': mapHeight + 'px'

            });
            jQuery("#text").css({
                'height': 260 +'px'
            });
        } else {
            jQuery(".mapWrapper").css({
                'width': 260 +'px'
            });
            jQuery("#text").css({
                'height': 250 +'px'
            });
        }

    }





});