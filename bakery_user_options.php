<?php 
	

	$user_options = [
		'do'=>
		[
			'action' =>
			[
				'subaction' => function($args,&$shellClass){
					echo "You executed a user command\n";
					$shellClass->setShite("something");
					echo $shellClass->getShite();
				}
			]
		]
	];
?>