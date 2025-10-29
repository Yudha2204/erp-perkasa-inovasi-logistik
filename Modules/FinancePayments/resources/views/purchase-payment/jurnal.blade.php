<x-jurnal-template
    title="Journal Payment"
    :transactionNumber="$jurnal->transaction"
    :transactionDate="$jurnal->date_payment"
    :description="$jurnal->description"
    :jurnals="$jurnal->jurnal"
    :currency="$jurnal->currency"
    :jurnalsIDR="$jurnal->jurnalIDR"
    :backUrl="route('finance.payments.purchase-payment.index')"
/>
