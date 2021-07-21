define([
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/url-builder',
    'mage/storage',
    'Magento_Checkout/js/model/error-processor',
    'Magento_Checkout/js/model/full-screen-loader'
], function (
    quote,
    urlBuilder,
    storage,
    errorProcessor,
    fullScreenLoader
) {
    'use strict';

    return function (methodRenderer) {
        var serviceUrl, payload = {};

        if (window.checkoutConfig.paymentEditMode) {
            // on profile page
            serviceUrl = urlBuilder.createUrl('/awSarp2/stripe/profile/setup-intent/', {});
            payload = {
                profile_id: window.checkoutConfig.profileId || 0
            }
        } else {
            // on checkout
            serviceUrl = urlBuilder.createUrl('/awSarp2/stripe/quote/setup-intent/', {});
            payload = {
                email: quote.guestEmail || ''
            };
        }

        fullScreenLoader.startLoader();

        return storage.post(serviceUrl, JSON.stringify(payload)).fail(
            function (response) {
                errorProcessor.process(response, methodRenderer.messageContainer);
            }
        ).always(
            function () {
                fullScreenLoader.stopLoader();
            }
        );
    };
});
