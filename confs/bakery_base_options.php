<?php 
	

	$options = [
		'clear'=>
			[
				'cache' => function($args,&$shellClass){ Cache::flush(); }; 
			],
		'get'=>
			[
				'page'=> function($args,&$shellClass){
					if(isset($args['id'])){
						$page = Page::getById($args['id']);
					}

					if(isset($args['path'])){
						$page = Page::getByPath($args['path']);
					}

					if(isset($args['handle'])){
						$pl = new PageList();
						 
						//Filters
						$pl->filterByCollectionTypeHandle($args['handle']); //Filters by page type handles. $ctHandle can be array of page type handles.
						  
						//Get the page List Results 
						$pages = $pl->getPage();
						
						$page = $pages[0];
					}

					$shellClass->setLastPage($page);
				},
				'pages'=> function($args,&$shellClass){
					$pl = new PageList();

					if(isset($args['pid'])){
						$pl->filterByParentID($args['pid']);
					}

					if(isset($args['path'])){
						$pl->filterByPath($args['path'],true);
					}

					if(isset($args['handle'])){
						$pl->filterByCollectionTypeHandle($args['handle']);
					}

					//Get the page List Results 
					$pages = $pl->getPage();

					//$pages = $pl->get($itemsToGet = 100, $offset = 0);

					$shellClass->setLastPages($pages);

				}
			],
		'last' =>
			[
				'page'=> 
				[
					'name'=>function($args,&$shellClass){ 
						echo $shellClass->getLastPage()->getCollectionName().PHP_EOL; 
					},
					'delete'=>function($args,&$shellClass){ 
						echo $shellClass->getLastPage()->delete(); 
					},
					'child'=>function($args,&$shellClass){ 

						$shellClass->setLastPage($shellClass->getLastPage()->getFirstChild()); 
					},
					'show'=>function($args,&$shellClass){ 
						$obj = $shellClass->getLastPage();
						echo "{\n";
							echo redString("id").":\t".$obj->getCollectionID().PHP_EOL;
							echo redString("name").":\t".$obj->getCollectionName().PHP_EOL;
							echo redString("desc").":\t".$obj->getCollectionDescription().PHP_EOL;
						echo "}\n";
					}
				],
				'pages'=> 
				[
						'names'=>function($args,&$shellClass){
							$obj = $shellClass->getLastPages();
							foreach ($obj as $page) {
								echo $page->getCollectionName().PHP_EOL;
							} 
						}
				]
			]
	];
?>