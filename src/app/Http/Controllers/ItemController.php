<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

class ItemController extends Controller
{
    /**
     * 商品一覧ページの表示
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tab = $request->input('tab', 'recommend');

        $query = Item::query();

        if (!empty($search)) {
            $query->where('item_name', 'like', "%{$search}%");
        }

        if ($tab === 'mylist') {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();

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
     */
    public function detail($id)
    {
        $item = Item::with(['categories', 'condition', 'likes', 'comments'])->findOrFail($id);
        $userId = Auth::id();
        $liked = $item->likes->contains('user_id', $userId);
        $tab = 'recommend';
        $item->is_sold = Purchase::where('item_id', $item->id)->exists();

        return view('detail', compact('item', 'liked', 'tab'));
    }

    /**
     * いいね登録/解除
     */
    public function toggleLike(Request $request)
    {
        $userId = Auth::id();
        $itemId = $request->item_id;

        $like = Like::where('user_id', $userId)
            ->where('item_id', $itemId)
            ->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $userId,
                'item_id' => $itemId,
            ]);
        }

        return redirect()->route('items.show', ['id' => $itemId]);
    }

    /**
     * 商品購入画面の表示
     */
    public function purchasePage(Item $item)
    {
        $user = Auth::user();
        $tab = session('tab', 'recommend');
        $message = session('message');

        return view('purchase', compact('item', 'user', 'tab', 'message'));
    }

    /**
     * コメント登録
     */
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
        $liked = $item->likes->contains('user_id', $userId);
        $tab = 'recommend';
        $item->is_sold = Purchase::where('item_id', $item->id)->exists();

        return view('detail', compact('item', 'liked', 'tab'));
    }
}
