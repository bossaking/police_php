CREATE SCHEMA `police` ;

CREATE TABLE `police`.`user` (
                                 `id` INT NOT NULL AUTO_INCREMENT,
                                 `name` VARCHAR(255) NOT NULL,
                                 `surname` VARCHAR(255) NOT NULL,
                                 `login` VARCHAR(255) NOT NULL,
                                 `password` VARCHAR(255) NOT NULL,
                                 `email` VARCHAR(255) NOT NULL,
                                 PRIMARY KEY (`id`));

CREATE TABLE `police`.`role` (
                                 `id` INT NOT NULL AUTO_INCREMENT,
                                 `title` VARCHAR(255) NOT NULL,
                                 PRIMARY KEY (`id`));

CREATE TABLE `police`.`user_role` (
                                      `id_user` INT NOT NULL,
                                      `id_role` INT NOT NULL,
                                      PRIMARY KEY (`id_user`, `id_role`),
                                      INDEX `user_role_role_fk_idx` (`id_role` ASC) VISIBLE,
                                      CONSTRAINT `user_role_user_fk`
                                          FOREIGN KEY (`id_user`)
                                              REFERENCES `police`.`user` (`id`)
                                              ON DELETE NO ACTION
                                              ON UPDATE NO ACTION,
                                      CONSTRAINT `user_role_role_fk`
                                          FOREIGN KEY (`id_role`)
                                              REFERENCES `police`.`role` (`id`)
                                              ON DELETE NO ACTION
                                              ON UPDATE NO ACTION);


CREATE TABLE `police`.`team` (
                                 `id` INT NOT NULL AUTO_INCREMENT,
                                 `team_name` VARCHAR(255) NOT NULL,
                                 PRIMARY KEY (`id`));

CREATE TABLE `police`.`status` (
                                   `id` INT NOT NULL AUTO_INCREMENT,
                                   `status_name` VARCHAR(45) NOT NULL,
                                   PRIMARY KEY (`id`));

CREATE TABLE `police`.`submission` (
                                       `id` INT NOT NULL AUTO_INCREMENT,
                                       `user_name` VARCHAR(255) NOT NULL,
                                       `user_surname` VARCHAR(255) NOT NULL,
                                       `user_identity` VARCHAR(255) NOT NULL,
                                       `user_station` VARCHAR(255) NOT NULL,
                                       `topic` VARCHAR(255) NOT NULL,
                                       `description` LONGTEXT NOT NULL,
                                       `team_id` INT NULL,
                                       `employee_id` INT NULL,
                                       `access_code` VARCHAR(255) NOT NULL,
                                       `access_code_showed` TINYINT NOT NULL,
                                       `status_id` INT NOT NULL,
                                       PRIMARY KEY (`id`),
                                       INDEX `team_fk_idx` (`team_id` ASC) VISIBLE,
                                       INDEX `status_fk_idx` (`status_id` ASC) VISIBLE,
                                       INDEX `employee_fk_idx` (`employee_id` ASC) VISIBLE,
                                       CONSTRAINT `team_fk`
                                           FOREIGN KEY (`team_id`)
                                               REFERENCES `police`.`team` (`id`)
                                               ON DELETE NO ACTION
                                               ON UPDATE NO ACTION,
                                       CONSTRAINT `status_fk`
                                           FOREIGN KEY (`status_id`)
                                               REFERENCES `police`.`status` (`id`)
                                               ON DELETE NO ACTION
                                               ON UPDATE NO ACTION,
                                       CONSTRAINT `employee_fk`
                                           FOREIGN KEY (`employee_id`)
                                               REFERENCES `police`.`user` (`id`)
                                               ON DELETE SET NULL
                                               ON UPDATE NO ACTION);

CREATE TABLE `police`.`comment` (
                                    `id` INT NOT NULL AUTO_INCREMENT,
                                    `employee_id` INT NOT NULL,
                                    `submission_id` INT NOT NULL,
                                    `text` LONGTEXT NOT NULL,
                                    PRIMARY KEY (`id`),
                                    INDEX `employee_comment_fk_idx` (`employee_id` ASC) VISIBLE,
                                    INDEX `submission_comment_fk_idx` (`submission_id` ASC) VISIBLE,
                                    CONSTRAINT `employee_comment_fk`
                                        FOREIGN KEY (`employee_id`)
                                            REFERENCES `police`.`user` (`id`)
                                            ON DELETE NO ACTION
                                            ON UPDATE NO ACTION,
                                    CONSTRAINT `submission_comment_fk`
                                        FOREIGN KEY (`submission_id`)
                                            REFERENCES `police`.`submission` (`id`)
                                            ON DELETE NO ACTION
                                            ON UPDATE NO ACTION);




/*Seedeng database*/
INSERT INTO `police`.`role` (`id`, `title`) VALUES ('1', 'admin');
INSERT INTO `police`.`role` (`id`, `title`) VALUES ('2', 'superior');
INSERT INTO `police`.`role` (`id`, `title`) VALUES ('3', 'employee');

/*Hasło admina to Admin123@*/
INSERT INTO `police`.`user` (`id`, `name`, `surname`, `password`, `login`, `email`) VALUES ('1', 'Admin', 'Admin', '$2y$10$XFQwUWYhRl3AJTd8p7EOnO/mhIqebxMZaEtpoXwJsEPZZRh3of9Ke', 'admin','admin@police.pl');

INSERT INTO `police`.`user_role` (`id_user`, `id_role`) VALUES ('1', '1');
INSERT INTO `police`.`user_role` (`id_user`, `id_role`) VALUES ('1', '2');
INSERT INTO `police`.`user_role` (`id_user`, `id_role`) VALUES ('1', '3');

INSERT INTO `police`.`status` (`id`, `status_name`) VALUES ('1', 'Opened');
INSERT INTO `police`.`status` (`id`, `status_name`) VALUES ('2', 'In progress');
INSERT INTO `police`.`status` (`id`, `status_name`) VALUES ('3', 'Closed');