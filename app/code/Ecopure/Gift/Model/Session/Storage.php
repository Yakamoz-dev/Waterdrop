<?php
namespace Ecopure\Gift\Model\Session;

class Storage extends \Magento\Framework\Session\Storage
{
    /**
     * @param string $namespace
     * @param array $data
     */
    public function __construct(
        $namespace = 'gift',
        array $data = []
    ) {
        parent::__construct($namespace, $data);
    }
}