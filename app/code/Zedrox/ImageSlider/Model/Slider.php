<?php

namespace Zedrox\ImageSlider\Model;

class Slider extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    
    protected $_eventPrefix = 'zedrox_slider';

    /**
     * @var string
     */

    protected $_eventObject = 'slider_details';

    /**
     * @var string
     */

    protected $_idFieldName = 'slider_id';

    /**
     * Resource initialization
     *
     * @return void
     */

    protected function _construct()
    {
        $this->_init('Zedrox\ImageSlider\Model\ResourceModel\Slider');
    }
}
