<?php
/**
 * @author     The S Group <support@sashas.org>
 * @copyright  2020  Sashas IT Support Inc. (https://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

declare(strict_types=1);

namespace TheSGroup\CheckoutCart\Test\Unit\Controller\Cart;

use Magento\Checkout\Model\Cart\RequestQuantityProcessor;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Quote\Api\CartRepositoryInterface;
use TheSGroup\CheckoutCart\Controller\Cart\UpdateItem;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item;

/**
 * Class UpdateItemTest
 */
class UpdateItemTest extends TestCase
{

    /** @var UpdateItem object */
    private $object;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $context;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $quantityProcessor;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $formKeyValidator;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $checkoutSession;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $json;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $logger;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $quoteRepository;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $request;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $response;

    /**
     *
     */
    public function testExecute()
    {
        $cartData =   [
             2 => ['remove' => true],
            'empty_cart' => ['test'],
            1 => ['qty' => 1],
            3 => ['qty' => 2],
        ];
        $quote = $this->getMockBuilder(Quote::class)
                      ->disableOriginalConstructor()
                      ->getMock();
        $quoteItem = $this->getMockBuilder(Item::class)
                      ->disableOriginalConstructor()
                      ->setMethods(['getHasError', 'clearMessage', 'setQty', 'getMessage'])
                      ->getMockForAbstractClass();
        $this->request->expects($this->atLeastOnce())->method('isPost')->willReturn(true);
        $this->request->expects($this->atLeastOnce())->method('getParam')->willReturn($cartData);
        $this->formKeyValidator->expects($this->atLeastOnce())->method('validate')->willReturn(true);
        $this->quantityProcessor->expects($this->atLeastOnce())->method('process')->willReturn($cartData);
        $quoteItem->expects($this->atLeastOnce())->method('getHasError')->willReturn(false);
        $quoteItem->expects($this->atLeastOnce())->method('clearMessage')->willReturn($quoteItem);
        $quoteItem->expects($this->never())->method('getMessage')->willReturn('test');
        $quoteItem->expects($this->atLeastOnce())->method('setQty')->willReturn($quoteItem);
        $quote->expects($this->atLeastOnce())->method('getItemById')->willReturn($quoteItem);
        $quote->expects($this->atLeastOnce())->method('removeAllItems')->willReturn($quote);
        $this->checkoutSession->expects($this->atLeastOnce())->method('getQuote')->willReturn($quote);
        $this->quoteRepository->expects($this->atLeastOnce())->method('save');
        $this->logger->expects($this->never())->method('critical');
        $this->response->expects($this->atLeastOnce())->method('representJson')->willReturn('{}');
        $this->json->expects($this->atLeastOnce())->method('serialize')->willReturn('{}');

        $this->assertNull($this->object->execute());
    }

    /**
     *
     */
    public function testExecuteItemHasError()
    {
        $cartData =   [
            1 => ['qty' => 1],
        ];
        $quote = $this->getMockBuilder(Quote::class)
                      ->disableOriginalConstructor()
                      ->getMock();
        $quoteItem = $this->getMockBuilder(Item::class)
                          ->disableOriginalConstructor()
                          ->setMethods(['getHasError', 'clearMessage', 'setQty', 'getMessage'])
                          ->getMockForAbstractClass();
        $this->request->expects($this->atLeastOnce())->method('isPost')->willReturn(true);
        $this->request->expects($this->atLeastOnce())->method('getParam')->willReturn($cartData);
        $this->formKeyValidator->expects($this->atLeastOnce())->method('validate')->willReturn(true);
        $this->quantityProcessor->expects($this->atLeastOnce())->method('process')->willReturn($cartData);
        $quoteItem->expects($this->atLeastOnce())->method('getHasError')->willReturn(true);
        $quoteItem->expects($this->atLeastOnce())->method('clearMessage')->willReturn($quoteItem);
        $quoteItem->expects($this->atLeastOnce())->method('getMessage')->willReturn('test');
        $quoteItem->expects($this->atLeastOnce())->method('setQty')->willReturn($quoteItem);
        $quote->expects($this->atLeastOnce())->method('getItemById')->willReturn($quoteItem);
        $quote->expects($this->never())->method('removeAllItems')->willReturn($quote);
        $this->checkoutSession->expects($this->atLeastOnce())->method('getQuote')->willReturn($quote);
        $this->quoteRepository->expects($this->never())->method('save');
        $this->logger->expects($this->never())->method('critical');
        $this->response->expects($this->atLeastOnce())->method('representJson')->willReturn('{}');
        $this->json->expects($this->atLeastOnce())->method('serialize')->willReturn('{}');

        $this->assertNull($this->object->execute());
    }

