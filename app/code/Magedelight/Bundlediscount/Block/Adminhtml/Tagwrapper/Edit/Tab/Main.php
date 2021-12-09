<?php
namespace Magedelight\Bundlediscount\Block\Adminhtml\Tagwrapper\Edit\Tab;

class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Main constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magedelight\Bundlediscount\Helper\Option $optionData
     * @param \Magedelight\Bundlediscount\Model\TagwrapperFactory $tagwrapperFactory
     * @param \Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories\CollectionFactory $tagCatagoryCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magedelight\Bundlediscount\Helper\Option $optionData,
        \Magedelight\Bundlediscount\Model\TagwrapperFactory $tagwrapperFactory,
        \Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories\CollectionFactory $tagCatagoryCollection,
        array $data = []
    ) {
        $this->_statusOption = $optionData;
        $this->tagwrapperFactory = $tagwrapperFactory;
        $this->_tagCatagoryCollection = $tagCatagoryCollection;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $id = $this->getRequest()->getParam('id');
        $tagWrapper = $this->tagwrapperFactory->create();
        if ($id) {
            $tagWrapper->load($id);
        }

        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('tagwrapper_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Tagwrapper Details'), 'class' => 'fieldset-wide']
        );

        $categoriesCollection = $this->_tagCatagoryCollection->create()
            ->addFieldToFilter('is_active', 1);
        
        $default=['value'=>'','label'=>'Choose Category'];
        $i = 0;
        $categories[$i]=$default;

        foreach ($categoriesCollection as $key => $value) {
            $i++;
            $categories[$i]=['value'=>$value->getEntityId(),'label'=>$value->getName()];
        }
        
        if (!empty($tagWrapper->getId())) {
            $fieldset->addField('id', 'hidden', ['name' => 'id', 'value' => $tagWrapper->getId()]);
        }

        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Tag Name'),
                'title' => __('Tag Name'),
                'required' => true,
                'value' => $tagWrapper->getName()
            ]
        );

        $fieldset->addField(
            'category',
            'select',
            [
                'name' => 'category',
                'label' => __('Category'),
                'title' => __('Category'),
                'required' => true,
                'value' => $tagWrapper->getCategory(),
                'values' => $categories
            ]
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Status'),
                'required' => true,
                'name' => 'is_active',
                'class' => 'required-entry',
                'value' => $tagWrapper->getIsActive(),
                'values' => $this->_statusOption->getStatusesOptionArray()
            ]
        );

        $form->setValues($tagWrapper->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Tagwrapper Details');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Tagwrapper Details');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return true;
    }
}
