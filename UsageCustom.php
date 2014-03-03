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


if (!defined('_CAN_LOAD_FILES_'))
	exit;

// Require model
require_once(dirname(__FILE__) . '/model/CatUsage.php');
require_once(dirname(__FILE__) . '/model/Usage.php');
require_once(dirname(__FILE__) . '/model/ProductUsage.php');


class UsageCustom extends Module
{
	const INSTALL_SQL_FILE = 'install.sql';
	const UNINSTALL_SQL_FILE = 'uninstall.sql';


	public function __construct()
	{
	 	$this->name = 'UsageCustom';
	 	$this->tab = 'Custom Module';
	 	$this->version = '1.0';
		$this->author = 'Julien HAY';
		$this->need_instance = 0;

	 	parent::__construct();

        $this->displayName = $this->l('Champs Usages Custom');
        $this->description = $this->l('Usages Custom pour fiche produit');
		$this->confirmUninstall = $this->l('Are you sure you want to delete UsageCustom ?');
		$this->secure_key = Tools::encrypt($this->name);
	}


	// ********************************************************
	//
	// INSTALLATION/DESINSTALLATION DU MODULE
	//
	// ********************************************************

	public function install()
	{
		if (!parent::install()
			|| !$this->execSqlFile(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE)
			|| !$this->registerHook('displayAdminProductsExtra')
			|| !$this->registerHook('actionProductUpdate')
			|| !$this->registerHook('displayRightColumnProduct')
			)
			return false;
		return true;
	}

	public function uninstall()
	{
		if (!parent::uninstall()
			|| !$this->execSqlFile(dirname(__FILE__).'/'.self::UNINSTALL_SQL_FILE))
			return false;
		return true;
	}

