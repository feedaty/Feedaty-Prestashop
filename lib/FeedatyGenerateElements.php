<?php
/*
* 2007-2017 PrestaShop
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
*  @copyright  2017 Feedaty
*  @version  Release: 2.1.0 $
*/

if (!defined('_PS_VERSION_'))
	exit;

class FeedatyGenerateElements extends Feedaty {


	/**
	*
	* @var $feedaty_current_language
	*/
	protected $feedaty_current_language;


	/**
	* Function __construct
	*
	* @param $feedaty_language
	*/
	public function __construct($feedaty_lang) {
		$feedaty_current_language = $feedaty_lang;
	}


	/**
	* Function fdGenerateStoreWidget()
	* 
	* @param $position
	*
	*/
	public function fdGenerateStoreWidget($position)
	{
		/* Creation of store widget only if current position is what set from settings */
		if ($position == Configuration::get('feedaty_store_position'))
		{
			$feedatyWebservice = new FeedatyWebservice($this->feedaty_current_language);
			/* Expire old stuff */
			$feedatyWebservice->fdRemoveExpiredCache();
			/* If plugin is enabled */
			$plugin_enabled = Configuration::get('feedaty_widget_store_enabled');

			if ($plugin_enabled != 0)
			{
				/* Get widget data */
				
				$data = $feedatyWebservice->fdGetData();		

				return $data[Configuration::get('feedaty_store_template')]['html_embed'];
			}
		}
	}


	/**
	* Function fdGenerateProductWidget()
	*
	* @param $position
	*
	*/
	public function fdGenerateProductWidget($position)
	{
		$feedatyWebservice = new FeedatyWebservice($this->feedaty_current_language);
		/* Creation of product widget only if current position is what set from settings */
		if ($position == Configuration::get('feedaty_product_position'))
		{
			/* Expire old stuff */
			$feedatyWebservice->fdRemoveExpiredCache();
			/* If plugin is enabled */
			$plugin_enabled = Configuration::get('feedaty_widget_product_enabled');

			if ($plugin_enabled != 0)
			{
				/* Get product id */
				$product = Tools::getValue('id_product');
				/* Get widget data */
				$data = $feedatyWebservice->fdGetData();

				// Return html embeded replacing product id 
				return  str_replace('__insert_ID__', $product, $data[Configuration::get('feedaty_product_template')]['html_embed']);
			}
		}
	}


	/**
	* Function fdGenerateMerchantSnippet()
	*
	* @param $position
	*
	*/
	public function fdGenerateMerchantSnippet($position) {
		$feedatyWebservice = new FeedatyWebservice($this->feedaty_current_language);
		
		if ($position == Configuration::get('feedaty_snip_merchant_position'))
		{
			/* Expire old stuff */
			$feedatyWebservice->fdRemoveExpiredCache();

			$plugin_enabled = Configuration::get('feedaty_snip_merchant_enabled');

			if ($plugin_enabled != 0)
			{
				$data = $feedatyWebservice->getMerchantRichSnippet();

				return $data;
			}
		}
	}


	/**
	* Function fdGenerateProductSnippet()
	* @param $position
	*
	* @return @data
	*/ 
	public function fdGenerateProductSnippet($position) {
		$feedatyWebservice = new FeedatyWebservice($this->feedaty_current_language);
		if ($position == Configuration::get('feedaty_snip_product_position'))
		{
			/* Expire old stuff */
			$feedatyWebservice->fdRemoveExpiredCache();

			$plugin_enabled = Configuration::get('feedaty_snip_product_enabled');

			if ($plugin_enabled != 0)
			{
				$product = Tools::getValue('id_product');
				$data = $feedatyWebservice->getProductRichSnippet($product);

				return $data;
			}
		}
	}


	/**
	* Function fdGenerateStars()
	* @param $data
	*a
	* @return @data
	*/ 
	public function fdGenerateStars($data)
	{
		if (!isset($data)) $data = 0;
		return '<img src="//'.'www.feedaty.com/rating/rate-small-'.(int)$data.'.png" height="15">';
	}

}