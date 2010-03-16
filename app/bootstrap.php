<?php

// Step 1: Load Nette Framework
// this allows Nette to load classes automatically so that
// you don't have to litter your code with 'require' statements
require_once LIBS_DIR . '/Nette/loader.php';

// Step 2a: Enable Nette\Debug
// for better exception and error visualisation
//Debug::enable();

//die('aaa');

// Step 2b: load configuration from config.ini file
Environment::loadConfig();

Debug::enable();

// Step 2c: enable RobotLoader - this allows load all classes automatically
$loader = new /*Nette\Loaders\*/RobotLoader();
$loader->addDirectory(APP_DIR);
$loader->addDirectory(LIBS_DIR);
$loader->register();

// Step 2d: establish database connection
require_once LIBS_DIR . '/dibi/dibi.php';
dibi::connect(Environment::getConfig('database'));
dibi::query("SET CHARACTER SET utf8");

$tables = dibi::getConnection()->getDatabaseInfo()->getTables();
if (empty($tables)) {
	$import = new Import();
	$import->installDatabase();
}

// Step 3: Get the front controller
$application = Environment::getApplication();

Environment::setMode(Environment::DEVELOPMENT);

// Step 4: Run the application!
$application->run();

