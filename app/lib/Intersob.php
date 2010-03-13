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
class Intersob {
	
	const DATA_DIR						= "/data/";
	
	/**
	 * @var		string	The prefix of tables in the database.
	 */
	const DATABASE_PREFIX				= "intersob_";
	
	/**
	 * @var		string 	The name of the table where the solutions of tasks by teams are saved.
	 */
	const DATABASE_TABLE_SOLUTIONS		= "solutions";
	
	/**
	 * @var		string 	The name of the table where the relation between tasks and users is saved.
	 */
	const DATABASE_TABLE_SURVEYORS		= "surveyors";
	
	/**
	 * @var		string	The name of table where the tasks are saved.
	 */
	const DATABASE_TABLE_TASKS			= "tasks";
	
	/**
	 * @var		string	The name of table where the teams are saved.
	 */
	const DATABASE_TABLE_TEAMS			= "teams";
	
	/**
	 * @var		string	The name of table where the users are saved. 
	 */
	const DATABASE_TABLE_USERS			= "users";
	
	/**
	 * @var		string	The name of view which contains the team results.
	 */
	const DATABASE_VIEW_RESULTS			= "view_results";
	
	/**
	 * @var		string	The name of view which containt information about solutions.
	 */
	const DATABASE_VIEW_SOLUTIONS 		= "view_solutions"; 
	
	/**
	 * @var		string	The name of view which contains information about the tasks.
	 */
	const DATABASE_VIEW_TASKS			= "view_tasks";
	
	/**
	 * @var		string	The name of view which contains the tasks and their surveyors.
	 */
	const DATABASE_VIEW_TASKS_AND_SURVEYORS	= "view_tasksAndSurveyors";
	
	/**
	 * 			It installs tables and views into the database.
	 *
	 */
	public static function installDatabase() {
		self::installDatabaseTables();
		self::installDatabaseViews();
		self::loadData();
	}
	
