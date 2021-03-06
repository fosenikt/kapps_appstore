<?php

spl_autoload_register(function($class) {
	$prefix = 'Kapps\\';
	$length = strlen($prefix);
	$base_directory = __DIR__ . '/';

	if(strncmp($prefix, $class, $length) !== 0) {
		return;
	}

	$class_end = substr($class, $length);
	$file = $base_directory . str_replace('\\', '/', $class_end) . '.php';

	if(file_exists($file)) {
		require $file;
	}
});