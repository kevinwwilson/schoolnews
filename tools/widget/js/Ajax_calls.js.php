<?php
 header("Content-type: text/javascript");
?>

/*yepnope1.5.x|WTFPL*/
(function(a,b,c){function d(a){return"[object Function]"==o.call(a)}function e(a){return"string"==typeof a}function f(){}function g(a){return!a||"loaded"==a||"complete"==a||"uninitialized"==a}function h(){var a=p.shift();q=1,a?a.t?m(function(){("c"==a.t?B.injectCss:B.injectJs)(a.s,0,a.a,a.x,a.e,1)},0):(a(),h()):q=0}function i(a,c,d,e,f,i,j){function k(b){if(!o&&g(l.readyState)&&(u.r=o=1,!q&&h(),l.onload=l.onreadystatechange=null,b)){"img"!=a&&m(function(){t.removeChild(l)},50);for(var d in y[c])y[c].hasOwnProperty(d)&&y[c][d].onload()}}var j=j||B.errorTimeout,l=b.createElement(a),o=0,r=0,u={t:d,s:c,e:f,a:i,x:j};1===y[c]&&(r=1,y[c]=[]),"object"==a?l.data=c:(l.src=c,l.type=a),l.width=l.height="0",l.onerror=l.onload=l.onreadystatechange=function(){k.call(this,r)},p.splice(e,0,u),"img"!=a&&(r||2===y[c]?(t.insertBefore(l,s?null:n),m(k,j)):y[c].push(l))}function j(a,b,c,d,f){return q=0,b=b||"j",e(a)?i("c"==b?v:u,a,b,this.i++,c,d,f):(p.splice(this.i++,0,a),1==p.length&&h()),this}function k(){var a=B;return a.loader={load:j,i:0},a}var l=b.documentElement,m=a.setTimeout,n=b.getElementsByTagName("script")[0],o={}.toString,p=[],q=0,r="MozAppearance"in l.style,s=r&&!!b.createRange().compareNode,t=s?l:n.parentNode,l=a.opera&&"[object Opera]"==o.call(a.opera),l=!!b.attachEvent&&!l,u=r?"object":l?"script":"img",v=l?"script":u,w=Array.isArray||function(a){return"[object Array]"==o.call(a)},x=[],y={},z={timeout:function(a,b){return b.length&&(a.timeout=b[0]),a}},A,B;B=function(a){function b(a){var a=a.split("!"),b=x.length,c=a.pop(),d=a.length,c={url:c,origUrl:c,prefixes:a},e,f,g;for(f=0;f<d;f++)g=a[f].split("="),(e=z[g.shift()])&&(c=e(c,g));for(f=0;f<b;f++)c=x[f](c);return c}function g(a,e,f,g,h){var i=b(a),j=i.autoCallback;i.url.split(".").pop().split("?").shift(),i.bypass||(e&&(e=d(e)?e:e[a]||e[g]||e[a.split("/").pop().split("?")[0]]),i.instead?i.instead(a,e,f,g,h):(y[i.url]?i.noexec=!0:y[i.url]=1,f.load(i.url,i.forceCSS||!i.forceJS&&"css"==i.url.split(".").pop().split("?").shift()?"c":c,i.noexec,i.attrs,i.timeout),(d(e)||d(j))&&f.load(function(){k(),e&&e(i.origUrl,h,g),j&&j(i.origUrl,h,g),y[i.url]=2})))}function h(a,b){function c(a,c){if(a){if(e(a))c||(j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}),g(a,j,b,0,h);else if(Object(a)===a)for(n in m=function(){var b=0,c;for(c in a)a.hasOwnProperty(c)&&b++;return b}(),a)a.hasOwnProperty(n)&&(!c&&!--m&&(d(j)?j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}:j[n]=function(a){return function(){var b=[].slice.call(arguments);a&&a.apply(this,b),l()}}(k[n])),g(a[n],j,b,n,h))}else!c&&l()}var h=!!a.test,i=a.load||a.both,j=a.callback||f,k=j,l=a.complete||f,m,n;c(h?a.yep:a.nope,!!i),i&&c(i)}var i,j,l=this.yepnope.loader;if(e(a))g(a,0,l,0);else if(w(a))for(i=0;i<a.length;i++)j=a[i],e(j)?g(j,0,l,0):w(j)?B(j):Object(j)===j&&h(j,l);else Object(a)===a&&h(a,l)},B.addPrefix=function(a,b){z[a]=b},B.addFilter=function(a){x.push(a)},B.errorTimeout=1e4,null==b.readyState&&b.addEventListener&&(b.readyState="loading",b.addEventListener("DOMContentLoaded",A=function(){b.removeEventListener("DOMContentLoaded",A,0),b.readyState="complete"},0)),a.yepnope=k(),a.yepnope.executeStack=h,a.yepnope.injectJs=function(a,c,d,e,i,j){var k=b.createElement("script"),l,o,e=e||B.errorTimeout;k.src=a;for(o in d)k.setAttribute(o,d[o]);c=j?h:c||f,k.onreadystatechange=k.onload=function(){!l&&g(k.readyState)&&(l=1,c(),k.onload=k.onreadystatechange=null)},m(function(){l||(l=1,c(1))},e),i?k.onload():n.parentNode.insertBefore(k,n)},a.yepnope.injectCss=function(a,c,d,e,g,i){var e=b.createElement("link"),j,c=i?h:c||f;e.href=a,e.rel="stylesheet",e.type="text/css";for(j in d)e.setAttribute(j,d[j]);g||(n.parentNode.insertBefore(e,n),m(c,0))}})(this,document);

