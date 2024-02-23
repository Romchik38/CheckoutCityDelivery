define([], function () {
    'use strict';

    return {
        /**
         * @return {Object}
         */
        getRules: function () {
            return {
                'city': {
                    'required': true
                }
            };
        }
    };
});