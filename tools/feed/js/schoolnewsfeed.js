var base_url = 'http://www.schoolnewsnetwork.org';

$(document).ready(function(){
    $('.schoolnewsfeed').each(function(i, obj) {
        var $obj = $(obj);
        var type = $obj.attr('snn-type');
        var name = $obj.attr('snn-name');

        $.ajax({
            type: 'GET',
            url: base_url + '/index.php/tools/feed/loader.php',
            crossDomain: true,
            data: {type: type, name: name},
            //use the older jsonp approach until virtually all browsers in use change to support xhr/CORS
            dataType: 'jsonp',
            success: function(data,status) {
                var fullUrl = addFullUrl(data.data);
                $obj.append(fullUrl);
                $('.cycle-slideshow').cycle();
            },
            error: function (responseData, textStatus, errorThrown) {
                alert('POST failed.' + errorThrown);
            }
        });
    });

    function addFullUrl(data) {
        var response = $('<noscript/>').append(data);
            response.find('img').each(function(){
                var url = $(this).attr('src-data');
                if (url.substring(0,4) != 'http') {
                    var full_url = base_url + url;

                    //this way images don't have an src attribute until the URL has been corrected
                    $(this).attr('src', full_url);
                }
            });
        return response.html();
    }
});
