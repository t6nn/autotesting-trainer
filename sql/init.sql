-- DROP DATABASE IF EXISTS newsy;
CREATE USER IF NOT EXISTS 'newsy'@'localhost' IDENTIFIED BY 'newsy';
CREATE DATABASE IF NOT EXISTS newsy;
USE newsy;

CREATE TABLE IF NOT EXISTS `ratings` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `rating` SMALLINT NOT NULL ,
  `url` VARCHAR(512) NOT NULL ,
  `user` VARCHAR(64) NOT NULL ,
  `rated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`),
  INDEX `idx_rating` (`rating`),
  INDEX `idx_url` (`url`),
  INDEX `idx_user` (`user`),
  UNIQUE `idx_user_url` (`user`, `url`)
) ENGINE = InnoDB;

GRANT SELECT, INSERT, UPDATE ON newsy.ratings TO 'newsy'@'localhost';