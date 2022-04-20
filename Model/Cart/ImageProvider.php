<?php
/**
 * @author     Sashas IT Support <support@sashas.org>
 * @copyright  2020  Sashas IT Support Inc. (http://www.extensions.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */
declare(strict_types=1);

namespace TheSGroup\CheckoutCart\Model\Cart;

use Magento\Catalog\Model\Product\Configuration\Item\ItemResolverInterface;
use Magento\Quote\Api\CartItemRepositoryInterface;
use Magento\Catalog\Helper\Image;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Model\Quote\Item;

/**
 * Class ImageProvider
 * Cart image provider
 */
class ImageProvider
{
    /**
     * @var CartItemRepositoryInterface
     */
    private $itemRepository;

    /**
     * @var Image
     */
    private $imageHelper;

    /**
     * @var ItemResolverInterface
     */
    private $itemResolver;

    /**
     * ImageProvider constructor.
     *
     * @param CartItemRepositoryInterface $itemRepository
     * @param Image $imageHelper
     * @param ItemResolverInterface $itemResolver
     */
    public function __construct(
        CartItemRepositoryInterface $itemRepository,
        Image $imageHelper,
        ItemResolverInterface $itemResolver
    ) {
        $this->itemRepository = $itemRepository;
        $this->imageHelper = $imageHelper;
        $this->itemResolver = $itemResolver;
    }

    /**
     * Get images by cart id
     *
     * @param string $cartId
     *
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getImages($cartId)
    {
        $itemData = [];

        $items = $this->itemRepository->getList($cartId);
        /** @var CartItemInterface $cartItem */
        foreach ($items as $cartItem) {

            $imageHelper = $this->imageHelper->init(
                $this->getProductForThumbnail($cartItem),
                'cart_page_product_thumbnail'
            );

            $itemData[$cartItem->getItemId()] =  [
                'src' => $imageHelper->getUrl(),
                'alt' => $imageHelper->getLabel(),
                'width' => $imageHelper->getWidth(),
                'height' => $imageHelper->getHeight(),
            ];
        }
        return $itemData;
    }

    /**
     * Get product for thumbnail image
     *
     * @param Item $cartItem
     *
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    private function getProductForThumbnail(Item $cartItem)
    {
        return $this->itemResolver->getFinalProduct($cartItem);
    }
}
