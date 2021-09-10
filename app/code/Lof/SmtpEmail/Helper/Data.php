<?php
/**
 * Landofcoder
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * http://landofcoder.com/license
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category   Landofcoder
 * @package    Lof_SmtpEmail
 * @copyright  Copyright (c) 2016 Landofcoder (http://www.landofcoder.com/)
 * @license    http://www.landofcoder.com/LICENSE-1.0.html
 */

namespace Lof\SmtpEmail\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
     /**
     * @var null $_storeId
     */
    protected $_storeId = null;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    /** @var \Magento\Store\Model\StoreManagerInterface */
    protected $_storeManager;
    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\ObjectManagerInterface
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->_objectManager = $objectManager;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }
        /**
     * @param int/null $storeId
     */
    public function setStoreId($storeId = null)
    {
        $this->_storeId = $storeId;
    }
     /**
     * Return brand config value by key and store
     *
     * @param string $key
     * @param \Magento\Store\Model\Store|int|string $store
     * @return string|null
     */
     public function getConfig($key, $store = null)
    {
        $store = $this->_storeManager->getStore($store);
        $websiteId = $store->getWebsiteId();
        $result = $this->scopeConfig->getValue(
            'lofsmtpemail/'.$key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store);
        return $result;
    }
    public function getIp() {
        return $this->_remoteAddress->getRemoteAddress();
    }
    /**
     * Get system config password
     * 
     * @param \Magento\Store\Model\ScopeInterface::SCOPE_STORE $store
     */
    public function getConfigPassword($store_id = null){
        return $this->scopeConfig->getValue('lofsmtpemail/smtp_config/password', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store_id);
    }
    
    /**
     * Get system config username
     * 
     * @param \Magento\Store\Model\ScopeInterface::SCOPE_STORE $store
     */
    public function getConfigUsername($store_id = null){
        return $this->scopeConfig->getValue('lofsmtpemail/smtp_config/username', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store_id);
    }    
    
    /**
     * Get system config password
     * 
     * @param \Magento\Store\Model\ScopeInterface::SCOPE_STORE $store
     */
    public function getConfigAuth($store_id = null){
        return $this->scopeConfig->getValue('lofsmtpemail/smtp_config/auth', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store_id);
    }
    /**
     * Get system config set return path
     *
     * @param \Magento\Store\Model\ScopeInterface::SCOPE_STORE $store
     * @return int
     */
    public function getConfigSetReturnPath($store_id = null)
    {
         return $this->scopeConfig->getValue('lofsmtpemail/smtp_config/set_return_path', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store_id);
    }
    /**
     * Get system config return path email
     *
     * @param \Magento\Store\Model\ScopeInterface::SCOPE_STORE $store
     * @return string
     */
    public function getConfigReturnPathEmail($store_id = null)
    {
       return $this->scopeConfig->getValue('lofsmtpemail/smtp_config/return_path_email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store_id);
    }
     /**
     * Get system config reply to
     *
     * @param \Magento\Store\Model\ScopeInterface::SCOPE_STORE $store
     * @return bool
     */
    public function getConfigSetReplyTo($store_id = null)
    {
        return $this->scopeConfig->getValue('lofsmtpemail/smtp_config/set_reply_to', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store_id);
    }
    /**
     * Get system config ssl
     * 
     * @param \Magento\Store\Model\ScopeInterface::SCOPE_STORE $store
     */
    public function getConfigSsl($store_id = null){
        return $this->scopeConfig->getValue('lofsmtpemail/smtp_config/ssl', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store_id);
    }
     /**
     * Get system config from
     *
     * @param \Magento\Store\Model\ScopeInterface::SCOPE_STORE $store
     * @return string
     */
    public function getConfigSetFrom($store_id = null)
    {
        return $this->scopeConfig->getValue('lofsmtpemail/smtp_config/set_from', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store_id);
    }
    /**
     * Get system config password
     * 
     * @param \Magento\Store\Model\ScopeInterface::SCOPE_STORE $store
     */
    public function getConfigSmtpHost($store_id = null){
        return $this->scopeConfig->getValue('lofsmtpemail/smtp_config/smtphost', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store_id);
    }
    
    /**
     * Get system config username
     * 
     * @param \Magento\Store\Model\ScopeInterface::SCOPE_STORE $store
     */
    public function getConfigPort($store_id = null){
        return $this->scopeConfig->getValue('lofsmtpemail/smtp_config/port', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store_id);
    }
    
}