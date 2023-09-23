<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['privacy_policy']     = 'home/privacy_policy';
//$route['terms_and_condition'] = 'home/terms_and_condition';
$route['userchangepassword/(:any)'] = 'userAuthentication/checkResetPwdLink/$1';
$route['terms_and_condition/(:any)'] = 'home/terms_and_condition/$1';
$route['404_override'] = 'home/error';
$route['sitemap.xml'] = 'home/sitemap';

//MAAk APIS  :START

$route['processRegistration'] = 'Apis_1/processRegistration';
$route['processLogin'] = 'Apis_1/processLogin';
$route['deleteAccount'] = 'Apis_1/deleteAccount';
$route['logout'] = 'Apis_1/logout';
$route['getCollection'] = 'Apis_1/getCollection';
$route['getBrandsData'] = 'Apis_1/getBrandsData';
$route['getCategoryData'] = 'Apis_1/getCategoryData';
$route['getSubCategoryData'] = 'Apis_1/getSubCategoryData';
$route['getAllProducts'] = 'Apis_1/getAllProducts';
$route['processAddCards'] = 'Apis_1/processAddCards';
$route['processDeleteCards'] = 'Apis_1/processDeleteCards';
$route['fetchUserCardData'] = 'Apis_1/fetchUserCardData';
$route['saveNewsletter'] = 'Apis_1/saveNewsletter';
$route['saveEnquiry'] = 'Apis_1/saveEnquiry';
$route['validateCoupon'] = 'Apis_1/validateCoupon';
$route['reorderProduct'] = 'Apis_1/reorderProduct';
$route['updateCartProduct'] = 'Apis_1/updateCartProduct';
$route['checkProductStock'] = 'Apis_1/checkProductStock';
$route['addToOrder'] = 'Apis_1/addToOrder';
$route['sendNotification'] = 'Apis_1/sendNotification';  //for testting
$route['refreshFcmToken'] = 'Apis_1/refreshFcmToken';
$route['getOrders'] = 'Apis_1/getOrders';
$route['siteSetting'] = 'Apis_1/siteSetting';
$route['updateCustomerInfo'] = 'Apis_1/updateCustomerInfo';
$route['updateCustomerAddress'] = 'Apis_1/updateCustomerAddress';
$route['processForgotPassword'] = 'Apis_1/processForgotPassword';
$route['requestOtp'] = 'Apis_1/requestOtp';
$route['validateOtp'] = 'Apis_1/validateOtp';
$route['changePassword'] = 'Apis_1/changePassword';
$route['forgetPassword'] = 'Apis_1/forgetPassword';
$route['getTimeSlots'] = 'Apis_1/getTimeSlots';
$route['getCity'] = 'Apis_1/getCity';
$route['getBanner'] = 'Apis_1/getBanner';
$route['getArea'] = 'Apis_1/getArea';
$route['fetchUserAddress'] = 'Apis_1/fetchUserAddress';
$route['saveUserAddress'] = 'Apis_1/saveUserAddress';
$route['deleteUserAddress'] = 'Apis_1/deleteUserAddress';
$route['cancelOrder'] = 'Apis_1/cancelOrder';
$route['validateRegisterOtp'] = 'Apis_1/validateRegisterOtp';
$route['getPaymentCharges'] = 'Apis_1/getPaymentCharges';
$route['printOrderReceipt'] = 'Apis_1/printOrderReceipt';
$route['checkVersion'] = 'Apis_1/checkVersion';
$route['addMoneyToWallet'] = 'Apis_1/addMoneyToWallet';
$route['fetchWalletTransaction'] = 'Apis_1/fetchWalletTransaction';
$route['saveEnquiryForm'] = 'Apis_1/saveEnquiryForm';
$route['fetchEnquiryList'] = 'Apis_1/fetchEnquiryList';
$route['fetchEnquiryMsgList'] = 'Apis_1/fetchEnquiryMsgList';
$route['replyEnquiry'] = 'Apis_1/replyEnquiry';
$route['getJobType'] = 'Apis_1/getJobType';
$route['getSocialStatus'] = 'Apis_1/getSocialStatus';
$route['getCoupon'] = 'Apis_1/getCoupon';
$route['getAllStores'] = 'Apis_1/getAllStores';
$route['saveLoyaltyCardNo'] = 'Apis_1/saveLoyaltyCardNo';
$route['collectionApi'] = 'Apis_1/collectionApi';
$route['addSuggestedProducts'] = 'Apis_1/addSuggestedProducts';
$route['viewCustomerProfile'] = 'Apis_1/viewCustomerProfile';
$route['updateCustomerProfile'] = 'Apis_1/updateCustomerProfile';
$route['editCart'] = 'Apis_1/editCart';
$route['deleteCart'] = 'Apis_1/deleteCart';
$route['cartListing'] = 'Apis_1/cartListing';
$route['checkout'] = 'Apis_1/checkout';
$route['orderAgain'] = 'Apis_1/orderAgain';
$route['similarProducts'] = 'Apis_1/similarProducts';
$route['getContactDetails'] = 'Apis_1/getContactDetails';
$route['saveContactDetails'] = 'Apis_1/saveContactDetails';
$route['profile_options'] = 'Apis_1/profile_options';
$route['saveRating'] = 'Apis_1/saveRating';
$route['footers'] = 'Apis_1/footers';
//added for APIS : end


// added for Delivery boy APIS : Start
$route['deliveryBoyLogin'] = 'Delivery_boy_apis/processLogin';
$route['viewProfile'] = 'Delivery_boy_apis/viewProfile';
$route['deliveryBoyChangePassword'] = 'Delivery_boy_apis/processChangePassword';
$route['deliveryBoyRequestOtp'] = 'Delivery_boy_apis/requestOtp';
$route['deliveryBoyValidateOtp'] = 'Delivery_boy_apis/validateOtp';
$route['deliveryBoyForgetPassword'] = 'Delivery_boy_apis/forgetPassword';
$route['deliveryBoyOrderStatusCount'] = 'Delivery_boy_apis/orderStatusCount';
$route['deliveryBoyGetOrders'] = 'Delivery_boy_apis/getOrders';
$route['deliveryBoyGetOrdersHistory'] = 'Delivery_boy_apis/getOrdersHistory';
$route['deliveryBoyGetOrdersRevenue'] = 'Delivery_boy_apis/getOrdersRevenue';
$route['deliveryBoyVerifyDeliveryCode'] = 'Delivery_boy_apis/verifyDeliveryCode';
$route['deliveryBoyGetPDF'] = 'Delivery_boy_apis/getPDF';
$route['deliveryBoyRefreshToken'] = 'Delivery_boy_apis/refreshFcmToken';
$route['deliveryBoyTriggerNotification'] = 'Delivery_boy_apis/triggerNotification';
$route['deliveryBoyInvoice/(:any)'] = 'Delivery_boy_apis/paintInvoice/$1';
// added for Delivery boy APIS : End

$route['translate_uri_dashes'] = FALSE;
