CREATE TABLE `users` (
    `id_user` INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `password` VARCHAR(128) NOT NULL,
    `logged` TIMESTAMP NOT NULL,
    UNIQUE(`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `teams` (
    `id_team` INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
    `name` VARCHAR(250) NOT NULL,
    UNIQUE(`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `tasks`(
    `id_task` INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
    `name` VARCHAR(250) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `empty` BOOL DEFAULT 0,
    UNIQUE(`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `solutions`(
    `id_solution` INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
    `id_team` INT(25) NOT NULL,
    `id_task` INT(25) NOT NULL,
    `score` INT(2) NOT NULL,
    `updated` TIMESTAMP NOT NULL,
    FOREIGN KEY (`id_team`) REFERENCES `teams` (`id_team`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (`id_task`) REFERENCES `tasks` (`id_task`) ON UPDATE CASCADE ON DELETE CASCADE,
    INDEX (`id_task`), INDEX (`id_team`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `surveyors`(
    `id_surveyor` INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
    `id_user` INT(25) UNSIGNED NOT NULL,
    `id_task` INT(25) UNSIGNED NOT NULL,
    FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (`id_task`) REFERENCES `tasks` (`id_task`) ON UPDATE CASCADE ON DELETE CASCADE,
    INDEX (`id_task`), INDEX (`id_task`),
    UNIQUE(`id_task`,`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

