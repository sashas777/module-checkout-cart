<?php
/**
 * @author     Sashas IT Support <support@sashas.org>
 * @copyright  2020  Sashas IT Support Inc. (http://www.extensions.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */
declare(strict_types=1);

namespace TheSGroup\CheckoutCart\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use TheSGroup\CheckoutCart\Model\Cart\ImageProvider;

/**
 * Class CheckoutCartConfigProvider
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 * Configuration provider class
 */
class CheckoutCartConfigProvider implements ConfigProviderInterface
{
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var ImageProvider
     */
    private $imageProvider;

    /**
     * CheckoutCartConfigProvider constructor.
     *
     * @param CheckoutSession $checkoutSession
     * @param ImageProvider $imageProvider
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        ImageProvider $imageProvider
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->imageProvider = $imageProvider;
    }
    /**
     * Get configuration
     *
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $quote = $this->checkoutSession->getQuote();
        $quoteId = $quote->getId();
        return [
            'cartImageData' => $this->imageProvider->getImages($quoteId)
        ];
    }
}
