<?php
namespace Ecopure\Salessequence\Model\ResourceModel;

class Meta extends \Magento\SalesSequence\Model\ResourceModel\Meta
{
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        $entityType = $object->getEntityType(); //order/invoice/creditmemo/shipment
        $storeId = $object->getStoreId(); //CURRENT STORE ID

        $prefix = "ECO".date('y').date('m').date('d');
        $activeProfile = $this->resourceProfile->loadActiveProfile($object->getId());
        $activeProfile->setPrefix($prefix); //SET CUSTOM PREFIX - DEFAULT: store_id
        //$activeProfile->setSuffix(''); //SET CUSTOM SUFFIX
        $activeProfile->setStartValue('1'); //SET START VALUE - DEFAULT: 1
        $activeProfile->setStep('1'); //SET INCREMENT STEP - DEFAULT: 1

        //[UPDATE] USEFUL FOR SHARED ORDER NUMBERS WITHIN MULTIPLE STORES
        //$object->setSequenceTable('custom_table'); //SET CUSTOM INCREMENT TABLE - DEFAULT: sequence_{entity_type}_{store_id}

        //SET DATA TO active_profile
        $object->setData(
            'active_profile',
            $activeProfile
        );

        return $this;
    }
}