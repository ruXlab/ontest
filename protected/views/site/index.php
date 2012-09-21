<div class="container">
	<div class="hero-unit">
        <h1 style="margin-bottom:0.5em">Simple amazing app!</h1>
        <p>
			<?php echo CHtml::link('Login via facebook', 
					array('site/login', 'type' => 'facebook'), 
					array('class' => "btn btn-primary btn-large"));?>
			<?php echo CHtml::link('Login via google', 
					array('site/login', 'type' => 'gcontacts'), 
					array('class' => "btn btn-danger btn-large"));?>
		</p>        
    </div>
</div>