	/**
	 *			It creates the tables in database.
	 */
	final private static function installDatabaseTables() {

		// This file loads required classes.
		require_once LIBS_DIR . '/dibi/dibi.php';
		
		// The users are people who can edit information about the teams and the tasks.
		$sql = "CREATE TABLE ".self::DATABASE_PREFIX.self::DATABASE_TABLE_USERS."(
			id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
			name VARCHAR(100) NOT NULL,
			password VARCHAR(128) NOT NULL,
			logged TIMESTAMP NOT NULL,
			INDEX(id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		dibi::query($sql);
		
		// The teams.
		$sql = "CREATE TABLE ".self::DATABASE_PREFIX.self::DATABASE_TABLE_TEAMS."(
			id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
			name VARCHAR(250) NOT NULL UNIQUE,
			INDEX(id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		dibi::query($sql);
		
		// The tasks
		$sql = "CREATE TABLE ".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS."(
			id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
			name VARCHAR(250) NOT NULL,
			description TEXT DEFAULT NULL,
            empty BOOL DEFAULT 0,
			INDEX(id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		dibi::query($sql);
		
		// The solutions of the tasks.
		$sql = "CREATE TABLE ".self::DATABASE_PREFIX.self::DATABASE_TABLE_SOLUTIONS."(
			id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
			teamID INT(25) NOT NULL,
			taskID INT(25) NOT NULL,
			score INT(2) NOT NULL,
			updated	TIMESTAMP NOT NULL,
			FOREIGN KEY (teamID) REFERENCES ".self::DATABASE_PREFIX.self::DATABASE_TABLE_TEAMS." (id)
				ON UPDATE CASCADE
				ON DELETE CASCADE,
			FOREIGN KEY (taskID) REFERENCES ".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS." (id)
				ON UPDATE CASCADE
				ON DELETE CASCADE,
			INDEX (taskID), INDEX (teamID)			
		) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		dibi::query($sql);
		
		// The surveyors
		$sql = "CREATE TABLE ".self::DATABASE_PREFIX.self::DATABASE_TABLE_SURVEYORS."(
			id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
			userID INT(25) UNSIGNED NOT NULL,
			taskID INT(25) UNSIGNED NOT NULL,
			FOREIGN KEY (userID) REFERENCES ".self::DATABASE_PREFIX.self::DATABASE_TABLE_USERS." (id)
				ON UPDATE CASCADE
				ON DELETE CASCADE,
			FOREIGN KEY (taskID) REFERENCES ".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS." (id)
				ON UPDATE CASCADE
				ON DELETE CASCADE,
			INDEX (taskID), INDEX (userID)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		dibi::query($sql);
	}
	
	/**
	 *			It creates the views in database.
	 */
	final private static function installDatabaseViews() {
		
		// This file loads required classes.
		require_once LIBS_DIR . '/dibi/dibi.php';
		
		// Tasks and their surveyors
		$sql = "CREATE VIEW ".self::DATABASE_PREFIX.self::DATABASE_VIEW_TASKS_AND_SURVEYORS." AS
			SELECT
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS.".id AS taskID,
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS.".name AS taskName,
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS.".description AS taskDescription,
                ".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS.".empty AS taskEmpty,
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_USERS.".id AS userID,
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_USERS.".name AS userName
			FROM ".self::DATABASE_PREFIX.self::DATABASE_TABLE_SURVEYORS."
			INNER JOIN ".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS." ON
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_SURVEYORS.".taskID = ".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS.".id
			INNER JOIN ".self::DATABASE_PREFIX.self::DATABASE_TABLE_USERS." ON
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_SURVEYORS.".userID = ".self::DATABASE_PREFIX.self::DATABASE_TABLE_USERS.".id
		";
		dibi::query($sql);
		
		// Tasks
		$sql = "CREATE VIEW ".self::DATABASE_PREFIX.self::DATABASE_VIEW_TASKS." AS
			SELECT
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS.".id AS taskID,
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS.".name AS taskName,
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS.".description AS taskDescription,
                ".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS.".empty AS taskEmpty,
				ROUND(AVG(".self::DATABASE_PREFIX.self::DATABASE_TABLE_SOLUTIONS.".score)) AS avgScore,
				MAX(".self::DATABASE_PREFIX.self::DATABASE_TABLE_SOLUTIONS.".score) AS maxScore,
				MIN(".self::DATABASE_PREFIX.self::DATABASE_TABLE_SOLUTIONS.".score) AS minScore,
				COUNT(".self::DATABASE_PREFIX.self::DATABASE_TABLE_SOLUTIONS.".id) AS numSolutions
			FROM ".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS."
			LEFT JOIN ".self::DATABASE_PREFIX.self::DATABASE_TABLE_SOLUTIONS." ON
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS.".id = ".self::DATABASE_PREFIX.self::DATABASE_TABLE_SOLUTIONS.".taskID
			GROUP BY ".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS.".id
			ORDER BY taskID
		";
		dibi::query($sql);
		
		// Results of the competition.
		$sql = "CREATE VIEW ".self::DATABASE_PREFIX.self::DATABASE_VIEW_RESULTS." AS
			SELECT
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_TEAMS.".id AS teamID,
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_TEAMS.".name AS teamName,
				".self::DATABASE_PREFIX.self::DATABASE_VIEW_TASKS.".taskID AS taskID,
				".self::DATABASE_PREFIX.self::DATABASE_VIEW_TASKS.".taskName AS taskName,
				".self::DATABASE_PREFIX.self::DATABASE_VIEW_TASKS.".avgScore AS taskAvgScore,
				(
					SELECT SUM(score) FROM ".self::DATABASE_PREFIX.self::DATABASE_TABLE_SOLUTIONS." AS help
					WHERE help.teamID = ".self::DATABASE_PREFIX.self::DATABASE_TABLE_TEAMS.".id
				) AS score,
				(
					SELECT score FROM ".self::DATABASE_PREFIX.self::DATABASE_TABLE_SOLUTIONS." AS help
					WHERE help.taskID = ".self::DATABASE_PREFIX.self::DATABASE_VIEW_TASKS.".taskID
					AND help.teamID = ".self::DATABASE_PREFIX.self::DATABASE_TABLE_TEAMS.".id
				) AS taskScore
			FROM
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_TEAMS.",
				".self::DATABASE_PREFIX.self::DATABASE_VIEW_TASKS."
			ORDER BY
				score DESC,
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_TEAMS.".name ASC,
				".self::DATABASE_PREFIX.self::DATABASE_VIEW_TASKS.".taskID ASC
		";
		dibi::query($sql);
		
		// Solutions.
		$sql = "CREATE VIEW ".self::DATABASE_PREFIX.self::DATABASE_VIEW_SOLUTIONS." AS
			SELECT
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_SOLUTIONS.".id AS solutionID,
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS.".id AS taskID,
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS.".name AS taskName,
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_TEAMS.".id AS teamID,
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_TEAMS.".name AS teamName,
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_SOLUTIONS.".score AS score
			FROM ".self::DATABASE_PREFIX.self::DATABASE_TABLE_SOLUTIONS."
			INNER JOIN ".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS." ON
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_SOLUTIONS.".taskID = ".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS.".id				
			INNER JOIN ".self::DATABASE_PREFIX.self::DATABASE_TABLE_TEAMS." ON
				".self::DATABASE_PREFIX.self::DATABASE_TABLE_SOLUTIONS.".teamID = ".self::DATABASE_PREFIX.self::DATABASE_TABLE_TEAMS.".id
			ORDER BY ".self::DATABASE_PREFIX.self::DATABASE_TABLE_SOLUTIONS.".updated DESC
		";
		dibi::query($sql);
	}
	
	final private static function loadData() {
		// 1. It loads users.
		$users = simplexml_load_file(APP_DIR . self::DATA_DIR . "users.xml");
		foreach ($users AS $user) {
			dibi::query("INSERT INTO [".self::DATABASE_PREFIX.self::DATABASE_TABLE_USERS."]",array(
				"name" => (string)$user["name"],
				"password" => sha1((string)$user["password"])	
			));
		}
		$users = dibi::query("SELECT * FROM [".self::DATABASE_PREFIX.self::DATABASE_TABLE_USERS."]")
			->fetchPairs("name","id");
			
		// 2. It loads tasks and for each task loads its surveyors.
		$tasks = simplexml_load_file(APP_DIR . self::DATA_DIR . "tasks.xml");
		foreach ($tasks AS $task) {
			dibi::query("INSERT INTO [".self::DATABASE_PREFIX.self::DATABASE_TABLE_TASKS."]",array(
				"name" => (string)$task["name"],
				"description" => (string)$task["description"],
			));
			$currentTask = dibi::insertId();
			foreach($task->surveyors->user AS $user) {
				dibi::query("INSERT INTO [".Intersob::DATABASE_PREFIX.Intersob::DATABASE_TABLE_SURVEYORS."]",array(
					"userID" => $users[(string)$user["name"]],
					"taskID" => "$currentTask"
				));
			}
		}
		
		// 3. It loads teams.
		$teams = simplexml_load_file(APP_DIR . self::DATA_DIR . "teams.xml");
			foreach ($teams AS $team) {
			dibi::query("INSERT INTO [".self::DATABASE_PREFIX.self::DATABASE_TABLE_TEAMS."]",array(
				"name" => "$team"	
			));
		}
	}
	
}