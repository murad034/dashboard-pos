<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'App\Http\Controllers\HomeController@index');
// ajax get data to dashboar
Route::get('/dashboard/api', 'App\Http\Controllers\Dashboard\DashboardController@getData');

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::get('/config', 'ConfigController@index')->name('config');
    Route::post('/config/update', 'ConfigController@update')->name('config.update');

    Route::post('/config/update-token', 'ConfigController@updateToken');

    Route::post('/config/update-signature', 'ConfigController@updateSignature');

    Route::post('/config/verify-mail', 'ConfigController@verifyMail');
    Route::get('/config/mail-confirm', 'ConfigController@mailConfirm');
});


// Profile page

Route::group(['namespace' => 'App\Http\Controllers\Profile'], function () {
    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::put('/profile/update/profile/{id}', 'ProfileController@updateProfile')->name('profile.update.profile');
    Route::put('/profile/update/password/{id}', 'ProfileController@updatePassword')->name('profile.update.password');
    Route::put('/profile/update/avatar/{id}', 'ProfileController@updateAvatar')->name('profile.update.avatar');

});

// Logs Page
Route::group(['namespace' => 'App\Http\Controllers\Log'], function () {
    Route::get('/logs', 'LogController@index');
    Route::get('/logs/api', 'LogController@show');

});

Route::group(['namespace' => 'App\Http\Controllers\Error'], function () {
    Route::get('/unauthorized', 'ErrorController@unauthorized')->name('unauthorized');
});

Route::group(['namespace' => 'App\Http\Controllers\User'], function () {
    //Users
    Route::get('/user', 'UserController@index')->name('user');
    Route::get('/user/create', 'UserController@create')->name('user.create');
    Route::post('/user/store', 'UserController@store')->name('user.store');
    Route::get('/user/edit/{id}', 'UserController@edit')->name('user.edit');
    Route::put('/user/update/{id}', 'UserController@update')->name('user.update');
    Route::get('/user/edit/password/{id}', 'UserController@editPassword')->name('user.edit.password');
    Route::put('/user/update/password/{id}', 'UserController@updatePassword')->name('user.update.password');
    Route::get('/user/show/{id}', 'UserController@show')->name('user.show');
    Route::get('/user/destroy/{id}', 'UserController@destroy')->name('user.destroy');
    // Roles
    Route::get('/role', 'RoleController@index')->name('role');
    Route::get('/role/create', 'RoleController@create')->name('role.create');
    Route::post('/role/store', 'RoleController@store')->name('role.store');
    Route::get('/role/edit/{id}', 'RoleController@edit')->name('role.edit');
    Route::put('/role/update/{id}', 'RoleController@update')->name('role.update');
    Route::get('/role/show/{id}', 'RoleController@show')->name('role.show');
    Route::get('/role/destroy/{id}', 'RoleController@destroy')->name('role.destroy');

    // set user brand
    Route::put('/user/brand/{id}', 'UserController@updateBrand');
    Route::post('/user/brand', 'UserController@addBrand');
});

// products page
Route::group(['namespace' => 'App\Http\Controllers\Product\Product'], function () {
//    product page CRUD function
    Route::get('/products', 'ProductController@index')->name('product');
//    get ajax api call route
    Route::get('/products/builder-api', 'ProductController@getStock');
    Route::get('/products/api', 'ProductController@getProduct');
    Route::post('/products/api', 'ProductController@saveProduct');
    Route::post('/products/api/save-stock', 'ProductController@saveStock');
    Route::post('/products/cost-api', 'ProductController@calculateCost');
    Route::delete('/products/api/{id}', 'ProductController@destroy');
    Route::put('/products/api/{id}', 'ProductController@update');
    Route::post('/products/save-img', 'ProductController@saveImage');
//    import csv and upload
    Route::post('/products/upload', 'CSVController@uploadCSV');
    Route::post('/products/import', 'CSVController@importCSV');
//    get web info for product
    Route::get('/products/web/api', 'ProductController@getProductWeb');
    Route::get('/products/api/getAllAjax', 'ProductController@getAllAjax');
});

