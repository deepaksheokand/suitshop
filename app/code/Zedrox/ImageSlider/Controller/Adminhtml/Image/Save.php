<?php

namespace Zedrox\ImageSlider\Controller\Adminhtml\Image;

use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    protected $uiExamplemodel;
    protected $imageFactory;
    protected $imageResourceModel;
    protected  $imageUploader;

    /**
     * @var Session
     */
    protected $adminsession;

    /**
     * @param Action\Context $context
     * @param Image           $uiExamplemodel
     * @param Session        $adminsession
     */
    public function __construct(
        Action\Context $context,
        \Zedrox\ImageSlider\Model\ImageFactory $imageFactory,
        \Zedrox\ImageSlider\Model\ResourceModel\Image $imageResourceModel,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        Session $adminsession,
        \Zedrox\ImageSlider\Model\ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageFactory = $imageFactory;
        $this->imageResourceModel = $imageResourceModel;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->adminsession = $adminsession;
        $this->imageUploader = $imageUploader;
    }

    /**
     * Save Image record action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirectFactory = $this->resultRedirectFactory->create();
        $postData = $this->getRequest()->getPostValue();
        // desktop image banner
        $image = (isset($postData['image']['0']['name'])) ? $postData['image']['0']['name'] : null;
        if ($image !== null) {
            try {

                $imageUploader = $this->imageUploader;
                if (isset($postData['image']['0']['tmp_name'])) {
                    $imageUploader->moveFileFromTmp($image);
                }
            } catch (\Exception $e) {
                $this->getMessageManager()->addErrorMessage($e->getMessage());
            }
        }
        $postData['image'] = $image;
        // mobile image banner
        $mobile_image = (isset($postData['mobile_image']['0']['name'])) ? $postData['mobile_image']['0']['name'] : null;
        if ($mobile_image !== null) {
            try {
                $imageUploader = $this->imageUploader;
                if (isset($postData['mobile_image']['0']['tmp_name'])) {
                    $imageUploader->moveFileFromTmp($mobile_image);
                }
            } catch (\Exception $e) {
                $this->getMessageManager()->addErrorMessage($e->getMessage());
            }
        }
        $postData['mobile_image'] = $mobile_image;
        $data = [
            'status' => $postData["status"],
            'title' => $postData["title"],
            // 'description' => $postData["description"],
            'slider_ids' => $postData["slider_ids"],
            'url_key' => $postData['url_key'],
            'image' => $postData['image'],
            'mobile_image' => $postData['mobile_image']

        ];

        $image_id = $this->getRequest()->getParam('image_id');
        if ($data) {
            $sliderData = $this->imageFactory->create();
            if ($image_id) {
                $sliderData->load($image_id);
                $data = [
                    'image_id' => $image_id,
                    'status' => $postData["status"],
                    'title' => $postData["title"],
                    // 'description' => $postData["description"],
                    'slider_ids' => $postData["slider_ids"],
                    'url_key' => $postData['url_key'],
                    'image' => $postData['image'],
                    'mobile_image' => $postData['mobile_image']

                ];
            }
            $sliderData->setData($data);

            try {
                $this->imageResourceModel->save($sliderData);
                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->adminsession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return   $resultRedirectFactory->setPath('*/*/add');
                    } else {
                        return   $resultRedirectFactory->setPath('*/*/edit', ['image_id' => $sliderData->getImageId(), '_current' => true]);
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
            return   $resultRedirectFactory->setPath('*/*/edit', ['image_id' => $this->getRequest()->getParam('image_id')]);
        }
        return   $resultRedirectFactory->setPath('*/*/');
    }
}
