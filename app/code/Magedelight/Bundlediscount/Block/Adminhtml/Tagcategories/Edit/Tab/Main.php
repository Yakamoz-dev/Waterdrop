<?php
namespace Magedelight\Bundlediscount\Block\Adminhtml\Tagcategories\Edit\Tab;

class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    private $statusOption;

    /**
     * Main constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magedelight\Bundlediscount\Helper\Option $optionData
     * @param \Magedelight\Bundlediscount\Model\TagcategoriesFactory $tagCategoriesFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magedelight\Bundlediscount\Helper\Option $optionData,
        \Magedelight\Bundlediscount\Model\TagcategoriesFactory $tagCategoriesFactory,
        array $data = []
    ) {
        $this->tagCategoriesFactory = $tagCategoriesFactory;
        $this->statusOption = $optionData;
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
        $tagCategories = $this->tagCategoriesFactory->create();
        if ($id) {
            $tagCategories->load($id);
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('tagcategories_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Tag Category Details'), 'class' => 'fieldset-wide']
        );

        if (!empty($tagCategories->getEntityId())) {
            $fieldset->addField(
                'entity_id',
                'hidden',
                ['name' => 'entity_id', 'value' => $tagCategories->getEntityId()]
            );
        }

        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Tag Category Name'),
                'title' => __('Tag Category Name'),
                'required' => true,
                'value' => $tagCategories->getName()
            ]
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Status'),
                'required' => true,
                'name' => 'is_active',
                'value' => $tagCategories->getIsActive(),
                'values' => $this->statusOption->getStatusesOptionArray()
            ]
        );

        $form->setValues($tagCategories->getData());
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
        return __('Tag Category Details');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Tag Category Details');
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
