<?php

namespace Zedrox\ImageSlider\Ui\Component\Listing\Grid\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class Action extends Column
{
    /** Url path */
    const ROW_EDIT_URL = 'imageslider/slider/edit';

    const ROW_VIEW_URL = "imageslider/slider/view";

    const ROW_DELETE_URL = "imageslider/slider/delete";
    /** @var UrlInterface */
    protected $_urlBuilder;

    /**
     * @var string
     */
    private $_editUrl;

    /**
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface       $urlBuilder
     * @param array              $components
     * @param array              $data
     * @param string             $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::ROW_EDIT_URL,
        $viewUrl = self::ROW_VIEW_URL,
        $deleteUrl = self::ROW_DELETE_URL
    ) 
    {
        $this->_urlBuilder = $urlBuilder;
        $this->_editUrl = $editUrl;
        $this->_viewUrl = $viewUrl;
        $this->_deleteUrl = $deleteUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source.
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                if (isset($item['slider_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->_urlBuilder->getUrl(
                            $this->_editUrl, 
                            ['slider_id' => $item['slider_id']]
                        ),
                        'label' => __('Edit'),
                    ];

                    $item[$name]['delete'] = [
                        'href' => $this->_urlBuilder->getUrl(
                            $this->_deleteUrl, 
                            ['slider_id' => $item['slider_id']]
                        ),
                        'label' => __('Delete'),
                    ];
                }
            }
        } 
        return $dataSource;
    }
}
