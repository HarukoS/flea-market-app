document.addEventListener("DOMContentLoaded", () => {
    const paymentSelect = document.getElementById("paymentOption");
    const paymentSummary = document.getElementById("paymentSummary");

    if (!paymentSelect || !paymentSummary) return;

    // 初期表示（ページロード時）
    paymentSummary.textContent = paymentSelect.value || "選択してください";

    // 選択変更時
    paymentSelect.addEventListener("change", () => {
        paymentSummary.textContent = paymentSelect.value || "選択してください";
    });
});
