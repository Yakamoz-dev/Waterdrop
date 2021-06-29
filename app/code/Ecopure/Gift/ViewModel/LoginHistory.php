<?php

namespace Ecopure\Gift\ViewModel;

use Magento\Framework\App\Http\Context;
use Ecopure\Gift\Model\Session;

class LoginHistory implements \Magento\Framework\View\Element\Block\ArgumentInterface
{

    protected $_httpContext;
    protected $_sessionManager;
    protected $_objectManager;

    public function __construct(Context $httpContext, Session $session, \Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->_httpContext = $httpContext;
        $this->_sessionManager = $session;
        $this->_objectManager = $objectManager;
    }

    public function getCustomer() {
        return $this->_sessionManager->getRoCustomer();
    }

    public function getProductName() {
        return $this->_sessionManager->getRoProduct();
    }

    public function getCheck() {
        return $this->_sessionManager->getRoCheck();
    }

    public function getGift() {
        $customer = $this->_sessionManager->getRoCustomer();
        $gift = [];
        if ($customer || $customer['id']) {
            $gift = $this->_objectManager->create('Ecopure\Gift\Model\ResourceModel\Gift\Collection')->addFieldToFilter('ro_model_no',$customer['model_no']);
            $gift->getData();
        }
        return $gift;
    }

    public function getAsin () {
        $customer = $this->_sessionManager->getRoCustomer();
        $product = $this->_objectManager->create('Ecopure\Gift\Model\Product');
        $product->load($customer['ro_id'],'ro_id');
        return $product->getRoAsin();
    }

    public function formatoption($options) {
        $return = array();

        $options = json_decode($options, true);

        foreach ($options as $o) {
            $o['brand'] = trim($o['brand']);
            if (!$o['brand']) {
                continue;
            }

            $return[$o['brand']][$o['model']] = $o;
            asort( $return[$o['brand']] );
        }
        $return2 = array();
        $i = 0;
        ksort($return  , SORT_STRING);

        foreach ($return as $k => $v) {
            foreach ($v as $vv) {
                $return2[$i]['v'] = $vv['brand'];
                $return2[$i]['n'] = $vv['brand'];
                $return2[$i]['s'][] = array(
                    'v' => $vv['model'],
                    'n' => $vv['model']
                );
            }
            $i ++;
        }
        return json_encode($return2);
    }
}