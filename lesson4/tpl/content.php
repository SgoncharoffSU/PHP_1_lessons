<div class="contentWrepper">
    <form enctype="multipart/form-data" method="post" class="formLoad">
    <input name="picture" type="file" />
    <input type="submit" value="Загрузить" />
    </form>

    <div class="message">
    
    <?php
        include ('../engine/upload.php');
    ?>
    </div>

    <div class="content">

    <?php 
        include ('../engine/galery.php');
    ?>

    </div>
    <div class="marginList"></div>
</div>

