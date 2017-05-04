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
/**
*
*
*
*/

class FeedatyWebservice extends Feedaty {

	/**
	*
	* @var 
	*/
	protected $feedaty_current_language;


	/**
	*
	*
	*/
	public function __construct($feedaty_lang) {
		$feedaty_current_language = $feedaty_lang;
	}


	/**
    * Function getReqToken - get the request token
    *  
    * @return $response
    *
    */
    private function getReqToken(){
        
        $header = array( 'Content-Type: application/x-www-form-urlencoded');
        $url = "http://api.feedaty.com/OAuth/RequestToken";

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);

        $response = json_decode(curl_exec($ch));

        curl_close($ch);

        return $response;
    }


    /**
    * Function serializeData - serialize data to send 
    * 
    * @param $fields
    *
    * @return $dati
    */
    private function serializeData($fields){
        $data = '';
        foreach($fields as $k => $v){
            $data .= $k . '=' . urlencode($v) . '&';
        }
        rtrim($data, '&');
        return $data;
    }


    /**
    * Function getAccessToken - get the access token
    *
    * @param $token
    *
    * @return $response - the access token
    */
    private function getAccessToken($token){

    	$merchant = Configuration::get('feedaty_code');
    	$secret = Configuration::get('feedaty_secret');

        $encripted_code = $this->encryptToken($token,$merchant,$secret);

        $fields = array( 'oauth_token' => $token->RequestToken,'grant_type'=>'authorization' );

        $header = array( 'Content-Type: application/x-www-form-urlencoded','Authorization: Basic '.$encripted_code,'User-Agent: Prestahop' );
        $dati = $this->serializeData($fields);
        $url = "http://api.feedaty.com/OAuth/AccessToken";

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dati);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);

        $response = json_decode(curl_exec($ch));

        curl_close($ch);

        return $response;
    }


    /**
    * Function encryptToken
    *
    * @param $token
    * @param $merchant
    * @param $secret
    *
    * @return $base64_sha_token - the encrypted token
    */
    private function encryptToken($token,$merchant,$secret){

        $sha_token = sha1($token->RequestToken.$secret);
        $base64_sha_token = base64_encode($merchant.":".$sha_token);
        return $base64_sha_token;   
    }


    /**
    * Function getProductRichSnippet 
    *
    * @param $product_id
    *
    * @return $content - the html product's rich snippet
    *
    */
    public function getProductRichSnippet($product_id){


		$content = json_decode($this->fdGetCache('FeedatyProdSnip'.Configuration::get('feedaty_code').$product_id.$this->feedaty_current_language));

		if (!$content)
		{
        	$merchant = Configuration::get('feedaty_code');
        	$path = 'http://white.zoorate.com/gen';
        	$dati = array( 'w' => 'wp','MerchantCode' => $merchant,'t' => 'microdata', 'version' => 2, 'sku' => $product_id );
        	$header = array( 'Content-Type: text/html','User-Agent: Prestashop' );
        	$dati = $this->serializeData($dati);
        	$path.='?'.$dati;
        	$ch = curl_init($path);
        	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, '250');
			curl_setopt($ch, CURLOPT_TIMEOUT_MS, '250');
        	$content = curl_exec($ch);
        	$http_resp = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        	//check http response code and content
        	if (Tools::strlen($content) > 50 && $http_resp == "200") {
        		// 4 hours of cache
				$this->fdSetCache('FeedatyProdSnip'.Configuration::get('feedaty_code').$product_id.$this->feedaty_current_language, json_encode($content), time() + (3 * 60 * 60)); 
        	}
        	else {
        		$content = "";
        		$logErrorEnabled = Configuration::get('feedaty_debug_enabled');
			 	if($logErrorEnabled != 0)
        		PrestaShopLogger::addLog("Feedaty Microdata response with ".$http_resp." error code", $severity = 3);
        	}
       		curl_close($ch);

       	}

        return $content;
    }
    

    /**
    * Function getMerchantRichSnippet
    *
    * @return $content- the html merchant's rich snippet
    *
    */
    public function getMerchantRichSnippet(){

    	$content = json_decode($this->fdGetCache('FeedatyStoreSnip'.Configuration::get('feedaty_code').$this->feedaty_current_language));

    	if (!$content)
		{
        	$merchant = Configuration::get('feedaty_code');
        	$path = 'http://white.zoorate.com/gen';
        	$dati = array(
                'w' => 'wp',
                'MerchantCode' => $merchant,
                't' => 'microdata',
                'version' => 2,
        	);
        	$header = array('Content-Type: text/html',
                'User-Agent: Prestashop'
        	);
        	$dati = $this->serializeData($dati);
        	$path.='?'.$dati;
        	$path = str_replace("=2&", "=2", $path);
        	$ch = curl_init($path);
        	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
       		curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
       		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, '250');
			curl_setopt($ch, CURLOPT_TIMEOUT_MS, '250');
        	$content = curl_exec($ch);
        	$http_resp = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        	if (Tools::strlen($content) > 50 &&  $http_resp == "200") { 
        		// 4 hours of cache
				$this->fdSetCache('FeedatyStoreSnip'.Configuration::get('feedaty_code').$this->feedaty_current_language, json_encode($content), time() + (4 * 60 * 60));
			} 
        	else {
        		$content = "";
        		$logErrorEnabled = Configuration::get('feedaty_debug_enabled');
			 	if($logErrorEnabled != 0)
        		PrestaShopLogger::addLog("Feedaty Microdata response with ".$http_resp." error code", $severity = 3);
        	}

        	curl_close($ch);
        }

        return $content;
    }


    /**
    * Function fdSendInstallationInfo()
    * Send some informations about plugin configuration for debugging potential errors and statistics
    *
    *
    */
	public function fdSendInstallationInfo()
	{
		/* Platform (obviously PrestaShop) and version */
		$fdata['keyValuePairs'][] = array('Key' => 'Platform', 'Value' => 'PrestaShop '._PS_VERSION_);
		/* Plugin version */
		$fdata['keyValuePairs'][] = array('Key' => 'Version', 'Value' => $this->version);
		/* Base store url */
		$fdata['keyValuePairs'][] = array('Key' => 'Url', 'Value' => _PS_BASE_URL_.__PS_BASE_URI__);
		/* Server os */
		$fdata['keyValuePairs'][] = array('Key' => 'Os', 'Value' => PHP_OS);
		/* Php version */
		$fdata['keyValuePairs'][] = array('Key' => 'Php Version', 'Value' => phpversion());
		/* Store name */
		$fdata['keyValuePairs'][] = array('Key' => 'Name', 'Value' => Configuration::get('PS_SHOP_NAME'));
		/* Widget configuration and positions */
		$fdata['keyValuePairs'][] = array('Key' => 'Widget_Store', 'Value' => (string)Configuration::get('feedaty_widget_store_enabled'));
		$fdata['keyValuePairs'][] = array('Key' => 'Widget_Product', 'Value' => (string)Configuration::get('feedaty_widget_product_enabled'));
		$fdata['keyValuePairs'][] = array('Key' => 'Widget_Store_Position', 'Value' => (string)Configuration::get('feedaty_store_position'));
		$fdata['keyValuePairs'][] = array('Key' => 'Widget_Product_Position', 'Value' => (string)Configuration::get('feedaty_product_position'));
		$fdata['keyValuePairs'][] = array('Key' => 'Status', 'Value' => (string)Configuration::get('feedaty_status_request'));
		/* Current server date */
		$fdata['keyValuePairs'][] = array('Key' => 'Date', 'Value' => date('c'));
		/* Feedaty Merchant code */
		$fdata['merchantCode'] = Configuration::get('feedaty_code');

		/* All is sent by curl */
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://'.'www.zoorate.com/ws/feedatyapi.svc/SetKeyValue');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, '60');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,  Tools::jsonEncode($fdata));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Expect:'));
		curl_exec($ch);
		curl_close($ch);
		/* We don't care about response */
	}


	/**
	* Function fdGetData() 
	* fdGetData is used to retrive widget informations (as html, name, preview) from feedaty api service 
	*
	*
	*/
	public function fdGetData()
	{
		/* If is cached we don't ask for that again */
		$content = $this->fdGetCache('FeedatyData'.Configuration::get('feedaty_code').$this->feedaty_current_language);

		if (!$content)
		{
			/* Otherwise we ask for it by a simply and quick curl request */
			$ch = curl_init();

			if (Tools::strlen($this->feedaty_current_language) > 0) $lang_url_part = '&lang='.$this->feedaty_current_language;
			curl_setopt($ch, CURLOPT_URL,
				'http://'.'widget.zoorate.com/go.php?function=feed_be&action=widget_list&merchant_code='.Configuration::get('feedaty_code').$this->feedaty_current_language);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, '60');

			$content = trim(curl_exec($ch));
			curl_close($ch);

			/* If everything is gone fine we can save it on cache */
			if (Tools::strlen($content) > 50)
				$this->fdSetCache('FeedatyData'.Configuration::get('feedaty_code').$this->feedaty_current_language,
					$content, time() + (24 * 60 * 60)); // 24 hours of cache
		}
		/* No matter if it's retrieved by cache or by curl, we need to decode json */
		$data = Tools::jsonDecode($content, true);
		return $data;
	}


	/**
	*
	*
	*
	*/
	public function fdSendOrder($var1) {
		if ($var1['newOrderStatus']->id == Configuration::get('feedaty_status_request'))
		{

			/* Load the order */
			$order = new Order($var1['id_order']);
			//var_dump($order);exit;
			/* Gets all products on order */
			$products = $order->getProducts();

			/* For each product we get picture, id, name, url */
			foreach ($products as $product)
			{
				$id_image = Product::getCover($product['product_id']);
				if (count($id_image) > 0)
				{
					$image = new Image($id_image['id_image']);
					$tmp['ThumbnailURL'] = _PS_BASE_URL_._THEME_PROD_DIR_.$image->getExistingImgPath().'.jpg';
				}
				$tmp['SKU'] = $product['product_id'];
				$tmp['Name'] = $product['product_name'];
				$tmp['Brand'] = '';
				$link = new Link();
				$tmp['URL'] = $link->getProductLink((int)$product['product_id']);

				$final_products[] = $tmp;

			}

			/* Gets information about customer who made the order */
			$customer = new Customer((int)$order->id_customer);	

			$address = new Address($order->id_address_delivery);
			$state = new Country($address->id_country);

			/* Retrive also order date, customer email, id order, prestashop version and plugin version */
			$tmp_order['ID'] = $var1['id_order'];
			$tmp_order['Date'] = $order->date_add;
			$tmp_order['CustomerEmail'] = $customer->email;
			$tmp_order['CustomerID'] = $customer->email;
			$tmp_order['Platform'] = 'PrestaShop '._PS_VERSION_.' / '.$this->version;
			if($state->iso_code != 'IT' && $state->iso_code != 'FR' && $state->iso_code != 'DE' && $state->iso_code != 'ES')
			{
				$tmp_order['Culture'] = 'en';
			}
			else
			{
				$tmp_order['Culture'] = strtolower($state->iso_code);
			}
			$tmp_order['Products'] = $final_products;

			$js_data['orders'][] = $tmp_order;

			$posted_data = json_encode($js_data);

			$merchant = Configuration::get('feedaty_code');
			$secret = Configuration::get('feedaty_secret');
			//CURL SESSION
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://'.'api.feedaty.com/Orders/Insert');

			$token = $this->getReqToken();
			$accessToken = $this->getAccessToken($token, $merchant, $secret);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, '60');
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,  Tools::jsonEncode($js_data));
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json', 'Authorization: Oauth '.$accessToken->AccessToken));
			curl_setopt($ch, CURLINFO_HEADER_OUT, true);
			$fd_resp = curl_exec($ch);
			curl_close($ch);

			 $fd_api_resp = json_encode($fd_resp);

			 $logErrorEnabled = Configuration::get('feedaty_debug_enabled');
			 if($logErrorEnabled != 0) {
			 	PrestaShopLogger::addLog("Posted data : ".$posted_data, $severity = 2);
			 	PrestaShopLogger::addLog("Feedaty Response : ".$fd_api_resp, $severity = 2);
			 }
		}
	}


	/* 
	* Function fdGetProductData() - is used to retrive a single product reviews from feedaty api service
	*
	*
	*/
	public function fdGetProductData($id)
	{
		/* If is cached we don't ask for that again */
		$content = $this->fdGetCache('feedaty_product_data_'.$id);

		if (!$content || Tools::strlen($content) < 20)
		{
			/* Otherwise we ask for it by a simply and quick curl request */
			$ch = curl_init();

			if (Tools::strlen($this->feedaty_current_language) > 0)
				$lang_url_part = '&lang='.$this->feedaty_current_language;

			curl_setopt($ch, CURLOPT_URL,
				'http://'.'widget.zoorate.com/go.php?function=feed&action=ws&task=product&merchant_code='.Configuration::get('feedaty_code').'&ProductID='.$id);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, '3');
			$content = trim(curl_exec($ch));
			curl_close($ch);

			/* If everything is gone fine we can save it on cache */
			if (Tools::strlen($content) > 0)
				$this->fdSetCache('feedaty_product_data_'.$id, $content, time() + (4 * 60 * 60));
		}

		/* No matter if it's retrieved by cache or by curl, we need to decode json */
		$data = Tools::jsonDecode($content, true);

		return $data;
	}


	/* 
	* Function fdRemoveExpiredCache delete expired cache 
	*
	*
	*/
	public function fdRemoveExpiredCache()
	{
		$q = 'DELETE FROM `'._DB_PREFIX_.'feedaty_cache` WHERE expiration < '.time();
		Db::getInstance()->execute($q);
	}


	/* 
	* Function _delete_feedaty_cache reset all cache 
	*
	*
	*/
	public function _delete_feedaty_cache()
	{
		$q = 'DELETE FROM `'._DB_PREFIX_.'feedaty_cache`';
		Db::getInstance()->execute($q);
	}


	/* 
	* Function fdGetCache gets cache if available or false if it isn't 
	*
	*
	*/
	public function fdGetCache($id)
	{
		$q = 'SELECT * FROM `'._DB_PREFIX_.'feedaty_cache` WHERE id_key = "'.pSQL((string)$id,true).'"';
		$cache = Db::getInstance()->getRow($q, false);

		if (isset($cache['value']) && Tools::strlen($cache['value']) > 0)
			return $cache['value'];
		else
			return false;
	}


	/* 
	* Function fdSetCache is used for save to cache a value 
	*
	*
	*/
	public function fdSetCache($id, $value, $expiration = null)
	{
		/* If expiration it's null se set a default 7 days */
		if (is_null($expiration))
			$expiration = (7 * 24 * 60 * 60) + time();

		/* First of all we remove expired cache */
		$this->fdRemoveExpiredCache();

		/* Check if there is already a value saved */
		$q = 'SELECT COUNT(*) FROM `'._DB_PREFIX_.'feedaty_cache` WHERE id_key = "'.pSQL((string)$id).'"';
		$count = Db::getInstance()->getValue($q, false);

		/* If isn't we add a new row */
		if ($count == 0)
			$q = 'INSERT INTO `'._DB_PREFIX_.'feedaty_cache` (`id_key`, `value`, `expiration`) values (\''.(string)$id.'\',\''.pSQL((string)$value,true).'\',\''.pSQL((int)$expiration).'\') ';
		/* If there is already a value we update its content */
		else
			$q = 'UPDATE `'._DB_PREFIX_.'feedaty_cache` SET `value` = \''.pSQL((string)$value,true).'\', `expiration` = \''.pSQL((int)$expiration).'\' WHERE `id_key` = \''.pSQL((string)$id).'\'';
		Db::getInstance()->Execute($q);
		return true;
	}


}