<x-jurnal-template
    title="Journal Cash & Bank Out"
    :transactionNumber="$jurnal->transaction"
    :transactionDate="$jurnal->date_kas_out"
    :description="$jurnal->description"
    :jurnals="$jurnal->jurnal"
    :currency="$jurnal->currency->initial"
    :backUrl="route('finance.kas.pembayaran.index')"
/>
