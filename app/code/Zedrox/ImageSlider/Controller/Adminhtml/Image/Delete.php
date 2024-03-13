<?php

namespace Zedrox\ImageSlider\Controller\Adminhtml\Image;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Delete extends Action
{
    public $sliderFactory;
    
    public function __construct(
        Context $context,
        \Zedrox\ImageSlider\Model\ImageFactory $imageFactory,
    ) {
        $this->imageFactory = $imageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('image_id');
        try {
            $imageModel = $this->imageFactory->create();
            $imageModel->load($id);
            $imageModel->delete();
            $this->messageManager->addSuccessMessage(__('You deleted the Image.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Zedrox_ImageSlider::delete');
    }
}
