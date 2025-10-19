<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use Stripe\Stripe;
use Stripe\PaymentIntent;

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

    // public function purchaseCreate(PurchaseRequest $request)
    // {
    //     $address = session('purchase_address');

    //     Purchase::create([
    //         'user_id'     => Auth::id(),
    //         'item_id'     => $request->item_id,
    //         'payment_method' => $request->payment_method,
    //         'postal_code' => $address['postal_code'] ?? Auth::user()->postal_code,
    //         'address'     => $address['address'] ?? Auth::user()->address,
    //         'building'    => $address['building'] ?? Auth::user()->building,
    //     ]);

    //     $search = $request->input('search');
    //     $tab = $request->input('tab', 'recommend');

    //     $query = Item::query();

    //     // 検索
    //     if (!empty($search)) {
    //         $query->where('item_name', 'like', "%{$search}%");
    //     }

    //     // マイリストタブの場合
    //     if ($tab === 'mylists') {
    //         /** @var \App\Models\User|null $user */
    //         $user = Auth::user();

    //         // ログインしていない、またはメール未認証なら空コレクション
    //         if (!$user || !$user->hasVerifiedEmail()) {
    //             $items = collect();
    //             return view('index', compact('items', 'search', 'tab'));
    //         }

    //         // @noinspection PhpUndefinedMethodInspection
    //         /** @var \Illuminate\Support\Collection|\App\Models\Item[] $likedItemIds */
    //         $likedItemIds = $user->likedItems()->pluck('items.id');
    //         $query->whereIn('id', $likedItemIds);
    //     }

    //     /** @var \Illuminate\Support\Collection|\App\Models\Item[] $items */
    //     $items = $query->get();

    //     $items->each(function ($item) {
    //         $item->is_sold = Purchase::where('item_id', $item->id)->exists();
    //     });

    //     return view('index', compact('items', 'search', 'tab'));
    // }

    //Stripe決済画面へ遷移
    public function create(Item $item, Request $request)
    {
        $user = Auth::user();
        $paymentMethod = $request->query('method', 'カード支払い');
        return view('create', compact('item', 'user', 'paymentMethod'));
    }

    public function createIntent(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        // フロントから受け取る金額や商品IDを使うのが理想
        $paymentIntent = PaymentIntent::create([
            'amount' => 1000, // ← 仮：ここを商品価格に変えることも可能
            'currency' => 'jpy',
            'payment_method_types' => ['card'],
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

    // Stripe決済後に呼ばれる
    public function store(Request $request)
    {
        // DB 登録処理
        Purchase::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
            'payment_method' => $request->payment_method,
            'postal_code' => $request->postal_code ?? Auth::user()->postal_code,
            'address' => $request->address ?? Auth::user()->address,
            'building' => $request->building ?? Auth::user()->building,
        ]);

        return redirect()->route('index')->with('success', '購入が完了しました！');
    }
}
