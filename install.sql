
-- -----------------------------------------------------
-- Table `PREFIX_hut_catusage`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `PREFIX_hut_catusage` (
  `id_catusage` INT unsigned NOT NULL AUTO_INCREMENT ,
  PRIMARY KEY (`id_catusage`) )
ENGINE = ENGINE_TYPE DEFAULT CHARSET=utf8;


-- -----------------------------------------------------
-- Table `PREFIX_hut_catusage_lang`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `PREFIX_hut_catusage_lang` (
  `id_catusage` INT unsigned NOT NULL ,
  `id_lang` INT unsigned NOT NULL ,
  `name` VARCHAR(250) NULL ,
  PRIMARY KEY (`id_catusage`, `id_lang`) )
ENGINE = ENGINE_TYPE DEFAULT CHARSET=utf8;



-- -----------------------------------------------------
-- Table `PREFIX_hut_usage`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `PREFIX_hut_usage` (
  `id_usage` INT unsigned NOT NULL AUTO_INCREMENT ,
  `id_catusage` INT unsigned NOT NULL ,
  PRIMARY KEY (`id_usage`, `id_catusage`) )
ENGINE = ENGINE_TYPE DEFAULT CHARSET=utf8;

-- -----------------------------------------------------
-- Table `PREFIX_hut_usage_lang`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `PREFIX_hut_usage_lang` (
  `id_usage` INT unsigned NOT NULL ,
  `id_lang` INT unsigned NOT NULL ,
  `name` VARCHAR(250) NULL ,
  PRIMARY KEY (`id_usage`, `id_lang`) )
ENGINE = ENGINE_TYPE DEFAULT CHARSET=utf8;


-- -----------------------------------------------------
-- Table `PREFIX_hut_usage_product`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `PREFIX_hut_usage_product` (
  `id_product` INT unsigned NOT NULL ,
  `id_usage` INT unsigned NOT NULL ,
  PRIMARY KEY (`id_product`, `id_usage`) )
ENGINE = ENGINE_TYPE DEFAULT CHARSET=utf8;
