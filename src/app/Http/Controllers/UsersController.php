<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ExhibitionRequest;

class UsersController extends Controller
{
    public function profileUpdate(ProfileRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // フォームデータを取得（image以外）
        $form = $request->except(['_token', 'image']);

        // 画像がアップロードされた場合
        if ($request->hasFile('image')) {
            // もし古い画像があれば削除
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            // 新しいファイル名を生成
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = 'UserId' . $user->id . '_' . $user->email . '.' . $extension;

            // 保存 (storage/app/public/profile_images に保存)
            $path = $request->file('image')->storeAs('profile_images', $filename, 'public');

            // DBに保存するのは相対パス（例: profile_images/user_1.jpg）
            $form['image'] = $path;
        }

        // ユーザー情報を更新
        $user->update($form);

        $search = $request->input('search');
        $tab = $request->input('tab', 'recommend');

        $query = Item::query();

        // 検索
        if (!empty($search)) {
            $query->where('item_name', 'like', "%{$search}%");
        }

        // マイリストタブの場合
        if ($tab === 'mylists') {
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

    public function mypage(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 'sell'); // pageで出品/購入を判定
        $userId = Auth::id(); // ログインユーザーID

        if ($page === 'sell') {
            $tab = 'myitem';
            $query = Item::where('user_id', $userId);
        } elseif ($page === 'buy') {
            $tab = 'buy';
            $query = Item::whereIn('id', function ($q) use ($userId) {
                $q->select('item_id')
                    ->from('purchases')
                    ->where('user_id', $userId);
            });
        } else {
            $tab = '';
            $query = Item::query();
        }

        // 検索
        if (!empty($search)) {
            $query->where('item_name', 'like', "%{$search}%");
        }

        $items = $query->get();

        // SOLD判定
        $items->each(function ($item) {
            $item->is_sold = Purchase::where('item_id', $item->id)->exists();
        });

        return view('mypage', compact('items', 'search', 'tab'));
    }

    public function sellpage(Request $request)
    {
        $user = Auth::user();
        $categories = Category::all();
        $conditions = Condition::all();
        return view('sell', compact('user', 'categories', 'conditions'));
    }

    public function sellitem(ExhibitionRequest $request)
    {
        $item = new Item();
        $item->item_name = $request->item_name;
        $item->brand_name = $request->brand_name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->condition_id = $request->condition;
        $item->user_id = auth()->id();
        $item->save();

        //カテゴリーを紐付け
        $item->categories()->sync($request->categories);

        //カテゴリー英名を取得してファイル名用にまとめる
        $categoryNames = Category::whereIn('id', $request->categories)
            ->pluck('category_name_en')
            ->toArray();

        $categoryNameStr = implode('-', $categoryNames);

        if ($request->hasFile('item_image')) {
            $file = $request->file('item_image');
            $extension = $file->getClientOriginalExtension();

            $fileName = "ItemId{$item->id}_{$categoryNameStr}.{$extension}";

            // publicディスクの items フォルダに保存
            $path = $file->storeAs('item_image', $fileName, 'public');

            // パスをDBに保存（必要に応じて）
            $item->item_image = $path;
            $item->save();
        }

        $search = $request->input('search');
        $page = $request->input('page', 'sell'); // pageで出品/購入を判定
        $userId = Auth::id(); // ログインユーザーID

        if ($page === 'sell') {
            $tab = 'myitem';
            $query = Item::where('user_id', $userId);
        } elseif ($page === 'buy') {
            $tab = 'buy';
            $query = Item::whereIn('id', function ($q) use ($userId) {
                $q->select('item_id')
                    ->from('purchases')
                    ->where('user_id', $userId);
            });
        } else {
            $tab = '';
            $query = Item::query();
        }

        // 検索
        if (!empty($search)) {
            $query->where('item_name', 'like', "%{$search}%");
        }

        $items = $query->get();

        // SOLD判定
        $items->each(function ($item) {
            $item->is_sold = Purchase::where('item_id', $item->id)->exists();
        });

        return view('mypage', compact('items', 'search', 'tab'));
    }
}
