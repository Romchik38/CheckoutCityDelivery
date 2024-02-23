define([
    'uiComponent',
    'Magento_Checkout/js/model/shipping-rates-validator',
    'Magento_Checkout/js/model/shipping-rates-validation-rules',
    '../../model/shipping-rates-validator/citydelivery',
    '../../model/shipping-rates-validation-rules/citydelivery'
], function (
    Component,
    defaultShippingRatesValidator,
    defaultShippingRatesValidationRules,
    citydeliveryRatesValidator,
    citydeliveryRatesValidationRules
) {
    'use strict';
    defaultShippingRatesValidator.registerValidator('citydelivery', citydeliveryRatesValidator);
    defaultShippingRatesValidationRules.registerRules('citydelivery', citydeliveryRatesValidationRules);

    return Component;
});