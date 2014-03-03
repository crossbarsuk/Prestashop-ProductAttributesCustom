<?php

/*
 * 2007-2013 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 *         DISCLAIMER   *
 * *************************************** */
/* Do not edit or add to this file if you wish to upgrade Prestashop to newer
 * versions in the future.
 * ****************************************************
 * @category
 * @package   UsageCustom
 * @author    Julien HAY <jules.hay@gmail.com>
 * @site
 * @copyright
 * @license
 */

class ProductUsage extends ObjectModel
{
	public $id_productUsage;
	public $name;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'hut_usage_product',
        'primary' => 'id_product',
        'multilang' => FALSE,
        'fields' => array(
        	'id_usage' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
        	'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
        ),
    );

    public static function getDefinition() {
	    return ProductUsage::$definition;
    }

    public static function getUsageByProduct($id_product)
    {
	    $query = new DbQuery();

		$query
		  ->select('id_usage')
		  ->from(ProductUsage::$definition['table'], 'p')
		  ->where(ProductUsage::$definition['primary'].' = '.(int)$id_product)
		  ;

		return Db::getInstance()->executeS($query);
    }


}


