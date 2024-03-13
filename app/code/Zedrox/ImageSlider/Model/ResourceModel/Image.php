<?php
namespace Zedrox\ImageSlider\Model\ResourceModel;

class Image extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    const KEY_MAGENTO_SLIDERIMAGE_TABLE = 'zedrox_sliderimage';
    const KEY_MAGENTO_SLIDERIMAGE_TABLE_ID = 'image_id';

    /**
     * construct
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            self::KEY_MAGENTO_SLIDERIMAGE_TABLE,
            self::KEY_MAGENTO_SLIDERIMAGE_TABLE_ID
        );
    }
}
