@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-page">
    <form id="purchase-form" action="{{ route('purchase.pre', ['item' => $item->id]) }}" method="POST">
        @csrf
        <div class="purchase-detail">
            <div class="page-left">

                <div class="item-header">
                    <div class="item-image">
                        <img src="{{ $item->image_url }}" alt="item_image">
                    </div>
                    <div class="item-info">
                        <div class="item-info__name">{{ $item->item_name }}</div>
                        <div class="item-info__price">¥{{ number_format($item->price) }}
                        </div>
                    </div>
                </div>

                <div class="payment">
                    <div class="payment-title">支払い方法</div>
                    <select class="payment-option" id="paymentOption" name="payment_method" required>
                        <option value="" disabled selected>選択してください</option>
                        <option value="コンビニ支払い">コンビニ支払い</option>
                        <option value="カード支払い">カード支払い</option>
                    </select>
                    <div class="form__error">
                        @error('payment_method')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="address">
                    <div class="address-header">
                        <div class="address-title">配送先</div>
                        <a href="{{ route('purchase.address.edit', ['item' => $item->id]) }}">変更する</a>
                    </div>
                    <div class="address-info">
                        <p>〒 {{ session('purchase_address.postal_code', $user->postal_code) }}</p>
                        <p>{{ session('purchase_address.address', $user->address) }}</p>
                        <p>{{ session('purchase_address.building', $user->building) }}</p>
                    </div>
                    <div class="form__error">
                        @error('postal_code')
                        {{ $message }}
                        @enderror
                    </div>
                    <div class="form__error">
                        @error('address')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

            </div>

            <div class=" page-right">
                <div class="summary-box">
                    <div class="row">
                        <div class="left">商品代金</div>
                        <div class="right">¥{{ number_format($item->price) }}</div>
                    </div>
                    <div class="row">
                        <div class="left">支払い方法</div>
                        <div class="right" id="paymentSummary">選択してください</div>
                    </div>
                </div>
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <input type="hidden" name="postal_code" value="{{ session('purchase_address.postal_code', $user->postal_code) }}">
                <input type="hidden" name="address" value="{{ session('purchase_address.address', $user->address) }}">
                <input type="hidden" name="building" value="{{ session('purchase_address.building', $user->building) }}">
                <button type="submit" class="buy-btn">購入する</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('js')
<script src="{{ asset('js/purchase.js') }}"></script>
@endsection