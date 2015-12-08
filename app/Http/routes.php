<?php

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::controller('articles', 'ArticleController');
    Route::controller('/', 'MainController');
});

Route::get('auth', function(\LaravelItalia\Entities\Repositories\UserRepository $userRepository){
    Auth::login($userRepository->findFirstBy(['name' => 'Francesco Malatesta']));
    return redirect('admin/articles');
});

Route::get('blank', function () {
    return view('admin.blank');
});
