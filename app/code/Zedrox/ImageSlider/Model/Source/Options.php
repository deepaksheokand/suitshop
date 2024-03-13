<?php

namespace Zedrox\ImageSlider\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    protected $sliderFactory;

    public function __construct(
        \Zedrox\ImageSlider\Model\SliderFactory $sliderFactory
    ) {
        $this->sliderFactory = $sliderFactory;
    }

    public function getCollection()
    {
        return $this->sliderFactory->create()->getCollection();
    }

    public function toOptionArray()
    {
        $collection = $this->getCollection();
        $options = [];

        foreach ($collection as $item) {
            $sliderId = $item->getSliderId();
            $title = $item->getTitle();

            $options[] = [
                'label' => __($title),
                'value' => $sliderId,
            ];
        }
        return $options;
    }
}
