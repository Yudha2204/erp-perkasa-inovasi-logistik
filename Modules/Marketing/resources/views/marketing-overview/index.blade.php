@extends('layouts.app')
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <!-- <h1 class="page-title">Marketing</h1> -->
                <h1>Marketing</h1>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- ROW-1 OPEN -->
            <div class="row">
                <div class="col-xl-4 col-md-6">
                    <div class="card">
                        <div class="row">
                            <div class="col-4">
                                <div class="card-img-absolute circle-icon bg-primary text-center align-self-center box-primary-shadow bradius">
                                    <i class="lnr lnr-enter fs-30  text-white mt-4"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="card-body p-4">
                                    <h2 class="mb-2 fw-normal mt-2">{{ $domestic }}</h2>
                                    <h5 class="fw-normal">Domestic This Month</h5>
                                    <a href="javascript:void(0)" class="btn btn-primary btn-sm mb-0">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card">
                        <div class="row">
                            <div class="col-4">
                                <div class="card-img-absolute circle-icon text-center align-self-center box-primary-shadow bradius" style="background-color: #FFC38B">
                                    <i class="lnr lnr-checkmark-circle fs-30  text-white mt-4"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="card-body p-4">
                                    <h2 class="mb-2 fw-normal mt-2">{{ $international }}</h2>
                                    <h5 class="fw-normal">International This Month</h5>
                                    <a href="javascript:void(0)" class="btn btn-primary btn-sm mb-0">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ROW-1 CLOSED -->

            <!-- ROW-2 OPEN -->
            <div class="row">
                @can('view-export@marketing')
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header justify-content-between d-flex d-inline">
                            <h1 class="card-title" style="font-size: 30px;">Marketing Export</h1>
                            <a href="{{ route('marketing.export.index') }}" class="btn btn-primary btn-sm mb-2">View All</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-nowrap text-md-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th>Job Order ID</th>
                                            <th>Client Name</th>
                                            <th>Origin</th>
                                            <th>Destionation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($marketingExport as $mEx)
                                        <tr>
                                            <td>{{ $mEx->job_order_id }}</td>
                                            <td>{{ $mEx->contact->customer_name }}</td>
                                            <td>{{ $mEx->origin }}</td>
                                            <td>{{ $mEx->destination }}</</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan
                <!-- COL END -->
                @can('view-import@marketing')
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header justify-content-between d-flex d-inline">
                            <h1 class="card-title" style="font-size: 30px;">Marketing Import</h1>
                            <a href="{{ route('marketing.import.index') }}" class="btn btn-primary-light btn-sm mb-2">View All</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-nowrap text-md-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th>Job Order ID</th>
                                            <th>Client Name</th>
                                            <th>Origin</th>
                                            <th>Destination</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($marketingImport as $mIm)
                                        <tr>
                                            <td>{{ $mIm->job_order_id }}</td>
                                            <td>{{ $mIm->contact->customer_name }}</td>
                                            <td>{{ $mIm->origin }}</td>
                                            <td>{{ $mIm->destination }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan
                <!-- COL END -->
            </div>
            <!-- ROW-2 CLOSED -->

        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>

@endsection