<?php
/**
* Copyright © 2018 Codazon. All rights reserved.
* See COPYING.txt for license details.
*/

namespace Codazon\ThemeOptions\Controller\Ajax;

class Slideshow extends \Magento\Framework\App\Action\Action
{
    protected $block;
    
    protected $resultLayoutFactory;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Codazon\Slideshow\Block\Widget\Slideshow $block,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->block = $block;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
        
    }
    
    public function execute()
    {
        $request = $this->getRequest();
        $resultJson = $this->resultJsonFactory->create();
        $result = [];
        $result['success'] = false;
        $result['html'] = null;
        //if ($request->getPost('post_template')) {
            $params = $request->getParams();
            $params['full_html'] = 1;
            $this->block->setData($params);
            $result['success'] = true;
            $result['html'] = $this->block->toHtml();
        //}
        return $resultJson->setJsonData(json_encode($result));
    }
}