<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PostController extends Controller
{
    // 投稿一覧表示
    public function index(Request $request)
    {
        // 基本クエリ（削除済み除外）
        $query = Post::query()->whereNull('deleted_at');

        // 検索（タイトル or 地域）
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('region', 'like', '%' . $request->search . '%');
            });
        }

        // 並び替え
        $sort = $request->get('sort', 'new');

        if ($sort === 'old') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'popular') {
            // お気に入り数の多い順
            $query->withCount('favorites')->orderBy('favorites_count', 'desc');
        } else {
            // 新しい順（デフォルト）
            $query->orderBy('created_at', 'desc');
        }

        // 投稿＋画像リレーション読み込み
        $catposts = $query->with('images')->paginate(10);

        // ビューへ渡す
        return view('home.index', compact('catposts'));
    }

    // 投稿詳細表示
    public function detail(Post $post)
    {
        // 関連データを事前ロード（N+1防止）
        $post->load(['user', 'images', 'videos']);
        // dd($post);

        return view('catpost.detail', compact('post'));
    }

// =================================================================================
   // 自分の投稿一覧表示機能


    public function myCatpost()
    {
        $user = Auth::user();

        // 自分の投稿＋画像を取得
        $myCatposts = Post::with('images')
            ->withCount('favorites')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('authority/catpost.index', compact('myCatposts'));
    }

    // 猫の情報投稿作成画面
    public function create()
    {
        $user = Auth::user();
        // dd($user);
        return view('authority/catpost.create', [
            'user' => $user,
        ]);
    }
// =================================================================================
// =================================================================================

// 猫の情報投稿用バリデーションメッセージ：ボツ

    public function validation(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:50',
            'age' => 'required|numeric|min:0|max:30',
            // 'gender' => 'required|in:オス,メス',
            'kinds' => 'required|string|max:50',
            'location' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vaccine' => 'nullable|string|max:500',
            'disease' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0|max:1000000',
        ], [
            'title.required' => 'タイトルは50文字以内で入力してください。',
            'age.required' => '推定年齢を入力してください。',
            'gender.required' => '性別を選択してください。',
            'kinds.required' => '猫の品種を入力してください。',
            'location.required' => '所在地を入力してください。',
            'start_date.required' => '掲載開始日を入力してください。',
            'end_date.required' => '掲載終了日を入力してください。',
                        // 'end_date.after_or_equal' => '掲載終了日は開始日以降の日付を指定してください。',
            'photo.required' => '画像を最低1枚アップロードしてください。',
            'vaccine.required' => '予防接種の情報を入力してください。',
            'disease.required' => '病歴の情報を入力してください。',
            'price.required' => '費用を入力してください。',
        ]);

        //  保存処理
        // Post::create($request->all());

        // return redirect()->route('catpost.create')->with('success', '投稿が作成されました！');
    }


// =================================================================================
// 画像のアップロード
// =================================================================================

    public function image(Request $request)
    {
        
        // ディレクトリ名を任意の名前で設定します
        $dir = 'img';

        // imgディレクトリを作成し画像を保存
        // storage/app/public/任意のディレクトリ名/
        $request->file('image')->store('public/' . $dir);

        // ページを更新します
        return redirect('/');

        $image = new User();
// $任意の変数名　=　テーブルを操作するモデル名();
// storage/app/public/任意のディレクトリ名/
        $image->post_id = $file_name;
        $image->post_id = 'storage/app/public/' . $dir . '/' . $file_name;
        $image->save();

   //ページを更新する
    return redirect('/');
    }

// =================================================================================
// =================================================================================
}
