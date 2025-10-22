@extends('layouts.app')
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>Process Management</h1>
                <p class="text-muted">Select and execute multiple processes at once</p>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- Results Section (Moved to Top) -->
            <div class="row" id="resultsSection" style="display: none;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Execution Results</h3>
                        </div>
                        <div class="card-body">
                            <div id="resultsContent"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Process Selection Form -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Process Selection</h3>
                        </div>
                        <div class="card-body">
                            <form id="processForm">
                                @csrf
                                
                                <!-- Period Selection (First) -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Select Period:</label>
                                        <select class="form-select" name="period" id="periodSelect">
                                            <option value="">Loading periods...</option>
                                        </select>
                                        <small class="form-text text-muted">Choose the period first, then select processes. Select January to enable Annual P&L Closing.</small>
                                    </div>
                                </div>

                                <!-- Process Selection -->
                                <div class="row mb-4" id="processSection" style="display: none;">
                                    <div class="col-12">
                                        <label class="form-label">Select Processes to Execute:</label>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="processes[]" value="exchange_revaluation" id="exchange_revaluation" disabled>
                                                    <label class="form-check-label" for="exchange_revaluation">
                                                        <strong>Exchange Revaluation</strong>
                                                        <br><small class="text-muted">Monthly currency revaluation process</small>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="processes[]" value="profit_loss_closing" id="profit_loss_closing" disabled>
                                                    <label class="form-check-label" for="profit_loss_closing">
                                                        <strong>Profit & Loss Closing</strong>
                                                        <br><small class="text-muted">Monthly P&L closing process</small>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="processes[]" value="annual_profit_loss_closing" id="annual_profit_loss_closing" disabled>
                                                    <label class="form-check-label" for="annual_profit_loss_closing">
                                                        <strong>Annual P&L Closing</strong>
                                                        <br><small class="text-muted">Annual closing (January only)</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Force Override -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="force" id="force" value="1">
                                            <label class="form-check-label" for="force">
                                                <strong>Force Override</strong>
                                                <br><small class="text-muted">Override existing completed processes</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="row">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-primary" id="checkStatusBtn">
                                            <i class="fe fe-check-circle"></i> Check Status
                                        </button>
                                        <button type="submit" class="btn btn-success" id="executeBtn" disabled>
                                            <i class="fe fe-play"></i> Execute Selected Processes
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="clearBtn">
                                            <i class="fe fe-refresh-cw"></i> Clear Selection
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Section -->
            <div class="row" id="statusSection" style="display: none;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Process Status</h3>
                        </div>
                        <div class="card-body">
                            <div id="statusContent"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let periods = [];

    // Load periods on page load
    loadPeriods();

    // Handle process selection changes
    $('input[name="processes[]"]').change(function() {
        updateExecuteButton();
    });

    // Check status button
    $('#checkStatusBtn').click(function() {
        checkProcessStatus();
    });

    // Clear selection button
    $('#clearBtn').click(function() {
        clearSelection();
    });

    // Form submission
    $('#processForm').submit(function(e) {
        e.preventDefault();
        executeProcesses();
    });

    function loadPeriods() {
        $.get('{{ route("process.periods") }}')
            .done(function(data) {
                periods = data;
                updatePeriodSelect();
            })
            .fail(function() {
                console.error('Failed to load periods');
            });
    }


    function updatePeriodSelect() {
        const select = $('#periodSelect');
        select.empty();
        select.append('<option value="">Select a period...</option>');
        
        periods.forEach(function(period) {
            select.append(`<option value="${period.value}">${period.label}</option>`);
        });
    }


    function updateFormVisibility() {
        const selectedPeriod = $('#periodSelect').val();
        const hasPeriod = selectedPeriod !== '';
        const isJanuary = selectedPeriod && selectedPeriod.endsWith('-01');

        // Show process selection if period is selected
        if (hasPeriod) {
            $('#processSection').show();
            // Enable monthly processes
            $('#exchange_revaluation, #profit_loss_closing').prop('disabled', false);
            
            // Enable/disable annual process based on January selection
            const annualCheckbox = $('#annual_profit_loss_closing');
            if (isJanuary) {
                annualCheckbox.prop('disabled', false);
                annualCheckbox.closest('.form-check').removeClass('text-muted');
            } else {
                annualCheckbox.prop('disabled', true);
                annualCheckbox.prop('checked', false);
                annualCheckbox.closest('.form-check').addClass('text-muted');
            }
        } else {
            $('#processSection').hide();
            // Disable all process checkboxes
            $('input[name="processes[]"]').prop('disabled', true).prop('checked', false);
        }
    }

    function updateExecuteButton() {
        const selectedProcesses = getSelectedProcesses();
        const hasSelection = selectedProcesses.length > 0;
        const hasPeriod = $('#periodSelect').val() !== '';
        
        // Can execute if we have processes and a period selected
        const canExecute = hasSelection && hasPeriod;

        $('#executeBtn').prop('disabled', !canExecute);
    }

    function getSelectedProcesses() {
        return $('input[name="processes[]"]:checked').map(function() {
            return $(this).val();
        }).get();
    }

    function checkProcessStatus() {
        const selectedProcesses = getSelectedProcesses();
        if (selectedProcesses.length === 0) {
            alert('Please select at least one process to check status.');
            return;
        }

        const period = $('#periodSelect').val();
        const year = period ? period.split('-')[0] : '';

        const formData = {
            processes: selectedProcesses,
            period: period,
            year: year
        };

        $.ajax({
            url: '{{ route("process.check-status") }}',
            method: 'POST',
            data: {
                ...formData,
                _token: '{{ csrf_token() }}'
            }
        })
        .done(function(data) {
            displayStatus(data);
        })
        .fail(function(xhr) {
            console.error('Failed to check status:', xhr.responseText);
            let errorMessage = 'Failed to check process status.';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseText) {
                errorMessage = xhr.responseText;
            }
            
            // Show error in status section
            $('#statusContent').html(`
                <div class="alert alert-danger">
                    <h5><i class="fe fe-alert-circle"></i> Error</h5>
                    <p>${errorMessage}</p>
                </div>
            `);
            $('#statusSection').show();
        });
    }

    function executeProcesses() {
        const selectedProcesses = getSelectedProcesses();
        if (selectedProcesses.length === 0) {
            alert('Please select at least one process to execute.');
            return;
        }

        const period = $('#periodSelect').val();
        const year = period ? period.split('-')[0] : '';

        const formData = {
            processes: selectedProcesses,
            period: period,
            year: year,
            force: $('#force').is(':checked')
        };

        // Disable buttons during execution
        $('#executeBtn').prop('disabled', true).html('<i class="fe fe-loader"></i> Executing...');
        $('#checkStatusBtn').prop('disabled', true);

        $.ajax({
            url: '{{ route("process.execute") }}',
            method: 'POST',
            data: {
                ...formData,
                _token: '{{ csrf_token() }}'
            }
        })
        .done(function(data) {
            displayResults(data);
        })
        .fail(function(xhr) {
            console.error('Failed to execute processes:', xhr.responseText);
            
            // If we have a JSON response, use displayResults to show it properly
            if (xhr.responseJSON) {
                displayResults(xhr.responseJSON);
            } else {
                // Only show generic error if no JSON response
                let errorMessage = 'Failed to execute processes.';
                if (xhr.responseText) {
                    errorMessage = xhr.responseText;
                }
                
                $('#resultsContent').html(`
                    <div class="alert alert-danger">
                        <h5><i class="fe fe-alert-circle"></i> Execution Error</h5>
                        <p>${errorMessage}</p>
                        <p><small>Please check the console for more details.</small></p>
                    </div>
                `);
                $('#resultsSection').show();
            }
        })
        .always(function() {
            // Re-enable buttons
            $('#executeBtn').prop('disabled', false).html('<i class="fe fe-play"></i> Execute Selected Processes');
            $('#checkStatusBtn').prop('disabled', false);
        });
    }

    function displayStatus(statuses) {
        let html = '<div class="table-responsive"><table class="table table-striped">';
        html += '<thead><tr><th>Process</th><th>Status</th><th>Period/Year</th></tr></thead><tbody>';
        
        statuses.forEach(function(status) {
            const statusClass = status.is_done ? 'success' : 'warning';
            const statusText = status.is_done ? 'Completed' : 'Not Completed';
            const period = status.period || status.year || 'N/A';
            
            html += `<tr>
                <td>${status.process}</td>
                <td><span class="badge bg-${statusClass}">${statusText}</span></td>
                <td>${period}</td>
            </tr>`;
        });
        
        html += '</tbody></table></div>';
        
        $('#statusContent').html(html);
        $('#statusSection').show();
    }

    function displayResults(data) {
        let html = '';
        
        // Summary FIRST
        if (data.summary) {
            const alertClass = data.summary.failed > 0 ? 'alert-warning' : 'alert-success';
            html += `<div class="alert ${alertClass}">
                <h5>Execution Summary</h5>
                <p>Total Selected: ${data.summary.total_selected} | 
                   Successful: ${data.summary.successful} | 
                   Failed: ${data.summary.failed}</p>
            </div>`;
        }

        // Results SECOND
        if (data.results && data.results.length > 0) {
            html += '<h5>Process Results:</h5>';
            html += '<div class="table-responsive"><table class="table table-striped">';
            html += '<thead><tr><th>Process</th><th>Status</th><th>Message</th></tr></thead><tbody>';
            
            data.results.forEach(function(result) {
                const statusClass = result.success ? 'success' : 'danger';
                const statusText = result.success ? 'Success' : 'Failed';
                
                html += `<tr>
                    <td>${result.process}</td>
                    <td><span class="badge bg-${statusClass}">${statusText}</span></td>
                    <td>${result.message}</td>
                </tr>`;
            });
            
            html += '</tbody></table></div>';
        }
        
        // Show errors THIRD if they exist
        if (data.errors && data.errors.length > 0) {
            html += '<div class="alert alert-danger">';
            html += '<h5><i class="fe fe-alert-circle"></i> Process Errors</h5>';
            data.errors.forEach(function(error) {
                html += `<p class="mb-2"><i class="fe fe-x-circle"></i> ${error}</p>`;
            });
            html += '</div>';
        }
        
        // Show general error message only if no specific errors exist
        else if (data.message && !data.success && (!data.errors || data.errors.length === 0)) {
            html += `<div class="alert alert-danger">
                <h5><i class="fe fe-alert-circle"></i> Error</h5>
                <p>${data.message}</p>
            </div>`;
        }

        // If no results and no errors, show a message
        if (!data.results && !data.errors && !data.message) {
            html += '<div class="alert alert-info">No processes were executed.</div>';
        }

        $('#resultsContent').html(html);
        $('#resultsSection').show();
    }

    function clearSelection() {
        $('input[name="processes[]"]').prop('checked', false);
        $('#periodSelect').val('');
        $('#force').prop('checked', false);
        $('#statusSection').hide();
        $('#resultsSection').hide();
        updateFormVisibility();
        updateExecuteButton();
    }

    // Update execute button when period changes
    $('#periodSelect').change(function() {
        updateFormVisibility();
        updateExecuteButton();
    });
});
</script>
@endpush
