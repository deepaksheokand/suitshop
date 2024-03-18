<?php

namespace Zedrox\BestSeller\Block;

use Exception;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Pricing\Render;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\View\LayoutFactory;
use Magento\GroupedProduct\Model\Product\Type\Grouped;
use Magento\Widget\Block\BlockInterface;

/**
 * Class AbstractSlider
 * @package Zedrox\BestSeller\Block
 */
abstract class AbstractSlider extends AbstractProduct implements BlockInterface, IdentityInterface
{
    /**
     * @var DateTime
     */
    protected $_date;
    /**
     * @var Data
     */
    // protected $_helperData;
    /**
     * @var CollectionFactory
     */
    protected $_productCollectionFactory;
    /**
     * @var Visibility
     */
    protected $_catalogProductVisibility;
    /**
     * @var HttpContext
     */
    protected $httpContext;
    /**
     * @var EncoderInterface|null
     */
    protected $urlEncoder;
    /**
     * @var Grouped
     */
    protected $grouped;
    /**
     * @var Configurable
     */
    protected $configurable;
    /**
     * @var
     */
    protected $rendererListBlock;
    /**
     * @var
     */
    private $priceCurrency;
    /**
     * @var LayoutFactory
     */
    private $layoutFactory;

    /**
     * AbstractSlider constructor.
     *
     * @param Context $context
     * @param CollectionFactory $productCollectionFactory
     * @param Visibility $catalogProductVisibility
     * @param DateTime $dateTime
     * @param Data $helperData
     * @param HttpContext $httpContext
     * @param EncoderInterface $urlEncoder
     * @param Grouped $grouped
     * @param Configurable $configurable
     * @param LayoutFactory $layoutFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $productCollectionFactory,
        Visibility $catalogProductVisibility,
        DateTime $dateTime,
        HttpContext $httpContext,
        EncoderInterface $urlEncoder,
        Grouped $grouped,
        Configurable $configurable,
        LayoutFactory $layoutFactory,
        array $data = []
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->httpContext               = $httpContext;
        $this->urlEncoder                = $urlEncoder;
        $this->grouped                   = $grouped;
        $this->configurable              = $configurable;
        $this->layoutFactory             = $layoutFactory;

        parent::__construct($context, $data);
    }

    /**
     * Get post parameters.
     *
     * @param Product $product
     *
     * @return array
     */
    public function getAddToCartPostParams(Product $product)
    {
        $url = $this->getAddToCartUrl($product);

        return [
            'action' => $url,
            'data'   => [
                'product'                               => $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlEncoder->encode($url),
            ]
        ];
    }


    /**
     * @return array|string[]
     */
    public function getIdentities()
    {
        $identities = [];
        if ($this->getProductCollection()) {
            foreach ($this->getProductCollection() as $product) {
                if ($product instanceof IdentityInterface) {
                    $identities += $product->getIdentities();
                }
            }
        }

        return $identities ?: [Product::CACHE_TAG];
    }

    /**
     * @return mixed
     */
    abstract public function getProductCollection();

 

    /**
     * @param $collection
     *
     * @return array
     */
    public function getProductParentIds($collection)
    {
        $productIds = [];

        foreach ($collection as $product) {
            if (isset($product->getData()['entity_id'])) {
                $productId = $product->getData()['entity_id'];
            } else {
                $productId = $product->getProductId();
            }

            $parentIdsGroup  = $this->grouped->getParentIdsByChild($productId);
            $parentIdsConfig = $this->configurable->getParentIdsByChild($productId);

            if (!empty($parentIdsGroup)) {
                $productIds[] = $parentIdsGroup;
            } elseif (!empty($parentIdsConfig)) {
                $productIds[] = $parentIdsConfig[0];
            } else {
                $productIds[] = $productId;
            }
        }

        return $productIds;
    }
}