// products categories page
Route::group(['namespace' => 'App\Http\Controllers\Product\Category'], function () {

    Route::get('/product-categories', 'CategoryController@index');
    Route::post('/product-categories/cat-api', 'CategoryController@saveMainCategory');
    Route::get('/product-categories/cat-api', 'CategoryController@getMainCategory');
    Route::put('/product-categories/cat-api/{id}', 'CategoryController@editMainCategory');
    Route::delete('/product-categories/cat-api/{id}', 'CategoryController@deleteMainCategory');

    Route::post('/product-categories/sub-api', 'CategoryController@saveSubCategory');
    Route::get('/product-categories/sub-api', 'CategoryController@getSubCategory');
    Route::put('/product-categories/sub-api/{id}', 'CategoryController@editSubCategory');
    Route::delete('/product-categories/sub-api/{id}', 'CategoryController@deleteSubCategory');

//    import csv and upload
    Route::post('/product-categories/cat-upload', 'CSVController@uploadMainCSV');
    Route::post('/product-categories/cat-import', 'CSVController@importMainCSV');
    Route::post('/product-categories/sub-upload', 'CSVController@uploadSubCSV');
    Route::post('/product-categories/sub-import', 'CSVController@importSubCSV');
});

// Bulk Pricing Edit page
Route::group(['namespace' => 'App\Http\Controllers\Product\BulkEdit'], function () {

    Route::get('/bulk-edit', 'BulkEditController@index');
    Route::get('/bulk-edit/api', 'BulkEditController@getProducts');
    Route::post('/bulk-edit/api', 'BulkEditController@saveProducts');
    Route::post('/bulk-edit/draft', 'BulkEditController@saveProductsDraft');
    Route::post('/bulk-edit/draft_schedule', 'BulkEditController@saveProductsDraftSchedule');

});

// Stock page
Route::group(['namespace' => 'App\Http\Controllers\Stock\Stock'], function () {

    Route::get('/stocks', 'StockController@index');
    Route::post('/stocks/api', 'StockController@saveStock');
    Route::get('/stocks/api', 'StockController@getStock');
    Route::put('/stocks/api/{id}', 'StockController@editStock');
    Route::delete('/stocks/api/{id}', 'StockController@deleteStock');

//    import csv and upload
    Route::post('/stocks/upload', 'CSVController@uploadCSV');
    Route::post('/stocks/import', 'CSVController@importCSV');
});

// stocks categories page
Route::group(['namespace' => 'App\Http\Controllers\Stock\Category'], function () {

    Route::get('/stock-categories', 'CategoryController@index');
    Route::post('/stock-categories/cat-api', 'CategoryController@saveMainCategory');
    Route::get('/stock-categories/cat-api', 'CategoryController@getMainCategory');
    Route::put('/stock-categories/cat-api/{id}', 'CategoryController@editMainCategory');
    Route::delete('/stock-categories/cat-api/{id}', 'CategoryController@deleteMainCategory');

    Route::post('/stock-categories/sub-api', 'CategoryController@saveSubCategory');
    Route::get('/stock-categories/sub-api', 'CategoryController@getSubCategory');
    Route::put('/stock-categories/sub-api/{id}', 'CategoryController@editSubCategory');
    Route::delete('/stock-categories/sub-api/{id}', 'CategoryController@deleteSubCategory');

//    import csv and upload
    Route::post('/stock-categories/cat-upload', 'CSVController@uploadMainCSV');
    Route::post('/stock-categories/cat-import', 'CSVController@importMainCSV');
    Route::post('/stock-categories/sub-upload', 'CSVController@uploadSubCSV');
    Route::post('/stock-categories/sub-import', 'CSVController@importSubCSV');
});

// Purchase Order Page
Route::group(['namespace' => 'App\Http\Controllers\Stock\Purchase'], function () {
    Route::get('/purchase', 'PurchaseController@index');
    Route::get('/purchase/api', 'PurchaseController@show');
    Route::post('/purchase/api', 'PurchaseController@store');
    Route::put('/purchase/api/{id}', 'PurchaseController@update');
    Route::delete('/purchase/api/{id}', 'PurchaseController@destroy');

});

// Receive Stock Page
Route::group(['namespace' => 'App\Http\Controllers\Stock\Receive'], function () {

    Route::get('/receive-stock', 'ReceiveController@index');
    Route::get('/receive-stock/api', 'ReceiveController@getLocation');
    Route::post('/receive-stock/api', 'ReceiveController@saveReceiveStock');
    Route::get('/receive-stock/data-api', 'ReceiveController@getReceiveStock');

});



