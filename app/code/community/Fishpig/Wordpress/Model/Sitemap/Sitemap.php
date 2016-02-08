<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

/**
 * Magento, if you're listening, you should have already added
 * the 2 variables below to this model (and every model!!)
 */

class Fishpig_Wordpress_Model_Sitemap_Sitemap extends Creare_CreareSeoCore_Model_Sitemap_Sitemap
{
	/**
	 * Event data
	 *
	 * @var string
	*/
	protected $_eventPrefix = 'sitemap_sitemap';
	protected $_eventObject = 'sitemap';
}
