<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Reviewコントローラー内で、Reviewモデル(/app/Review.php ←モデル)を使えるようにするための記述です。
// 実際にデータベースへのデータ挿入はモデルが行うため、コントローラー内でモデルとそのメソッドを呼び出す必要があるため、
// useでコントローラー内にモデルを取り込んでいます。
// 実際に使う際には読み込んだReviewのあとに使用したいメソッドを書いていく流れになります。
use App\Review;

class ReviewController extends Controller
{
    // /routes/web.php で指定された以下の各種アクションが実行されるということ
    public function index()
    {
        // where()はデータを取得する際の絞り込み条件指定するメソッドで、statusが1（アクティブな投稿）を取得しています。
        // statusで絞っている理由は、レビューを論理削除した場合statusが0になる設計が考えられているためです。
        // そのため、アクティブなレビューだけを取得する意味で、status=1の投稿だけを取得するようにしています。
        // 論理削除とは実際にはデータを削除せずに、削除されたと見なすフラッグと呼ばれるカラムを設定することでユーザーには
        // 削除しているかのように振る舞わせることを指します。
        // orderBy()ではデータの並び順を指定してます。1つ目の引数には並び替えに使いたいカラム名、
        // 2つ目の引数には並び順を昇順（ASC）、降順(DESC)のどちらかを指定します。
        // 本教材では投稿日が新しい順に並べたいので、データの作成日である created_atカラム を 降順（DESC） に並べています。
        // get()の代わりにpaginate(数を指定)を使う事で、自動的にページネーションを生成します。
        $reviews = Review::where('status', 1)->orderBy('created_at', 'DESC')->paginate(9);
        // ↓コメントアウトを外し、トップページにアクセスすると、デバッグ画面になり、$reviewsの中身を確認できます。
        // dd($reviews);
        // compactの引数に変数名を指定することでビューで変数が使えるようになります。渡すのを忘れずに。
    	return view('index', compact('reviews'));
    }
    
    // showメソッドを追加する際に引数に$idを指定します。この値はルーティングのURLに記述した{id}の値が入ってくるようになります。
    // そのため、ルーティングに書いた変数とコントローラーメソッドの変数名を一致させる必要があるため、注意してください。
    // 例えばURL /show/10 にアクセスした場合、URLの数値が$idとしてコントローラーに渡ります。
    public function show($id)
    {
        // 今回はレビュー情報を1件取得すればいいため、Reviewモデルを通じて、URLパラメーターに一致かつ、status=1（アクティブなレビュー）を1件取得します。
        // そのために->first()を用いて1件取得しています。
        // まずURLから渡ってきたidに一致するレビューを取りに行くため、 reviewsテーブルのidとURLパラメーターが一致する行を取得しています。
        // 次にstatus=1である必要があるため、->where() を繋げています。
        // このように複数条件で指定したい場合はwhere()を続けて書くことができます。
        // 条件の指定が終わったら、条件に一致した最初の行を取得する->first()でデータを取得して、データをビューに渡しています。
        $review = Review::where('id', $id)->where('status', 1)->first();
        return view('show', compact('review'));
    }
    
    public function create()
    {
        return view('review');
    }
    
