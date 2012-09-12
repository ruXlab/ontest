<h2>Photos</h2>

<ul class="thumbnails">
<?php foreach($photos as $photo) : ?>
  <li class="span4">
    <a href="#" class="thumbnail">
      <img src="<?php echo $photo['source']?>" title="<?php echo CHtml::encode($photo['name'])?>" width="320" height="320">
    </a>
  </li>
<?php endforeach; ?>
</ul>


<h2>Friends</h2>
<ul >
<?php foreach($people as $someone) : ?>
  <li class="span3">
    <a href="<?php echo $someone['link'];?>" target="_blank" title="<?php echo CHtml::encode($someone['bio']);?>">
      <!--<img src="<?php echo $someone['photo']?>" >-->
      <?php echo CHtml::encode("{$someone['name']}")?>
    </a>
  </li>
<?php endforeach; ?>
</ul>
