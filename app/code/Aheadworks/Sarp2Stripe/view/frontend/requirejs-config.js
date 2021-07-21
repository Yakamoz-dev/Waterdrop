var config = {
    map: {
        '*': {
            awSarp2StripeCardsChecker: 'Aheadworks_Sarp2Stripe/js/customer/stripe-cards-checker'
        }
    },
    config: {
        mixins: {
            'StripeIntegration_Payments/js/view/payment/method-renderer/stripe_payments': {
                'Aheadworks_Sarp2Stripe/js/checkout/view/payment-method/renderer/stripe-payments-mixin': true
            },
        }
    }
};
