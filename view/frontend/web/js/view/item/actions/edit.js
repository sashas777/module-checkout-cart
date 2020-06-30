/*
 * @author     Sashas IT Support <support@sashas.org>
 * @copyright  2020  Sashas IT Support Inc. (http://www.extensions.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

define([
    'uiComponent',
    'Magento_Customer/js/customer-data'
], function (Component, customerData) {
    'use strict';

    return Component.extend({

        displayArea: 'row_actions',

        isVisibleInSiteVisibility: function (item_id) {
            return this.getItemById(item_id).is_visible_in_site_visibility;
        },

        getConfigureUrl: function (item_id) {
            return this.getItemById(item_id).configure_url;
        },

        getItemById: function(item_id) {
            let cartItems = customerData.get(['cart'])().items;

            for (let i=0; i < cartItems.length; i++) {
                if (cartItems[i].item_id == item_id)  //eslint-disable-line eqeqeq
                    return cartItems[i];
            }
        }
    });
});
