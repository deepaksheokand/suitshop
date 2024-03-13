<?php

namespace Zedrox\ImageSlider\Block;

class Slider extends \Magento\Framework\View\Element\Template
{
    protected $sliderImageCollection;
    protected $imageUploader;
    protected $slider;
    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Zedrox\ImageSlider\Model\ResourceModel\Image\CollectionFactory $sliderImageCollection,
        \Zedrox\ImageSlider\Model\ImageUploader $imageUploader,
        \Zedrox\ImageSlider\Model\Image $slider,
        array $data = []
    )
    {   
        $this->sliderImageCollection = $sliderImageCollection;
        $this->imageUploader = $imageUploader;
        $this->slider = $slider;
        parent::__construct($context, $data);
    }

        public function getSliders()
        {
         $collection = $this->slider->getCollection();
         return $collection;
        }

	public function getSliderBaseUrl($imageName)
	{
        $baseUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $imageUrl = $baseUrl . 'slider/image/' . $imageName;
        return $imageUrl;
	}

    public function getCollection()
    {
        return $this->sliderImageCollection->create();
    }
}
