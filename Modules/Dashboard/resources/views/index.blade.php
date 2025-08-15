@extends('layouts.app')
@php
    $title = 'Dashboard';
@endphp

@section('title', $title . " - " .Auth::user()->name)

@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>Dashboard</h1>
            </div>
            <!-- PAGE-HEADER END -->

            <h3>Date Range</h3>
            <!-- ROW-1 OPEN -->
            <div class="row">
                <div class="col-md-2 col-xl-2">
                    <div class="card" style="background-color: #EAFF98; text-align: center;">
                        <div class="card-body">
                            <h4 class="card-title" style="color: #B6D43C; font-weight: bolder; font-size: large;">On Progress</h4>
                            <h2 class="card-text">2</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-xl-2">
                    <div class="card" style="background-color: #BADEFF;text-align: center;">
                        <div class="card-body">
                            <h4 class="card-title" style="color: #77AEE1; font-weight: bolder; font-size: large;">Shipped</h4>
                            <h2 class="card-text">12</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-xl-2">
                    <div class="card" style="background-color: #9CF1BE;text-align: center;">
                        <div class="card-body">
                            <h4 class="card-title" style="color: #4BB877; font-weight: bolder; font-size: large;">Completed</h4>
                            <h2 class="card-text">20</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ROW-1 CLOSED -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card-body">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Chart name</h3>
                            </div>
                            <div class="card-body">
                                <div id="chart-combination" class="chartsh"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header justify-content-between d-flex d-inline">
                            <h1 class="card-title" style="font-size: 30px;">Shipment Information</h1>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-nowrap text-md-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th>Job Order ID</th>
                                            <th>Client Name</th>
                                            <th>Description</th>
                                            <th>Services</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>0001/02/A</td>
                                            <td>Abdul</td>
                                            <td>Herbal Tea</td>
                                            <td>Import Domestic SF</td>
                                            <td>Delivered</td>
                                        </tr>
                                        <tr>
                                            <td>0001/02/5</td>
                                            <td>Mamad</td>
                                            <td>Herbal Tea</td>
                                            <td>Export Domestic SF</td>
                                            <td>Delivered</td>
                                        </tr>
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