<?php

namespace Zedrox\ImageSlider\Controller\Adminhtml\Slider;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Delete extends Action
{
    public $sliderFactory;
    
    public function __construct(
        Context $context,
        \Zedrox\ImageSlider\Model\SliderFactory $sliderFactory,
    ) {
        $this->sliderFactory = $sliderFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('slider_id');
        try {
            $sliderModel = $this->sliderFactory->create();
            $sliderModel->load($id);
            $sliderModel->delete();
            $this->messageManager->addSuccessMessage(__('You deleted the Slider.'));
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
