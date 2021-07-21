define([
    'underscore',
    'jquery',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/full-screen-loader',
    'mageUtils',
], function (_, $, quote, fullScreenLoader, utils) {
    'use strict';

    /**
     * Retrieve and prepare address object
     * @return {*}
     */
    function getBillingAddress() {
        var address = {};

        _.each(quote.billingAddress(), function (value, key) {
            if (!_.isFunction(value)) {
                address[key] = value;
            }
        });

        return utils.serialize(address);
    }

    return function (paymentData, messageContainer) {
        var settings = {
                global: false, // prevent ajax complete event and refresh sections
                url: window.checkoutConfig.stripeSavePaymentUrl,
                method: 'POST',
                dataType: 'json',
                data: {
                    form_key: window.FORM_KEY,
                    billing_address: getBillingAddress(),
                    payment: paymentData
                }
            };

        fullScreenLoader.startLoader();

        return $.ajax(settings)
            .fail(function (response) {
                if (!stripe.isAuthenticationRequired(response.responseJSON.message)) {
                    messageContainer.addErrorMessage({message: response.responseJSON.message});
                }
            })
            .always(function () {
                fullScreenLoader.stopLoader();
            });
    };
});
