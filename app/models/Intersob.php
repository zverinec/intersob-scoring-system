<?php
/**
 * @author 		Jan Papousek
 * @version 	2009-02-08
 * @package		intersob
 */


/**
 *				This class contains the basic information about project.
 * @package 	intersob
 */
class Import extends AbstractModel {

	/**
	 * 			It installs tables and views into the database.
	 *
	 */
	public function installDatabase() {
		$this->getConnection()->loadFile(IMPORT_DIR . "/tables.sql");
		$this->getConnection()->loadFile(IMPORT_DIR . "/views.sql");
		self::loadData();
	}
	
	private function loadData() {
		// 1. It loads users.
		$users = simplexml_load_file(IMPORT_DIR . "/users.xml");
		foreach ($users AS $user) {
			dibi::query("INSERT INTO [users]",array(
				"name" => (string)$user["name"],
				"password" => sha1((string)$user["password"])	
			));
		}
		$users = dibi::query("SELECT * FROM [users]")
			->fetchPairs("name","id_user");

		// 2. It loads tasks and for each task loads its surveyors.
		$tasks = simplexml_load_file(IMPORT_DIR . "/tasks.xml");
		foreach ($tasks AS $task) {
			dibi::query("INSERT INTO [tasks]",array(
				"name" => (string)$task["name"],
				"description" => (string)$task["description"],
			));
			$currentTask = dibi::insertId();
			foreach($task->surveyors->user AS $user) {
				dibi::query("INSERT INTO [surveyors]",array(
					"id_user" => $users[(string)$user["name"]],
					"id_task" => "$currentTask"
				));
			}
		}
		
		// 3. It loads teams.
		$teams = simplexml_load_file(IMPORT_DIR . "/teams.xml");
			foreach ($teams AS $team) {
			dibi::query("INSERT INTO [teams]",array(
				"name" => "$team"	
			));
		}
	}
	
}