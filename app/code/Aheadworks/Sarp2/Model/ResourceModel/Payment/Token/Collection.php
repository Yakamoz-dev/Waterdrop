<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Sarp2
 * @version    2.15.3
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2\Model\ResourceModel\Payment\Token;

use Aheadworks\Sarp2\Model\Payment\Token;
use Aheadworks\Sarp2\Model\ResourceModel\Payment\Token as TokenResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Aheadworks\Sarp2\Model\ResourceModel\Payment\Token
 */
class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    protected $_idFieldName = 'token_id';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(Token::class, TokenResource::class);
    }

    /**
     * @inheritDoc
     */
    protected function _afterLoad()
    {
        foreach ($this as $item) {
            $this->getResource()->unserializeFields($item);
        }
        return parent::_afterLoad();
    }
}
