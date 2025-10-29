<x-jurnal-template
    title="Journal Payment"
    :transactionNumber="$data->transaction"
    :transactionDate="$data->date_payment"
    :description="$data->description"
    :jurnals="$data->jurnal"
    :currency="$data->currency->initial"
    :backUrl="route('finance.payments.purchase-payment.index')"
/>