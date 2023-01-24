CREATE TABLE `police`.`team` (
                                 `id` INT NOT NULL AUTO_INCREMENT,
                                 `team_name` VARCHAR(255) NOT NULL,
                                 PRIMARY KEY (`id`));


/*Seedeng database*/
INSERT INTO `police`.`role` (`id`, `title`) VALUES ('1', 'admin');
INSERT INTO `police`.`role` (`id`, `title`) VALUES ('2', 'superior');
INSERT INTO `police`.`role` (`id`, `title`) VALUES ('3', 'employee');

/*Hasło admina to Admin123@*/
INSERT INTO `police`.`user` (`id`, `name`, `surname`, `password`, `login`, `email`) VALUES ('1', 'Admin', 'Admin', '$2y$10$XFQwUWYhRl3AJTd8p7EOnO/mhIqebxMZaEtpoXwJsEPZZRh3of9Ke', 'admin','admin@police.pl');

INSERT INTO `police`.`user_role` (`id_user`, `id_role`) VALUES ('1', '1');
INSERT INTO `police`.`user_role` (`id_user`, `id_role`) VALUES ('1', '2');
INSERT INTO `police`.`user_role` (`id_user`, `id_role`) VALUES ('1', '3');