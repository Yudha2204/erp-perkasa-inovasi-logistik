@extends('layouts.app')
@section('content')
@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush
<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1>General Journal</h1>
            </div>

            <!-- Search Form -->
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('generalledger.general-journal.index') }}" class="row g-3">
                                <div class="col-md-6">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           value="{{ request('search') }}" 
                                           placeholder="Journal number, description, or currency...">
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fe fe-search me-1"></i>Search
                                    </button>
                                    <a href="{{ route('generalledger.general-journal.index') }}" class="btn btn-secondary me-2">
                                        <i class="fe fe-refresh-cw me-1"></i>Reset
                                    </a>
                                    @can('create-general_journal@finance')
                                    <a class="btn btn-success" href="{{ route('generalledger.general-journal.create') }}">
                                        <i class="fe fe-plus me-1"></i>Add New
                                    </a>
                                    @endcan
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PAGE-HEADER END -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="journals-table">
                                    <thead>
                                        <tr>
                                            <th>Journal Number</th>
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th>Currency</th>
                                            <th>Total Debit</th>
                                            <th>Total Credit</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($journals as $journal)
                                        <tr>
                                            <td>{{ $journal->journal_number }}</td>
                                            <td>{{ $journal->date->format('d/m/Y') }}</td>
                                            <td>{{ $journal->description }}</td>
                                            <td>{{ $journal->currency->initial }}</td>
                                            <td class="text-right">{{ number_format($journal->total_debit, 2) }}</td>
                                            <td class="text-right">{{ number_format($journal->total_credit, 2) }}</td>
                                            <td>
                                                <div class="dropdown" style="position: absolute; display: inline-block;">
                                                    <a href="javascript:void(0)" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                                                    <div class="dropdown-menu" style="min-width: 7rem; z-index: 999999999;">
                                                        @can('view-general_journal@finance')
                                                        <a href="{{ route('generalledger.general-journal.show', $journal->id) }}" class="btn text-purple btn-sm dropdown-item"><span class="fe fe-eye fs-14"></span> Detail</a>
                                                        @endcan
                                                        @can('edit-general_journal@finance')
                                                        <a href="{{ route('generalledger.general-journal.edit', $journal->id) }}" class="btn text-warning btn-sm dropdown-item"><span class="fe fe-edit fs-14"></span> Edit</a>
                                                        @endcan
                                                        @can('delete-general_journal@finance')
                                                        <!-- delete -->
                                                        <form action="{{ route('generalledger.general-journal.destroy', $journal->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn text-danger btn-sm dropdown-item" onclick="return confirmDelete()"><span class="fe fe-trash fs-14"></span> Delete</button>
                                                        </form>  
                                                        @endcan
                                                        @can('view-general_journal@finance')
                                                        <a href="{{ route('generalledger.general-journal.jurnal', $journal->id) }}" class="btn text-success btn-sm dropdown-item">
                                                        <i class="fe fe-edit fs-14""></i> Journal
                                                        </a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
    $('#journals-table').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "order": [[ 1, "desc" ]],
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "language": {
            "search": "Search in table:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": "(filtered from _MAX_ total entries)",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            }
        },
        "columnDefs": [
            { "orderable": false, "targets": 6 } // Disable sorting on Actions column
        ]
    });
});
</script>
@endpush
