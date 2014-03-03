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

class Usage extends ObjectModel
{
	public $id_usage;
	public $id_catusage;

	public $name;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'hut_usage',
        'primary' => 'id_usage',
        'multilang' => TRUE,
        'fields' => array(
            'id_usage' => array('type' => self::TYPE_INT, 'validate' => 'isInt'), // Auto increment donc non requis
            'id_catusage' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'required' => TRUE),

            // Lang field
            'name' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
        ),
    );

	public static function getDefinition() {
	    return Usage::$definition;
    }

	public static function getAllByLang($id_lang, $where='')
    {
	    $query = new DbQuery();

		$query
		  ->select('distinct '.'l.'.Usage::$definition['primary'].', l.*')
		  ->from(Usage::$definition['table'], 'p')
		  ->leftJoin(Usage::$definition['table'].'_lang', 'l', 'l.'.Usage::$definition['primary'])
		  ->where('l.'.Usage::$definition['primary'].' = p.'.Usage::$definition['primary'])
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
		  ->from(Usage::$definition['table'], 'p')
		  ->leftJoin(Usage::$definition['table'].'_lang', 'l', 'l.'.Usage::$definition['primary'])
		  ->where('l.'.Usage::$definition['primary'].' = p.'.Usage::$definition['primary'])
		  ->where('p.'.Usage::$definition['primary'].' = '.(int)$id)
		  ;

		return Db::getInstance()->executeS($query);
    }
}

