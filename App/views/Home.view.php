<html>
    <head>
        <title><?php echo $title; ?></title>
        
    </head>
    <body>
        <?php echo $menu->toHTML(); ?>
        <?php echo $content->toHTML(); ?>
        
    </body>
</html>