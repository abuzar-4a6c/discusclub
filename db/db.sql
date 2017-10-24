-- MySQL Script generated by MySQL Workbench
-- Tue 24 Oct 2017 03:09:52 PM CEST
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
  `role_id` INT NOT NULL DEFAULT 1,
  `forgot_pass` VARCHAR(256) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_user_role1_idx` (`role_id` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  CONSTRAINT `fk_user_role1`
    FOREIGN KEY (`role_id`)
    REFERENCES `forum`.`role` (`id`)
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
-- Table `forum`.`ips`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum`.`ips` ;

CREATE TABLE IF NOT EXISTS `forum`.`ips` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ip_adres` VARCHAR(50) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` INT NULL,
  `topic_id` INT NULL,
  `blocked` TINYINT(1) NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_aantal_bekeken_user_idx` (`user_id` ASC),
  INDEX `fk_aantal_bekeken_topics1_idx` (`topic_id` ASC),
  CONSTRAINT `fk_aantal_bekeken_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `forum`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_aantal_bekeken_topics1`
    FOREIGN KEY (`topic_id`)
    REFERENCES `forum`.`topic` (`id`)
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
  `topic_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_message_topic1_idx` (`topic_id` ASC),
  CONSTRAINT `fk_message_topic1`
    FOREIGN KEY (`topic_id`)
    REFERENCES `forum`.`topic` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
