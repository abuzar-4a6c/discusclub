-- MySQL Script generated by MySQL Workbench
-- Tue 31 Oct 2017 02:01:25 PM CET
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema forum
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `forum` ;

-- -----------------------------------------------------
-- Schema forum
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `forum` DEFAULT CHARACTER SET utf8 ;
USE `forum` ;

-- -----------------------------------------------------
-- Table `forum`.`role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum`.`role` ;

CREATE TABLE IF NOT EXISTS `forum`.`role` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(32) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `forum`.`album`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum`.`album` ;

CREATE TABLE IF NOT EXISTS `forum`.`album` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_album_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_album_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `forum`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `forum`.`image`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum`.`image` ;

CREATE TABLE IF NOT EXISTS `forum`.`image` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `path` TEXT NOT NULL,
  `album_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_image_album1_idx` (`album_id` ASC),
  CONSTRAINT `fk_image_album1`
    FOREIGN KEY (`album_id`)
    REFERENCES `forum`.`album` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `forum`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum`.`user` ;

CREATE TABLE IF NOT EXISTS `forum`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(256) NOT NULL,
  `first_name` VARCHAR(256) NOT NULL,
  `last_name` VARCHAR(256) NOT NULL,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(256) NOT NULL,
  `role_id` INT NOT NULL DEFAULT 2,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `signature` TEXT NULL,
  `birthdate` DATE NULL,
  `location` VARCHAR(256) NULL,
  `profile_img` INT NOT NULL DEFAULT 1,
  `news` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_user_role1_idx` (`role_id` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  INDEX `fk_user_image1_idx` (`profile_img` ASC),
  CONSTRAINT `fk_user_role1`
    FOREIGN KEY (`role_id`)
    REFERENCES `forum`.`role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_image1`
    FOREIGN KEY (`profile_img`)
    REFERENCES `forum`.`image` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `forum`.`category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum`.`category` ;

CREATE TABLE IF NOT EXISTS `forum`.`category` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `forum`.`sub_category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum`.`sub_category` ;

CREATE TABLE IF NOT EXISTS `forum`.`sub_category` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `category_id` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_sub_categorieen_categorieen1_idx` (`category_id` ASC),
  CONSTRAINT `fk_sub_categorieen_categorieen1`
    FOREIGN KEY (`category_id`)
    REFERENCES `forum`.`category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `forum`.`state`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum`.`state` ;

CREATE TABLE IF NOT EXISTS `forum`.`state` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `forum`.`topic`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum`.`topic` ;

CREATE TABLE IF NOT EXISTS `forum`.`topic` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `sub_category_id` INT NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `user_id` INT NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `state_id` INT NOT NULL DEFAULT 1,
  `last_changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_topics_sub_categorieen1_idx` (`sub_category_id` ASC),
  INDEX `fk_topics_user1_idx` (`user_id` ASC),
  INDEX `fk_topics_state1_idx` (`state_id` ASC),
  CONSTRAINT `fk_topics_sub_categorieen1`
    FOREIGN KEY (`sub_category_id`)
    REFERENCES `forum`.`sub_category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_topics_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `forum`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_topics_state1`
    FOREIGN KEY (`state_id`)
    REFERENCES `forum`.`state` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `forum`.`ip`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum`.`ip` ;

CREATE TABLE IF NOT EXISTS `forum`.`ip` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(15) NOT NULL,
  `blocked` TINYINT(1) NOT NULL DEFAULT 0,
  `user_id` INT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_ip_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_ip_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `forum`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `forum`.`view`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum`.`view` ;

CREATE TABLE IF NOT EXISTS `forum`.`view` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `topic_id` INT NOT NULL,
  `ip_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_aantal_bekeken_topics1_idx` (`topic_id` ASC),
  INDEX `fk_ips_ip1_idx` (`ip_id` ASC),
  CONSTRAINT `fk_aantal_bekeken_topics1`
    FOREIGN KEY (`topic_id`)
    REFERENCES `forum`.`topic` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ips_ip1`
    FOREIGN KEY (`ip_id`)
    REFERENCES `forum`.`ip` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `forum`.`reply`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum`.`reply` ;

CREATE TABLE IF NOT EXISTS `forum`.`reply` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `topic_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_reply_user1_idx` (`user_id` ASC),
  INDEX `fk_reply_topics1_idx` (`topic_id` ASC),
  CONSTRAINT `fk_reply_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `forum`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reply_topics1`
    FOREIGN KEY (`topic_id`)
    REFERENCES `forum`.`topic` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `forum`.`favorite`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum`.`favorite` ;

CREATE TABLE IF NOT EXISTS `forum`.`favorite` (
  `user_id` INT NOT NULL,
  `topic_id` INT NOT NULL,
  PRIMARY KEY (`user_id`, `topic_id`),
  INDEX `fk_user_has_topics_topics1_idx` (`topic_id` ASC),
  INDEX `fk_user_has_topics_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_user_has_topics_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `forum`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_topics_topics1`
    FOREIGN KEY (`topic_id`)
    REFERENCES `forum`.`topic` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `forum`.`message`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum`.`message` ;

CREATE TABLE IF NOT EXISTS `forum`.`message` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `message` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `opened` TINYINT(1) NOT NULL DEFAULT 0,
  `user_id_1` INT NOT NULL,
  `user_id_2` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_message_user1_idx` (`user_id_1` ASC),
  INDEX `fk_message_user2_idx` (`user_id_2` ASC),
  CONSTRAINT `fk_message_user1`
    FOREIGN KEY (`user_id_1`)
    REFERENCES `forum`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_message_user2`
    FOREIGN KEY (`user_id_2`)
    REFERENCES `forum`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `forum`.`sponsor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum`.`sponsor` ;

CREATE TABLE IF NOT EXISTS `forum`.`sponsor` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `image_id` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `url` VARCHAR(256) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_sponsor_image1_idx` (`image_id` ASC),
  CONSTRAINT `fk_sponsor_image1`
    FOREIGN KEY (`image_id`)
    REFERENCES `forum`.`image` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `forum`.`news`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum`.`news` ;

CREATE TABLE IF NOT EXISTS `forum`.`news` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `sub_category_id` INT NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_news_sub_category1_idx` (`sub_category_id` ASC),
  CONSTRAINT `fk_news_sub_category1`
    FOREIGN KEY (`sub_category_id`)
    REFERENCES `forum`.`sub_category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `forum`.`news_reply`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum`.`news_reply` ;

CREATE TABLE IF NOT EXISTS `forum`.`news_reply` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `content` TEXT NOT NULL,
  `news_id` INT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_news_reply_user1_idx` (`user_id` ASC),
  INDEX `fk_news_reply_news1_idx` (`news_id` ASC),
  CONSTRAINT `fk_news_reply_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `forum`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_news_reply_news1`
    FOREIGN KEY (`news_id`)
    REFERENCES `forum`.`news` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `forum`.`album_reply`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum`.`album_reply` ;

CREATE TABLE IF NOT EXISTS `forum`.`album_reply` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `album_id` INT NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_album_reply_user1_idx` (`user_id` ASC),
  INDEX `fk_album_reply_album1_idx` (`album_id` ASC),
  CONSTRAINT `fk_album_reply_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `forum`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_album_reply_album1`
    FOREIGN KEY (`album_id`)
    REFERENCES `forum`.`album` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `forum`.`forgot`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum`.`forgot` ;

CREATE TABLE IF NOT EXISTS `forum`.`forgot` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `token` VARCHAR(256) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_forgot_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_forgot_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `forum`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
