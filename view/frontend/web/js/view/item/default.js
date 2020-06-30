/*
 * @author     Sashas IT Support <support@sashas.org>
 * @copyright  2020  Sashas IT Support Inc. (http://www.extensions.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

define([
    'uiComponent',
    'ko',
    'jquery',
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/customer-data',
    'TheSGroup_CheckoutCart/js/action/update-cart',
    'Magento_Checkout/js/model/shipping-rate-processor/new-address',
    'Magento_Checkout/js/model/shipping-rate-registry'
], function (
    Component,
    ko,
    $,
    quote,
    customerData,
    updateCartAction,
    defaultShippingProcessor,
    rateRegistry
) {
    'use strict';

    let imageData = window.checkoutConfig.cartImageData;
    let quoteMessages = window.checkoutConfig.quoteMessages;

    return Component.extend({
        defaults: {
            template: 'TheSGroup_CheckoutCart/cart/item/default'
        },
        imageData: imageData,
        quoteMessages: quoteMessages,
        quoteItems: [],

        /**
         * @inheritdoc
         */
        initialize: function () {
            this._super();

            let quoteItemsTemp = quote.getItems();

            for (let i=0; i < quoteItemsTemp.length; i++) {
                let itemId = quoteItemsTemp[i].item_id;

                this.quoteItems[itemId] = quoteItemsTemp[i];
                this.quoteItems[itemId].qty = ko.observable(quoteItemsTemp[i].qty);

                this.quoteItems[itemId].qty.subscribe(function (newQtyValue) {
                    let data = {'cart': {[itemId] : {'qty' : newQtyValue}}};
                    var deferred = $.Deferred();

                    updateCartAction(data, deferred);
                    $.when(deferred).done(function () {
                        customerData.invalidate(['cart']);
                        customerData.reload(['cart'], true);
                        rateRegistry.set(quote.shippingAddress().getCacheKey(), null);
                        defaultShippingProcessor.getRates(quote.shippingAddress());
                    });

                });
            }
            let cartItems = customerData.get(['cart'])().items;

            for (let i=0; i < cartItems.length; i++) {
                this.quoteItems[cartItems[i].item_id].canApplyMsrp = cartItems[i].canApplyMsrp;
                this.quoteItems[cartItems[i].item_id].product_has_url = cartItems[i].product_has_url;
                this.quoteItems[cartItems[i].item_id].product_url = cartItems[i].product_url;
            }
        },

        /**
         * @param {String} quoteItemId
         * @return {Object}
         */
        getQuoteItem: function(quoteItemId) {
            return this.quoteItems[quoteItemId];
        },

        /**
         * @param {Object} quoteItem
         * @return {String}
         */
        getName: function (item) {
            return item.name;
        },

        getId: function (item) {
            return item.item_id;
        },

        checkQtyField: function(data, event) {
            return /^[1-9]*$/.test(event.key);
        },

        getQty: function (itemId) {
            return  this.getQuoteItem(itemId).qty;
        },

        canApplyMsrp: function (item_id) {
            return this.getQuoteItem(item_id).canApplyMsrp;
        },

        hasProductUrl: function (item_id) {
            return this.getQuoteItem(item_id).product_has_url;
        },

        getProductUrl: function (item_id) {
            return this.getQuoteItem(item_id).product_url;
        },

        /**
         * @param {Object} item
         * @return {Array}
         */
        getImageItem: function (item) {
            if (this.imageData[item['item_id']]) {
                return this.imageData[item['item_id']];
            }
            return [];
        },

        /**
         * @param {Object} item
         * @return {null}
         */
        getSrc: function (item) {
            if (this.imageData[item['item_id']]) {
                return this.imageData[item['item_id']].src;
            }
            return null;
        },

        /**
         * @param {Object} item
         * @return {null}
         */
        getWidth: function (item) {
            if (this.imageData[item['item_id']]) {
                return this.imageData[item['item_id']].width;
            }
            return null;
        },

        /**
         * @param {Object} item
         * @return {null}
         */
        getHeight: function (item) {
            if (this.imageData[item['item_id']]) {
                return this.imageData[item['item_id']].height;
            }
            return null;
        },

        /**
         * @param {Object} item
         * @return {null}
         */
        getAlt: function (item) {
            if (this.imageData[item['item_id']]) {
                return this.imageData[item['item_id']].alt;
            }
            return null;
        },

        /**
         * @param {Object} item
         * @return {null}
         */
        getMessage: function (item) {
            if (this.quoteMessages[item['item_id']]) {
                return this.quoteMessages[item['item_id']];
            }
            return null;
        }
    });
});
