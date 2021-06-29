<?php
namespace Ecopure\Gift\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
/**
 * Description of ProductActions
 *
 * @author dharmendra
 */
class ProductEdit extends Column
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /** Url Path */
    const URL_PATH_EDIT = 'ecopure_gift/product/edit';
    const URL_PATH_DEL = 'ecopure_gift/product/delete';

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = array(),
        UrlInterface $urlBuilder,
        array $data = array())
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return void
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['ro_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl(self::URL_PATH_EDIT, ['ro_id' => $item['ro_id']]),
                        'label' => __('Edit')
                    ];
//                    $item[$name]['delete'] = [
//                        'href' => $this->urlBuilder->getUrl(self::URL_PATH_DEL, ['ro_id' => $item['ro_id']]),
//                        'label' => __('Delete'),
//                        'confirm' => [
//                            'title' => __('Delete "${ $.$data.ro_id }"'),
//                            'message' => __('Are you sure you wan\'t to delete the Product "${ $.$data.ro_id }" ?')
//                        ]
//                    ];
                }
            }
        }
        return $dataSource;
    }
}
