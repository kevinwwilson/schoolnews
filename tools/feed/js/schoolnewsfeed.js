var base_url = 'http://kent-newsv2.local';

$(document).ready(function(){
    $('.schoolnewsfeed').each(function(i, obj) {
        var type = $(obj).attr('snn-type');
        var name = $(obj).attr('snn-name');

        $.ajax({
            type: 'GET',
            url: 'http://kent-newsv2.local/index.php/tools/feed/loader.php',
            crossDomain: true,
            data: {type: type, name: name},
            dataType: 'jsonp',
            success: function(data,status) {
                var fullUrl = addFullUrl(data.data);
                //TODO: may need to nanually trigger the slideshow
                $(obj).append(fullUrl);
            },
            error: function (responseData, textStatus, errorThrown) {
                alert('POST failed.' + errorThrown);
            }
        });
    });

    function addFullUrl(data) {
        $obj = $(data);
        $('img').each(function(i, image){
            var url = $(image).attr('src');
            var full_url = base_url + url;
            console.log(full_url);
            //TODO: Check first to see if the current url already starts with http.
            $(image).attr('src', full_url);
        });
        return $obj;
    }
});
