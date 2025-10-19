<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

/**
 * アイテムコントローラークラス
 */
class ItemController extends Controller
{
    /**
     * 商品一覧ページ
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tab = $request->input('tab', 'recommend');

        $query = Item::query();

        // 検索
        if (!empty($search)) {
            $query->where('item_name', 'like', "%{$search}%");
        }

        // マイリストタブの場合
        if ($tab === 'mylist') {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            // ログインしていない、またはメール未認証なら空コレクション
            if (!$user || !$user->hasVerifiedEmail()) {
                $items = collect();
                return view('index', compact('items', 'search', 'tab'));
            }

            // @noinspection PhpUndefinedMethodInspection
            /** @var \Illuminate\Support\Collection|\App\Models\Item[] $likedItemIds */
            $likedItemIds = $user->likedItems()->pluck('items.id');
            $query->whereIn('id', $likedItemIds);
        }

        /** @var \Illuminate\Support\Collection|\App\Models\Item[] $items */
        $items = $query->get();

        $items->each(function ($item) {
            $item->is_sold = Purchase::where('item_id', $item->id)->exists();
        });

        return view('index', compact('items', 'search', 'tab'));
    }

    /**
     * 商品詳細画面の表示
     * @param string $id 商品ID
     * @return view ビュー
     */
    public function detail($id)
    {
        $item = Item::with(['categories', 'condition', 'likes', 'comments'])->findOrFail($id);
        $userId = Auth::id();

        // ログインユーザーがすでにLikeしているか判定
        $liked = $item->likes->contains('user_id', $userId);

        // detail ページではタブはおすすめを既定値にする
        $tab = 'recommend';

        $item->is_sold = Purchase::where('item_id', $item->id)->exists();

        return view('detail', compact('item', 'liked', 'tab'));
    }

    public function toggleLike(Request $request)
    {
        $userId = Auth::id();
        $itemId = $request->item_id;

        // 既にLikeがあるか確認
        $like = Like::where('user_id', $userId)
            ->where('item_id', $itemId)
            ->first();

        if ($like) {
            // 既にあれば削除
            $like->delete();
        } else {
            // なければ作成
            Like::create([
                'user_id' => $userId,
                'item_id' => $itemId,
            ]);
        }

        // itemを取得してビューに渡す
        $item = Item::with(['categories', 'condition', 'likes', 'comments'])->findOrFail($itemId);

        // ログインユーザーがすでにLikeしているか判定
        $liked = $item->likes->contains('user_id', $userId);

        // detailページではタブはおすすめを既定値にする
        $tab = 'recommend';

        return redirect()->route('item.detail', ['item' => $itemId]);
    }

    public function purchasePage(Item $item)
    {
        $user = Auth::user();
        $tab = session('tab', 'recommend'); // セッションから渡されたら使う
        $message = session('message');

        return view('purchase', compact('item', 'user', 'tab', 'message'));
    }

    public function comment(CommentRequest $request)
    {
        $userId = Auth::id();
        $itemId = $request->item_id;

        Comment::create([
            'comment' => $request->comment,
            'user_id' => $userId,
            'item_id' => $itemId,
        ]);

        $item = Item::with(['categories', 'condition', 'likes', 'comments'])->findOrFail($itemId);

        // ログインユーザーがすでにLikeしているか判定
        $liked = $item->likes->contains('user_id', $userId);

        // detail ページではタブはおすすめを既定値にする
        $tab = 'recommend';

        // ← ここがポイント
        // コメント後も購入済み判定をセット
        $item->is_sold = Purchase::where('item_id', $item->id)->exists();

        return view('detail', compact('item', 'liked', 'tab'));
    }
}
