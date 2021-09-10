<?php
/**
 * Landofcoder
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * http://www.landofcoder.com/license-agreement.html
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

namespace Lof\SmtpEmail\Model;

use Magento\Cms\Api\Data\PageInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Emaillog extends \Magento\Framework\Model\AbstractModel
{
    const STATUS_PENDING = 'Pending';

    const STATUS_SENT = 'Sent';

    const STATUS_FAILED = 'Failed';

    const STATUS_BLACKLIST = 'Backlist';

    const STATUS_BLOCKIP = 'Blockip';

    const STATUS_SPAM = 'Spam';

    protected $blacklist;

    protected $_logger;

    protected $helper;

    protected $blockip;

    protected $spam;

     protected $messageManager;
    /**
     * Initialize resource model
     *
     * @return void
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Lof\SmtpEmail\Logger\Logger $logger,
        \Lof\SmtpEmail\Model\Blacklist $blacklist,
        \Lof\SmtpEmail\Model\Blockip $blockip,
        \Lof\SmtpEmail\Helper\Data $helper,
        \Lof\SmtpEmail\Model\Spam $spam,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        ObjectManagerInterface $objectManager,
        DateTime $coreDate
    ) {
        $this->spam = $spam;
        $this->blockip = $blockip;
        $this->_logger = $logger;
        $this->objectManager = $objectManager;
        $this->coreDate = $coreDate;
        $this->blacklist = $blacklist;
        $this->helper = $helper;
        $this->messageManager = $messageManager;
        parent::__construct($context,$registry);
    }
    protected function _construct()
    {
        
        $this->_init('Lof\SmtpEmail\Model\ResourceModel\Emaillog');
    }

    public static function getStatusEmail()
    {
        return [
            self::STATUS_PENDING      => __('Pending'),
            self::STATUS_SENT       => __('Sent'),
            self::STATUS_FAILED        => __('Failed'),
            self::STATUS_BLACKLIST        => __('Blacklist'),
            self::STATUS_BLOCKIP      => __('Block'),
            self::STATUS_SPAM        => __('Spam')
        ];
    }

    public function checkSpam($message)
    {

        $recipient = $message->getTo()->current()->getEmail();
        
        foreach ($this->spam->getCollection()->addFieldToFilter('is_active',1) as $key => $spam) {

            switch ($spam->getData('scope')) {
                case 'email':
                    $subject = $recipient;
                    break;
                case 'subject':
                        $subject = $message->getSubject();
                    break;
                case 'body':
                        $subject = $message->getBody();
                    break;
            }

            $matches = [];
            $subject = strip_tags($subject);
            try {
                if(strripos($spam->getPattern(),'/') > 0) {
                    preg_match($spam->getPattern(), $subject, $matches);
                } else {
                    preg_match('/'.$spam->getPattern().'/', $subject, $matches);
                    
                }
                 if (count($matches) > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                 $this->messageManager->addError('Warning: preg_match():Delimiter must not be alphanumeric or backslash. Please contact with admin check pattern text spam');
            }
            
            return true;
        }
    }
    public function messageLog($message,$sender_email) {
       $recipient = $message->getTo()->current()->getEmail();
       $body = (quoted_printable_decode($message->getBodyText()));
        $this->setData([
            'created_at'        => date('Y-m-d H:i:s'),
            'subject'           => $message->getSubject(),
            'body'              => $body,
            'recipient_email'   => $recipient,
            'status'            => self::STATUS_PENDING,
            'sender_email'      => $sender_email
        ]);
        $this->save();
        return $this->getEmaillogId();
    }
   
    public function updateStatus($emaillogId, $status)
    {
        $this->load($emaillogId);
        if ($this->getId()) {
            $this->setStatus($status)->save();
        }
    }
    
    public function isBlacklist($message) {
       $recipient = $message->getTo()->current()->getEmail();
        
        foreach ($this->blacklist->getCollection() as $key => $_blacklist) {
            if($_blacklist->getEmail() == $recipient) {
                return  true;
            }
        }
    }

    public function isBlockip() {
         foreach ($this->blockip->getCollection() as $key => $_blockip) {
            if($_blockip->getIp() == $this->helper->getIp()) {
                return  true;
            }
        }
    }

    public function clearLog() {
        $keep_email = $this->helper->getConfig('clear/log');
        $time = time() - $keep_email*24*60*60;
        $time = date('Y-m-d H:i:s',$time);
        if($keep_email > 0) {
            $collection=$this->getCollection()->addFieldToFilter('created_at',['lt' => $time]);
            foreach ($collection as $key => $_collection) {
                $_collection->delete();         
            }
        }
    }
}