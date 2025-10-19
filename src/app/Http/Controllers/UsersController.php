<?php

namespace App\Http\Controllers;

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
    /**
     * プロフィール編集画面表示
     */
    public function profile()
    {
        return view('profile');
    }

    /**
     * プロフィール編集
     */
    public function profileUpdate(ProfileRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $form = $request->except(['_token', 'image']);

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            // 新しいファイル名を生成
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = 'UserId' . $user->id . '_' . $user->email . '.' . $extension;

            $path = $request->file('image')->storeAs('profile_images', $filename, 'public');

            $form['image'] = $path;
        }

        $user->update($form);

        $search = $request->input('search');
        $tab = $request->input('tab', 'recommend');

        $query = Item::query();

        if (!empty($search)) {
            $query->where('item_name', 'like', "%{$search}%");
        }

        if ($tab === 'mylists') {
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
     * プロフィール画面表示
     */
    public function mypage(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 'sell');
        $userId = Auth::id();

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

        if (!empty($search)) {
            $query->where('item_name', 'like', "%{$search}%");
        }

        $items = $query->get();

        $items->each(function ($item) {
            $item->is_sold = Purchase::where('item_id', $item->id)->exists();
        });

        return view('mypage', compact('items', 'search', 'tab'));
    }

    /**
     * 商品出品画面表示
     */
    public function sellpage(Request $request)
    {
        $user = Auth::user();
        $categories = Category::all();
        $conditions = Condition::all();
        return view('sell', compact('user', 'categories', 'conditions'));
    }

    /**
     * 商品出品
     */
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

        $item->categories()->sync($request->categories);

        $categoryNames = Category::whereIn('id', $request->categories)
            ->pluck('category_name_en')
            ->toArray();

        $categoryNameStr = implode('-', $categoryNames);

        if ($request->hasFile('item_image')) {
            $file = $request->file('item_image');
            $extension = $file->getClientOriginalExtension();

            $fileName = "ItemId{$item->id}_{$categoryNameStr}.{$extension}";

            $path = $file->storeAs('item_image', $fileName, 'public');

            $item->item_image = $path;
            $item->save();
        }

        $search = $request->input('search');
        $page = $request->input('page', 'sell');
        $userId = Auth::id();

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

        if (!empty($search)) {
            $query->where('item_name', 'like', "%{$search}%");
        }

        $items = $query->get();

        $items->each(function ($item) {
            $item->is_sold = Purchase::where('item_id', $item->id)->exists();
        });

        return view('mypage', compact('items', 'search', 'tab'));
    }
}
