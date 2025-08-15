@extends('layouts.app')
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>Realtime Tracking</h1>
            </div>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex d-inline">
                        <form action="{{ route('realtime-tracking.index') }}">
                            <div class="input-group">
                                <div class="input-group-text"><i class="fe fe-search"></i></div>
                                <input type="text" name="search" value="{{ $search }}" class="form-control" id="autoSizingInputGroup" placeholder="Searching.....">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-nowrap text-md-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Job Order Id</th>
                                            <th>Ship Date</th>
                                            <th>Mode</th>
                                            <th>Type</th>
                                            <th>Origin</th>
                                            <th>Destination</th>
                                            <th>Category</th>
                                            <th>POD Balik</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @forelse ($mergedData as $key => $item)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td><a href="{{ route('realtime-tracking.detail',[$item->marketing->source, $item->id])}}">{{ $item->job_order_id }}</a></td>
                                            <td>{{ $item->departure_date }}</td>
                                            <td>
                                                @if ($item->transportation == 1)
                                                <i class="fa fa-plane" style="font-size: 20px"></i>
                                                @elseif ($item->transportation == 2)
                                                <i class="fa fa-ship" style="font-size: 20px"></i>
                                                @else
                                                <i class="fa fa-taxi" style="font-size: 20px"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->transportation_desc != null)
                                                    {{ $item->transportation_desc }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $item->origin }}</td>
                                            <td>{{ $item->destination }}</td>
                                            <td>{{ $item->marketing->source }}</td>
                                            <td>{{ $item->activity->return_pod_date ?? '' }}</td>
                                            @empty
                                            <td colspan="9"  align="center">
                                                <span class="text-danger">
                                                    <strong>Data is Empty</strong>
                                                </span>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>
@endsection