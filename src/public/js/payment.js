document.addEventListener("DOMContentLoaded", () => {
    const stripeKey = document.querySelector('meta[name="stripe-key"]').content;
    const intentUrl = document.querySelector('meta[name="intent-url"]').content;
    const storeUrl = document.querySelector('meta[name="store-url"]').content;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const itemId = document.querySelector('meta[name="item-id"]').content;

    const stripe = Stripe(stripeKey);

    const style = {
        base: {
            fontSize: '16px',
            color: '#32325d',
            '::placeholder': { color: '#a0aec0' },
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        },
        invalid: { color: '#fa755a', iconColor: '#fa755a' },
    };

    const elements = stripe.elements();
    const cardElement = elements.create('card', { style });
    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    const errorElement = document.getElementById('card-errors');
    const submitButton = document.getElementById('submit-button');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        submitButton.disabled = true;

        try {
            const response = await fetch(intentUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    payment_method: "カード支払い",
                    item_id: itemId
                })
            });

            const data = await response.json();
            const clientSecret = data.clientSecret;

            const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: { card: cardElement }
            });

            if (error) {
                errorElement.textContent = error.message;
                submitButton.disabled = false;
            } else if (paymentIntent.status === 'succeeded') {
                alert('支払いが完了しました！');

                await fetch(storeUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        payment_method: "カード支払い"
                    })
                });

                window.location.href = "/";
            }
        } catch (err) {
            console.error(err);
            errorElement.textContent = '決済処理でエラーが発生しました。';
            submitButton.disabled = false;
        }
    });
});