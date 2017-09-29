<?php
  use yii\bootstrap\Modal;
  
	Modal::begin([
		'id' => 'modal_dialog',
		'header' => $title,
		
		]);
	
	echo"<div id='modalContent'></div>";
	
	Modal::end();
	?>
	
	<!-- Define css rule for model header section of title -->
	
	<style>
	.modal-header
	{
		font-size: 20px;
	}
	
	</style>