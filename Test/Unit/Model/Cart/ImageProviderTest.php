<?php
/**
 * @author     The S Group <support@sashas.org>
 * @copyright  2020  Sashas IT Support Inc. (https://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

declare(strict_types=1);

namespace TheSGroup\CheckoutCart\Test\Unit\Model\Cart;

use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\Product\Configuration\Item\ItemResolverInterface;
use Magento\Quote\Api\CartItemRepositoryInterface;
use TheSGroup\CheckoutCart\Model\Cart\ImageProvider;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Quote\Model\Quote\Item;

/**
 * Class ImageProviderTest
 */
class ImageProviderTest extends TestCase
{

    /** @var ImageProvider object */
    private $object;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $itemRepository;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $imageHelper;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $itemResolver;

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function testGetImages()
    {
        $itemId = 1;
        $textVal = 'test';
        $result = [
            $itemId => [
                'src' => $textVal,
                'alt' => $textVal,
                'width' => $textVal,
                'height' => $textVal,
            ]
        ];
        $cartItem = $this->getMockBuilder(Item::class)
                         ->disableOriginalConstructor()
                         ->getMock();
        $cartItem->expects($this->atLeastOnce())->method('getItemId')->willReturn($itemId);
        $this->itemRepository->expects($this->atLeastOnce())->method('getList')->willReturn([$cartItem]);
        $this->imageHelper->expects($this->atLeastOnce())->method('init')->willReturn($this->imageHelper);
        $this->imageHelper->expects($this->atLeastOnce())->method('getUrl')->willReturn($textVal);
        $this->imageHelper->expects($this->atLeastOnce())->method('getLabel')->willReturn($textVal);
        $this->imageHelper->expects($this->atLeastOnce())->method('getWidth')->willReturn($textVal);
        $this->imageHelper->expects($this->atLeastOnce())->method('getHeight')->willReturn($textVal);
        $this->itemResolver->expects($this->atLeastOnce())->method('getFinalProduct');

        $this->assertEquals($result, $this->object->getImages(1));
    }

    /**
     *
     */
    protected function setUp()
    {
        $this->itemRepository = $this->getMockBuilder(CartItemRepositoryInterface::class)
                                     ->disableOriginalConstructor()
                                     ->getMock();
        $this->imageHelper = $this->getMockBuilder(Image::class)
                                  ->disableOriginalConstructor()
                                  ->getMock();
        $this->itemResolver = $this->getMockBuilder(ItemResolverInterface::class)
                                   ->disableOriginalConstructor()
                                   ->getMock();
        $this->object = (new ObjectManager($this))->getObject(
            ImageProvider::class,
            [
                'itemRepository' => $this->itemRepository,
                'imageHelper' => $this->imageHelper,
                'itemResolver' => $this->itemResolver,
            ]
        );
    }
}
