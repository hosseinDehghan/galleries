<?php
Route::group(['middleware' => ['web']], function () {
    Route::get('galleries','Hosein\Galleries\Controllers\GalleriesController@index');
    Route::get('galleries/editCategory/{id}','Hosein\Galleries\Controllers\GalleriesController@editCategory');
    Route::get('galleries/deleteCategory/{id}','Hosein\Galleries\Controllers\GalleriesController@deleteCategory');
    Route::post('galleries/creatCategory','Hosein\Galleries\Controllers\GalleriesController@creatCategory');
    Route::post('galleries/updateCategory/{id}','Hosein\Galleries\Controllers\GalleriesController@updateCategory');
    Route::get('galleries/getCategory/{type}','Hosein\Galleries\Controllers\GalleriesController@getCategoryWithType');
    Route::post('galleries/creatGallery','Hosein\Galleries\Controllers\GalleriesController@creatGallery');
    Route::get('galleries/editGallery/{id}','Hosein\Galleries\Controllers\GalleriesController@editGallery');
    Route::post('galleries/addToGallery/{id}','Hosein\Galleries\Controllers\GalleriesController@addToGallery');
    Route::post('galleries/updateGallery/{id}','Hosein\Galleries\Controllers\GalleriesController@updateGallery');
    Route::get('galleries/getGallery/{id}','Hosein\Galleries\Controllers\GalleriesController@getGallery');
    Route::get('galleries/deleteImgGallery/{id}/{img}','Hosein\Galleries\Controllers\GalleriesController@deleteImgGallery');
    Route::get('galleries/deleteGallery/{id}','Hosein\Galleries\Controllers\GalleriesController@deleteGallery');


});