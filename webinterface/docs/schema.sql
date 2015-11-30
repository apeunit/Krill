SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `raspberries`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `raspberries` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `serial` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `logs`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `logs` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  `type` INT NOT NULL ,
  `message` VARCHAR(255) NOT NULL DEFAULT '' ,
  `raspberry_id` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_logs_raspberries_idx` (`raspberry_id` ASC) ,
  CONSTRAINT `fk_logs_raspberries`
    FOREIGN KEY (`raspberry_id` )
    REFERENCES `raspberries` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `youtubes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `youtubes` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  `access_token` VARCHAR(255) NULL ,
  `refresh_token` VARCHAR(255) NULL ,
  `expires_in` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `configs`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `configs` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  `key` VARCHAR(255) NOT NULL ,
  `value` VARCHAR(255) NOT NULL ,
  `raspberry_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_configs_raspberries1_idx` (`raspberry_id` ASC) ,
  CONSTRAINT `fk_configs_raspberries1`
    FOREIGN KEY (`raspberry_id` )
    REFERENCES `raspberries` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

