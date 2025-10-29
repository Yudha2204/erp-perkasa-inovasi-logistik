<x-jurnal-template
    title="Journal Cash & Bank In"
    :transactionNumber="$jurnal->transaction"
    :transactionDate="$jurnal->date_kas_in"
    :description="$jurnal->description"
    :jurnals="$jurnal->jurnal"
    :currency="$jurnal->currency"
    :jurnalsIDR="$jurnal->jurnalIDR"
    :backUrl="route('finance.kas.penerimaan.index')"
/>
