<?php

namespace Zedrox\ImageSlider\Controller\Adminhtml\Slider;

use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    protected $uiExamplemodel;
    protected $sliderResourceModel;
    protected $imageUploader;
    /**
     * @var Session
     */
    protected $adminsession;

    protected $sliderFactory;
    protected $resultRedirectFactory;

    /**
     * @param Action\Context $context
     * @param Session        $adminsession
     */
    public function __construct(
        Action\Context $context,
        \Zedrox\ImageSlider\Model\SliderFactory $sliderFactory,
        \Zedrox\ImageSlider\Model\ResourceModel\Slider $sliderResourceModel,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        Session $adminsession
    ) {
        parent::__construct($context);
        $this->sliderFactory = $sliderFactory;
        $this->sliderResourceModel = $sliderResourceModel;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->adminsession = $adminsession;
    }

    /**
     * Save Slider record action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirectFactory = $this->resultRedirectFactory->create();
        $postData = $this->getRequest()->getPostValue();
        $data = [
            'title' => $postData["title"],
            'status' => $postData["status"]
        ];

        $slider_id = $this->getRequest()->getParam('slider_id');

        if ($data) {
            $sliderData = $this->sliderFactory->create();
            if ($slider_id) {
                $sliderData->load($slider_id);
                $data = [
                    'slider_id' => $slider_id,
                    'title' => $postData["title"],
                    'status' => $postData["status"]
                ];
            }
            $sliderData->setData($data);
            try {
                $this->sliderResourceModel->save($sliderData);
                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->adminsession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return   $resultRedirectFactory->setPath('*/*/add');
                    } else {
                        return   $resultRedirectFactory->setPath('*/*/edit', ['slider_id' => $sliderData->getSliderId(), '_current' => true]);
                    }
                }
                return   $resultRedirectFactory->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                die($e);
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }
            $this->_getSession()->setFormData($data);
            return   $resultRedirectFactory->setPath('*/*/edit', ['slider_id' => $this->getRequest()->getParam('slider_id')]);
        }
        return   $resultRedirectFactory->setPath('*/*/');
    }
}
