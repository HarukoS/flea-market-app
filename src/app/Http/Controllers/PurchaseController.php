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
    /**
     * 送付先住所変更画面表示
     */
    public function editAddress(Item $item)
    {
        $user = Auth::user();
        $tab = 'recommend';
        return view('address', compact('item', 'user', 'tab'));
    }

    /**
     * 送付先住所変更
     */
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

    /**
     * 購入完了しStripe決済画面表示
     */
    public function preStore(PurchaseRequest $request, Item $item)
    {
        $purchase = Purchase::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'payment_method' => $request->payment_method,
            'postal_code' => $request->postal_code ?? Auth::user()->postal_code,
            'address' => $request->address ?? Auth::user()->address,
            'building' => $request->building ?? Auth::user()->building,
        ]);

        $method = $request->payment_method;

        return redirect()->route('payment.payment', [
            'item' => $item->id,
            'method' => $method
        ]);
    }

    public function payment(Item $item, Request $request)
    {
        $user = Auth::user();
        $paymentMethod = $request->query('method', 'カード支払い');
        return view('payment', compact('item', 'user', 'paymentMethod'));
    }

    public function createIntent(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $item = Item::findOrFail($request->item_id);
        $amount = $item->price * 100;

        $method = $request->input('payment_method', 'カード支払い');
        $types = $method === 'コンビニ支払い' ? ['konbini'] : ['card'];

        $paymentIntent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'jpy',
            'payment_method_types' => $types,
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

    public function store()
    {
        return redirect()->route('index')->with('success', '購入が完了しました！');
    }
}