    // Requestや$requestはLaravelの基本的なクラスであるHTTPリクエストクラスで、HTTP通信に関わる様々な機能を利用できます。
    // HTTPリクエストの1つであるPOSTメソッドで送信されたデータを取得する際にHTTPリクエストクラスを活用します。
    // そのため、storeメソッド内で使えるようにするため、引数に記述しています。
    // HTTPリクエストクラスで取得できるデータはビューのinputタグのname属性に合わせた名前でデータを取得する事ができます。
    public function store(Request $request)
    {
        // storeメソッドの最初にある$post = $request->all();はPOSTで送信されたデータを $postに代入しています。
        // もし中身が気になったら、$postをdd()（デバッグ関数）でデバッグしてみるのが良いでしょう。
        $post = $request->all();
        
        // バリデーションルールは配列で渡すようになっており、キーがビューのform部品の名前と一致するように指定します。
        $validatedData = $request->validate([
            // バリューには指定したいルールを複数記述します。タイトルと本文は必須である required
            // タイトルは最大値を指定したいので、max:255で255文字に制限しています。
            'title' => 'required|max:255',
            'body' => 'required',
            // imageに対しては拡張子を画像以外受け付けないように指定してあります。
            // その上で最大ファイルサイズが2Mですね。（サイズはkb単位で指定します）
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        // imageという名前のファイルが存在するのか否かを判定しています。
        if ($request->hasFile('image')) {
        //文字などのデータとは異なりファイルを扱う際は$request->file('formのname属性')で送信されたファイルを操作する事ができます。
        // /views/review.blade.php の画像部分のnameは<input name="image">のため以下のようになる
        // ->store('アップロードパス')とメソッドを追加する事で、storage/app以下の任意のディレクトリにファイルをアップロードできます。
            $request->file('image')->store('/public/images');
            $data = ['user_id' => \Auth::id(),  // user_idカラムにはログインしているユーザーのid（ \Auth::id() ）
                     'title' => $post['title'], // titleカラムにはレビュー投稿ページの<input name="title">に入力した値
                     'body' => $post['body'],   // bodyカラムにはレビュー投稿ページの<input name="body">に入力した値
                     //  アップロードの実装が終わり、次はアップロードした画像のパスをデータベースに保存する必要があります。
                     //  その理由としては、アップロードした画像はハッシュ名（ランダムな名前）で保存したいため、
                     //  どのレビューとどの画像が紐付くのかをデータベースに保存しておく必要があります。
                     //  ハッシュ名で画像を保存する理由としては、セキュリティのためです。
                     //  わかりやすい名前で保存してしまうと直接URLにアクセスされてしまう等のセキュリティリスクが上がるため、
                     //  ランダムな名前で保存します。
                     //  そのため、画像のハッシュ名をデータベース挿入用の配列に追加します。それが以下のコードになります。
                     //  MySQLにログインし、imageの名前とアプロードされた名前が同じになっているはず。
                     'image' => $request->file('image')->hashName()
                    ];
        } else {
            $data = ['user_id' => \Auth::id(),
                     'title' => $post['title'],
                     'body' => $post['body']
                    ];
        }
        // insertメソッドはデータベースに新しいレコード（行）を追加するメソッドです。
        // insertメソッドは引数に配列を渡すことで、以下のように扱われます。
        // ［配列のキー ：テーブルのカラム名　=>　配列のバリュー ：挿入されるデータ］
        // 実際にデータを挿入する際にはReviewモデルのinsertメソッドを呼び出します。そのコードが以下になります。
        // モデルに対して、::に続けてメソッド名を記述することで、メソッドを呼び出す事ができます。
        // ReviewモデルがベースのModelを継承したモデル。
        // insertは元々親のModelが持っているメソッドですね。Laravelではこういった基本的なメソッドは最初から準備されていて、
        // 私たちはそれを利用するだけで大抵のデータベース操作はできてしまいます。
        // 各種メソッドは以下を参照
        Review::insert($data);
        
        // returnはメソッドを終了させ、後述している命令を実行するメソッドです。
        // ここではreturnのあとにredirect（転送させる命令）を書き、URL / つまりトップページに戻す処理を行っています。
        // ->with()はリダイレクトの際やビューを返すときにデータを渡すことのできるメソッドです。
        // with()は以下のように2つの引数を与えることで、セッションデータを配列として渡すことができます。
        // セッションの配列キーにflash_messageと入力することで、トップページへ戻った際に前項で追加したフラッシュメッセージを表示できます。
        // ->with('キー', 'バリュー');
        return redirect('/')->with('flash_message', '投稿が完了しました');
    }
}

// 【データベースについて】
// メソッド名　　内容
// find　主キーを指定して検索
// where　検索条件を指定 orWhere や引数でAND,OR検索ができる
// count　件数を取得する
// first　データを1件だけ取得する
// toArray　データを配列に変換
// toJson　データをJSONに変換
// create,save　データの作成。createの場合は、モデルのfillableの設定が必須
// insert　複数のデータを一括作成
// update,save　データの更新
// delete,destroy　データの削除。destroyは主キーを複数指定して削除可能

// books は CREATE DATABASE books CHARACTER SET utf8mb4; コマンドで最初(1-1)に作ったデータベースのこと
// reviews はテーブルのこと

// 公式：HTTPリクエストクラス　https://readouble.com/laravel/6.x/ja/requests.html
// 公式：Modelについて　https://readouble.com/laravel/6.x/ja/eloquent.html

// 論理削除と物理削除　https://qiita.com/jonson29/items/4743409eda08fcdf5410
// Laravel クエリビルダ　https://readouble.com/laravel/6.x/ja/queries.html