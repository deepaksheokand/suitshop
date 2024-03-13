<?php

namespace Zedrox\ImageSlider\Model\ResourceModel\Image;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'image_id';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Zedrox\ImageSlider\Model\Image', 'Zedrox\ImageSlider\Model\ResourceModel\Image');
    }
}
