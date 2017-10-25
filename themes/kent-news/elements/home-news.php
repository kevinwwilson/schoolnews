<?php   if (is_array($newsList) && count($newsList) > 0) {
  //it is only if this array of news is loaded by the page type controller that these will shown, even`
  //though this is shown on all pages that include the sidebar.
  ?>
  <div class="sidebar-box">
      <div class="tab purple" style="width: 67%;">
          Latest Headlines
      </div>
      <div class="frame purple">
        <ul class="cycle-slideshow"
            data-cycle-slides="li"
            data-cycle-timeout="8000"
            data-cycle-carousel-visible=1
            data-cycle-auto-height="calc"
            >
            <?php
                  foreach ($newsList as $newsItem) {
              ?>
              <li style="display: none;">
                  <div class="main-image">
                      <img src="<?php echo $newsItem->resizeMainImage(221, 155); ?>">
                  </div>
                  <h3><a href="<?php echo $newsItem->link?>"><?php echo $newsItem->title ?></a></h3>
                  <div class="summary"><?php echo $newsItem->summary ?></div>
              </li>
              <?php
                  }
            ?>
        </ul>
    </div>
</div>
<?php } ?>
