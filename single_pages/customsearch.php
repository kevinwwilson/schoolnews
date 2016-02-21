<style>

.custom_search_form input {
  width: 200px;
  margin-right: 20px;
  font-weight: bold;
}

.custom_search_form select {
  width: 270px;
  font-weight: bold;
}

.custom_search_form label {
  width: 150px;
  display: inline;
  font-weight: bold;
}

</style>

<div class="custom_search_form">
    <form id="search_form" action="/customsearch" method="post">
      <label for="search">Search:</label>
      <input id="search" name="search"></input>
      <label for="districts_list">Search in District:</label>
      <select name="district_list" id="districts" class="district_list">
        <option value=""></option>
        <?php foreach($districtList as $district) { ?>
            <option><?php echo $district->value; ?></option>
        <?php } ?>
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
    var formUrl = '/customsearch';
    $('#submit_search').click(function(){
      submitSearch();
    });

    function submitSearch(){
      var district = $( "#districts option:selected" ).text();
      var search = $("#search").val();
      var query = encodeURIComponent(search + ' ' + district);
      var url = formUrl + '?q=' + query;
      $('#search_form').attr('action', url).submit();
    }
</script>
