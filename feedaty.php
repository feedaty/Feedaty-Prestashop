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
*  @author Feedaty <info@feedaty.com>
*  @copyright  2012-2017 Feedaty
*  @version  Release: 2.1.0 $
*/

if (!defined('_PS_VERSION_'))
	exit;

class Feedaty extends Module
{

	/**
	* Function __construct - The constructor
	*
	*/
	public function __construct()
	{
		if(version_compare(_PS_VERSION_, '1.6','>=')) $this->bootstrap = true;
		$this->name = 'feedaty';
		$this->tab = 'front_office_features';
		$this->version = '2.1.0';
		$this->author = 'Feedaty.com';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Feedaty');
		$this->description = $this->l('Add the Feedaty review system into your PrestaShop');
		$this->feedaty_current_language = $this->context->language->iso_code;
		// Call classes from lib
		require(_PS_MODULE_DIR_.$this->name.'/lib/FeedatyClasses.php');


		// Old PrestaShop version, load the backward compatibility
		if (version_compare(_PS_VERSION_, '1.5.0.2', '<=')) {
	    		require(_PS_MODULE_DIR_.$this->name.'/backward_compatibility/backward.php');
		}
	}

	/**
	* Function install() - called on module install
	*
	*
	*/
	public function install()
	{

		if (Shop::isFeatureActive()) Shop::setContext(Shop::CONTEXT_ALL);

		/* Create (if not exists) the feedaty cache table */
		Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'feedaty_cache` (
			`id_key` VARCHAR(250) NULL,
			`value` TEXT NULL,
			`expiration` INT NULL,
			UNIQUE INDEX `id_key` (`id_key`)
		)
		ENGINE='._MYSQL_ENGINE_);

