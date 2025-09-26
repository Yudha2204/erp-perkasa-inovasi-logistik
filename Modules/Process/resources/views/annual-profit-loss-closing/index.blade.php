@extends('layouts.app')
@section('content')

@section('title', 'Annual Profit & Loss Process')

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
                                    <i class="fe fe-calendar"></i>
                                    Annual Profit & Loss Closing
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <h5><i class="fe fe-info"></i> Annual P&L Closing Process</h5>
                                    <p>This process will:</p>
                                    <ul>
                                        <li>Reset accumulated balances from Jan-Dec of the selected year for all P&L accounts and Profit Loss Summary</li>
                                        <li>Create automatic reversing journal: Current Earning (Debit) - Retained Earning (Credit)</li>
                                        <li>Transfer accumulated P&L to retained earnings</li>
                                    </ul>
                                    <p><strong>Note:</strong> This process should be run at the beginning of January each year for the previous year's data.</p>
                                </div>

                                <div id="annual-pl-error"></div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <form id="annualPlClosingForm">
                                            <meta name="csrf-token" content="{{ csrf_token() }}">

                                            <div class="form-group">
                                                <label for="year">Year</label>
                                                <select class="form-control" id="year" name="year" required>
                                                    <option value="">Select Year</option>
                                                </select>
                                                <small class="form-text text-muted">Select the year to close (previous years only)</small>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="force" name="force">
                                                    <label class="form-check-label" for="force">
                                                        Force closing (even if already done)
                                                    </label>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary" id="executeBtn">
                                                <i class="fe fe-play"></i> Execute Annual Closing
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
                                    <h5><i class="fe fe-bar-chart-2"></i> Annual Closing Result</h5>
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
    loadYears();

    $('#annualPlClosingForm').on('submit', function(e) {
        e.preventDefault();
        executeAnnualClosing();
    });

    $('#checkStatusBtn').on('click', function() {
        checkStatus();
    });
});

$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
});

function loadYears() {
    $.get("{{ route('process.annual-pl-closing.years') }}")
        .done(function(years) {
            const select = $('#year');
            years.forEach(function(year) {
                select.append(`<option value="${year.value}">${year.label}</option>`);
            });
        })
        .fail(function() {
            console.error('Failed to load years');
        });
}

function executeAnnualClosing() {
  const formData = {
    year: $('#year').val(),
    force: $('#force').is(':checked')
  };

  if (!formData.year) {
    alert('Please select a year');
    return;
  }

  const url = "{{ route('process.annual-pl-closing.execute') }}";

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
      $('#executeBtn').prop('disabled', false).html('<i class="fe fe-play"></i> Execute Annual Closing');
    });
}

function checkStatus() {
    const year = $('#year').val();
    if (!year) {
        alert('Please select a year');
        return;
    }

    $.get("{{ route('process.annual-pl-closing.status') }}", { year: year })
        .done(function(response) {
            const statusText = response.is_done ? 
                '<span class="badge badge-success">Already Done</span>' : 
                '<span class="badge badge-warning">Not Done</span>';

            $('#statusContent').html(`
                <p><strong>Year:</strong> ${response.year}</p>
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
                        <p><strong>Year:</strong> ${data.year || ''}</p>
                        <p><strong>Accumulated P&L:</strong> ${formatNumber(data.accumulated_pl || 0)} IDR</p>
                        <p><strong>Message:</strong> ${data.message || ''}</p>
                    </div>
                </div>
            </div>
        </div>
    `;

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
    $('#annual-pl-error').html(alertHtml);
}

function formatNumber(number, decimals = 2) {
    return new Intl.NumberFormat('id-ID', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    }).format(number);
}
</script>
@endpush
