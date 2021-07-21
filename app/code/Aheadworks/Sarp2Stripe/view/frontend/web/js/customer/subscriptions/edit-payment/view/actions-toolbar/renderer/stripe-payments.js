define([
    'jquery',
    'Aheadworks_Sarp2/js/customer/subscriptions/edit-payment/view/actions-toolbar/renderer/default',
    'uiRegistry',
    'Magento_Checkout/js/model/payment/additional-validators',
    'Aheadworks_Sarp2Stripe/js/customer/subscriptions/edit-payment/action/save-payment',
], function ($, Component, registry, additionalValidators, savePaymentAction) {
    'use strict';

    return Component.extend({

        /**
         * @inheritdoc
         */
        validate: function (component) {
            return this._super(component) && additionalValidators.validate();
        },

        /**
         * @inheritDoc
         */
        initMethodsRenderComponent: function () {
            if (this.methodCode) {
                this.methodRendererComponent = registry.get('payment.payments-list.' + this.methodCode);
                this.isSaveActionAllowed = this.methodRendererComponent.isPlaceOrderActionAllowed;
                this.methodRendererComponent.getPlaceOrderDeferredObject = this.applySaveAction.bind(this);
                this.methodRendererComponent.redirectAfterPlaceOrder = true;
            }

            return this;
        },

        /**
         * Apply save action instead of place order for payment component
         *
         * @returns {boolean}
         */
        applySaveAction: function () {
            var self = this,
                paymentComponent = this._getMethodRenderComponent(),
                data = paymentComponent.getData();

            return savePaymentAction(data, paymentComponent.messageContainer)
                .always(function () {
                    self.isSaveActionAllowed(true);
                });
        },
    });
});
