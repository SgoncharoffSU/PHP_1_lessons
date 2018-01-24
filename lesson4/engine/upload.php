<?php
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if (!@copy($_FILES['picture']['tmp_name'], IMG_DIR . $_FILES['picture']['name'])) {
            echo 'Что-то пошло не так';
        }
        else {
            if ( $db->insertImage(IMG_DIR . $_FILES['picture']['name']) !== FALSE ) {
                echo 'Загрузка удачна';
            }
            else {
                echo 'Загрузка не удалась';
            }
        }
    }
?>