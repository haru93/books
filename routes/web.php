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

    Auth::routes();
    
    // ->name() を追加することにより、ルーティングに任意の名前を付けることができます。
    // ルーティングに名前を付けることで、簡易に該当URLへアクセスでき、URLの変更にも強くなるため名前を付けています。
    // 公式：ルートに一度名前を付ければ、その名前をグローバルなroute関数で使用すれば、URLを生成したり、リダイレクトしたりできます。
    // 　　　URLの生成：$url = route('profile');　リダイレクトの生成：return redirect()->route('profile');
    Route::get('/' , 'ReviewController@index')->name('index');
    Route::get('/home', 'HomeController@index')->name('home');
    // URL/show/{id}のidとはreviewsテーブルのidを指しています。つまりレビュー詳細ページのURLは閲覧するレビューによって変化します。
    // id=1のレビューを見たい：/show/1　｜　id=10のレビューを見たい：/show/10
    // URLにパラメーターを組み込む際にはパラメーター部分を {}（波カッコ）で囲う必要があります。
    Route::get('/show/{id}', 'ReviewController@show')->name('show');
    
    // ログインしている人だけがアクセスできるルーティンググループ
    // ミドルウェアとはブラウザ経由で来たリクエストをコントローラーに処理が行く前に何らかの処理を追加できる機能になります。
    // 以下はログイン認証のミドルウェア。authと指定することにより、ログイン保護されたルーティング。
    // /Auth/LoginController.php でログイン後にリダイレクトされる先などが修正できる。
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/review', 'ReviewController@create')->name('create');
        Route::post('/review/store', 'ReviewController@store')->name('store');
    });
        // そもそもサーバとのやりとりはHTTPというプロトコルを使っています。
        // GET, POSTというのは、その中の仕様で取り決められたHTTPメソッドというものの一部です。
        // GETは主にユーザーがデータを要求する時
        // POSTはユーザーがサーバーにデータを渡す時
        // に使われるHTTPメソッドと認識しておいてください。
    
    // 公式：Laravelのルーティングについて　https://readouble.com/laravel/6.x/ja/routing.html
    // 公式：Laravelのミドルウェアについて　https://readouble.com/laravel/6.x/ja/middleware.html