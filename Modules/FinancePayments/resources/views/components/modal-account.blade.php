<div class="modal fade" id="modal-create-account" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">+ Add Account Type</h5>
              <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <form action="{{ route('finance.master-data.account-type.store') }}" method="POST">
                          @csrf
                          <div class="form-group">
                              <label>Classification</label>
                              <select class="form-control select2 form-select"
                                  data-placeholder="Choose one" name="classification_id">
                                  @if(!isset($show))
                                  @foreach ($classifications as $classification)
                                      <option {{ old('classification_id') == $classification->id ? "selected" : "" }} value="{{ $classification->id }}">{{ $classification->code }} - {{ $classification->classification }}</option>
                                  @endforeach
                                  @endif
                              </select>
                          </div>
                          <div class="form-group">
                              <label>Code</label>
                              <input type="text" name="code" value="{{ old('code') }}" id="code" class="form-control">
                          </div>
                          <div class="form-group">
                              <label>Name</label>
                              <input type="text" name="name" value="{{ old('name') }}" id="name" class="form-control">
                          </div>
                          <div class="form-group">
                              <label>Cash Flow</label>
                              <select class="form-control select2 form-select"
                                  data-placeholder="Choose one" name="cash_flow">
                                  <option {{ old('cash_flow') == 0 ? "selected" : "" }} value="0">Undefined</option>
                                  <option {{ old('cash_flow') == 1 ? "selected" : "" }} value="1">Operation Activities</option>
                                  <option {{ old('cash_flow') == 2 ? "selected" : "" }} value="2">Investing Activities</option>
                                  <option {{ old('cash_flow') == 3 ? "selected" : "" }} value="3">Financing Activities</option>
                              </select>
                          </div>
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