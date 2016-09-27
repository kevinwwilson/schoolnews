(function($) {
    var type = '';
    var name = '';

    $.ajax({
        type: 'GET',
        url: 'http://kent-newsv2.local/index.php/tools/feed/loader.php',
        crossDomain: true,
        //data: 'add get variables'
        dataType: 'jsonp',
        success: function(data,status) {

        },
        error: function (responseData, textStatus, errorThrown) {
            alert('POST failed.' + errorThrown);
        }
    });
})(jQuery);
