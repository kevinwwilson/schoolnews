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
            <img src="<?php echo $newsItem->resizeMainImage(180, 127); ?>">
        </div>
        <h3><a href="<?php echo $newsItem->link?>"><?php echo $newsItem->title ?></a></h3>
        <div class="summary"><?php echo $newsItem->summary ?></div>
    </li>
    <?php 
        }
    ?>
</ul>
