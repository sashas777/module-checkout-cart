<?php
/**
 * @author     The S Group <support@sashas.org>
 * @copyright  2020  Sashas IT Support Inc. (https://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

declare(strict_types=1);

namespace TheSGroup\CheckoutCart\Test\Unit\Model;

use Magento\Checkout\Model\Session as CheckoutSession;
use TheSGroup\CheckoutCart\Model\Cart\ImageProvider;
use TheSGroup\CheckoutCart\Model\CheckoutCartConfigProvider;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Quote\Model\Quote;

/**
 * Class CheckoutCartConfigProviderTest
 */
class CheckoutCartConfigProviderTest extends TestCase
{

    /** @var CheckoutCartConfigProvider object */
    private $object;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $checkoutSession;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $imageProvider;

    /**
     *
     */
    public function testGetConfig()
    {
        $result = [
            'cartImageData' => ['test']
        ];
        $quote = $this->getMockBuilder(Quote::class)
                      ->disableOriginalConstructor()
                      ->getMock();
        $quote->expects($this->atLeastOnce())->method('getId')->willReturn(1);
        $this->checkoutSession->expects($this->atLeastOnce())->method('getQuote')->willReturn($quote);
        $this->imageProvider->expects($this->atLeastOnce())->method('getImages')->willReturn($result['cartImageData']);
        $this->assertEquals($result, $this->object->getConfig());
    }

    /**
     *
     */
    protected function setUp(): void
    {
        $this->checkoutSession = $this->getMockBuilder(CheckoutSession::class)
                                  ->disableOriginalConstructor()
                                  ->getMock();
        $this->imageProvider = $this->getMockBuilder(ImageProvider::class)
                                      ->disableOriginalConstructor()
                                      ->getMock();
        $this->object = (new ObjectManager($this))->getObject(
            CheckoutCartConfigProvider::class,
            [
                'checkoutSession' => $this->checkoutSession,
                'imageProvider' => $this->imageProvider,
            ]
        );
    }
}
