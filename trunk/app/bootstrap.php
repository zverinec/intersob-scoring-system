<?php

// Step 1: Load Nette Framework
// this allows Nette to load classes automatically so that
// you don't have to litter your code with 'require' statements
require_once LIBS_DIR . '/Nette/loader.php';

// Step 2a: Enable Nette\Debug
// for better exception and error visualisation

// Step 2b: load configuration from config.ini file
Environment::getApplication()->catchExceptions = false;
Environment::loadConfig();
if (Environment::getConfig("debug")->enable) {
    Debug::enable(Debug::DEVELOPMENT);
    if (Environment::getConfig("debug")->profiler) {
	Debug::enableProfiler();
    }
}

// Step 2c: enable RobotLoader - this allows load all classes automatically
$loader = new /*Nette\Loaders\*/RobotLoader();
$loader->addDirectory(APP_DIR);
$loader->addDirectory(LIBS_DIR);
$loader->register();

// Step 2d: establish database connection
require_once LIBS_DIR . '/dibi/dibi.php';
dibi::connect(Environment::getConfig('database'));

if (Environment::getConfig("debug")->enable && isset($_GET["reset"])) {
    dibi::begin();
    dibi::query("DROP TABLE IF EXISTS [surveyors]");
    dibi::query("DROP TABLE IF EXISTS [users]");
    dibi::query("DROP TABLE IF EXISTS [solutions]");
    dibi::query("DROP TABLE IF EXISTS [tasks]");
    dibi::query("DROP TABLE IF EXISTS [teams]");
    dibi::query("DROP VIEW IF EXISTS [view_tasks_and_surveyors]");
    dibi::query("DROP VIEW IF EXISTS [view_tasks]");
    dibi::query("DROP VIEW IF EXISTS [view_results]");
    dibi::query("DROP VIEW IF EXISTS [view_solutions]");
    dibi::commit();
}

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

