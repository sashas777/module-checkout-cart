<?php
/**
 * @author     The S Group <support@sashas.org>
 * @copyright  2020  Sashas IT Support Inc. (https://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

declare(strict_types=1);

namespace TheSGroup\CheckoutCart\Test\Unit\Observer;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use TheSGroup\CheckoutCart\Observer\RemoveBlock;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\View\Element\Template;

/**
 * Class RemoveBlockTest
 */
class RemoveBlockTest extends TestCase
{

    /** @var RemoveBlock object */
    private $object;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $scopeConfig;

    /**
     *
     */
    public function testExecute()
    {
        $observer = $this->getMockBuilder(Observer::class)
                                  ->disableOriginalConstructor()
                                  ->setMethods(['getBlock'])
                                  ->getMock();
        $block = $this->getMockBuilder(Template::class)
                         ->disableOriginalConstructor()
                         ->getMock();
        $block->expects($this->atLeastOnce())->method('getNameInLayout')->willReturn('checkout.cart.form');
        $block->expects($this->atLeastOnce())->method('setTemplate');
        $observer->expects($this->atLeastOnce())->method('getBlock')->willReturn($block);
        $this->scopeConfig->expects($this->atLeastOnce())->method('getValue')->willReturn(true);
        $this->assertNull($this->object->execute($observer));
    }

    /**
     *
     */
    protected function setUp(): void
    {
        $this->scopeConfig = $this->getMockBuilder(ScopeConfigInterface::class)
                         ->disableOriginalConstructor()
                         ->getMock();
        $this->object = (new ObjectManager($this))->getObject(
            RemoveBlock::class,
            [
                'scopeConfig' => $this->scopeConfig
            ]
        );
    }
}
