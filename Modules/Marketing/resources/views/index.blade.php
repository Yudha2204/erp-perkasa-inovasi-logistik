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
                                    <h2 class="mb-2 fw-normal mt-2">18</h2>
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
                                <div class="card-img-absolute circle-icon bg-primary text-center align-self-center box-primary-shadow bradius">
                                    <i class="lnr lnr-checkmark-circle fs-30  text-white mt-4"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="card-body p-4">
                                    <h2 class="mb-2 fw-normal mt-2">10</h2>
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
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header justify-content-between d-flex d-inline">
                            <h1 class="card-title" style="font-size: 30px;">Marketing Export</h1>
                            <button type="button" class="btn btn-primary btn-sm mb-2">View All</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-nowrap text-md-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th>Job Order ID</th>
                                            <th>Client Name</th>
                                            <th>From</th>
                                            <th>To</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>0001</td>
                                            <td>Abdul</td>
                                            <td>IND</td>
                                            <td>SG</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- COL END -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header justify-content-between d-flex d-inline">
                            <h1 class="card-title" style="font-size: 30px;">Marketing Import</h1>
                            <button type="button" class="btn btn-primary-light btn-sm mb-2">View All</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-nowrap text-md-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th>Job Order ID</th>
                                            <th>Client Name</th>
                                            <th>From</th>
                                            <th>To</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>0001</td>
                                            <td>Abdul</td>
                                            <td>IND</td>
                                            <td>SG</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- COL END -->
            </div>
            <!-- ROW-2 CLOSED -->

        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>

@endsection