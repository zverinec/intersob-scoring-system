CREATE VIEW `view_tasks` AS
    SELECT
	`id_task`,
	`tasks`.`name` AS `task_name`,
	`tasks`.`description` AS `task_description`,
	`tasks`.`empty` AS `task_empty`,
	CONCAT_WS(': ', LPAD(`tasks`.`id_task`,2,'0'), `tasks`.`name`) AS `task_id_name`,
	ROUND(AVG(`solutions`.`score`)) AS `avg_score`,
	MAX(`solutions`.`score`) AS `max_score`,
	MIN(`solutions`.`score`) AS `min_score`,
	COUNT(`solutions`.`id_solution`) AS `num_solutions`
    FROM `tasks`
    LEFT JOIN `solutions` USING(`id_task`)
    GROUP BY `tasks`.`id_task`
    ORDER BY `id_task`;

CREATE VIEW `view_tasks_and_surveyors` AS
    SELECT
	`view_tasks`.*,
	`id_user` AS `id_user`,
	`users`.`name` AS `user_name`
    FROM `surveyors`
    INNER JOIN `view_tasks` USING(`id_task`)
    INNER JOIN `users` USING(`id_user`);

CREATE VIEW `view_results` AS
    SELECT
	`id_team`,
	`teams`.`name` AS `team_name`,
	`id_task`,
	`tasks`.`task_name`,
	`tasks`.`avg_score` AS task_avg_score,
	(SELECT SUM(`score`) FROM `solutions` AS `help` WHERE `help`.`id_team` = `teams`.`id_team`) AS `score`,
	(SELECT score FROM `solutions` AS `help`
	 WHERE `help`.`id_task` = `tasks`.`id_task`
	 AND `help`.`id_team` =  `teams`.`id_team`
	) AS `task_score`
    FROM
	`teams`,`view_tasks` AS `tasks`
    ORDER BY
	`score` DESC,
	`teams`.`name` ASC,
	`tasks`.`id_task` ASC;

CREATE VIEW `view_solutions` AS
    SELECT
	`solutions`.`id_solution`,
	`tasks`.`id_task`,
	`tasks`.`name` AS `task_name`,
	`teams`.`id_team`,
	`teams`.`name` AS `team_name`,
	`solutions`.`score` AS `score`
    FROM `solutions`
    INNER JOIN `tasks` USING(`id_task`)
    INNER JOIN `teams` USING(`id_team`)
    ORDER BY `solutions`.`updated` DESC;