	private function execSqlFile($file)
	{
		// Installation base
		if (!file_exists($file))
			return false;
		else if (!$sql = file_get_contents($file))
			return false;
		$sql = str_replace(array('PREFIX_', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
		$sql = preg_split("/;\s*[\r\n]+/", trim($sql));

		foreach ($sql as $query)
			if (!Db::getInstance()->execute(trim($query)))
				return false;

		return true;
	}



	// ********************************************************
	//
	// ECRANS ADMINISTRATION DONNEES REFERENCES
	//
	// ********************************************************

	public function postProcess()
	{
		// AJOUT / MODIFICATION

		if (Tools::isSubmit('submitCatUsageValidedit') || Tools::isSubmit('submitCatUsageValidadd'))
		{
			// Ajout d'une categorie
			$this->context->controller->getLanguages();

			$id_cat = Tools::getValue('id_catusage');

			$obj = new CatUsage($id_cat);

			foreach ($this->context->controller->_languages as $language)
				$obj->name[$language['id_lang']] = Tools::getValue('name_'.$language['id_lang']);

			if(Tools::getValue('id_catusage') != '') $obj->update();
			else $obj->add();
		}
		// Suppression
		if (Tools::isSubmit('deletehut_catusage'))
		{
			$id_cat = Tools::getValue('id_catusage');
			$obj = new CatUsage($id_cat);
			$obj->delete();
		}


		// AJOUT / MODIFICATION
		if (Tools::isSubmit('submitUsageValidedit') || Tools::isSubmit('submitUsageValidadd'))
		{
			// Ajout d'une categorie
			$this->context->controller->getLanguages();

			$id_cat = Tools::getValue('id_usage');

			$obj = new Usage($id_cat);
			$obj->id_catusage = Tools::getValue('id_catusage');

			foreach ($this->context->controller->_languages as $language)
				$obj->name[$language['id_lang']] = Tools::getValue('name_'.$language['id_lang']);

			if(Tools::getValue('id_usage') != '') $obj->update();
			else $obj->add();
		}
		// Suppression
		if (Tools::isSubmit('deletehut_usage'))
		{
			$id_cat = Tools::getValue('id_usage');
			$obj = new Usage($id_cat);
			$obj->delete();
		}
	}

	public function initToolbar($classname = '', $id_ref = '')
	{
		$current_index = AdminController::$currentIndex;
		$token = Tools::getAdminTokenLite('AdminModules');

		$back = Tools::safeOutput(Tools::getValue('back', ''));

		if (!isset($back) || empty($back))
			$back = $current_index.'&amp;configure='.$this->name.'&token='.$token;

		switch ($this->_display)
		{
			case 'add':
			case 'edit':
				$this->toolbar_btn['save'] = array(
					'href' => '#',
					'desc' => $this->l('Sauvegarder')
				);

				$this->toolbar_btn['cancel'] = array(
					'href' => $back,
					'desc' => $this->l('Annuler')
				);
				break;

			case 'index':
				$suffix_url='';
				if(Tools::getValue('id_catusage'))
				{
					$suffix_url = '&id_catusage='.(int)Tools::getValue('id_catusage');
				}

				$this->toolbar_btn['new'] = array(
					'href' => $current_index.'&amp;configure='.$this->name.'&amp;token='.$token.'&amp;add'.$classname.$suffix_url,
					'desc' => $this->l('Ajouter')
				);
				$this->toolbar_btn['back'] = array(
					'href' => $back,
					'desc' => $this->l('Retour ')
				);
				break;
			default:
				break;
		}

		return $this->toolbar_btn;
	}

	private function initFormCategory()
	{
		$helper = new HelperForm();

		$helper->module = $this;
		$helper->name_controller = $this->name;
		$helper->identifier = $this->identifier;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->languages = $this->context->controller->_languages;
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->default_form_language = $this->context->controller->default_form_language;
		$helper->allow_employee_form_lang = $this->context->controller->allow_employee_form_lang;
		$helper->toolbar_scroll = true;
		$helper->title = $this->displayName;
		$helper->toolbar_btn = $this->initToolbar();

		return $helper;
	}

	public function getContent()
	{
		global $cookie;

		$this->_html = '';
		$this->postProcess();

     	if (Tools::isSubmit('updatehut_catusage'))
     	{
	     	$this->_display = 'edit';

			$this->_displayFormCat('CatUsage');
     	}
     	elseif (Tools::isSubmit('addCatUsage'))
     	{
	     	$this->_display = 'add';

			$this->_displayFormCat('CatUsage');
     	}

     	elseif (Tools::isSubmit('updatehut_usage'))
     	{
	     	$this->_display = 'edit';

			$this->_displayFormCat('Usage');
     	}
     	elseif (Tools::isSubmit('addUsage'))
     	{
	     	$this->_display = 'add';

			$this->_displayFormCat('Usage');
     	}

     	elseif (Tools::isSubmit('viewhut_catusage'))
     	{
	     	$id = Tools::getValue('id_catusage');
	     	$this->_display = 'index';

	     	$fields_list = array(
				'name' => array(
					'title' => $this->l('Nom'),
					'width' => 120,
					'type' => 'text',
				)
			);

			$this->_html = $this->displayList(
				'Usage',
				$fields_list,
				array('edit', 'delete'),
				'id_catusage='.(int)$id
			);
     	}
	    else
	    {
	    	$this->_display = 'index';

	     	$fields_list = array(
				'name' => array(
					'title' => $this->l('Nom'),
					'width' => 120,
					'type' => 'text',
				)
			);

			$this->_html = $this->displayList(
				'CatUsage',
				$fields_list,
				array('view', 'edit', 'delete')
			);
     	}


        return $this->_html;
	}


	public function displayList($classname, $fields, $actions, $where = '')
	{
		$html_return = "";
		/* Language */
	 	$defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));
		$languages = Language::getLanguages(false);

		$definition = call_user_func(array($classname, 'getDefinition'));

		$this->fields_list = $fields;

		// Genere la liste
		$helper = new HelperList();

		$helper->shopLinkType = '';
		$helper->module = $this;
		$helper->identifier = $definition['primary'];
		$helper->actions = $actions;

		$helper->imageType = 'jpg';

		// Toolbar
		$helper->toolbar_btn = $this->initToolbar($classname);
		$helper->title = $this->displayName;
		$helper->table = $definition['table'];
		$helper->token = Tools::getAdminTokenLite('AdminModules');

		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		if($where != '') $helper->currentIndex .= '&'.$where.'&viewhut_catusage';

		// Condition pour methode getAll
		$conditions = array();
		$conditions[] = (int)Configuration::get('PS_LANG_DEFAULT');
		if($where != '') $conditions[] = $where;

		$list = call_user_func_array(array($classname, 'getAllByLang'), $conditions);

		$html_return .= $helper->generateList($list, $this->fields_list);

		return $html_return;
	}


