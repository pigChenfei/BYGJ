<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/26
 * Time: 15:15
 */

Route::group(['namespace' => 'Admin'],function(){


    Auth::routes();

    Route::group(['middleware' => 'auth:admin'],function(){
        Route::get('/','HomeController@index');
        Route::resource('carriers', 'CarrierController');
        Route::patch('toggleCarrierStatus/{carrier_id}','CarrierController@toggleCarrierStatus')->name('toggleCarrierStatus');
        Route::get('carrier.showAssignPermissions/{carrier_id}','CarrierController@showAssignPermissions')->name('carrier.showAssignPermissions');
        Route::post('carrier.saveAssignPermissions/{carrier_id}','CarrierController@saveAssignPermissions')->name('carrier.saveAssignPermissions');
        Route::get('carrier.showCarrierUsers/{carrier_id}','CarrierController@showCarrierUsers')->name('carrier.showCarrierUsers');
        Route::get('carrier.showCarrierBackUpDomain/{carrier_id}','CarrierController@showCarrierBackUpDomain')->name('carrier.showCarrierBackUpDomain');
        Route::get('carriers.showCreateUserModal/{carrier_id}','CarrierController@showCreateUserModal')->name('carriers.showCreateUserModal');
        Route::get('carriers.showCreateBackUpDomainModal/{carrier_id}','CarrierController@showCreateBackUpDomainModal')->name('carriers.showCreateBackUpDomainModal');
        Route::get('carriers.showEditDomainModal/{carrier_domain_id}','CarrierController@showEditDomainModal')->name('carriers.showEditDomainModal');
        Route::post('carriers.updateDomain/{carrier_domain_id}','CarrierController@updateDomain')->name('carriers.updateDomain');
        Route::post('carriers.createDomain/{carrier_id}','CarrierController@createDomain')->name('carriers.createDomain');
        Route::post('carriers.createUser/{carrier_id}','CarrierController@createUser')->name('carriers.createUser');
        Route::get('carriers.showEditUserModal/{carrier_user_id}','CarrierController@showEditUserModal')->name('carriers.showEditUserModal');
        Route::patch('carriers.toggleCarrierUserStatus/{carrier_user_id}','CarrierController@toggleCarrierUserStatus')->name('carriers.toggleCarrierUserStatus');
        Route::patch('carriers.toggleCarrierBackUpDomainStatus/{id}','CarrierController@toggleCarrierBackUpDomainStatus')->name('carriers.toggleCarrierBackUpDomainStatus');
        Route::post('carriers.updateUser/{carrier_user_id}','CarrierController@updateUser')->name('carriers.updateUser');
        Route::resource('games', 'GameController');
        Route::get('games.index', 'GameController@index')->name('games.index');
        Route::get('games.create', 'GameController@create')->name('games.create');
        Route::post('games.store', 'GameController@store')->name('games.store');
        Route::get('games.show/{id}', 'GameController@show')->name('games.show');
        Route::post('game.toggleGameStatus/{game_id}','GameController@toggleGameStatus')->name('game.toggleGameStatus');
        Route::post('game.showAssignCarriersModal','GameController@showAssignCarriersModal')->name('game.showAssignCarriersModal');
        Route::post('games.updateCarriersGames','GameController@updateCarriersGames')->name('games.updateCarriersGames');
        Route::get('loginCarrierSystem/{carrier_id}','CarrierController@loginCarrierAdminSystem')->name('loginCarrierSystem');
        Route::resource('payments', 'PaymentController');
        Route::get('payments.getInfo', 'PaymentController@getInfo')->name('payments.getInfo'); // list详情
        Route::resource('payTypes', 'PayTypeController');
        Route::resource('plats', 'PlatController');
        
        Route::resource('templates', 'TemplateController');
        Route::post('templates.showAssignCarriersModal', 'TemplateController@showAssignCarriersModal')->name('templates.showAssignCarriersModal');
        Route::get('templates/edit/{id}', 'TemplateController@edit')->name('templates.edit');
        Route::post('templates.updateCarriersTemplates','TemplateController@updateCarriersTemplates')->name('templates.updateCarriersTemplates');
        
        Route::get('plats/{id}/editChild', 'PlatController@editChild')->name('plats.editChild');
        Route::get('plats/{id}/createChild', 'PlatController@createChild')->name('plats.createChild');
        Route::post('plats/storeChild', 'PlatController@storeChild')->name('plats.storeChild');
        Route::post('plats/{id}/updateChild', 'PlatController@updateChild')->name('plats.updateChild');
        Route::delete('plats/{id}/destroyChild', 'PlatController@destroyChild')->name('plats.destroyChild');
    });

});