		$success = $this->feedatyInstallerVersion();
		return $success;
	}

	/**
	* Function uninstall() - called on module uninstall
	*
	*
	*/
	public function uninstall() 
	{
		Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'feedaty_cache`'._MYSQL_ENGINE_);

		if (!parent::uninstall() ||
    		!Configuration::deleteByName('feedaty_code') ||
			!Configuration::deleteByName('feedaty_secret') ||
			!Configuration::deleteByName('feedaty_store_template') ||
			!Configuration::deleteByName('feedaty_store_position') ||
			!Configuration::deleteByName('feedaty_widget_store_enabled') ||
			!Configuration::deleteByName('feedaty_product_template') ||
			!Configuration::deleteByName('feedaty_product_position') ||
			!Configuration::deleteByName('feedaty_widget_product_enabled') ||
			!Configuration::deleteByName('feedaty_widget_prod_prev_en') ||
			!Configuration::deleteByName('feedaty_snip_merchant_position') ||
			!Configuration::deleteByName('feedaty_snip_merchant_enabled') ||
			!Configuration::deleteByName('feedaty_snip_product_position') ||
			!Configuration::deleteByName('feedaty_snip_product_enabled') ||
			!Configuration::deleteByName('feedaty_product_review_enabled') ||
			!Configuration::deleteByName('feedaty_count_review') ||
			!Configuration::deleteByName('feedaty_status_request')
  		)
    		return false;

		return true;
	}

	/*
	*--------------------------------------------------------------------------
	*	HOOKS FOR PRESTASHOP >= 1.5.0.2
	*--------------------------------------------------------------------------
	*/

	//Store page hooks
	public function hookDisplayHeader($params) {

		$this->context->controller->addCSS(_MODULE_DIR_.$this->name.'/css/feedaty_styles.css');
	}

	public function hookDisplayHome($params)
	{
		$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);

		$feedaty_widget_data = $feedatyGenerateElements->fdGenerateStoreWidget('displayHome');
		$feedaty_microdata = $feedatyGenerateElements->fdGenerateMerchantSnippet('displayHome');

		$this->feedatySmartyWidgets($feedaty_widget_data,$feedaty_microdata,'home');

		return $this->fetchTemplate('FeedatyWidgetStore.tpl');	
	}

	public function hookDisplayFooter($params)
	{
		$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);

		$feedaty_widget_data = $feedatyGenerateElements->fdGenerateStoreWidget('displayFooter');
		$feedaty_microdata = $feedatyGenerateElements->fdGenerateMerchantSnippet('displayFooter');

		$this->feedatySmartyWidgets($feedaty_widget_data,$feedaty_microdata,'footer');

		return $this->fetchTemplate('FeedatyWidgetStore.tpl');	
	}

	public function hookDisplayTop($params)
	{
		$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);

		$feedaty_widget_data = $feedatyGenerateElements->fdGenerateStoreWidget('displayTop');
		$feedaty_microdata = $feedatyGenerateElements->fdGenerateMerchantSnippet('displayTop');

		$this->feedatySmartyWidgets($feedaty_widget_data,$feedaty_microdata,'top');

		return $this->fetchTemplate('FeedatyWidgetStore.tpl');
	}

	public function hookDisplayLeftColumn($params)
	{
		$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);

		$feedaty_widget_data = $feedatyGenerateElements->fdGenerateStoreWidget('displayLeftColumn');
		$feedaty_microdata =  $feedatyGenerateElements->fdGenerateMerchantSnippet('displayLeftColumn');

		$this->feedatySmartyWidgets($feedaty_widget_data,$feedaty_microdata,'leftColumn');

		return $this->fetchTemplate('FeedatyWidgetStore.tpl');
	}

	public function hookDisplayRightColumn($params)
	{
		$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);

		$feedaty_widget_data = $feedatyGenerateElements->fdGenerateStoreWidget('displayRightColumn');
		$feedaty_microdata = $feedatyGenerateElements->fdGenerateMerchantSnippet('displayRightColumn');
		
		$this->feedatySmartyWidgets($feedaty_widget_data,$feedaty_microdata,'rightColumn');

		return $this->fetchTemplate('FeedatyWidgetStore.tpl');
	}


	// Product Page hooks
	public function hookDisplayLeftColumnProduct()
	{
		$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);

		$feedaty_widget_data = $feedatyGenerateElements->fdGenerateProductWidget('displayLeftColumnProduct');
		$feedaty_microdata =  $feedatyGenerateElements->fdGenerateProductSnippet('displayLeftColumnProduct');

		$this->feedatySmartyWidgets($feedaty_widget_data,$feedaty_microdata,'leftColumnProduct');

		return $this->fetchTemplate('FeedatyTemplateProduct.tpl');
	}

	public function hookDisplayRightColumnProduct()
	{
		$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);

		$feedaty_widget_data = $feedatyGenerateElements->fdGenerateProductWidget('displayRightColumnProduct');
		$feedaty_microdata =  $feedatyGenerateElements->fdGenerateProductSnippet('displayRightColumnProduct');

		$this->feedatySmartyWidgets($feedaty_widget_data,$feedaty_microdata,'rightColumnProduct');

		return $this->fetchTemplate('FeedatyTemplateProduct.tpl');
	}

	public function hookDisplayProductButtons() 
	{
		$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);

		$feedaty_widget_data = $feedatyGenerateElements->fdGenerateProductWidget('displayProductButtons');
		$feedaty_microdata =  $feedatyGenerateElements->fdGenerateProductSnippet('displayProductButtons');

		$this->feedatySmartyWidgets($feedaty_widget_data,$feedaty_microdata,'productButtons');

		return $this->fetchTemplate('FeedatyTemplateProduct.tpl');
	}

	public function hookDisplayProductAdditionalInfo() {
		$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);

		$feedaty_widget_data = $feedatyGenerateElements->fdGenerateProductWidget('displayProductAdditionalInfo');
		$feedaty_microdata =  $feedatyGenerateElements->fdGenerateProductSnippet('displayProductAdditionalInfo');

		$this->feedatySmartyWidgets($feedaty_widget_data,$feedaty_microdata,'ProductAdditionalInfo');

		return $this->fetchTemplate('FeedatyTemplateProduct.tpl');		
	}

	public function hookDisplayFooterProduct($params) 
	{
		if(version_compare(_PS_VERSION_, '1.6','>=')) {

			$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);
			$feedatyWebservice = new FeedatyWebservice($this->feedaty_current_language);

		    if (Configuration::get('feedaty_product_review_enabled') == 1) {
		  		// Get id of product 
				$id_pro = Tools::getValue('id_product');
				// Get product informations 
				$toview['data_review'] = $feedatyWebservice->fdGetProductData($id_pro);
				// Send n. of reviews to show to smarty 
				$toview['count_review'] = Configuration::get('feedaty_count_review');
				// Set url based on n. of reviews 
				if (Tools::strlen($toview['data_review']['Product']['Url']) == 0) $url = $toview['data_review']['Url'];
				else $url = $toview['data_review']['Product']['Url'];
				// Create html of link 
				$toview['link'] = '<a href="'.$toview['data_review']['Product']['Url'].'">'.$this->l('Read all reviews').'</a>';

				// Generate stars img 
				if (is_array($toview['data_review']['Feedbacks']))
					foreach ($toview['data_review']['Feedbacks'] as $k => $v)
						$toview['data_review']['Feedbacks'][$k]['stars_html'] = $feedatyGenerateElements->fdGenerateStars($v['ProductRating']);

				// Send vars to smarty
				$this->smarty->assign('data_review', $toview['data_review']);
				$this->smarty->assign('count_review', $toview['count_review']);
				$this->smarty->assign('feedaty_link', $toview['link']);
				

		  		$tabTitleSuffix ='('.count($toview['data_review']['Feedbacks']).')';
		  		$tabContent = $this->fetchTemplate('/views/templates/front/productReviews.tpl');
		  		$this->context->smarty->assign('tabTitleSuffix', $tabTitleSuffix);
        		$this->context->smarty->assign('tabContent', $tabContent);

        		// Finally retrive template
        		return $this->fetchTemplate('feedatyProductTab.tpl');
			
		    }

		}
	}


	/*
	*----------------------------------------------------
	*	HOOKS FOR PRESTASHOP < 1.5.0.2
	*----------------------------------------------------
	*/

	// Product Page hooks
	public function hookextraLeft()
	{
		$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);

		$html = $feedatyGenerateElements->fdGenerateProductWidget('extraLeft');
		$html.= $feedatyGenerateElements->fdGenerateProductSnippet('extraLeft');

		return $html;
	}

	public function hookextraRight()
	{
		$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);

		$html = $feedatyGenerateElements->fdGenerateProductWidget('extraRight');
		$html.= $feedatyGenerateElements->fdGenerateProductSnippet('extraRight');

		return $html;
	}
	
	public function hookproductActions()
	{
		$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);

		$html = $feedatyGenerateElements->fdGenerateProductWidget('productActions');
		$html.= $feedatyGenerateElements->fdGenerateProductSnippet('productActions');

		return $html;
	}

	public function hookproductOutOfStock()
	{
		$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);

		$html = $feedatyGenerateElements->fdGenerateProductWidget('productOutOfStock');
		$html.= $feedatyGenerateElements->fdGenerateProductSnippet('productOutOfStock');

		return $html;
	}

	public function hookproductfooter()
	{
		$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);

		$html = $feedatyGenerateElements->fdGenerateProductWidget('productfooter');
		$html.= $feedatyGenerateElements->fdGenerateProductSnippet('productfooter');

		return $html;
	}


	//Store page hooks
	public function hookheader()
	{
		$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);

		$html = $feedatyGenerateElements->fdGenerateStoreWidget('header');
		$html.= $feedatyGenerateElements->fdGenerateMerchantSnippet('header');

		return $html;
	}

	public function hookhome()
	{
		$feedatyGenerateElements = new FeedatyGenerateElements();

		$html = $feedatyGenerateElements->fdGenerateStoreWidget('home');
		$html.= $feedatyGenerateElements->fdGenerateMerchantSnippet('home');

		return $html;
	}

	public function hookfooter()
	{
		$feedatyGenerateElements = new FeedatyGenerateElements();

		$html = $feedatyGenerateElements->fdGenerateStoreWidget('footer');
		$html .= $feedatyGenerateElements->fdGenerateMerchantSnippet('footer');

		return $html;	
	}

	public function hooktop()
	{
		$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);

		$html = $feedatyGenerateElements->fdGenerateStoreWidget('top');
		$html .= $feedatyGenerateElements->fdGenerateMerchantSnippet('top');

		return $html;
	}

	public function hookleftColumn()
	{
		$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);

		$html = $feedatyGenerateElements->fdGenerateStoreWidget('leftColumn');
		$html .= $feedatyGenerateElements->fdGenerateMerchantSnippet('leftColumn');

		return $html;
	}

	public function hookrightColumn()
	{
		$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);

		$html = $feedatyGenerateElements->fdGenerateStoreWidget('rightColumn');
		$html.= $feedatyGenerateElements->fdGenerateMerchantSnippet('rightColumn');
		return $html;
	}


	public function hookBackOfficeHeader()
	{
		/* Add the css on feedaty backend page */
		if(version_compare(_PS_VERSION_, '1.6','>=')) $this->context->controller->addCSS(_MODULE_DIR_.$this->name.'/css/ps_style.16.css');

		elseif (version_compare(_PS_VERSION_, '1.5', '<'))
			return '<link rel="stylesheet" type="text/css" href="'._MODULE_DIR_.$this->name.'/css/ps_style.css" />';
		elseif (method_exists($this->context->controller, 'addCSS'))
			$this->context->controller->addCSS(_MODULE_DIR_.$this->name.'/css/ps_style.css');
		else
			Tools::addCSS(_MODULE_DIR_.$this->name.'/css/ps_style.css');
	}


	// Add new tab for feedaty reviews 
	public function hookProductTab()
	{
		if (Configuration::get('feedaty_product_review_enabled') == 1)
			return $this->fetchTemplate('/views/templates/front/productTab.tpl');
	}

	// Content for tab on product page
	public function hookProductTabContent()
	{	
		$feedatyWebservice = new FeedatyWebservice($this->feedaty_current_language);
		$feedatyGenerateElements = new FeedatyGenerateElements($this->feedaty_current_language);

		/* If reviews on product pages are enabled */
		if (Configuration::get('feedaty_product_review_enabled') == 1)
		{
			/* Get id of product */
			$id_pro = Tools::getValue('id_product');
			/* Get product informations */
			$toview['data_review'] = $feedatyWebservice->fdGetProductData($id_pro);
			/* Send n. of reviews to show to smarty */
			$toview['count_review'] = Configuration::get('feedaty_count_review');
			/* Set url based on n. of reviews */
			if (Tools::strlen($toview['data_review']['Product']['Url']) == 0) $url = $toview['data_review']['Url'];
			else $url = $toview['data_review']['Product']['Url'];
			/* Create html of link */
			$toview['link'] = '<a href="'.$toview['data_review']['Product']['Url'].'">'.$this->l('Read all reviews').'</a>';

			/* Generate stars img */
			if (is_array($toview['data_review']['Feedbacks']))
				foreach ($toview['data_review']['Feedbacks'] as $k => $v)
					$toview['data_review']['Feedbacks'][$k]['stars_html'] = $feedatyGenerateElements->fdGenerateStars($v['ProductRating']);

			/* Send vars to smarty */
			$this->smarty->assign('data_review', $toview['data_review']);
			$this->smarty->assign('count_review', $toview['count_review']);
			$this->smarty->assign('feedaty_link', $toview['link']);
			/* Finally retrive template */
			return $this->fetchTemplate('/views/templates/front/productTabContent.tpl');
		}
	}

	/**
	* Function hookUpdateOrderStatus - is used to send order when status will be reach to feedaty api service 
	* 
	* @param $var1 
	*
	*/
	public function hookUpdateOrderStatus($var1)
	{
		$feedatyWebservice = new FeedatyWebservice($this->feedaty_current_language);
		/* Configuration status is reached */
		$feedatyWebservice->fdSendOrder($var1);
	}

	//getContent regards the backend configuration page of feedaty plugin
	public function getContent()
	{
		$feedatyWebservice = new FeedatyWebservice($this->feedaty_current_language);
		$feedatyPositions = new FeedatyPositions();

		$this->smarty->cache = false;
		$this->smarty->force_compile = true;
		$html = '';
		/* This div is added to add some css rules on old prestashop versions */
		if (version_compare(_PS_VERSION_, '1.5', '<'))
			$html .= '<div class="ps14">';
		/* Internal cache system will be used for feedaty, this function delete expired cache */
		$feedatyWebservice->_delete_feedaty_cache();
		if (Tools::getValue('export') == 'csv')
		{
			/* Export of csv with last 3 months orders */
			$html .= $this->fdExportCsv();
		}
		$feedaty['msg'] = '';
		
		if (Tools::getValue('act') == 'requesttrial')
		{
			//Send information to Feedaty for requesting a free trial account 
			$inputerror = 0;
			if (Tools::strlen(Tools::getValue('feedaty_email')) == 0 || Tools::strlen(Tools::getValue('feedaty_password')) == 0)
				$inputerror = 1;
			elseif (!Validate::isEmail(Tools::getValue('feedaty_email')))
				$inputerror = 1;

			if ($inputerror == 0)
			{
				/* We collect from input: email and password;
				from prestashop: name of store, url, (for statistical purposes) server os, prestashop version, plugin version and user ip */
				$data_post['feedaty_email'] = (string)Tools::getValue('feedaty_email');
				$data_post['feedaty_password'] = (string)Tools::getValue('feedaty_password');
				$data_post['name'] = (string)Configuration::get('PS_SHOP_NAME');
				$data_post['url'] = (string)_PS_BASE_URL_.__PS_BASE_URI__;
				$data_post['os'] = (string)PHP_OS;
				$data_post['platform'] = (string)'PrestaShop '._PS_VERSION_;
				$data_post['pv'] = (string)$this->version;
				$data_post['ip'] = (string)$_SERVER['REMOTE_ADDR'];

				// Request is sent by curl 
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://'.'widget.stage.zoorate.com/plugin/install-files/plugin/prestashop/');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, '60');
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_post);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
				$content = trim(curl_exec($ch));
				curl_close($ch);

				$content = Tools::jsonDecode($content, true);
			}

			//We use ajax or simply post for old browsers or errors so we sent msg if it is ok or create json for ajax 
			if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && Tools::strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
			{
				if ($inputerror == 1)
					$return['success'] = -1;
				elseif ($content['success'] == 1)
					$return['success'] = 1;
				else
					$return['success'] = 0;

				echo  Tools::jsonEncode($return);
				exit;
			}
			else
				if ($content['success'] == 1)
					$feedaty['msg'] = 1;
				else
					$feedaty['msg'] = 2;
		}
		/* Update settings */
		if (Tools::isSubmit('submitModule') && Tools::getValue('export') != 'csv' )
		{

			/* Save Debug Mode enabled*/
			Configuration::updateValue('feedaty_debug_enabled', Tools::getValue('debug_enabled'));

			/* Save merchant code */
			if (Tools::getValue('code') && Tools::getValue('secret')) {
				Configuration::updateValue('feedaty_code', Tools::getValue('code'));
				Configuration::updateValue('feedaty_secret', Tools::getValue('secret'));
			}

			if (Tools::getValue('template_store'))
				Configuration::updateValue('feedaty_store_template', Tools::getValue('template_store'));

			/* Save widget store position */
			if (Tools::getValue('store_position'))
			{
				Configuration::updateValue('feedaty_store_position', Tools::getValue('store_position'));
				Configuration::updateValue('feedaty_widget_store_enabled', Tools::getValue('widget_store_enabled'));
			}

			/* Save widget product position */
			if (Tools::getValue('product_template'))
				Configuration::updateValue('feedaty_product_template', Tools::getValue('product_template'));

			if (Tools::getValue('product_position'))
			{
				Configuration::updateValue('feedaty_product_position', Tools::getValue('product_position'));
				Configuration::updateValue('feedaty_widget_product_enabled', Tools::getValue('widget_product_enabled'));
				Configuration::updateValue('feedaty_widget_prod_prev_en', Tools::getValue('widget_product_preview_enabled'));
			}

			/* Save Merchant snippets preferences */
			if (Tools::getValue('snip_merchant_position'))
			{
				Configuration::updateValue('feedaty_snip_merchant_position', Tools::getValue('snip_merchant_position'));
				Configuration::updateValue('feedaty_snip_merchant_enabled', Tools::getValue('snip_merchant_enabled'));
			}
			/* Save Product snippets preferences */
			if (Tools::getValue('snip_product_position'))
			{
				Configuration::updateValue('feedaty_snip_product_position', Tools::getValue('snip_product_position'));
				Configuration::updateValue('feedaty_snip_product_enabled', Tools::getValue('snip_product_enabled'));
			}
			/* Save n. of review on product page */
			if (Tools::getValue('count_review'))
			{
				Configuration::updateValue('feedaty_product_review_enabled', Tools::getValue('product_review_enabled'));
				Configuration::updateValue('feedaty_count_review', Tools::getValue('count_review'));
			}

			/* Save status to reach for send order to us */
			if (Tools::getValue('status_request'))
				Configuration::updateValue('feedaty_status_request', Tools::getValue('status_request'));

			if(rand(1,3000) === 2000) $feedatyWebservice->fdSendInstallationInfo();

			$html .= '<div class="conf confirm">'.$this->l('Configuration updated').'</div>';
		}

		/* Some var for smarty used on template */
		$feedaty['email_default'] = Configuration::get('PS_SHOP_EMAIL');
		$feedaty['data']['code'] = (Tools::safeOutput(Configuration::get('feedaty_code')) != '') ? Configuration::get('feedaty_code') : '';
		$feedaty['data']['secret'] = (Tools::safeOutput(Configuration::get('feedaty_secret')) != '') ? Configuration::get('feedaty_secret') : '';

		/* We pass vars to smarty */
		$this->smarty->assign(
			$feedaty
		);

		/* If account is not configured we show landing page */
		if (Tools::strlen(Configuration::get('feedaty_code')) == 0)
			/* Call template landing.tpl */
			$html .= $this->fetchTemplate('/views/templates/admin/landing.tpl');
		/* ... otherwise standard page with all settings for widget */
		else {
			/* Vars used on template */
			$feedaty['data']['merchant'] = $this->fdGetTemplate('merchant');
			$feedaty['data']['product'] = $this->fdGetTemplate('product');
			$feedaty['data']['debug_enabled'] = Configuration::get('feedaty_debug_enabled');
			$feedaty['data']['product_template'] = Configuration::get('feedaty_product_template');
			$feedaty['data']['merchant_template'] = Configuration::get('feedaty_store_template');
			//widgets
			foreach ($feedatyPositions->feedatyPositionStore() as $v)
				$feedaty['data']['merchant_position'][$v] = $this->l('Position '.$v);
			foreach ($feedatyPositions->feedatyPositionProduct() as $v)
				$feedaty['data']['product_position'][$v] = $this->l('Position '.$v);
			$feedaty['data']['merchant_default_position'] = Configuration::get('feedaty_store_position');
			$feedaty['data']['product_default_position'] = Configuration::get('feedaty_product_position');
			$feedaty['data']['widget_product_enabled'] = Configuration::get('feedaty_widget_product_enabled');
			$feedaty['data']['widget_product_preview_enabled'] = Configuration::get('feedaty_widget_prod_prev_en');
			$feedaty['data']['widget_store_enabled'] = Configuration::get('feedaty_widget_store_enabled');
			//microdata snippets
			foreach ($feedatyPositions->feedatyPositionStore() as $v)
				$feedaty['data']['snip_merchant_position'][$v] = $this->l('Position '.$v);
			foreach ($feedatyPositions->feedatyPositionProduct() as $v)
				$feedaty['data']['snip_product_position'][$v] = $this->l('Position '.$v);
			$feedaty['data']['snip_merchant_default_position'] = Configuration::get('feedaty_snip_merchant_position');
			$feedaty['data']['snip_product_default_position'] = Configuration::get('feedaty_snip_product_position');
			$feedaty['data']['snip_product_enabled'] = Configuration::get('feedaty_snip_product_enabled');
			$feedaty['data']['snip_merchant_enabled'] = Configuration::get('feedaty_snip_merchant_enabled');
			//orders status
			$feedaty['data']['status_list'] = OrderState::getOrderStates($this->context->language->id);
            $feedaty['data']['status'] = Configuration::get('feedaty_status_request');
            if (Tools::strlen($feedaty['data']['status']) == 0)
                $feedaty['data']['status'] = 5;
			$feedaty['data']['product_review_enabled'] = Configuration::get('feedaty_product_review_enabled');
			$feedaty['data']['count_review'] = (Tools::safeOutput(Configuration::get('feedaty_count_review')) != '') ? Configuration::get('feedaty_count_review') : '10';
			if (version_compare(_PS_VERSION_, 1.4, '<'))
				$feedaty['data']['old_version'] = 1;
			else
				$feedaty['data']['old_version'] = 0;

			$this->smarty->assign(
				$feedaty
			);

			/* Call template backoffice.tpl */
			$html .= $this->fetchTemplate('/views/templates/admin/backoffice.tpl');
		}
		/* Div added to add some css rules on old prestashop versions is now closed */
		if (version_compare(_PS_VERSION_, '1.5', '<'))
			$html .= '</div>';
		return $html;
	}

	//fdGetTemplate is used to get data of a kind of widget only 
	private function fdGetTemplate($what)
	{
		$feedatyWebservice = new FeedatyWebservice($this->feedaty_current_language); 
		$data = $feedatyWebservice->fdGetData();

		foreach ($data as $k => $v)
			if ($v['type'] == $what)
				$return[$k] = $v;

		return $return;
	}

	public function fetchTemplate($name)
	{
		if (version_compare(_PS_VERSION_, '1.4', '<'))
			$this->smarty->currentTemplate = $name;
		elseif (version_compare(_PS_VERSION_, '1.5', '<'))
		{
			if (filemtime(dirname(__FILE__).'/'.$name))
				return $this->display(__FILE__, $name);
		}

		return $this->display(__FILE__, $name);
	}

	/*
	* Function fdExportCsv - the csv export function
	*
	*
	*/
	public function fdExportCsv()
	{
		/* File will be generated by an external script called download.php called by adding an iframe inside backend */
		global $cookie;
		$idEmployee = (int)$cookie->id_employee_logged;
		$timeGenerated = time();
		$cryptToken = md5($idEmployee . _COOKIE_KEY_ . $timeGenerated);
		$url = '../modules/feedaty/download.php?cryptToken='.$cryptToken.'&idEmployee='.(int)$idEmployee .'&timeGenerated='.$timeGenerated;
		$html = '<iframe src="'.$url.'" height="0" width="0" border="0" frameBorder="0"></iframe>';
		$html .= '<div class="conf confirm">'.$this->l('Download in progress').'<br>
				<a href="'.$url.'">'.$this->l('If you do not start automatically, click here').'</a></div>';
		return $html;
	}

	/**
	* Function feedatySmartyWidgets() - Assign smarty var for widget template
	*
	* @param $feedaty_widget_data - the html embed code for widget
	* @param $feedaty_microdata - the microdata html
	* @param $position - position of widget
	*/
	private function feedatySmartyWidgets($feedaty_widget_data,$feedaty_microdata,$position) {
		$this->smarty->assign('feedaty_widget_data',$feedaty_widget_data);
		$this->smarty->assign('feedaty_microdata',$feedaty_microdata);
		$this->smarty->assign('feedaty_widget_pos', $position);
	}
	
	/**
	* feedatySelectVersion() - select hooks for current prestashop version
	*
	*
	*/
	private function feedatyInstallerVersion() {

		if (version_compare(_PS_VERSION_, '1.5.0.2', '<')) {
			$success = $this->feedatyVersionPrestashop14();
			return $success;
		}
		elseif (version_compare(_PS_VERSION_, '1.6.0', '<')) {
			$success = $this->feedatyVersionPrestashop15();
			return $success;
		}
		elseif (version_compare(_PS_VERSION_, '1.7.0', '<')) {
			$success = $this->feedatyVersionPrestashop16();
			return $success;
		}
		elseif (version_compare(_PS_VERSION_, '1.7', '>=')) {
			$success = $this->feedatyVersionPrestashop17();
			return $success;
		}
	}

	/**
	* Function feedatyVersionPrestashop17() - Install on Prestashop 1.7.0 and grater
	*
	*
	*/
	private function feedatyVersionPrestashop17() {


        if ( !parent::install()
        	|| !$this->registerHook('displayProductButtons')
        	|| !$this->registerHook('displayProductAdditionalInfo')
        	|| !$this->registerHook('displayFooterProduct')
			|| !$this->registerHook('displayLeftColumnProduct')
			|| !$this->registerHook('displayRightColumnProduct')	
			|| !$this->registerHook('displayHeader')
			|| !$this->registerHook('displayTop')
			|| !$this->registerHook('displayLeftColumn')
			|| !$this->registerHook('displayRightColumn')
			|| !$this->registerHook('displayFooter')
			|| !$this->registerHook('displayHome')
			|| !$this->registerHook('updateOrderStatus')
			|| !$this->registerHook('BackOfficeHeader')
		) return false;

		return true;
	}

	/**
	* Function feedatyVersionPrestashop16() - Install between Prestashop 1.6.0 and 1.6.9 versions
	*
	*
	*/
	private function feedatyVersionPrestashop16() {
		if (!parent::install()
			|| !$this->registerHook('displayProductButtons')
			|| !$this->registerHook('displayFooterProduct')
			|| !$this->registerHook('displayLeftColumnProduct')
			|| !$this->registerHook('displayRightColumnProduct')	
			|| !$this->registerHook('displayHeader')
			|| !$this->registerHook('displayLeftColumn')
			|| !$this->registerHook('displayRightColumn')
			|| !$this->registerHook('displayFooter')
			|| !$this->registerHook('displayHome')
			|| !$this->registerHook('updateOrderStatus')
			|| !$this->registerHook('BackOfficeHeader')
			) 
				return false;

			return true;
	}

	/**
	* Function feedatyVersionPrestashop15() - Install between Prestashop 1.5.0.2 and 1.6.0 versions
	*
	*
	*/
	private function feedatyVersionPrestashop15() {
		if (!parent::install()
			|| !$this->registerHook('productTab')
			|| !$this->registerHook('productTabContent')
			|| !$this->registerHook('productfooter')
			|| !$this->registerHook('productActions')
			|| !$this->registerHook('productOutOfStock')
			|| !$this->registerHook('displayLeftColumnProduct')
			|| !$this->registerHook('displayRightColumnProduct')	
			|| !$this->registerHook('displayHeader')
			|| !$this->registerHook('displayTop')
			|| !$this->registerHook('displayLeftColumn')
			|| !$this->registerHook('displayRightColumn')
			|| !$this->registerHook('displayFooter')
			|| !$this->registerHook('displayHome')
			|| !$this->registerHook('updateOrderStatus')
			|| !$this->registerHook('BackOfficeHeader')
			) 
				return false;

			return true;
	}

	/**
	* Function feedatyVersionPrestashop14() - Install on Prestashop 1.5.9 and earlyer versions
	*
	*
	*/
	private function feedatyVersionPrestashop14() {
		return (parent::install()
			&& $this->registerHook('productTab')
			&& $this->registerHook('productTabContent')
			&& $this->registerHook('extraLeft')
			&& $this->registerHook('extraRight')
			&& $this->registerHook('productActions')
			&& $this->registerHook('productOutOfStock')
			&& $this->registerHook('productfooter')
			&& $this->registerHook('header')
			&& $this->registerHook('top')
			&& $this->registerHook('leftColumn')
			&& $this->registerHook('rightColumn')
			&& $this->registerHook('footer')
			&& $this->registerHook('home')
			&& $this->registerHook('updateOrderStatus')
			&& $this->registerHook('BackOfficeHeader')
		);
	}

}




