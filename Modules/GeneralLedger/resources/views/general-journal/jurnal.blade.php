<x-jurnal-template
    title="General Journal"
    :transactionNumber="$jurnal->journal_number"
    :transactionDate="$jurnal->date_recieve"
    :description="$jurnal->description"
    :jurnals="$jurnal->jurnal"
    :currency="$jurnal->currency"
    :jurnalsIDR="$jurnal->jurnalIDR"
    :backUrl="route('generalledger.general-journal.index')"
/>
