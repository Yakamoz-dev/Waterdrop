<?php namespace Ecopure\CustomerAddresses\Plugin\StateFilter;

class StateFilter

{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    protected $allowedUsStates;
    /**
     * StateFilter constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function afterToOptionArray($subject, $options)
    {
        $allowedStates = $this->scopeConfig->getValue('checkout/state_filter/us_state_filter', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->allowedUsStates = explode(",", $allowedStates);
        $result = array_filter($options, function ($option) {
            if (isset($option['label'])) {
                return in_array($option['label'], $this->allowedUsStates);
            }
            return true;
        });
        return $result;
    }
}
