<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 新しいデータベーステーブルを作成するには、Schemaファサードのcreateメソッドを使用します。createメソッドは引数を２つ取ります。
        // 最初はテーブルの名前(reviews)で、２つ目は新しいテーブルを定義するために使用するBlueprintオブジェクトを受け取る「クロージャ」です。
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            // マイグレーションファイルの中で外部キー制約を設定しています。 以下のコードがreviewsテーブルの中のuser_idは
            // usersテーブルの中に存在するものしか入れることができませんといった制約です。
            // もしこの制約がない場合、usersテーブルに存在しない人が本のレビューを書くとはおかしいですよね？
            // こういったデータの不整合を防ぐのが外部キー制約になります。
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('title');
            $table->longText('body');
            $table->string('image')->nullable(); //画像投稿しない場合に備えてNULL許可しておく
            $table->tinyInteger('status')->default(1)->comment('0=下書き, 1=アクティブ, 2=削除済み');
            // $table->timestamps()としてしまうと、レコードが作成された日時が入らないので、DB:rawで行う
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}

// 公式　マイグレーション解説
// https://readouble.com/laravel/6.x/ja/migrations.html