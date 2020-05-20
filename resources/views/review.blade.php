@extends('layouts.app')

@section('content')
  <h1 class='pagetitle'>レビュー投稿ページ</h1>

  <!--バリデーションのエラーメッセージ返ってきた時のみエラー文が表示されるようになっています。-->
  <!--日本語メッセージ resources/lang/ja/validation.php-->
  <!--Laravel日本語化ファイル　https://gist.github.com/syokunin/b37725686b5baf09255b-->
  <!--config/app.php内の'locale' => 'ja',に変更。この値が影響して言語ファイルが適用されています。-->
  @if ($errors->any())
    <!--bootstrapのalertクラスを使って目立つデザインを作った上で、-->
    <div class="alert alert-danger container">
        <ul>
            <!--@foreachのループを使って、エラー内容をリストタグとして出力される仕組みです。-->
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
  @endif

  <div class="row justify-content-center container">
      <div class="col-md-10">
        <!--formタグを使う際にはmethodを記述する必要があります。今回はデータの登録用フォームなので、POSTを指定しています。-->
        <!--route関数は名前を付けたルーティングのURLを呼び出すLaravelで使える関数です。-->
        <!--route('ここにルーティングの名前') と記述することにより自動でURLを生成します。-->
        <!--web.php にある->name('store')の部分が名前として設定されている。-->
        <!--本パートのパターンだとroute('store') →　/review/storeに変換されるということですね。-->
        <!--本のサムネイル画像も登録したいので enctype="multipart/form-data" を指定します。-->
        <!--この指定は文字データの登録ならば不要なのですが、画像やPDFなどのファイルをアップロードしたい場合、必ず必要になるので忘れず記述しましょう。-->
        <form method='POST' action="{{ route('store') }}" enctype="multipart/form-data">
          <!--ｸﾛｽｻｲﾄ･ﾘｸｴｽﾄ･ﾌｫｰｼﾞｴﾘ対策用のコードとなり、Laravelでformを扱う際には必ずformタグの中に入れなければならないコード-->
          @csrf
          <div class="card">
              <div class="card-body">
                <!--各フォーム(inputやtextareaなど)をform-groupを付与したdivタグで囲みます。-->
                <!--その中のinputタグにform-controlを追加することで、入力しやすい以下のようなフォームを簡単に実装できます。-->
                <div class="form-group">
                  <label>本のタイトル</label>
                  <input type='text' class='form-control' name='title' placeholder='タイトルを入力'>
                </div>
                <div class="form-group">
                <label>レビュー本文</label>
                  <textarea class='description form-control' name='body' placeholder='本文を入力'></textarea>
                </div>
                <div class="form-group">
                  <label for="file1">本のサムネイル</label>
                  <input type="file" id="file1" name='image' class="form-control-file">
                </div>
                <input type='submit' class='btn btn-primary' value='レビューを登録'>
              </div>
          </div>
        </form>
        
      </div>
  </div>
@endsection