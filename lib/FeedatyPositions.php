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
class FeedatyPositions extends Feedaty {

	/**
	* @var $fd_position_store
	*/
	protected $fd_position_store;

	/**
	* @var $fd_position_product
	*/
	protected $fd_pos_product;


	public function __construct() {

	}

	/**
	* Function feedatyPositionStore() - Return array of positions for store page
	*
	* @return array fd_position_store
	*/
	public function feedatyPositionStore() {

		/*if(version_compare(_PS_VERSION_, '1.7.1','>=')) {
		
			$this->fd_position_store = array(
				'displayNav1',
				'displayNav2',
				'displayTop',
				'displayHome',
				'displayRightColumn',
				'displayLeftColumn',
				'displayFooter'
			);
		}*/

		if(version_compare(_PS_VERSION_, '1.5.0.3','>=')) {
		
			$this->fd_position_store = array(
				'displayTop',
				'displayHome',
				'displayRightColumn',
				'displayLeftColumn',
				'displayFooter'
			);
		}

		else {
			$this->fd_position_store = array(
				'top',
				'leftColumn',
				'rightColumn',
				'footer',
				'home'
			);
		}

		return $this->fd_position_store;
	}



	/**
	* Function feedatyPositionProduct() - return array of positions for product pages
	*
	* @return array fd_pos_product
	*/
	public function feedatyPositionProduct() {

		if(version_compare(_PS_VERSION_, '1.7.1','>=')) {
			$this->fd_pos_product = array(
				'displayLeftColumnProduct',
				'displayRightColumnProduct',
				'displayProductAdditionalInfo'
			);
		}

		elseif(version_compare(_PS_VERSION_, '1.6','>=')) {

			$this->fd_pos_product = array(
				'displayLeftColumnProduct',
				'displayRightColumnProduct',
				'displayProductButtons'
			);
		}

		elseif(version_compare(_PS_VERSION_, '1.5.0.3','>=')) {

			$this->fd_pos_product = array(
				'displayLeftColumnProduct',
				'displayRightColumnProduct',
				'productActions',
				'productOutOfStock',
				'productfooter'
			);
		}

		else {
			$this->fd_pos_product = array(
				'extraLeft',
				'extraRight',
				'productActions',
				'productOutOfStock',
				'productfooter'
			);
		}

		return $this->fd_pos_product;
	}

}