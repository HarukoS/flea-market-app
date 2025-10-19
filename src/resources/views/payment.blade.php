<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <title>決済ページ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">

    <meta name="stripe-key" content="{{ config('services.stripe.key') }}">
    <meta name="intent-url" content="{{ route('payment.intent') }}">
    <meta name="store-url" content="{{ route('payment.store') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="item-id" content="{{ $item->id }}">
</head>

<body>
    <div class="container mt-5">
        <h2>決済ページ</h2>

        <p>商品名：<strong>{{ $item->item_name }}</strong></p>
        <p>価格：<strong>¥{{ number_format($item->price) }}</strong></p>
        <p>支払い方法：<strong>{{ $paymentMethod }}</strong></p>

        @if($paymentMethod === 'カード支払い')
        <form id="payment-form">
            @csrf
            <div class="mb-3">
                <label>カード情報</label>
                <div id="card-element"></div>
            </div>
            <div id="card-errors" class="text-danger mb-3"></div>
            <button type="submit" id="submit-button" class="btn btn-primary">支払う</button>
        </form>
        @else
        <form method="POST" action="{{ route('payment.store') }}">
            @csrf
            <input type="hidden" name="item_id" value="{{ $item->id }}">
            <input type="hidden" name="payment_method" value="コンビニ支払い">
            <button type="submit" class="btn btn-primary">購入確定（コンビニ支払い）</button>
        </form>
        @endif

        <div class="back__button mt-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">戻る</a>
        </div>
    </div>

    @if($paymentMethod === 'カード支払い')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ asset('js/payment.js') }}"></script>
    @endif
</body>

</html>