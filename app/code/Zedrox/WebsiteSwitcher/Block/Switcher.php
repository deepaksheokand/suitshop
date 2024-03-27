<?php

namespace Zedrox\WebsiteSwitcher\Block;


class Switcher extends \Magento\Framework\View\Element\Template
{
    protected $_storeManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    )
    {   
        $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    public function getWebsites() {
        return $this->_storeManager->getWebsites();
    }

    public function getCurrentWebsiteId()
    {
        return $this->_storeManager->getWebsite()->getId();
    }

    public function getMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

    }
}
