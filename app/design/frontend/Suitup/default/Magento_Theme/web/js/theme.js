/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mage/smart-keyboard-handler',
    'mage/mage',
    'domReady!'
], function ($, keyboardHandler) {
    'use strict';
    /* ========================================== 
	Account Logins
	========================================== */
	$('.links:has(li.customer-welcome)').addClass('account-login');
});
