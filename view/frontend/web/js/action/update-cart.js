/*
 * @author     Sashas IT Support <support@sashas.org>
 * @copyright  2020  Sashas IT Support Inc. (http://www.extensions.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

define([
    'jquery',
    'Magento_Checkout/js/model/quote',
    'mage/url',
    'Magento_Checkout/js/model/error-processor',
    'mage/storage',
    'Magento_Ui/js/modal/alert',
], function ($, quote, urlBuilder, errorProcessor, storage, alert) {
    'use strict';

    return function (data, deferred) {
        deferred = deferred || $.Deferred();

        $.extend(data, {
            'form_key': $.mage.cookies.get('form_key')
        });

        return $.ajax({
            url: urlBuilder.build('checkoutcart/cart/UpdateItem'),
            data: data,
            type: 'post',
            dataType: 'json',
            context: this,

            /** @inheritdoc */
            beforeSend: function () {
                $(document.body).trigger('processStart');
            },

            /** @inheritdoc */
            complete: function () {
                $(document.body).trigger('processStop');
            }
        }).done(function (response) {
            if (response.success) {
                deferred.resolve();
            } else {
                if (response['error_message']) {
                    alert({
                        content: response['error_message']
                    });
                }
                deferred.reject();
                errorProcessor.process(response);
            }
        }).fail(function () {
            deferred.reject();
            errorProcessor.process(response);
        });
    };
});