// Stock Take Page
Route::group(['namespace' => 'App\Http\Controllers\Stock\Take'], function () {
    Route::get('/stock-take', 'TakeController@index');
});

// Order Keypad Designer page
Route::group(['namespace' => 'App\Http\Controllers\Designer\OrderKeypad'], function () {
    Route::get('/order-keypad-designer', 'OrderKeypadController@index');
    Route::post('/order-keypad-designer/api', 'OrderKeypadController@saveKeyPad');
    Route::get('/order-keypad-designer/api', 'OrderKeypadController@getAllKeyPad');
    Route::get('/order-keypad-designer/api/{id}', 'OrderKeypadController@getKeyPad');
    Route::put('/order-keypad-designer/api/{id}', 'OrderKeypadController@editKeyPad');

//    get builder page
    Route::get('/order-keypad-designer/builder', 'BuilderController@index');
    Route::post('/order-keypad-designer/builder/push_multiple_json', 'BuilderController@pushMultipleJson');
    Route::post('/order-keypad-designer/builder/clone_layout', 'BuilderController@cloneLayout');
    Route::post('/order-keypad-designer/builder/add_layout', 'BuilderController@addLayout');
    Route::get('/order-keypad-designer/builder/show_products', 'BuilderController@showProducts');
    Route::get('/order-keypad-designer/builder/show_functions', 'BuilderController@showFunctions');
    Route::get('/order-keypad-designer/builder/pull_json/{id}', 'BuilderController@pullJson');
    Route::post('/order-keypad-designer/builder/push_json', 'BuilderController@pushJson');
    Route::get('/order-keypad-designer/builder/load_clone_layout_list', 'BuilderController@loadCloneLayoutList');
    Route::delete('/order-keypad-designer/builder/delay_out/{id}', 'BuilderController@delayOut');
    Route::post('/order-keypad-designer/builder/push_draft', 'BuilderController@pushDraft');
    Route::post('/order-keypad-designer/builder/push_draft_schedule', 'BuilderController@pushDraftSchedule');
});

// media board designer page
Route::group(['namespace' => 'App\Http\Controllers\Designer\MediaBoard'], function () {
    Route::get('/media-board-designer', 'MediaBoardController@index');
    Route::get('/media-board-designer/api', 'MediaBoardController@getOrderKeyPad');
    Route::post('/media-board-designer/api', 'MediaBoardController@saveOrderKeyPad');
    Route::put('/media-board-designer/api/{id}', 'MediaBoardController@putOrderKeyPad');
    Route::delete('/media-board-designer/api/{id}', 'MediaBoardController@deleteOrderKeyPad');
// get builder page
    Route::get('/media-board-designer/builder', 'BuilderController@index');
});

// media board designer page
Route::group(['namespace' => 'App\Http\Controllers\Designer\CustomerReceipt'], function () {
    Route::get('/customer-receipt-designer', 'CustomerReceiptController@index');
    Route::get('/customer-receipt-designer/api', 'CustomerReceiptController@show');
    Route::post('/customer-receipt-designer/api', 'CustomerReceiptController@create');
    Route::put('/customer-receipt-designer/api/{id}', 'CustomerReceiptController@edit');
    Route::delete('/customer-receipt-designer/api/{id}', 'CustomerReceiptController@destroy');
// get builder page
    Route::get('/customer-receipt-designer/editor', 'BuilderController@index');
    Route::get('/customer-receipt-designer/editor/edit-api/{id}', 'BuilderController@getBuilder');
    Route::post('/customer-receipt-designer/editor/edit-api', 'BuilderController@saveBuilder');
    Route::post('/customer-receipt-designer/editor/edit-api/draft', 'BuilderController@saveDraftBuilder');
    Route::post('/customer-receipt-designer/editor/edit-api/draft-scheduleat', 'BuilderController@saveDraftWithScheduleAtBuilder');
});

// email marketing designer page
Route::group(['namespace' => 'App\Http\Controllers\Designer\EmailMarketing'], function () {
    Route::get('/email-marketing-designer', 'EmailMarketingController@index');
    Route::get('/email-marketing-designer/api', 'EmailMarketingController@show');
    Route::post('/email-marketing-designer/api', 'EmailMarketingController@create');
    Route::put('/email-marketing-designer/api/{id}', 'EmailMarketingController@edit');
    Route::delete('/email-marketing-designer/api/{id}', 'EmailMarketingController@destroy');
// get builder page
    Route::get('/email-marketing-designer/editor', 'BuilderController@index');
    Route::get('/email-marketing-designer/editor/edit-api/{id}', 'BuilderController@getBuilder');
    Route::post('/email-marketing-designer/editor/edit-api', 'BuilderController@saveBuilder');
});

