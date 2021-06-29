<?php
namespace Ecopure\Gift\Controller\Adminhtml\Gift;

use Magento\Backend\App\Action;
use Ecopure\Gift\Model\Gift;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Ecopure_Gift::giftro';
    protected $dataProcessor;
    protected $dataPersistor;
    protected $model;

    public function __construct(
        Action\Context $context,
        PostDataProcessor $dataProcessor,
        Gift $model,
        DataPersistorInterface $dataPersistor
    ) {
        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        $this->model = $model;
        parent::__construct($context);
    }

    public function execute()
    {

        $orderData = $this->getRequest()->getPostValue();
        $data = $orderData['gift'];
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {

            $data = $this->dataProcessor->filter($data);

            if (!$this->dataProcessor->validate($data)) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->model->getId(), '_current' => true]);
            }

            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $this->model->load($id);
            }

            if (isset($data['image'][0]['name']) && isset($data['image'][0]['tmp_name'])) {
                $data['image']=$data['image'][0]['name'];
//                $this->imageUploader = \Magento\Framework\App\ObjectManager::getInstance()->get(
//                    'Ecopure\Gift\ImageUpload'
//                );
//                $this->imageUploader->moveFileFromTmp($data['image']);
            } elseif (isset($data['image'][0]['image']) && !isset($data['image'][0]['tmp_name'])) {
                $data['image'] = $data['image'][0]['image'];
            } else {
                $data['image'] = null;
            }

            if(!empty($data['dynamic_rows_container']['dynamic_rows_container'])) {
                $data['options'] = json_encode($data['dynamic_rows_container']['dynamic_rows_container']);
            } else {
                $data['options'] = null;
            }

            $this->model->setData($data);

//            $this->_eventManager->dispatch(
//                'ecopure_gift_product_prepare_save',
//                ['ecopure_gift_product' => $this->model, 'request' => $this->getRequest()]
//            );

            try {
                $this->model->save();
                $this->messageManager->addSuccess(__('You saved the Gift.'));
                $this->dataPersistor->clear('gift');
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

            $this->dataPersistor->set('gift', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
