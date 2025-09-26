@extends('layouts.app')
@section('content')

@section('title', 'Exchange Revaluation')

@section('content')
<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>Process</h1>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fe fe-exchange-alt"></i>
                                    Exchange Revaluation
                                </h3>
                            </div>
                            <div class="card-body">
                                <div id="error">

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <form id="revaluationForm">
                                        <meta name="csrf-token" content="{{ csrf_token() }}">
                                            <div class="form-group">
                                                <label for="period">Period</label>
                                                <select class="form-control" id="period" name="period" required>
                                                    <option value="">Select Period</option>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="force" name="force">
                                                    <label class="form-check-label" for="force">
                                                        Force revaluation (even if already done)
                                                    </label>
                                                </div>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-primary" id="executeBtn">
                                                <i class="fe fe-play"></i> Execute Revaluation
                                            </button>
                                            
                                            <button type="button" class="btn btn-info" id="checkStatusBtn">
                                                <i class="fe fe-info-circle"></i> Check Status
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div id="statusInfo" class="alert alert-info" style="display: none;">
                                            <h5><i class="fe fe-info-circle"></i> Status Information</h5>
                                            <div id="statusContent"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div id="resultSection" style="display: none;">
                                    <h5><i class="fe fe-chart-line"></i> Revaluation Results</h5>
                                    <div id="resultContent"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Load available periods
    loadPeriods();
    
    // Form submission
    $('#revaluationForm').on('submit', function(e) {
        e.preventDefault();
        executeRevaluation();
    });
    
    // Check status button
    $('#checkStatusBtn').on('click', function() {
        checkStatus();
    });
});

$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
  });

function loadPeriods() {
    $.get('/process/exchange-revaluation/periods')
        .done(function(periods) {
            const select = $('#period');
            periods.forEach(function(period) {
                select.append(`<option value="${period.value}">${period.label}</option>`);
            });
        })
        .fail(function() {
            console.error('Failed to load periods');
        });
}

function executeRevaluation() {
  const formData = {
    period: $('#period').val(),
    force: $('#force').is(':checked')
  };

  if (!formData.period) {
    alert('Please select a period');
    return;
  }

  const url = "{{ route('process.exchange-revaluation.execute') }}"; // safer than hardcoding

  $('#executeBtn').prop('disabled', true).html('<i class="fe fe-spinner fe-spin"></i> Processing...');
  $('#resultSection').hide();

  $.post(url, formData)
    .done(function (response) {
      if (response.success) {
        showResult(response.data);
        showAlert('success', response.message);
      } else {
        if (response.already_done) {
          if (confirm(response.message + ' Do you want to proceed anyway?')) {
            formData.force = true;
            $.post(url, formData).done(r => { showResult(r.data); showAlert('success', r.message); });
            return;
          }
        } else {
          showAlert('error', response.message);
        }
      }
    })
    .fail(function (xhr) {
      const response = xhr.responseJSON;
      showAlert('error', response ? response.message : 'An error occurred');
    })
    .always(function () {
      $('#executeBtn').prop('disabled', false).html('<i class="fe fe-play"></i> Execute Revaluation');
    });
}   

function checkStatus() {
    const period = $('#period').val();
    if (!period) {
        alert('Please select a period');
        return;
    }
    
    $.get('/process/exchange-revaluation/status', { period: period })
        .done(function(response) {
            const statusText = response.is_done ? 
                '<span class="badge badge-success">Already Done</span>' : 
                '<span class="badge badge-warning">Not Done</span>';
            
            $('#statusContent').html(`
                <p><strong>Period:</strong> ${response.period}</p>
                <p><strong>Status:</strong> ${statusText}</p>
            `);
            $('#statusInfo').show();
        })
        .fail(function() {
            showAlert('error', 'Failed to check status');
        });
}

function showResult(data) {
    let html = `
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6>Summary</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Period:</strong> ${data.period}</p>
                        <p><strong>Total Accounts Processed:</strong> ${data.total_accounts_processed}</p>
                        <p><strong>Accounts with Revaluation:</strong> ${data.accounts_with_revaluation}</p>
                        <p><strong>Total Revaluation Amount:</strong> ${formatNumber(data.total_revaluation_amount)} IDR</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    if (data.details && data.details.length > 0) {
        html += `
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6>Revaluation Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Account Code</th>
                                            <th>Account Name</th>
                                            <th>Currency</th>
                                            <th>Balance FC</th>
                                            <th>Exchange Rate</th>
                                            <th>Revaluation Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
        `;
        
        data.details.forEach(function(detail) {
            html += `
                <tr>
                    <td>${detail.account_code}</td>
                    <td>${detail.account_name}</td>
                    <td>${detail.currency}</td>
                    <td>${formatNumber(detail.balance_fc || 0)}</td>
                    <td>${formatNumber(detail.exchange_rate || 0, 4)}</td>
                    <td>${formatNumber(detail.revaluation_amount)} IDR</td>
                </tr>
            `;
        });
        
        html += `
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    $('#resultContent').html(html);
    $('#resultSection').show();
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible mb-2" role="alert">
            ${message}
        </div>
    `;
    
    $('#error').html(alertHtml);
}

function formatNumber(number, decimals = 2) {
    return new Intl.NumberFormat('id-ID', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    }).format(number);
}
</script>
@endpush
