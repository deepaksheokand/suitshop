<?php

namespace Zedrox\ImageSlider\Model;

use Zedrox\ImageSlider\Model\ResourceModel\Image\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class ImageFormDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    /**
     * @var array
     */
    protected $loadedData;
    protected $_storeManager;   

    // @codingStandardsIgnoreStart
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $imageCollectionFactory,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $imageCollectionFactory->create();
        $this->_storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    // @codingStandardsIgnoreEnd

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $item) {
            $this->loadedData[$item->getId()] = $item->getData();
            if ($item->getImage()) {
                $m['image'][0]['name'] = $item->getImage();
                $m['image'][0]['url'] = $this->getMediaUrl() . 'slider/image/' . $item->getImage();
                $fullData = $this->loadedData;
                $this->loadedData[$item->getId()] = array_merge($fullData[$item->getId()], $m);
            }
            if ($item->getMobileImage()) {
                $mimage['mobile_image'][0]['name'] = $item->getMobileImage();
                $mimage['mobile_image'][0]['url'] = $this->getMediaUrl() . 'slider/image/' . $item->getMobileImage();
                $fullData = $this->loadedData;
                $this->loadedData[$item->getId()] = array_merge($fullData[$item->getId()], $mimage);
            }
        }
        return $this->loadedData;
    }
    
    public function getMediaUrl()
    {
        $mediaUrl = $this->_storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl;
    }
}