if (!(typeof jQuery !== "undefined" && jQuery !== null)) {
    yepnope('http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js');
}

yepnope([{
  load: ['<?php echo BASE_URL ?>/tools/widget/css/default.css'],
  complete: function () {
       get_news();
    }
  }
]);



    //set defaults
    if (typeof snn_district === 'undefined') {
        var snn_district = 'Kent ISD';
    }
    
    if (typeof snn_max_display === 'undefined') {
        var snn_max_display = 3;
    }
    
    if (typeof snn_subscribe === 'undefined') {
        var snn_subscribe = 'http://www.schoolnewsnetwork.org/newsletter';
    }
    
    if (typeof snn_summary ==='undefined'){
        var snn_summary = 'all';
    }
    

function get_news() {



    (function($) {
    var alternate = "odd";
	var iart = 0;
        $.ajax({
            type: 'GET',
            url: '<?php echo BASE_URL ?>/tools/widget/loadNews.php',
            crossDomain: true,
            dataType: 'jsonp',
            success: function(data,status) {
                $article_list = $("<div>").addClass('FB_news_feed');
                    $("<h2>")
                            .addClass('FB_Section_Title')
                            .text('Our District News')
                            .appendTo($article_list);
                
				$.each(data, function (i, item) {
					if (data[i].District.search(snn_district) > -1 && iart < snn_max_display) {
						$article = $("<div>")
							.addClass('FB_article')
							.addClass(alternate);

						if (data[i].Thumbnail != ''  && data[i].Thumbnail !=0) {
							var image = $("<img>")
								.attr("src",data[i].Thumbnail);
						} else {
                                                        var image = $("<img>");
                                                }
                                                
                                                if (snn_summary == 'all') {
                                                    image.appendTo($article);
                                                } else if (snn_summary == 'first' & iart < 1){
                                                    image.appendTo($article);
                                                }

						var title = $("<h3>")
							.addClass('FB_News_Title')
							.text(data[i].Headline)
							.appendTo($article);
                                                        
						$("<a>")
							.html(title)
							.attr("href",data[i].URL)
							.appendTo($article);         
						
                                                var summary = $("<div>")
							.addClass('FB_News_Summary')
							.html(data[i].Summary);
                                                        
                                                if (snn_summary == 'all') {
                                                    summary.appendTo($article);
                                                } else if (snn_summary == 'first' & iart < 1){
                                                    summary.appendTo($article);
                                                }


						$article.appendTo($article_list);
						if (alternate =="odd") {
							alternate = "even";
						}
						else {alternate = "odd";}
						iart++;
					}
                });
                
                if (snn_districtlanding.length > 1) {
                    $("<a>")
                        .addClass('FB_ReadMore')
                        .text('View All Articles')
                        .attr("href", snn_districtlanding)
                        .attr("target", "_blank")
                        .appendTo($article_list);
                }

                
                $("<a>")
                        .addClass('FB_Subscribe')
                        .attr("href", snn_subscribe)
                        .attr("target", "_blank")
                        .text('Subscribe')
                        .appendTo($article_list);
                        
                //display article set
                $article_list.appendTo("#result_set");
                
                
            },
            error: function (responseData, textStatus, errorThrown) {
                alert('POST failed.' + errorThrown);
            }
        });
    })(jQuery);
}



