<x-jurnal-template
    title="Journal Receive Payment"
    :transactionNumber="$jurnal->transaction"
    :transactionDate="$jurnal->date_recieve"
    :description="$jurnal->description"
    :jurnals="$jurnal->jurnal"
    :currency="$jurnal->currency"
    :backUrl="route('finance.piutang.receive-payment.index')"
/>