// Order Payment Station Page
Route::group(['namespace' => 'App\Http\Controllers\Station\OrderPayment'], function () {
    Route::get('/order-payment-station', 'OrderPaymentController@index');
    Route::get('/order-payment-station/get-table', 'OrderPaymentController@getTable');
    Route::get('/order-payment-station/api/{id}', 'OrderPaymentController@show');
    Route::post('/order-payment-station/api', 'OrderPaymentController@store');
    Route::put('/order-payment-station/api/{id}', 'OrderPaymentController@edit');
    Route::delete('/order-payment-station/api/{id}', 'OrderPaymentController@destroy');

    Route::middleware('auth:api')->post('/order-payment-station/api/ping', 'OrderPaymentController@terminalsPing');

    Route::get('/order-payment-station/table-api', 'OrderPaymentController@showKeyPad');
});



// Order Make Station Page
Route::group(['namespace' => 'App\Http\Controllers\Station\OrderMake'], function () {
    Route::get('/order-make-station', 'OrderMakeController@index');
    Route::get('/order-make-station/api', 'OrderMakeController@show');
    Route::post('/order-make-station/api', 'OrderMakeController@store');
    Route::put('/order-make-station/api/{id}', 'OrderMakeController@update');
    Route::delete('/order-make-station/api/{id}', 'OrderMakeController@destroy');

});

// Media Display Station Page
Route::group(['namespace' => 'App\Http\Controllers\Station\MediaDisplay'], function () {
    Route::get('/media-display-station', 'MediaDisplayController@index');
    Route::get('/media-display-station/api', 'MediaDisplayController@show');
    Route::post('/media-display-station/api', 'MediaDisplayController@store');
    Route::put('/media-display-station/api/{id}', 'MediaDisplayController@update');
    Route::delete('/media-display-station/api/{id}', 'MediaDisplayController@destroy');

});

// Custom Keypad Page
Route::group(['namespace' => 'App\Http\Controllers\CustomKeypad'], function () {
    Route::get('/custom-keypad-keys', 'CustomKeypadController@index');
    Route::get('/custom-keypad-keys/api', 'CustomKeypadController@show');
    Route::post('/custom-keypad-keys/api', 'CustomKeypadController@store');
    Route::put('/custom-keypad-keys/api/{id}', 'CustomKeypadController@update');
    Route::delete('/custom-keypad-keys/api/{id}', 'CustomKeypadController@destroy');

});

// Promos Page
Route::group(['namespace' => 'App\Http\Controllers\Promo'], function () {
    Route::get('/promos', 'PromoController@index');
    Route::get('/promos/api', 'PromoController@show');
    Route::post('/promos/api', 'PromoController@store');
    Route::put('/promos/api/{id}', 'PromoController@update');
    Route::delete('/promos/api/{id}', 'PromoController@destroy');

});

// Quotes Page
Route::group(['namespace' => 'App\Http\Controllers\Quote'], function () {
    Route::get('/pos', 'QuoteController@index');
    Route::get('/pos/api', 'QuoteController@show');
    Route::post('/pos/api', 'QuoteController@store');
    Route::put('/pos/api/{id}', 'QuoteController@update');
    Route::delete('/pos/api/{id}', 'QuoteController@destroy');
    Route::post('/pos/pdf/download', 'QuoteController@pdfDownload');
});


// Customer Page
Route::group(['namespace' => 'App\Http\Controllers\Customer'], function () {
    Route::get('/customers', 'CustomerController@index');
    Route::get('/customers/api', 'CustomerController@show');
    Route::post('/customers/api', 'CustomerController@store');
    Route::put('/customers/api/{id}', 'CustomerController@update');
    Route::delete('/customers/api/{id}', 'CustomerController@destroy');
    Route::get('/customers/history', 'CustomerController@showHistory');
//    import csv and upload
    Route::post('/customers/upload', 'CSVController@uploadCSV');
    Route::post('/customers/import', 'CSVController@importCSV');
});

