<?php
namespace Zedrox\ImageSlider\Model\ResourceModel;

class Slider extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    const KEY_MAGENTO_SLIDER_TABLE = 'zedrox_slider';
    const KEY_MAGENTO_SLIDER_TABLE_ID = 'slider_id';

    /**
     * construct
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            self::KEY_MAGENTO_SLIDER_TABLE,
            self::KEY_MAGENTO_SLIDER_TABLE_ID
        );
    }
}
