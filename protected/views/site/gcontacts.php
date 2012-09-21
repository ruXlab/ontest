<h2>My google contacts</h2>

<style>
.contactCell {
	margin: 7px 0px;
	overflow: hidden;
	text-overflow: ellipsis;
	-o-text-overflow: ellipsis;
	white-space: nowrap;
}
.contactCell a {
	display: block;
}
.contactCell .contactName {
	font-weight: bold;
}
.contactCell .contact {
	color: gray;
}
.contactCell div.contactPhoto {
	float:left; 
	width:50px; 
	height:50px; 
	margin-right: 5px;
	border: 1px solid #ddd;
	border-radius: 2px;
	background-color; #eee;
}
</style>

<div class="row">
<?php foreach($contacts as $contact) : ?>
  <div class="span4 contactCell" >
    <a href="<?php echo !$contact['email'] ? '#' : 'mailto:'.CHtml::encode($contact['email']);?>"  >
		<?php echo CHtml::tag('div', array(
			'style' => "background:url({$contact['photo']})", 
			'title' => CHtml::encode($contact['name']),
			'class' => 'contactPhoto'
			), '');?>
    	<span class="contactName">
		<?php echo (CHtml::encode($contact['name']));?>
		</span>
		<br/>
		<span class="contact">
		<?php if ($contact['phone']) : ?>
		<?php echo CHtml::encode($contact['phone']);?>
		<?php else : ?>
		<?php echo CHTML::encode($contact['email']);?>
		<?php endif ?>
		<span>
    </a>
  </div>
<?php endforeach; ?>
</div>
