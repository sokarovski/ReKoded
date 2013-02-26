<div class="menuView">
    <?php foreach($items as $item=>$uri) { ?>
    <a href="<?php echo App::$c->url.$uri; ?>"><?php echo $item; ?></a>
    <?php } ?>
</div>