<x-jurnal-template
    title="Journal Invoice"
    :transactionNumber="$jurnal->transaction"
    :transactionDate="$jurnal->date_invoice"
    :description="$jurnal->description"
    :jurnals="$jurnal->jurnal"
    :currency="$jurnal->currency"
    :jurnalsIDR="$jurnal->jurnalIDR"
    :backUrl="route('finance.piutang.invoice.index')"
/>
