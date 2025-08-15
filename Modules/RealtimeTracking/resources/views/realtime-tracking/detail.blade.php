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
                <div class="col-xl-9">
                    <div class="card">
                        <div class="card-body">
                            <h6>JOB ORDER ID</h6>
                            <h3>{{ $operation->job_order_id }}  {{ $operation->marketing->source }}</h3>
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="fs-15 fw-bold mb-0" style="color: #ABBDCC">CIPL No.</p>
                                    <h4><b>{{ $operation->marketing->no_cipl }}</b></h4>
                                </div>
                                <div class="col-md-4">
                                    <p class="fs-15 fw-bold mb-0" style="color: #ABBDCC">PO No.</p>
                                    <h4><b>{{ $operation->marketing->no_po }}</b></h4>
                                </div>
                                <div class="col-md-4">
                                    <p class="fs-15 fw-bold mb-0" style="color: #ABBDCC">Shipment Type</p>
                                    <h4>
                                        @if ($operation->transportation == 1)
                                            <b>Air Freight</b>
                                        @elseif ($operation->transportation == 2)
                                            <b>Sea Freight</b>
                                        @else
                                            <b>Land Trucking</b>
                                        @endif
                                    </h4>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <p class="fs-15 fw-bold mb-0" style="color: #ABBDCC">Comodity Type</p>
                                    <h4><b>{{ $operation->marketing->description }}</b></h4>
                                </div>
                                <div class="col-md-4">
                                    <p class="fs-15 fw-bold mb-0" style="color: #ABBDCC">Shipper</p>
                                    <h4><b>{{ $operation->recepient_name }}</b></h4>
                                </div>
                                <div class="col-md-4">
                                    <p class="fs-15 fw-bold mb-0" style="color: #ABBDCC">Mode of Transport</p>
                                    <h4>
                                        @if ($operation->transportation_desc)
                                            <b>{{ $operation->transportation_desc }}</b>
                                        @else
                                            <b>Land Trucking</b>
                                        @endif
                                    </h4>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="container">
                                        <ul class="notification">
                                            {{-- departure --}}
                                            <li>
                                                <div class="notification-time">
                                                    @if ($operation->transportation == 1)
                                                    <i class="fa fa-plane" style="font-size: 30px"></i>
                                                    @elseif ($operation->transportation == 2)
                                                    <i class="fa fa-ship" style="font-size: 30px"></i>
                                                    @else
                                                    <i class="fa fa-taxi" style="font-size: 30px"></i>
                                                    @endif
                                                </div>
                                                <div class="notification-icon">
                                                    <a href="javascript:void(0);"></a>
                                                </div>
                                                <div class="notification-body">
                                                    <div class="media mt-0">
                                                        <div class="media-body ms-3 d-flex">
                                                            <div class="">
                                                                <p class="fs-15 fw-bold mb-0" style="color: #ABBDCC">Departure</p>
                                                                <h5 class="mb-0"><b>{{ $operation->pickup_address }}</b></h5>
                                                                <p class="mt-0">{{ $operation->pickup_address_desc }}</p>
                                                                <h6>
                                                                    @if ($operation->departure_date)
                                                                        {{ date('d M Y', strtotime($operation->departure_date)) }}
                                                                    @endif
                                                                     <br> 
                                                                    @if ($operation->departure_time)
                                                                        {{ Carbon\Carbon::parse($operation->departure_time)->format('H:i') }}
                                                                    @endif
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            {{-- end departure --}}
                                            {{-- progress --}}
                                            @foreach ($progress as $p)
                                                <li>
                                                    <div class="notification-time">
                                                        @if ($p->transportation == 1)
                                                        <i class="fa fa-plane" style="font-size: 30px"></i>
                                                        @elseif ($p->transportation == 2)
                                                        <i class="fa fa-ship" style="font-size: 30px"></i>
                                                        @else
                                                        <i class="fa fa-taxi" style="font-size: 30px"></i>
                                                        @endif
                                                    </div>
                                                    <div class="notification-icon">
                                                        <a href="javascript:void(0);"></a>
                                                    </div>
                                                    <div class="notification-body">
                                                        <div class="media mt-0">
                                                            <div class="media-body ms-3 d-flex">
                                                                <div class="">
                                                                    <p class="fs-15 fw-bold mb-0" style="color: #ABBDCC">{{ $p->description }}</p>
                                                                    <h5 class="mb-0"><b>{{ $p->location }}</b></h5>
                                                                    <p class="mt-0">{{ $p->location_desc }}</p>
                                                                    <h6>
                                                                        @if ($p->date_progress)
                                                                            {{ date('d M Y', strtotime($p->date_progress)) }}
                                                                        @endif
                                                                         <br> 
                                                                        @if ($p->time_progress)
                                                                            {{ Carbon\Carbon::parse($p->time_progress)->format('H:i') }}
                                                                        @endif
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                            {{-- end progress --}}
                                            {{-- arrival --}}
                                            <li>
                                                <div class="notification-time">
                                                    @if ($operation->transportation == 1)
                                                    <i class="fa fa-plane" style="font-size: 30px"></i>
                                                    @elseif ($operation->transportation == 2)
                                                    <i class="fa fa-ship" style="font-size: 30px"></i>
                                                    @else
                                                    <i class="fa fa-taxi" style="font-size: 30px"></i>
                                                    @endif
                                                </div>
                                                <div class="notification-icon">
                                                    <a href="javascript:void(0);"></a>
                                                </div>
                                                <div class="notification-body">
                                                    <div class="media mt-0">
                                                        <div class="media-body ms-3 d-flex">
                                                            <div class="">
                                                                <p class="fs-15 fw-bold mb-0" style="color: #ABBDCC">Arrival</p>
                                                                <h5 class="mb-0"><b>{{ $operation->delivery_address }}</b></h5>
                                                                <p class="mt-0">{{ $operation->delivery_address_desc }}</p>
                                                                <h6>
                                                                    @if ($operation->arrival_date)
                                                                        {{ date('d M Y', strtotime($operation->arrival_date)) }}
                                                                    @endif
                                                                     <br> 
                                                                    @if ($operation->arrival_time)
                                                                        {{ Carbon\Carbon::parse($operation->arrival_time)->format('H:i') }}
                                                                    @endif
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            {{-- end arrival --}}
                                        </ul>
                                    </div>
                                </div>
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