// Marketing Page
Route::group(['namespace' => 'App\Http\Controllers\Marketing\Marketing'], function () {
    Route::get('/marketing', 'MarketingController@index');
    Route::get('/marketing/api', 'MarketingController@show');
    Route::post('/marketing/api', 'MarketingController@store');
    Route::put('/marketing/api/{id}', 'MarketingController@update');
    Route::delete('/marketing/api/{id}', 'MarketingController@destroy');
    Route::post('/marketing/send_mail', 'MarketingController@sendEmail');
    Route::post('/marketing/check_open', 'MarketingController@checkOpens');
    Route::get('/marketing/get_log', 'MarketingController@getLog');

});

// Tag Page
Route::group(['namespace' => 'App\Http\Controllers\Marketing\Tag'], function () {
    Route::get('/tags', 'TagController@index');
    Route::get('/tags/api', 'TagController@show');
    Route::post('/tags/api', 'TagController@store');
    Route::put('/tags/api/{id}', 'TagController@update');
    Route::delete('/tags/api/{id}', 'TagController@destroy');

});

// Automation Page
Route::group(['namespace' => 'App\Http\Controllers\Marketing\Automation'], function () {
    Route::get('/automation', 'AutomationController@index');
    Route::get('/automation/api', 'AutomationController@show');
    Route::post('/automation/api', 'AutomationController@store');
    Route::put('/automation/api/{id}', 'AutomationController@update');
    Route::delete('/automation/api/{id}', 'AutomationController@destroy');

    // get builder page
    Route::get('/automation/builder', 'BuilderController@index');
    Route::get('/automation/builder-api', 'BuilderController@getBuilder');
    Route::post('/automation/builder-api', 'BuilderController@saveBuilder');

});

// Roster Page
Route::group(['namespace' => 'App\Http\Controllers\Roster'], function () {
    Route::get('/rosters', 'RosterController@index');
    Route::get('/rosters/api', 'RosterController@show');
    Route::post('/rosters/api', 'RosterController@store');
    Route::put('/rosters/api/{id}', 'RosterController@update');

    Route::get('/rosters/builder', 'BuilderController@index');
    Route::get('/rosters/table', 'BuilderController@getTable');
    Route::get('/rosters/get-resource', 'BuilderController@getResources');
    Route::get('/rosters/staff', 'BuilderController@getSingleStaffRosterPage');
    Route::post('/rosters/builder-api', 'BuilderController@store');
    Route::put('/rosters/builder-api/{id}', 'BuilderController@update');
    Route::delete('/rosters/builder-api/{id}', 'BuilderController@destroy');
});

// Report Page
Route::group(['namespace' => 'App\Http\Controllers\Report'], function () {
    Route::get('/reports', 'ReportController@index');
});

// Locations Page
Route::group(['namespace' => 'App\Http\Controllers\Location'], function () {
    Route::get('/locations', 'LocationController@index');
    Route::get('/locations/api', 'LocationController@show');
    Route::post('/locations/api', 'LocationController@store');
    Route::post('/locations/api/{id}', 'LocationController@update');
    Route::delete('/locations/api/{id}', 'LocationController@destroy');

    //    import csv and upload
    Route::post('/locations/upload', 'CSVController@uploadCSV');
    Route::post('/locations/import', 'CSVController@importCSV');

});

// Suppliers Page
Route::group(['namespace' => 'App\Http\Controllers\Supplier'], function () {
    Route::get('/suppliers', 'SupplierController@index');
    Route::get('/suppliers/api', 'SupplierController@show');
    Route::post('/suppliers/api', 'SupplierController@store');
    Route::put('/suppliers/api/{id}', 'SupplierController@update');
    Route::delete('/suppliers/api/{id}', 'SupplierController@destroy');

    //    import csv and upload
    Route::post('/suppliers/upload', 'CSVController@uploadCSV');
    Route::post('/suppliers/import', 'CSVController@importCSV');

});

// Staff Page
Route::group(['namespace' => 'App\Http\Controllers\Staff'], function () {
    Route::get('/staff', 'StaffController@index');
    Route::get('/staff/api', 'StaffController@show');
    Route::post('/staff/api', 'StaffController@store');
    Route::put('/staff/api/{id}', 'StaffController@update');
    Route::delete('/staff/api/{id}', 'StaffController@destroy');

});