    /**
     *
     */
    public function testExecuteException()
    {
        $cartData =   [
            1 => ['qty' => 1],
        ];
        $quote = $this->getMockBuilder(Quote::class)
                      ->disableOriginalConstructor()
                      ->getMock();
        $quoteItem = $this->getMockBuilder(Item::class)
                          ->disableOriginalConstructor()
                          ->getMock();
        $this->request->expects($this->atLeastOnce())->method('isPost')->willReturn(true);
        $this->request->expects($this->atLeastOnce())->method('getParam')->willReturn($cartData);
        $this->formKeyValidator->expects($this->atLeastOnce())->method('validate')->willReturn(true);
        $this->quantityProcessor->expects($this->atLeastOnce())->method('process')->willReturn($cartData);
        $quote->expects($this->atLeastOnce())->method('getItemById')->willReturn($quoteItem);
        $quote->expects($this->never())->method('removeAllItems')->willReturn($quote);
        $this->checkoutSession->expects($this->atLeastOnce())->method('getQuote')->willReturn($quote);
        $this->quoteRepository->expects($this->atLeastOnce())->method('save')->willThrowException(new \Exception());
        $this->logger->expects($this->atLeastOnce())->method('critical');
        $this->response->expects($this->atLeastOnce())->method('representJson')->willReturn('{}');
        $this->json->expects($this->atLeastOnce())->method('serialize')->willReturn('{}');

        $this->assertNull($this->object->execute());
    }

    /**
     *
     */
    public function testExecuteNotPost()
    {
        $this->request->expects($this->atLeastOnce())->method('isPost')->willReturn(false);
        $this->response->expects($this->atLeastOnce())->method('representJson')->willReturn('{}');
        $this->json->expects($this->atLeastOnce())->method('serialize')->willReturn('{}');
        $this->request->expects($this->never())->method('getParam');
        $this->quantityProcessor->expects($this->never())->method('process');
        $this->assertNull($this->object->execute());
    }

    /**
     *
     */
    public function testExecuteInvalidFormKey()
    {
        $this->request->expects($this->atLeastOnce())->method('isPost')->willReturn(true);
        $this->formKeyValidator->expects($this->atLeastOnce())->method('validate')->willReturn(false);
        $this->response->expects($this->atLeastOnce())->method('representJson')->willReturn('{}');
        $this->json->expects($this->atLeastOnce())->method('serialize')->willReturn('{}');
        $this->quantityProcessor->expects($this->never())->method('process');
        $this->request->expects($this->never())->method('getParam');
        $this->assertNull($this->object->execute());
    }

    /**
     *
     */
    public function testExecuteInvalidCartData()
    {
        $this->request->expects($this->atLeastOnce())->method('isPost')->willReturn(true);
        $this->request->expects($this->atLeastOnce())->method('getParam')->willReturn('test');
        $this->formKeyValidator->expects($this->atLeastOnce())->method('validate')->willReturn(true);
        $this->quantityProcessor->expects($this->never())->method('process');

        $this->assertNull($this->object->execute());
    }

    /**
     *
     */
    protected function setUp(): void
    {
        $this->context = $this->getMockBuilder(Context::class)
                              ->disableOriginalConstructor()
                              ->getMock();
        $this->quantityProcessor = $this->getMockBuilder(RequestQuantityProcessor::class)
                                        ->disableOriginalConstructor()
                                        ->getMock();
        $this->formKeyValidator = $this->getMockBuilder(FormKeyValidator::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();
        $this->checkoutSession = $this->getMockBuilder(CheckoutSession::class)
                                      ->disableOriginalConstructor()
                                      ->getMock();
        $this->json = $this->getMockBuilder(Json::class)
                           ->disableOriginalConstructor()
                           ->getMock();
        $this->logger = $this->getMockBuilder(LoggerInterface::class)
                             ->disableOriginalConstructor()
                             ->getMock();
        $this->quoteRepository = $this->getMockBuilder(CartRepositoryInterface::class)
                                      ->disableOriginalConstructor()
                                      ->getMock();
        $this->request = $this->getMockBuilder(RequestInterface::class)
                              ->disableOriginalConstructor()
                              ->setMethods(['isPost'])
                              ->getMockForAbstractClass();
        $this->response = $this->getMockBuilder(Http::class)
                               ->disableOriginalConstructor()
                               ->getMock();
        $this->context->expects($this->any())->method('getRequest')->willReturn($this->request);
        $this->context->expects($this->any())->method('getResponse')->willReturn($this->response);

        $this->object = (new ObjectManager($this))->getObject(
            UpdateItem::class,
            [
                'context' => $this->context,
                'quantityProcessor' => $this->quantityProcessor,
                'formKeyValidator' => $this->formKeyValidator,
                'checkoutSession' => $this->checkoutSession,
                'json' => $this->json,
                'logger' => $this->logger,
                'quoteRepository' => $this->quoteRepository,
            ]
        );
    }
}
