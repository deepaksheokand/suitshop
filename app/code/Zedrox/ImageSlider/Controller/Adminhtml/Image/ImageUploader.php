<?php

namespace Zedrox\ImageSlider\Controller\Adminhtml\Image;

use Magento\Framework\Controller\ResultFactory;

class ImageUploader extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\ImageSlider\Model\ImageUploader
     */
    protected $imageUploader;

    /**
     * ImageUploader constructor.
     * @param \Magento\ImageSlider\Model\ImageUploader $imageUploader
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Zedrox\ImageSlider\Model\ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $imageId = $this->_request->getParam('param_name', 'image');
            $result = $this->imageUploader->saveFileToTmpDir($imageId);
            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(),'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
