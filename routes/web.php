<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

//ゲストログイン
Route::get('/guest/login','Auth\LoginController@guestLogin')->name('guest_login');

//ホーム画面を表示
Route::get('/home', 'HomeController@getOther_outfitShow')->name('home');

//コーディネートの検索(他人)
Route::post('/home/search','HomeController@getOther_outfitSearch')->name('search_home');

Route::middleware('verified')->group(function() {
    // 本登録していないとアクセスできないURL

    //いいねをつける
    Route::get('/home/like/{id}','HomeController@exeOther_outfitLike')->name('like_home');
    
    //いいねを消す
    Route::get('/home/nolike/{id}','HomeController@exeOther_outfitNolike')->name('nolike_home');

    //フォローする
    Route::put('/home/follow/{name}','HomeController@follow')->name('follow_home');
    //フォローを外す
    Route::delete('/home/follow/{name}','HomeController@unfollow')->name('unfollow_home');
    
    //服一覧を表示
    Route::get('/cloth/list','ClothController@getClothShow')->name('show_cloth');
    
    //服のカテゴリで絞り込み
    Route::post('/cloth/search','ClothController@getClothSearch')->name('search_cloth');
    
    //服を追加
    Route::post('/cloth/add','ClothController@exeClothAdd')->name('add_cloth');
    
    //服の編集
    Route::post('/cloth/edit','ClothController@exeClothEdit')->name('edit_cloth');
    
    //服の削除
    Route::post('/cloth/delete/{id}','ClothController@exeClothDelete')->name('delete_cloth');
    
    //プロフィールの表示
    Route::get('/profile','ProfileController@getProfileShow')->name('show_profile');
    
    //プロフィールの追加
    Route::post('/profile/add','ProfileController@exeProfileAdd')->name('add_profile');
    
    //プロフィールの編集
    Route::post('/profile/edit','ProfileController@exeProfileEdit')->name('edit_profile');
    
    //コーディネート一覧の表示
    Route::get('/coordination/list','OutfitController@getOutfitShow')->name('show_outfit');
    
    //コーディネートをタグで絞り込み
    Route::post('/outfit/search','OutfitController@getOutfitSearch')->name('search_outfit');
    
    //コーディネートの追加
    Route::post('/coordination/add','OutfitController@exeOutfitAdd')->name('add_outfit');
    
    //コーディネートの編集
    Route::post('/coordination/edit','OutfitController@exeOutfitEdit')->name('edit_outfit');
    
    //コーディネートの削除
    Route::post('/coordination/delete/{id}','OutfitController@exeOutfitDelete')->name('delete_outfit');
});




