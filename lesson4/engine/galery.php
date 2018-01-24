<?php
if ( array_key_exists('image_id',$_REQUEST) ) { //Отдельная страница с картинкой
  $image=$db->getImage($_REQUEST['image_id']);
  if (count($image)==1) {
    $image=$image[0];
    echo '<div class = "blok_img full" >
      <a href="'. $image->file .'" target="_blank"><span class="counter">'.$image->count.'</span><img src="'. $image->file .'" width="'.$image->w.'" height="'.$image->h.'" class="pimg" title="'. $image->title .'" /></a>
    </div>';
  }
}
else { //Страница с галереей
  foreach ($db->getAllImages() as $image) {
    echo '<div class = "blok_img" >
    <a href="./index.php?image_id='. $image['id'] .'" target="_blank"><span class="counter">'.$image['count'].'</span><img src="'. $image['file'] .'" class="pimg" title="'. $image['title'] .'" /></a>
  </div>';
  }
}
?>
