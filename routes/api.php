<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::middleware('json.response')->group(function () {

    // public routes
    Route::post('/login', 'Api\PassportController@login')->name('login');
    Route::post('/register', 'Api\PassportController@register')->name('register');

    // private routes
    Route::middleware('auth')->group(function () {

        Route::get('/logout', 'Api\PassportController@logout')->name('logout');

        //User
        Route::get('/users/shop', 'Api\UserController@hasShop');

        //Views
        Route::get('/myviews', 'Api\ViewController@myViews');

        //Shop Followers
        Route::get('/myfollowers', 'Api\ShopFollowerController@myFollowers');
        Route::get('/follow/{id}', 'Api\ShopFollowerController@follow');
        Route::get('/followed', 'Api\ShopFollowerController@index');

        //Shop
        Route::post('/shop', 'Api\ShopController@store');
        Route::post('/updateshop', 'Api\ShopController@update');
        Route::delete('/shops/{id}', 'Api\ShopController@destroy');
        Route::get('/myshop', 'Api\ShopController@myShop');

        //Promotions
        Route::post('/promotion', 'Api\PromotionController@store');
        Route::get('/mypromotion', 'Api\PromotionController@myPromotion');

        //Posts
        Route::get('/myposts', 'Api\PostController@myPosts');
        Route::post('/posts', 'Api\PostController@store');
        Route::post('/product_post', 'Api\PostController@productPost');
        Route::post('/posts/{id}', 'Api\PostController@update');
        Route::delete('/posts/{id}', 'Api\PostController@destroy');

        //Products
        Route::get('/myproducts', 'Api\ProductController@myProducts');
        Route::post('/products/discount', 'Api\ProductController@setDiscount');
        Route::post('/products', 'Api\ProductController@store');
        Route::post('/products/{id}', 'Api\ProductController@update');
        Route::delete('/products/{id}', 'Api\ProductController@destroy');

        //Faqs
        Route::get('/faqs', 'Api\FaqController@index');
        Route::post('/faqs', 'Api\FaqController@store');
        Route::post('/faqs/{id}', 'Api\FaqController@update');
        Route::delete('/faqs/{id}', 'Api\FaqController@destroy');

        //Reviews
        Route::get('/reviews/shops', 'Api\ReviewController@indexMyShop');
        Route::get('/reviews/products', 'Api\ReviewController@indexMyProduct');
        Route::post('/reviews/products', 'Api\ReviewController@productStore');
        Route::post('/reviews/shops', 'Api\ReviewController@shopStore');
        Route::post('/reviews/{id}', 'Api\ReviewController@update');
        Route::delete('/reviews/{id}', 'Api\ReviewController@destroy');

        //Report
        Route::post('/reports/users', 'Api\ReportController@userStore');
        Route::post('/reports/shops', 'Api\ReportController@shopStore');
        Route::post('/reports/conversations', 'Api\ReportController@conversationStore');
        Route::post('/reports/reviews', 'Api\ReportController@reviewStore');
        Route::post('/reports/products', 'Api\ReportController@productStore');
        Route::post('/reports/posts', 'Api\ReportController@postStore');
        Route::post('/reports/faqs', 'Api\ReportController@faqStore');

        //Messages
        // Route::get('/messages', 'Api\MessageController@index');
        // Route::get('/messages/{id}', 'Api\MessageController@show');
        // Route::post('/messages/shop', 'Api\MessageController@shopSent');
        // Route::post('/messages/customer', 'Api\MessageController@customerSent');
        Route::post('/messages', 'Api\MessageController@store');
        Route::post('/messages/new', 'Api\MessageController@newMessage');
        Route::delete('/messages/{id}', 'Api\MessageController@destroy');

        //Conversations
        Route::get('/conversations/shop', 'Api\ConversationController@shopConversations');
        Route::get('/conversations/customer', 'Api\ConversationController@customerConversations');

        //Notifications
        Route::get('/notifications/user', 'Api\NotificationController@userIndex');
        Route::get('/notifications/shop', 'Api\NotificationController@shopIndex');
        Route::get('/notifications/admin', 'Api\NotificationController@adminIndex');
        Route::post('/notifications/{id}', 'Api\NotificationController@setRead');
    });

    //Unauthenticated APIs

    //User
    Route::get('/user', 'Api\UserController@index');

    //Shop
    Route::get('/shops', 'Api\ShopController@index');
    Route::get('/shops/{id}', 'Api\ShopController@show');

    //Shop Types
    Route::get('/shoptypes', 'Api\ShopTypeController@index');

    //Categories
    Route::get('/categories', 'Api\CategoryController@index');

    //Packages
    Route::get('/packages', 'Api\PackageController@index');

    //Promotions
    Route::get('/promotions', 'Api\PromotionController@index');

    //Posts
    Route::get('/posts', 'Api\PostController@index');
    Route::get('/posts/shop/{id}', 'Api\PostController@getShopPosts');

    //Products
    Route::get('/products', 'Api\ProductController@index');
    Route::post('/products/search', 'Api\ProductController@getFiltered');
    Route::get('/products/shop/{id}', 'Api\ProductController@getShopProducts');
    Route::get('/products/{id}', 'Api\ProductController@show');

    //Faqs
    Route::get('/faqs/shop/{id}', 'Api\FaqController@getShopFaqs');

    //Reviews
    Route::get('/reviews/shops/{id}', 'Api\ReviewController@shopReviews');
    Route::get('/reviews/products/{id}', 'Api\ReviewController@productReviews');


    //Image Attachments 
    Route::post(
        '/attachment/{type}',
        function (Request $request, $type) {

            return response()->json([
                'status' => 'done',
                'url' => Storage::url($request->file('image')->store($type))
            ]);
        }
    );
});
