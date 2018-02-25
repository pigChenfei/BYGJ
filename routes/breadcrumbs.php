<?php

//--------------------------------吴刚Begin ------------------------

Breadcrumbs::register('carrierPlayerLevels.index',function($breadcrumbs){

    $breadcrumbs->push('会员等级列表',route('carrierPlayerLevels.index'));

});


Breadcrumbs::register('CarrierPlayerLevels.rebateFlowShow',function($breadcrumbs,$carrierPlayerLevel){

    $breadcrumbs->parent('carrierPlayerLevels.index');

    $breadcrumbs->push($carrierPlayerLevel->level_name.'洗码设置',route('CarrierPlayerLevels.rebateFlowShow',$carrierPlayerLevel->id));


});


Breadcrumbs::register('carrierImages.index',function($breadcrumbs){

    $breadcrumbs->push('图片素材管理',route('carrierImages.index'));

});

Breadcrumbs::register('carrierImages.showUploadImageModal',function($breadcrumbs){

    $breadcrumbs->parent('carrierImages.index');

    $breadcrumbs->push('素材上传',route('carrierImages.showUploadImageModal'));


});


//--------------------------------吴刚end---------------------------


//-----------------------------------王宁begin---------------------------------//

Breadcrumbs::register('carrierAgentLevels.index',function($breadcrumbs){

    $breadcrumbs->push('代理层级',route('carrierAgentLevels.index'));

});

Breadcrumbs::register('carrierAgentLevels.create',function($breadcrumbs){

    $breadcrumbs->parent('carrierAgentLevels.index');

    $breadcrumbs->push(Lang::get('carrierAgentLevelField.create_carrier_agent_level_title') ,route('carrierAgentLevels.create'));
});

Breadcrumbs::register('carrierAgentLevels.edit',function($breadcrumbs,$category){

    $breadcrumbs->parent('carrierAgentLevels.index');

    $breadcrumbs->push(Lang::get('carrierAgentLevelField.edit_carrier_agent_level_title') ,route('carrierAgentLevels.edit',$category->id));

});


//-----------------------------------王宁end---------------------------------//


//-----------------------------------郭威威begin---------------------------------//

//Breadcrumbs::register('carrierGamePlats.index',function($breadcrumbs){
//
//    $breadcrumbs->push('游戏平台',route('carrierGamePlats.index'));
//
//});
//
//Breadcrumbs::register('carrierGamePlats.create',function($breadcrumbs){
//
//    $breadcrumbs->parent('carrierGamePlats.index');
//
//    $breadcrumbs->push(Lang::get('carrierGamePlatsField.create_carrier_game_plats_title') ,route('carrierGamePlats.create'));
//});
//
//Breadcrumbs::register('carrierGamePlats.edit',function($breadcrumbs,$category){
//
//    $breadcrumbs->parent('carrierGamePlats.index');
//
//    $breadcrumbs->push(Lang::get('carrierGamePlatsField.edit_carrier_game_plats_title') ,route('carrierGamePlats.edit',$category->id));
//
//});

//-----------------------------------郭威威end---------------------------------//


//// Home
//Breadcrumbs::register('home', function($breadcrumbs)
//{
//    $breadcrumbs->push('Home', route('home'));
//});
//
//// Home > About
//Breadcrumbs::register('about', function($breadcrumbs)
//{
//    $breadcrumbs->parent('home');
//    $breadcrumbs->push('About', route('about'));
//});
//
//// Home > Blog
//Breadcrumbs::register('blog', function($breadcrumbs)
//{
//    $breadcrumbs->parent('home');
//    $breadcrumbs->push('Blog', route('blog'));
//});
//
//// Home > Blog > [Category]
//Breadcrumbs::register('category', function($breadcrumbs, $category)
//{
//    $breadcrumbs->parent('blog');
//    $breadcrumbs->push($category->title, route('category', $category->id));
//});
//
//// Home > Blog > [Category] > [Page]
//Breadcrumbs::register('page', function($breadcrumbs, $page)
//{
//    $breadcrumbs->parent('category', $page->category);
//    $breadcrumbs->push($page->title, route('page', $page->id));
//});