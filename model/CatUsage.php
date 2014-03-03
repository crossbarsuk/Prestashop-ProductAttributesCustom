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

class CatUsage extends ObjectModel
{
	public $id_catusage;
	public $name;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'hut_catusage',
        'primary' => 'id_catusage',
        'multilang' => TRUE,
        'fields' => array(
        	'id_catusage' => array('type' => self::TYPE_INT, 'validate' => 'isInt'), // Auto increment donc non requis

            // Lang field
            'name' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
        ),
    );

    public static function getDefinition() {
	    return CatUsage::$definition;
    }

    public static function getAllByLang($id_lang, $where='')
    {
	    $query = new DbQuery();

		$query
		  ->select('distinct '.'l.'.CatUsage::$definition['primary'].', l.*')
		  ->from(CatUsage::$definition['table'], 'p')
		  ->leftJoin(CatUsage::$definition['table'].'_lang', 'l', 'l.'.CatUsage::$definition['primary'])
		  ->where('l.'.CatUsage::$definition['primary'].' = p.'.CatUsage::$definition['primary'])
		  ->where('l.id_lang = '.(int)$id_lang)
		  ;

		if($where != '') $query->where($where);

		return Db::getInstance()->executeS($query);
    }

    public static function getOne($id)
    {
	    $query = new DbQuery();

		$query
		  ->select('p.*, l.*')
		  ->from(CatUsage::$definition['table'], 'p')
		  ->leftJoin(CatUsage::$definition['table'].'_lang', 'l', 'l.'.CatUsage::$definition['primary'])
		  ->where('l.'.CatUsage::$definition['primary'].' = p.'.CatUsage::$definition['primary'])
		  ->where('p.'.CatUsage::$definition['primary'].' = '.(int)$id)
		  ;

		return Db::getInstance()->executeS($query);
    }


}


