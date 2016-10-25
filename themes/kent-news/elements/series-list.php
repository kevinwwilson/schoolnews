<?php   if (is_array($seriesList) && count($seriesList) > 0) {
  //it is only if this array of news is loaded by the page type controller that these will shown, even`
  //though this is shown on all pages that include the sidebar.
  ?>
<h2 class="widget-title">Our Series</h2>
<ul class="cycle-slideshow"
    data-cycle-slides="li"
    data-cycle-timeout="8000"
    data-cycle-carousel-visible=1
    data-cycle-auto-height="calc"
    >
    <?php
          foreach ($seriesList as $series) {
      ?>
      <li style="display: none;">
          <div class="main-image">
              <img src="<?php echo $series->resizeMainImage(221, 155); ?>">
          </div>
          <h3><a href="<?php echo $series->link?>"><?php echo $series->title ?></a></h3>
          <div class="summary"><?php echo $series->summary ?></div>
      </li>
      <?php
          }
    ?>
</ul>
<?php } ?>
