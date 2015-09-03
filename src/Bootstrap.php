<?php

$folders = ['src','src/basics', 'src/interfaces','src/model', 'src/rules', 'src/qualifiers', 'src/costs'];
foreach ($folders as $folder) 
	foreach (glob("$folder/*.php") as $filename) 
		require_once "$filename";
