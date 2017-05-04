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

// WebService Class
if (!in_array('FeedatyWebservice', get_declared_classes()))
	require_once(dirname(__FILE__).'/FeedatyWebservice.php');

// Return Product Page Positions
if (!in_array('FeedatyPositions', get_declared_classes()))
	require_once(dirname(__FILE__).'/FeedatyPositions.php');

// GenerateElements Class
if (!in_array('FeedatyGenerateElements', get_declared_classes()))
	require_once(dirname(__FILE__).'/FeedatyGenerateElements.php');
