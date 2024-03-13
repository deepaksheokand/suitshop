<?php

namespace Zedrox\ImageSlider\Model\ResourceModel\Slider;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'slider_id';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Zedrox\ImageSlider\Model\Slider', 'Zedrox\ImageSlider\Model\ResourceModel\Slider');
    }
}
