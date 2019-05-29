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



Route::group(['middleware' => ['json.response']], function () {

    // public routes
    Route::post('/login', 'Api\PassportController@login')->name('login.api');
    Route::post('/register', 'Api\PassportController@register')->name('register.api');

    // private routes
    Route::middleware('auth:api')->group(function () {

        Route::get('/logout', 'Api\PassportController@logout')->name('logout');
        // Route::get('/user', 'Api\PassportController@details')->name('details');


        // Pulsespace API's
        Route::get('/users/shop', 'Api\UserController@hasShop');
        Route::get('/user', 'Api\UserController@index');

        //Store Followers
        Route::get('/follow/{id}', 'Api\ShopFollowerController@follow');
        Route::get('/followed', 'Api\ShopFollowerController@index');


        //Shop
        Route::get('/shops', 'Api\ShopController@index');
        Route::get('/shops/{id}', 'Api\ShopController@show');
        Route::post('/shop', 'Api\ShopController@store');
        Route::post('/updateshop', 'Api\ShopController@update');
        Route::delete('/shops/{id}', 'Api\ShopController@destroy');
        Route::get('/myshop', 'Api\ShopController@myShop');


        //Shop Types
        Route::get('/shoptypes', 'Api\ShopTypeController@index');


        //Categories
        Route::get('/categories', 'Api\CategoryController@index');
        // Route::get('/categories/{parent}', 'Api\CategoryController@show');

        //Packages
        Route::get('/packages', 'Api\PackageController@index');


        //Promotions
        Route::get('/promotions', 'Api\PromotionController@index');
        Route::post('/promotion', 'Api\PromotionController@store');
        Route::get('/mypromotion', 'Api\PromotionController@myPromotion');



        //Posts
        Route::get('/myposts', 'Api\PostController@myPosts');
        Route::get('/posts', 'Api\PostController@index');
        Route::get('/posts/shop/{id}', 'Api\PostController@getShopPosts');
        Route::post('/posts', 'Api\PostController@store');
        Route::post('/product_post', 'Api\PostController@productPost');
        Route::post('/posts/{id}', 'Api\PostController@update');
        Route::delete('/posts/{id}', 'Api\PostController@destroy');
        // Route::get('/myposts', 'Api\PostController@show');

        //Products
        Route::get('/products', 'Api\ProductController@index');
        Route::get('/myproducts', 'Api\ProductController@myProducts');
        Route::post('/products', 'Api\ProductController@getFiltered');
        Route::post('/products/discount', 'Api\ProductController@setDiscount');
        Route::get('/products/shop/{id}', 'Api\ProductController@getShopProducts');
        Route::get('/products/{id}', 'Api\ProductController@show');
        Route::post('/products', 'Api\ProductController@store');
        Route::post('/products/{id}', 'Api\ProductController@update');
        Route::delete('/products/{id}', 'Api\ProductController@destroy');


        //Faqs
        Route::get('/faqs', 'Api\FaqController@index');
        Route::get('/faqs/shop/{id}', 'Api\FaqController@getShopFaqs');
        Route::post('/faqs', 'Api\FaqController@store');
        Route::post('/faqs/{id}', 'Api\FaqController@update');
        Route::delete('/faqs/{id}', 'Api\FaqController@destroy');

        //Reviews
        Route::get('/reviews/shops', 'Api\ReviewController@indexMyShop');
        Route::get('/reviews/products', 'Api\ReviewController@indexMyProduct');
        Route::get('/reviews/shops/{id}', 'Api\ReviewController@shopReviews');
        Route::get('/reviews/products/{id}', 'Api\ReviewController@productReviews');
        Route::post('/reviews/products', 'Api\ReviewController@productStore');
        Route::post('/reviews/shops', 'Api\ReviewController@shopStore');
        Route::post('/reviews/{id}', 'Api\ReviewController@update');
        Route::delete('/reviews/{id}', 'Api\ReviewController@destroy');

        //Product Reviews
        Route::post('/products/reviews', 'Api\ProductReviewController@store');
        Route::delete('/products/reviews/{id}', 'Api\ProductReviewController@destroy');


        //Messages
        // Route::get('/messages', 'Api\MessageController@index');
        Route::get('/messages/{id}', 'Api\MessageController@show');
        Route::post('/messages/shop', 'Api\MessageController@shopSent'); //might not get used 
        Route::post('/messages/customer', 'Api\MessageController@customerSent'); //might not get used
        Route::delete('/messages/{id}', 'Api\MessageController@destroy'); //Delete chat with some user

        //Conversations
        Route::get('/conversations/shop', 'Api\ConversationController@shopConversations');
        Route::get('/conversations/customer', 'Api\ConversationController@customerConversations');
    });

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
