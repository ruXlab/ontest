<h2>Photos</h2>

<?php foreach($photos as $photo) : ?>
<ul class="thumbnails">
  <li class="span4">
    <a href="#" class="thumbnail">
      <img src="<?php echo $photo['source']?>" alt="" width="320" height="320">
    </a>
  </li>
  ...
</ul>

<?php endforeach; ?>