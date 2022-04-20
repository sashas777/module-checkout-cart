<?php
/**
 * @author     Sashas IT Support <support@sashas.org>
 * @copyright  2020  Sashas IT Support Inc. (http://www.extensions.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */
declare(strict_types=1);

namespace TheSGroup\CheckoutCart\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class RemoveBlock
 * Observer removes default items block
 */
class RemoveBlock implements ObserverInterface
{
    public const ACTIVE_PATH = 'thesgroup_checkoutcart/checkoutcart_group/active';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * RemoveBlock constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Observer to remove block
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Framework\View\Element\Template $block */
        $block = $observer->getBlock();
        if ($block->getNameInLayout() == 'checkout.cart.form' &&
            $this->scopeConfig->getValue(static::ACTIVE_PATH, ScopeInterface::SCOPE_STORE)
        ) {
            $block->setTemplate(false);
        }
    }
}
