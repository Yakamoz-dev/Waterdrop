<?php
namespace Ecopure\Gift\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Ecopure\Gift\Model\Product;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Ecopure_Gift::product';
    protected $dataProcessor;
    protected $dataPersistor;
    protected $model;

    public function __construct(
        Action\Context $context,
        PostDataProcessor $dataProcessor,
        Product $model,
        DataPersistorInterface $dataPersistor
    ) {
        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        $this->model = $model;
        parent::__construct($context);
    }

    public function execute()
    {

        $productData = $this->getRequest()->getPostValue();
        $data = $productData['product'];
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {

            $data = $this->dataProcessor->filter($data);


            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $this->model->load($id);
            }


            $this->model->setData($data);

//            $this->_eventManager->dispatch(
//                'ecopure_gift_product_prepare_save',
//                ['ecopure_gift_product' => $this->model, 'request' => $this->getRequest()]
//            );

            if (!$this->dataProcessor->validate($data)) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->model->getId(), '_current' => true]);
            }

            try {
                $this->model->save();
                $this->messageManager->addSuccess(__('You saved the Product.'));
                $this->dataPersistor->clear('product');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        ['id' => $this->model->getId(),
                            '_current' => true]
                    );
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Post.'));
            }

            $this->dataPersistor->set('product', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
