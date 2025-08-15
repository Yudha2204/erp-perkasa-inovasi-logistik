{{-- modal transaction format --}}
<div class="modal fade" id="modal-transaction-format" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Pengaturan Nomor Transaksi</h5>
              <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                  </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <div class="panel panel-primary">
                          <div class="tab-menu-heading tab-menu-heading-boxed">
                              <div class="tabs-menu tabs-menu-border">
                                  <!-- Tabs -->
                                  <ul class="nav panel-tabs">
                                      <li><a href="#tab29" class="active" data-bs-toggle="tab">Custom Format</a></li>
                                      <li><a href="#tab30" data-bs-toggle="tab">Tambah Baru</a></li>
                                  </ul>
                              </div>
                          </div>
                          <div class="panel-body tabs-menu-body mt-3">
                              <div class="tab-content">
                                  <div class="tab-pane active" id="tab29">
                                      <div class="d-flex d-inline">
                                          <select class="form-control select2 form-select"
                                              data-placeholder="select.." id="selectNoTransactions" required>
                                              <option label="select.." selected disabled></option>  
                                              @if(!isset($show))
                                              @foreach ($no_transactions as $t)
                                                @if( $t->no_transaction_quotation)
                                                    <option value="{{ $t->no_transaction_quotation }}" data-id="{{ $t->id }}">{{ $t->no_transaction_quotation }}</option>
                                                @elseif ($t->no_transaction_order)
                                                    <option value="{{ $t->no_transaction_order }}" data-id="{{ $t->id }}">{{ $t->no_transaction_order }}</option>
                                                @elseif ($t->no_transaction_payment)
                                                    <option value="{{ $t->no_transaction_payment }}" data-id="{{ $t->id }}">{{ $t->no_transaction_payment }}</option>   
                                                @endif
                                              @endforeach
                                              @endif
                                          </select>
                                          <button type="button" class="btn text-danger btn-sm" id="deleteOption">
                                              <span class="fe fe-trash-2 fs-14"></span>
                                          </button>
                                      </div>
                                      <br><br>
                                      <div class="mt-3" style="text-align: right">
                                          <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                          <button type="button" id="changeTransactionNum" class="btn btn-primary" class="btn-close" data-bs-dismiss="modal" aria-label="Close">Save</button>
                                      </div>
                                  </div>
                                  <div class="tab-pane" id="tab30">
                                      <form action="{{ $routerCreate }}" method="POST" enctype="multipart/form-data">
                                          @csrf
                                          <div class="row">
                                              <div class="col-lg-4">
                                                  <input required type="text" class="form-control remark-input" name="head-code" placeholder="Example: INV"  />
                                              </div>
                                              <div class="col-lg-4">
                                                  <input type="text" class="form-control remark-input" disabled placeholder="tahun" />
                                              </div>
                                              <div class="col-lg-4">
                                                  <input required type="text" class="form-control remark-input" name="tail-code" placeholder="Example: XV" />
                                              </div>
                                          </div>
                                          <div class="form-group">
                                              <label for="" class="form-label">Mulai Dari Nomor</label>
                                              <input required type="text" class="form-control" name="no_cipl" id="no_cipl" value="{{ old('no_cipl') }}" placeholder=""  required>
                                          </div>
                                          <br><br>
                                          <div class="mt-3" style="text-align: right">
                                              <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                              <button type="submit" class="btn btn-primary">Save</button>
                                          </div>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
  
<script>
    document.getElementById('changeTransactionNum').addEventListener('click', function() {
        var selectedOption = $('#selectNoTransactions').val();
        $('#no_transactions').val(selectedOption);
    })

    document.addEventListener('DOMContentLoaded', function() {
        var deleteButton = document.getElementById('deleteOption');
        deleteButton.addEventListener('click', function(event) {
            event.preventDefault();
            var selectElement = document.getElementById('selectNoTransactions');
            var selectedOption = selectElement.options[selectElement.selectedIndex];
            if (!selectedOption?.value) {
                alert('No option selected');
                return;
            }
            
            var optionId = selectedOption.getAttribute('data-id');
            const optionUrl = "{{ $routerDestroy }}".replace(':id', optionId);

            if(confirm('Are you sure want to delete this item?')) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    method: 'DELETE',
                    dataType: 'json',
                    url: optionUrl,
                    success:function(){
                    alert('Item deleted successfully');
                    $('#no_transactions').val("");
                    location.reload();
                    },
                    error: function() {
                    alert('Item deleted successfully');
                    $('#no_transactions').val("");
                    location.reload();
                    }
                });
            }
        });
    });
</script>