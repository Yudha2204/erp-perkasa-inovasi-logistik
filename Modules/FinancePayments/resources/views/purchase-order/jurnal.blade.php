<x-jurnal-template
    title="Journal Account Payable"
    :transactionNumber="$jurnal->transaction"
    :transactionDate="$jurnal->date_order"
    :description="$jurnal->description"
    :jurnals="$jurnal->jurnal"
    :currency="$jurnal->currency"
    :jurnalsIDR="$jurnal->jurnalIDR"
    :backUrl="route('finance.payments.account-payable.index')"
    :jurnalsIDR="$jurnal->jurnalIDR"
/>
