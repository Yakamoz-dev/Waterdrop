define(
    [
        'jquery',
        'underscore',
        'Magento_Checkout/js/model/quote',
        'Magento_Customer/js/model/customer',
        'Aheadworks_Sarp2Stripe/js/checkout/model/payment/place-order',
        'Aheadworks_Sarp2Stripe/js/checkout/action/create-setup-intent'
    ],
    function (jQuery, _, quote, customer, placeMixedOrderAction, createSetupIntentAction) {
        'use strict';

        return function (renderer) {
            return renderer.extend({
                /**
                 * @inheritdoc
                 */
                hasSavedCards: function() {
                    var totals,
                        hasSavedCards = this._super();

                    totals = quote.totals();
                    if (totals['grand_total'] !== undefined && totals['grand_total'] == 0
                        && (quote.isAwSarp2QuoteMixed() || quote.isAwSarp2QuoteSubscription())
                    ) {
                        hasSavedCards = false;
                    }

                    return hasSavedCards;
                },

                /**
                 * @inheritdoc
                 */
                placeOrder: function () {
                    var self = this,
                        _super = this._super.bind(this);

                    if (self.validate()) {
                        if (quote.isAwSarp2QuoteMixed()
                            || quote.isAwSarp2QuoteSubscription()
                            || window.checkoutConfig.paymentEditMode
                        ) {
                            self.createSetupIntent().done(function() {
                                _super();
                            });
                        } else {
                            return this._super();
                        }
                    }
                },

                /**
                 * Create setup intent
                 * @return {Promise}
                 */
                createSetupIntent: function() {
                    var self = this;

                    return createSetupIntentAction(self).done(function (clientSecret) {
                        if (!_.isEmpty(clientSecret)) {
                            self.config().useSetupIntents = true;
                            self.config().setupIntentClientSecret = clientSecret;
                        } else {
                            self.config().useSetupIntents = false;
                        }
                    });
                },

                /**
                 * @inheritdoc
                 */
                getPlaceOrderDeferredObject: function () {
                    var self = this;

                    if (quote.totals()['grand_total'] > 0) {
                        return this._super();
                    }

                    if (quote.isAwSarp2QuoteMixed() || quote.isAwSarp2QuoteSubscription()) {
                        return placeMixedOrderAction(self);
                    }

                    return this._super();
                }
            });
        }
    }
);
