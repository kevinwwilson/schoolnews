<style>

table.gsc-branding,
table.gcsc-branding,
.gsc-table-cell-thumbnail.gsc-thumbnail {
	display: none;
}

.gs-bidi-start-align.gs-snippet {
    margin-left: 8px;
}

.custom_search_form input {
  width: 200px;
  margin-right: 20px;
}

.custom_search_form select {
  width: 270px;
}

.custom_search_form label {
  width: 150px;
  display: inline;
  font-weight: bold;
}

</style>

<div class="custom_search_form">
    <form id="search_form" action="/customsearch" method="post">
      <label for="search">Look For:</label>
      <input id="search" name="search" value="<?php echo $searchText ?>"></input>
      <label for="districts_list">Which Districts:</label>
      <select name="district_list" id="districts" class="district_list">
        <option value="">All Districts</option>
        <?php
            $selected = '';
            $i = 1;
            foreach($districtList as $district) {
                if ($selectedDist && $selectedDist == $i) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }

                if ($district->value != "All Districts") {
                ?>
                <option <?php echo $selected; ?> value="<?php echo $i++ ?>" ><?php echo $district->value; ?></option>
        <?php
            }
        } //end foreach
            ?>
      </select>
      <input id="submit_search" type="button" value="Search"></input>
    </form>
</div>
<script>
  (function() {
    var cx = '016213019444767079058:kfgr2stainc';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//cse.google.com/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:searchresults-only></gcse:searchresults-only>

<script>
    $('#submit_search').click(function(){
      submitSearch();
    });

    function submitSearch(){
        var district = $( "#districts option:selected" ).text();
        var distVal = $( "#districts option:selected" ).val();

        //all districts doesn't append anything to the search
        if (district == 'All Districts') {
            district = '';
        }
        var formUrl = '/customsearch';
        var search = $("#search").val();
        var query = search + ' ' + district;
        var params = {q: query, text: search, dist: distVal}
        var url = formUrl + '?' + jQuery.param(params);
        $('#search_form').attr('action', url).submit();
    }
</script>
