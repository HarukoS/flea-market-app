<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <title>決済ページ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #card-element {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 4px;
            height: 40px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>決済ページ</h2>

        <p>商品名：<strong>{{ $item->item_name }}</strong></p>
        <p>価格：<strong>¥{{ number_format($item->price) }}</strong></p>
        <p>支払い方法：<strong>{{ $paymentMethod }}</strong></p>

        @if($paymentMethod === 'カード支払い')
        {{-- カード決済フォーム --}}
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
        {{-- コンビニ支払いフォーム --}}
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
    <script>
        const stripe = Stripe("{{ config('services.stripe.key') }}");

        const style = {
            base: {
                fontSize: '16px',
                color: '#32325d',
                '::placeholder': {
                    color: '#a0aec0'
                },
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif'
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        const elements = stripe.elements();
        const cardElement = elements.create('card', {
            style
        });
        cardElement.mount('#card-element');

        const form = document.getElementById('payment-form');
        const paymentMessage = document.getElementById('payment-message');
        const errorElement = document.getElementById('card-errors');
        const submitButton = document.getElementById('submit-button');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            submitButton.disabled = true;

            try {
                const response = await fetch("{{ route('payment.intent') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        payment_method: "カード支払い",
                        item_id: {{ $item->id }}
                    })
                });

                const data = await response.json();
                const clientSecret = data.clientSecret;

                const {
                    paymentIntent,
                    error
                } = await stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: cardElement
                    }
                });

                if (error) {
                    errorElement.textContent = error.message;
                    submitButton.disabled = false;
                } else if (paymentIntent.status === 'succeeded') {
                    alert('支払いが完了しました！');
                    // サーバー側で DB 登録
                    await fetch("{{ route('payment.store') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            item_id: {{ $item->id }},
                            payment_method: "カード支払い"
                        })
                    });
                    window.location.href = "{{ route('index') }}";
                }
            } catch (err) {
                console.error(err);
                errorElement.textContent = '決済処理でエラーが発生しました。';
                submitButton.disabled = false;
            }
        });
    </script>
    @endif
</body>

</html>