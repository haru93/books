@extends('layouts.app')

<!--子ビューで読み込むことで、トップページにアクセスされた時のみ、-->
<!--レイアウトファイルのheadタグ内へlinkタグを埋め込むことができます。-->
@section('css')
    <link href="{{ asset('css/top.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row justify-content-center">
        <!--ReviewControllerからreturn view('index', compact('reviews'));のcompactで送られてきた変数$reviews-->
        @foreach($reviews as $review)
            <div class="col-md-4">
                <div class="card mb50">
                    <div class="card-body">
                        <!--サムネイル画像の有無でダミー画像を分岐表示-->
                        @if(!empty($review->image))
                            <!--Laravelをウェブサーバー上で運用する際にアクセスできるディレクトリはpublicのみです。-->
                            <!--簡単に書くと、public/index.phpに必ずアクセスが行き、そこからURLに応じて処理が分岐されます。-->
                            <!--そのままではstorageディレクトリに直接アクセスすることはできません。-->
                            <!--そのため、storage/app/publicを public/storageにシンボリックリンク（ショートカットのようなもの）を貼ることでURLによるアクセスが可能になります。-->
                            <!--シンボリックリンクを作るコマンド　php artisan storage:link-->
                            <div class='image-wrapper'><img class='book-image' src="{{ asset('storage/images/'.$review->image) }}"></div>
                        @else
                            <div class='image-wrapper'><img class='book-image' src="{{ asset('images/dummy.png') }}"></div>
                        @endif
                        <h3 class='h3 book-title'>{{ $review->title }}</h3>
                        <p class='description'>{{ $review->body }}</p>
                        <!--公式：route関数は指定された名前付きルートへのURLを生成し、ルートにパラメーターを受け付ける場合は第２引数で指定します-->
                        <!--公式：$url = route('routeName', ['id' => 1]);-->
                        <!--詳細ページへのルーティングはURLパラメーターとして、reviewsテーブルのidが必要ですね。-->
                        <!--そのため、route関数 でパラメーターを渡すために、2つ目の引数に配列でパラメーターを与えてあげる必要があります。-->
                        <!--route('show', ['変数名' => 実際に入る値 ])-->
                        <!--パラメーター名=id、実際の値はreiewsテーブルのid値として、ルーティングを設定-->
                        <a href="{{ route('show', ['id' => $review->id ]) }}" class='btn btn-secondary detail-btn'>詳細を読む</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <!--自動的に生成されたページネーションの中にlinks()という項目があり、link()の正体はビューで使えるHTMLコードです。-->
    <!--そのため、そのままビューで出力するだけで、Bootstrapデザインのページネーションを使うことができます。-->
    <!--ReviewController の　$reviews = Review::where('status', 1)->orderBy('created_at', 'DESC')->paginate(9);-->
    {{ $reviews->links() }}
@endsection

<!--Bootstrapのクラス解説-->
<!--row　rowで囲まれた中身は一つの行グループとなる（display-flexと同等）-->
<!--col-md-4　rowの中で定義する横幅（列の広さ）を定義するクラス。12分割したうちの4なので、横幅は33%となる。-->
<!--justify-content-center　flex-boxの子要素をセンタリング-->
<!--card　パネルのような見た目の外枠を作るクラス-->
<!--card-body　cardの中のメインエリアを定義-->

<!--公式　Bootstrapカードコンポーネント　https://cccabinet.jpn.org/bootstrap4/components/card-->
<!--Bootstrapグリッドシステム　http://websae.net/twitter-bootstrap-grid-system-21060224/-->

<!--シンボリックリンク　https://qiita.com/u-dai/items/8a904cc7fd2795c0e70d-->
<!--PHP isset, empty, is_null の違い早見表　https://qiita.com/shinichi-takii/items/00aed26f96cf6bb3fe62-->