-- MySQL Script generated by MySQL Workbench
-- Tue Jul 24 16:40:40 2018
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`user` (
  `id_user` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(45) NULL,
  `password` VARCHAR(45) NULL,
  `firstname` VARCHAR(45) NOT NULL,
  `lastname` VARCHAR(45) NOT NULL,
  `longitude` DOUBLE NULL,
  `latitude` DOUBLE NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`nurse`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`nurse` (
  `id_nurse` INT NOT NULL AUTO_INCREMENT,
  `birthdate` DATETIME NOT NULL,
  `id_user` INT NOT NULL,
  `is_valid` TINYINT NOT NULL,
  PRIMARY KEY (`id_nurse`, `id_user`),
  INDEX `fk_nurse_user1_idx` (`id_user` ASC),
  CONSTRAINT `fk_nurse_user1`
    FOREIGN KEY (`id_user`)
    REFERENCES `mydb`.`user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`children`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`children` (
  `id_children` INT NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(45) NOT NULL,
  `birthdate` DATETIME NOT NULL,
  `description` VARCHAR(45) NOT NULL,
  `id_user` INT NOT NULL,
  PRIMARY KEY (`id_children`, `id_user`),
  INDEX `fk_children_user1_idx` (`id_user` ASC),
  CONSTRAINT `fk_children_user1`
    FOREIGN KEY (`id_user`)
    REFERENCES `mydb`.`user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`post` (
  `id_post` INT NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(45) NOT NULL,
  `id_user` INT NOT NULL,
  `longitude` DOUBLE NULL,
  `latitude` DOUBLE NULL,
  `title` VARCHAR(45) NOT NULL,
  `date_start` DATETIME NULL,
  `date_end` DATETIME NULL,
  `nb_children` INT NOT NULL,
  `hourly_rate` DOUBLE NOT NULL,
  `note` VARCHAR(45) NULL,
  PRIMARY KEY (`id_post`, `id_user`),
  INDEX `fk_post_user_idx` (`id_user` ASC),
  CONSTRAINT `fk_post_user`
    FOREIGN KEY (`id_user`)
    REFERENCES `mydb`.`user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`comment` (
  `id_comment` INT NOT NULL AUTO_INCREMENT,
  `content` VARCHAR(255) NOT NULL,
  `id_post` INT NOT NULL,
  `title` VARCHAR(45) NOT NULL,
  `created_at` DATETIME NULL,
  `uptated_at` DATETIME NULL,
  `id_user` INT NOT NULL,
  PRIMARY KEY (`id_comment`, `id_post`, `id_user`),
  INDEX `fk_comment_post1_idx` (`id_post` ASC),
  INDEX `fk_comment_user1_idx` (`id_user` ASC),
  CONSTRAINT `fk_comment_post1`
    FOREIGN KEY (`id_post`)
    REFERENCES `mydb`.`post` (`id_post`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comment_user1`
    FOREIGN KEY (`id_user`)
    REFERENCES `mydb`.`user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
