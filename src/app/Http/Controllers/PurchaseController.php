<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{
    public function editAddress(Item $item)
    {
        $user = Auth::user();
        $tab = 'recommend';
        return view('address', compact('item', 'user', 'tab'));
    }

    public function updateAddress(AddressRequest $request, Item $item)
    {
        session([
            'purchase_address' => $request->only(['postal_code', 'address', 'building'])
        ]);

        return redirect()
            ->route('purchase.page', ['item' => $item->id])
            ->with([
                'tab' => 'recommend',
            ]);
    }

    public function purchaseCreate(PurchaseRequest $request)
    {
        $address = session('purchase_address');

        Purchase::create([
            'user_id'     => Auth::id(),
            'item_id'     => $request->item_id,
            'payment_method' => $request->payment_method,
            'postal_code' => $address['postal_code'] ?? Auth::user()->postal_code,
            'address'     => $address['address'] ?? Auth::user()->address,
            'building'    => $address['building'] ?? Auth::user()->building,
        ]);

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
}
