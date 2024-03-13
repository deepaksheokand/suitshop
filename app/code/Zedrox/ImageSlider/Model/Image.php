<?php

namespace Zedrox\ImageSlider\Model;

class Image extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'zedrox_sliderimage';

    /**
     * @var string
     */
    protected $_eventObject = 'image_details';

    /**
     * @var string
     */
    protected $_idFieldName = 'image_id';

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Zedrox\ImageSlider\Model\ResourceModel\Image');
    }
}