    private function _displayFormCat($classname, $conditions='')
    {
	    $definition = call_user_func(array($classname, 'getDefinition'));

	    if($this->_display == "edit") $title_fieldset = $this->l('Modifier');
	    else $title_fieldset = $this->l('Ajouter');

		$this->fields_form[0]['form'] = array(
			'tinymce' => true,
			'legend' => array(
				'title' => $title_fieldset
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('Nom :'),
					'name' => 'name',
					'lang' => true,
					'size' => 40
				)
			),
			'submit' => array(
				'name' => 'submitInfo',
				'title' => $this->l('   Save   '),
				'class' => 'button'
			)
		);


		$this->context->controller->getLanguages();
		$helper = $this->initFormCategory();

		if($this->_display == "edit"){
			$id = (int)Tools::getValue($definition['primary']);
			$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name.'&'.$definition['primary'].'='.$id;

			$conditions[] = $id;
			$list = call_user_func_array(array($classname, 'getOne'), $conditions);

			$this->context->controller->getLanguages();

			foreach ($list as $field)
				$this->fields_value['name'][$field['id_lang']] = $field['name'];


			$helper->fields_value = $this->fields_value;
		}

		if($classname == 'Usage') $helper->currentIndex .= '&viewhut_catusage&id_catusage='.(int)Tools::getValue('id_catusage');

		$helper->submit_action = 'submit'.$classname.'Valid'.$this->_display;

		$this->_html .= $helper->generateForm($this->fields_form);

		return;
    }


	// ********************************************************
	//
	// HOOK - ONGLET ADMIN PRODUIT
	//
	// ********************************************************

	public function hookDisplayAdminProductsExtra($params)
	{
		$id_product = Tools::getValue('id_product');

        $allcat = CatUsage::getAllByLang((int)Configuration::get('PS_LANG_DEFAULT'));
        $return_arr = array();

        foreach($allcat as $cat)
        {
	        $tmp = array();
	        $tmp = $cat;
	        $tmp['usages'] = Usage::getAllByLang((int)Configuration::get('PS_LANG_DEFAULT'), 'id_catusage='.$cat['id_catusage']);

	        $return_arr[] = $tmp;
        }

        $this->context->smarty->assign(array(
            'cats' => $return_arr,
            'selection' => ProductUsage::getUsageByProduct($id_product)
        ));


        return $this->display(__FILE__, 'views/templates/admin/admin.tpl');
    }

	//Save Data
	public function hookActionProductUpdate($params)
	{
       	$id_product = Tools::getValue('id_product');
        $id_usage = Tools::getValue('id_usage');

        if(isset($id_product) && !empty($id_usage))
        {
	        $tmp = new ProductUsage($id_product);
	        $tmp->delete();

	        foreach($id_usage as $id)
	        {
		        $tmp = new ProductUsage();
		        $tmp->id_product = $id_product;
		        $tmp->id_usage = $id;
		        $tmp->save();
	        }
        }
	}


	// ********************************************************
	//
	// HOOK - AFFICHAGE FRONT EXTRA RIGHT PRODUCT
	//
	// ********************************************************

	public function hookDisplayRightColumnProduct($params)
	{
		$id_product = Tools::getValue('id_product');

        $allcat = CatUsage::getAllByLang((int)Configuration::get('PS_LANG_DEFAULT'));
        $return_arr = array();

        foreach($allcat as $cat)
        {
	        $tmp = array();
	        $tmp = $cat;
	        $tmp['usages'] = Usage::getAllByLang((int)Configuration::get('PS_LANG_DEFAULT'), 'id_catusage='.$cat['id_catusage']);

	        $return_arr[] = $tmp;
        }

        $this->context->smarty->assign(array(
            'cats' => $return_arr,
            'selection' => ProductUsage::getUsageByProduct($id_product)
        ));


        return $this->display(__FILE__, 'views/templates/front/product.tpl');
	}


}

?>