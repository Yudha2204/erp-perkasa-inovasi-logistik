@extends('layouts.app')
@section('content')

    <div class="main-content app-content mt-0" style="padding-bottom: 50px;">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container-fluid">

                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <h1 style="font-size: 36px; font-weight: bold; color: #015377;">Dashboard</h1>
                    <p>{{$domesticperPaginator}}</p>
                </div>
                <!-- PAGE-HEADER END -->

                <div style="display: flex; width: 100%; height: 120px; gap: 20px;">
                    <div class="d-flex justify-content-center align-items-center" style="width: 255px; height: 100px; border-radius: 10px; background-color: white;">
                        <div class="d-flex justify-content-center align-items-center" style="border-radius: 100%; height: 70px; width: 70px; background-color: #FFE0EB;">
                            <svg svg width="45" height="43" viewBox="0 0 45 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M30.901 26.5605H38.4385C39.6817 26.5605 40.874 26.0667 41.7531 25.1876C42.6322 24.3085 43.126 23.1163 43.126 21.873C43.126 20.6298 42.6322 19.4376 41.7531 18.5585C40.874 17.6794 39.6817 17.1855 38.4385 17.1855H12.4098C12.0617 17.1854 11.7205 17.0883 11.4244 16.9051C11.1284 16.722 10.8892 16.4601 10.7335 16.1487L9.89354 14.4724C9.73794 14.161 9.49871 13.8991 9.20265 13.716C8.9066 13.5328 8.56541 13.4357 8.21729 13.4355H4.72042C4.40998 13.4353 4.10434 13.5121 3.83091 13.6591C3.55748 13.8061 3.32483 14.0187 3.15384 14.2777C2.98284 14.5368 2.87885 14.8343 2.8512 15.1435C2.82355 15.4527 2.87311 15.764 2.99542 16.0493L7.01354 25.4243C7.15822 25.7618 7.39882 26.0494 7.70547 26.2514C8.01212 26.4534 8.37134 26.5609 8.73854 26.5605H15.9817C16.2879 26.5607 16.5894 26.6359 16.8599 26.7796C17.1304 26.9232 17.3616 27.1309 17.5332 27.3845C17.7049 27.6381 17.8118 27.9299 17.8447 28.2344C17.8775 28.5389 17.8353 28.8468 17.7217 29.1312L16.0323 33.3668C15.9191 33.6509 15.8772 33.9585 15.9101 34.2625C15.9431 34.5666 16.0499 34.858 16.2213 35.1113C16.3927 35.3646 16.6235 35.5722 16.8935 35.7158C17.1635 35.8595 17.4646 35.9349 17.7704 35.9355H21.5992C21.8804 35.9357 22.158 35.8725 22.4115 35.7508C22.6649 35.6291 22.8878 35.4519 23.0635 35.2324L29.4385 27.2637C29.6141 27.0444 29.8366 26.8674 30.0898 26.7457C30.3429 26.624 30.6202 26.5607 30.901 26.5605ZM17.8135 15.3105H26.7198L23.0635 10.4355C22.8889 10.2027 22.6624 10.0137 22.4021 9.8835C22.1417 9.75332 21.8546 9.68555 21.5635 9.68555H18.0348C17.7153 9.68572 17.4012 9.76752 17.1222 9.92319C16.8432 10.0789 16.6086 10.3032 16.4407 10.575C16.2728 10.8468 16.1771 11.157 16.1627 11.4762C16.1483 11.7953 16.2157 12.1129 16.3585 12.3987L17.8135 15.3105Z" fill="#FF82AC"/>
                            </svg>

                        </div>
                        <div class="d-flex flex-column" style="margin-left: 20px;">
                            <p style="font-size: 16px; color: #718EBF; margin-bottom: -5px;">International</p>
                            <p id="international_count" style="font-size: 25px; color: #232323; margin-bottom: -5px;">{{$international}}</p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center" style="width: 255px; height: 100px; border-radius: 10px; background-color: white;">
                        <div class="d-flex justify-content-center align-items-center" style="border-radius: 100%; height: 70px; width: 70px; background-color: #FFF5D9;">
                            <svg width="37" height="21" viewBox="0 0 37 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.875 0.75H25L29.875 7.25H33.125C34.9287 7.25 36.375 8.69625 36.375 10.5V15.375H33.125C33.125 16.6679 32.6114 17.9079 31.6971 18.8221C30.7829 19.7364 29.5429 20.25 28.25 20.25C26.9571 20.25 25.7171 19.7364 24.8029 18.8221C23.8886 17.9079 23.375 16.6679 23.375 15.375H13.625C13.625 16.6679 13.1114 17.9079 12.1971 18.8221C11.2829 19.7364 10.0429 20.25 8.75 20.25C7.45707 20.25 6.21709 19.7364 5.30285 18.8221C4.38861 17.9079 3.875 16.6679 3.875 15.375H0.625V4C0.625 2.19625 2.07125 0.75 3.875 0.75ZM3.0625 3.1875V7.25H16.0625V3.1875H3.0625ZM18.5 3.1875V7.25H26.8525L23.7812 3.1875H18.5ZM8.75 12.9375C8.10353 12.9375 7.48355 13.1943 7.02643 13.6514C6.56931 14.1085 6.3125 14.7285 6.3125 15.375C6.3125 16.0215 6.56931 16.6415 7.02643 17.0986C7.48355 17.5557 8.10353 17.8125 8.75 17.8125C9.39647 17.8125 10.0165 17.5557 10.4736 17.0986C10.9307 16.6415 11.1875 16.0215 11.1875 15.375C11.1875 14.7285 10.9307 14.1085 10.4736 13.6514C10.0165 13.1943 9.39647 12.9375 8.75 12.9375ZM28.25 12.9375C27.6035 12.9375 26.9835 13.1943 26.5264 13.6514C26.0693 14.1085 25.8125 14.7285 25.8125 15.375C25.8125 16.0215 26.0693 16.6415 26.5264 17.0986C26.9835 17.5557 27.6035 17.8125 28.25 17.8125C28.8965 17.8125 29.5165 17.5557 29.9736 17.0986C30.4307 16.6415 30.6875 16.0215 30.6875 15.375C30.6875 14.7285 30.4307 14.1085 29.9736 13.6514C29.5165 13.1943 28.8965 12.9375 28.25 12.9375Z" fill="#FFBB38"/>
                            </svg>
                        </div>
                        <div class="d-flex flex-column" style="margin-left: 20px;">
                            <p style="font-size: 16px; color: #718EBF; margin-bottom: -5px;">Domestik</p>
                            <p id="domestic_count" style="font-size: 25px; color: #232323; margin-bottom: -5px;">{{$domestic}}</p>
                        </div>
                    </div>
                </div>    
                <h1 style="font-size: 22px; font-weight: 600; color: #333B69; line-height: 27.72px;">International & Domestic Overview</h1>
                <div style="display: flex; width: 100%; height: 500px; gap: 20px;">
                    <div style="width:50%; height: 100%; border-radius: 10px; background-color: white;">
                        <div class="chart-container">
                            <canvas id="chartBar" class="h-500"></canvas>
                            <canvas id="chartBarDate" class="h-500" style="display: none;"></canvas>
                        </div>
                    </div>
                    <div style="width:50%; height: 100%; border-radius: 10px; background-color: white;">
                        <div class="chart-container">
                            <canvas id="chartPie" class="h-500"></canvas>
                            <canvas id="chartPieDateInternational" class="h-500" style="display: none;"></canvas>
                            <canvas id="chartPieDateDomestic" class="h-500" style="display: none;"></canvas>
                        </div>
                    </div>
                </div>

                <div style="width: 100%; height: fit-content; margin-top: 50px;">
                    <h1 style="font-size: 22px; font-weight: 600; color: #333B69; line-height: 27.72px;">Activity</h1>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-4">
                            <button id="international-btn" class="filter-btn btn" data-target="international" style="font-size: 16px; font-weight: 500; color: black; padding-bottom: 5px; padding-left: 10px; padding-right: 10px; border-radius: 1px;">International</button>
                            <button id="domestic-btn" class="filter-btn btn" data-target="domestic" style="font-size: 16px; font-weight: 500; color: black; padding-bottom: 5px; padding-left: 10px; padding-right: 10px; border-radius: 1px;">Domestic</button>

                            <!-- date -->
                            <button id="international-btn-date" class="filter-btn btn" data-target="international" style="display: none; font-size: 16px; font-weight: 500; color: black; padding-bottom: 5px; padding-left: 10px; padding-right: 10px; border-radius: 1px;">International</button>
                            <button id="domestic-btn-date" class="filter-btn btn" data-target="domestic" style="display: none; font-size: 16px; font-weight: 500; color: black; padding-bottom: 5px; padding-left: 10px; padding-right: 10px; border-radius: 1px;">Domestic</button>
                        </div>
                        <div class="d-flex gap-3">
                            <span style="margin-top: 3px;">Select date:</span>
                            <div class="d-flex gap-2">
                                <input type="text" id="start_datepicker" name="start_date" style="width: 100px; text-align: center; border: 1px solid #D8D8DC;">
                                <div style="margin-top: 2px;">-</div>
                                <input type="text" id="end_datepicker" name="end_date" style="width: 100px; text-align: center; border: 1px solid #D8D8DC;">
                            </div>
                            <button class="btn btn-primary" id="customDate" style="padding: 0px 20px;">submit</button>
                        </div>
                    </div>
                    <div style="background-color: white; padding: 50px 10px; margin-top: 30px;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" style="font-size: 16px; font-weight: 600; color: #015377; line-height: 20.16px;">Kategori</th>
                                    <th scope="col" style="font-size: 16px; font-weight: 600; color: #015377; line-height: 20.16px;">Customer</th>
                                    <th scope="col" style="font-size: 16px; font-weight: 600; color: #015377; line-height: 20.16px;">Asal</th>
                                    <th scope="col" style="font-size: 16px; font-weight: 600; color: #015377; line-height: 20.16px;">Tujuan</th>
                                    <th scope="col" style="font-size: 16px; font-weight: 600; color: #015377; line-height: 20.16px;">Date</th>
                                    <th scope="col" style="font-size: 16px; font-weight: 600; color: #015377; line-height: 20.16px;">Keterangan</th>
                                    <th scope="col" style="font-size: 16px; font-weight: 600; color: #015377; line-height: 20.16px;">Status</th>
                                </tr>
                            </thead>
                            <tbody id="domestic-data">
                                @foreach ($domesticperPaginator as $data)
                                    <tr>
                                        <td>{{ $data->marketing->source }} - 
                                            {{ $data->transportation == 1 ? "Air Freight" : ($data->transportation == 2 ? "Sea Freight" : "Land Trucking") }} - 
                                            {{ $data->transportation_desc }}
                                        </td>
                                        <td>{{$data->marketing->contact->customer_name ?? 'undefined'}}</td>
                                        <td>{{$data->origin}}</td>
                                        <td>{{$data->destination}}</td>
                                        <td>{{$data->departure_date ? $data->departure_date : "" }}</td>
                                        <td>{{$data->marketing->description}}</td>
                                        <td>{{ $data->status == 1 ? " On - Progress" : ($data->status == 2 ? "End - Progress" : "Unknowns") }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tbody id="international-data">
                                @foreach($internationalperPaginator as $data)
                                    <tr>
                                        <td>{{ $data->marketing->source }} - 
                                            {{ $data->transportation == 1 ? "Air Freight" : ($data->transportation == 2 ? "Sea Freight" : "Land Trucking") }} - 
                                            {{ $data->transportation_desc }}
                                        </td>
                                        <td>{{$data->marketing->contact->customer_name ?? 'undefined'}}</td>
                                        <td>{{$data->origin}}</td>
                                        <td>{{$data->destination}}</td>
                                        <td>{{$data->departure_date ? $data->departure_date : "" }}</td>
                                        <td>{{$data->marketing->description}}</td>
                                        <td>{{ $data->status == 1 ? " On - Progress" : ($data->status == 2 ? "End - Progress" : "Unknowns") }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end items-align-center" style="margin-top: 40px;">
                            <div>
                                {{$paginator}}
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
<script src="../assets/plugins/chart/Chart.bundle.js"></script>
<script src="../assets/js/chart.js"></script>
<!-- Masukkan link CSS jQuery UI di header template Anda -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- Masukkan jQuery dan jQuery UI di footer template Anda -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<!-- date picker -->
<script>
    $(function() {
        $("#start_datepicker").datepicker({
            onSelect: function(selectedDate) {
                $("#end_datepicker").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#end_datepicker").datepicker({
            onSelect: function(selectedDate) {
                $("#start_datepicker").datepicker("option", "maxDate", selectedDate);
            }
        });

        // bar
        var international = [0, 0, 0, 0, 0, 0, 0];
        var domestic = [0, 0, 0, 0, 0, 0, 0];
        function updateChart(international, domestic) {
            var label = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            var ctx = document.getElementById("chartBarDate").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: label,
                    datasets: [{
                        label: 'International',
                        data: international,
                        backgroundColor: '#6c5ffc',
                        borderColor: '#6c5ffc',
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointRadius: 2
                    }, {
                        label: 'Domestic',
                        data: domestic,
                        backgroundColor: '#05c3fb',
                        borderColor: '#05c3fb',
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointRadius: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "#9ba6b5",
                            },
                            display: true,
                            gridLines: {
                                color: 'rgba(119, 119, 142, 0.2)'
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                fontColor: "#9ba6b5",
                            },
                            display: true,
                            gridLines: {
                                color: 'rgba(119, 119, 142, 0.2)'
                            },
                            scaleLabel: {
                                display: false,
                                labelString: 'Thousands',
                                fontColor: 'rgba(119, 119, 142, 0.2)'
                            }
                        }]
                    },
                    legend: {
                        labels: {
                            fontColor: "#9ba6b5"
                        },
                    },
                }
            });
        }

        // pie
        var airFreightInter = 0;
        var seaFreightInter = 0;
        var landTruckingInter = 0;
        var defaultTotalInter = 0;

        var airFreightDomestic = 0;
        var seaFreightDomestic = 0;
        var landTruckingDomestic = 0;
        var defaultTotalDomestic = 0;

        function drawPieChartInter(dataValues, persentase) {
            var label = persentase.map((item, index) => item + " " + ["Air Freight", "Sea Freight", "Land Trucking"][index]);
            var backgroundColor = ["#FF6384", "#36A2EB", "#FFCE56"];
            var ctxPie = document.getElementById("chartPieDateInternational").getContext('2d');
            var myPieChart = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: label,
                    datasets: [{
                        data: dataValues,
                        backgroundColor: backgroundColor,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        labels: {
                            fontColor: "#9ba6b5"
                        },
                    },
                }
            });
        }
        function drawPieChartDomistic(dataValues, persentase) {
            var label = persentase.map((item, index) => item + " " + ["Air Freight", "Sea Freight", "Land Trucking"][index]);
            var backgroundColor = ["#FF6384", "#36A2EB", "#FFCE56"];
            var ctxPie = document.getElementById("chartPieDateDomestic").getContext('2d');
            var myPieChart = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: label,
                    datasets: [{
                        data: dataValues,
                        backgroundColor: backgroundColor,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        labels: {
                            fontColor: "#9ba6b5"
                        },
                    },
                }
            });
        }

        $("#customDate").click(function() {
            var startDate = $("#start_datepicker").val();
            var endDate = $("#end_datepicker").val();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'GET',
                dataType: 'json',
                data: {
                    'startDate': startDate,
                    'endDate': endDate
                },
                url: '{{ route('dashboard.getDataByDate') }}',
                success:function(response) 
                {
                    console.log(response);
                    if(response){
                        document.getElementById("international-btn").style.display = "none";
                        document.getElementById("domestic-btn").style.display = "none";
                        document.getElementById("international-btn-date").style.display = "block";
                        document.getElementById("domestic-btn-date").style.display = "block";
                        
                        const international_data = document.getElementById("international-data");
                        international_data.innerHTML = "";
                        const domestic_data = document.getElementById("domestic-data");
                        domestic_data.innerHTML = "";

                        const international_count = document.getElementById("international_count").innerText = response.internationalperPaginator.data.length;
                        const domestic_count = document.getElementById("domestic_count").innerText = response.domesticperPaginator.data.length;
    
                        response.internationalperPaginator.data.forEach(element => {
                            var formTemplate = `
                                <tr>
                                    <td>${element.marketing.source} - ${element.transportation  == 1 ? "Air Freight" : (element.transportation == 2 ? "Sea Freight" : "Land Trucking")} - ${element.transportation_desc}</td>
                                    <td>${element.user.customer_name}</td>
                                    <td>${element.origin}</td>
                                    <td>${element.destination}</td>
                                    <td>${element.departure_date ? element.departure_date: "" }</td>
                                    <td>${element.marketing.description}</td>
                                    <td>${element.status  == 1 ? " On - Progress" : (element.status == 2 ? "End - Progress" : "Unknowns")}</td>
                                </tr>
                            `;
                            international_data.innerHTML += formTemplate;
                        });

                        response.domesticperPaginator.data.forEach(element => {
                            var formTemplate = `
                                <tr>
                                    <td>${element.marketing.source} - ${element.transportation  == 1 ? "Air Freight" : (element.transportation == 2 ? "Sea Freight" : "Land Trucking")} - ${element.transportation_desc}</td>
                                    <td>${element.user.customer_name}</td>
                                    <td>${element.origin}</td>
                                    <td>${element.destination}</td>
                                    <td>${element.departure_date ? element.departure_date: "" }</td>
                                    <td>${element.marketing.description}</td>
                                    <td>${element.status  == 1 ? " On - Progress" : (element.status == 2 ? "End - Progress" : "Unknowns")}</td>
                                </tr>
                            `;
                            domestic_data.innerHTML += formTemplate;
                        });

                        // bar
                        document.getElementById('chartBar').style.display = "none"
                        document.getElementById('chartBarDate').style.display = "block"
                        international = [response.international.Sun, response.international.Mon, response.international.Tue, response.international.Wed, response.international.Thu, response.international.Fri, response.international.Sat];
                        domestic = [response.domestic.Sun, response.domestic.Mon, response.domestic.Tue, response.domestic.Wed, response.domestic.Thu, response.domestic.Fri, response.domestic.Sat];
                        updateChart(international, domestic);

                        // pie
                        // international
                        airFreightInter = 0;
                        seaFreightInter = 0;
                        landTruckingInter = 0;
                        defaultTotalInter = 0;
                        
                        document.getElementById('chartPie').style.display = "none"
                        document.getElementById('chartPieDateInternational').style.display = "block"
                        response.internationalperPaginator.data.forEach(element => {
                            if(element.transportation == 1){
                                airFreightInter++;
                            }
                            else if(element.transportation == 2){
                                seaFreightInter++;
                            }
                            else{
                                landTruckingInter++;
                            }
                        });
                        var inter = [airFreightInter, seaFreightInter, landTruckingInter];
                        defaultTotalInter = inter.reduce((acc, curr) => acc + curr, 0);
                        var persentaseInter = inter.map(item => ((item / defaultTotalInter) * 100).toFixed(2) + "%");
                        drawPieChartInter(inter, persentaseInter);

                        // domestic
                        airFreightDomestic = 0;
                        seaFreightDomestic = 0;
                        landTruckingDomestic = 0;
                        defaultTotalDomestic = 0;
                        response.domesticperPaginator.data.forEach(element => {
                            if(element.transportation == 1){
                                airFreightDomestic++;
                            }
                            else if(element.transportation == 2){
                                seaFreightDomestic++;
                            }
                            else{
                                landTruckingDomestic++;
                            }
                        });

                        var domesticPie = [airFreightDomestic, seaFreightDomestic, landTruckingDomestic];
                        defaultTotalDomestic = domesticPie.reduce((acc, curr) => acc + curr, 0);
                        var persentaseDomestic = domesticPie.map(item => ((item / defaultTotalDomestic) * 100).toFixed(2) + "%");
                        drawPieChartDomistic(domesticPie, persentaseDomestic);
                    }
                }
            })
        });

        $("#domestic-data").hide();
        function highlightButton(btn) {
            btn.css({
                "font-size": "16px",
                "font-weight": "500",
                "color": "#1814F3",
                "padding-bottom": "5px",
                "border-bottom": "1px solid #1814F3",
                "padding-left": "10px",
                "padding-right": "10px",
                "border-radius": "1px"
            });
        }
        function resetButton(btn) {
            btn.css({
                "font-size": "16px",
                "font-weight": "500",
                "color": "",
                "padding-bottom": "5px",
                "border-bottom": "",
                "padding-left": "10px",
                "padding-right": "10px",
                "border-radius": "1px"
            });
        }
        highlightButton($('#international-btn-date')); 

        $("#international-btn-date").click(function() {
            $("#domestic-data").hide();
            $("#international-data").show();
            document.getElementById('chartPieDateInternational').style.display = "block"
            document.getElementById('chartPieDateDomestic').style.display = "none"

            highlightButton($(this)); 
            resetButton($('#domestic-btn-date')); 
        });

        $("#domestic-btn-date").click(function() {
            $("#international-data").hide();
            $("#domestic-data").show();
            document.getElementById('chartPieDateInternational').style.display = "none"
            document.getElementById('chartPieDateDomestic').style.display = "block"

            highlightButton($(this)); 
            resetButton($('#international-btn-date')); 
        });
    });
</script>

<!-- pie -->
<script>
    $(document).ready(function() {
        $("#domestic-data").hide();
        function highlightButton(btn) {
            btn.css({
                "font-size": "16px",
                "font-weight": "500",
                "color": "#1814F3",
                "padding-bottom": "5px",
                "border-bottom": "1px solid #1814F3",
                "padding-left": "10px",
                "padding-right": "10px",
                "border-radius": "1px"
            });
        }
        function resetButton(btn) {
            btn.css({
                "font-size": "16px",
                "font-weight": "500",
                "color": "",
                "padding-bottom": "5px",
                "border-bottom": "",
                "padding-left": "10px",
                "padding-right": "10px",
                "border-radius": "1px"
            });
        }

        // pie
        var airFreight = 0;
        var seaFreight = 0;
        var landTrucking = 0;
        var defaultTotal = 0;

        function drawPieChart(dataValues, persentase) {
            var label = persentase.map((item, index) => item + " " + ["Air Freight", "Sea Freight", "Land Trucking"][index]);
            var backgroundColor = ["#FF6384", "#36A2EB", "#FFCE56"];
            var ctxPie = document.getElementById("chartPie").getContext('2d');
            var myPieChart = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: label,
                    datasets: [{
                        data: dataValues,
                        backgroundColor: backgroundColor,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        labels: {
                            fontColor: "#9ba6b5"
                        },
                    },
                }
            });
        }

        // Fungsi untuk memuat data dari backend
        function loadData(url) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'GET',
                dataType: 'json',
                url: url,
                success: function(response) {
                    airFreight = 0;
                    seaFreight = 0;
                    landTrucking = 0;
                    if (response.length > 0) {
                        response.forEach(element => {
                            if(element.transportation == 1){
                                airFreight++;
                            }
                            else if(element.transportation == 2){
                                seaFreight++;
                            }
                            else{
                                landTrucking++;
                            }
                        });
                    }
                   
                    var data = [airFreight, seaFreight, landTrucking];
                    defaultTotal = data.reduce((acc, curr) => acc + curr, 0);
                    if (defaultTotal !== 0) {
                        var persentase = data.map(item => ((item / defaultTotal) * 100).toFixed(2) + "%");
                    } else {
                        persentase = data.map(item => "0%");
                    }
                    drawPieChart(data, persentase);
                },
            });
        }

        // Default: Load data international
        loadData('{{ route('dashboard.getDataInternational') }}');
        highlightButton($('#international-btn')); 

        $("#international-btn").click(function() {
            $("#domestic-data").hide();
            $("#international-data").show();
            loadData('{{ route('dashboard.getDataInternational') }}');
            highlightButton($(this)); 
            resetButton($('#domestic-btn')); 
        });

        $("#domestic-btn").click(function() {
            $("#international-data").hide();
            $("#domestic-data").show();
            loadData('{{ route('dashboard.getDataDomestic') }}');
            highlightButton($(this)); 
            resetButton($('#international-btn')); 
        });
    });
</script>

<!-- bar -->
<script>
   $(document).ready(function() {
        var international = [0, 0, 0, 0, 0, 0, 0];
        var domestic = [0, 0, 0, 0, 0, 0, 0];

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'GET',
            dataType: 'json',
            url: '{{ route('dashboard.getBarData') }}',
            success: function(response) {
                international = [response.international.Sun, response.international.Mon, response.international.Tue, response.international.Wed, response.international.Thu, response.international.Fri, response.international.Sat];
                domestic = [response.domestic.Sun, response.domestic.Mon, response.domestic.Tue, response.domestic.Wed, response.domestic.Thu, response.domestic.Fri, response.domestic.Sat];
                
                updateChart(international, domestic);
            },
        });

        function updateChart(international, domestic) {
            var label = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            var ctx = document.getElementById("chartBar").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: label,
                    datasets: [{
                        label: 'International',
                        data: international,
                        backgroundColor: '#6c5ffc',
                        borderColor: '#6c5ffc',
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointRadius: 2
                    }, {
                        label: 'Domestic',
                        data: domestic,
                        backgroundColor: '#05c3fb',
                        borderColor: '#05c3fb',
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointRadius: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: "#9ba6b5",
                            },
                            display: true,
                            gridLines: {
                                color: 'rgba(119, 119, 142, 0.2)'
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                fontColor: "#9ba6b5",
                            },
                            display: true,
                            gridLines: {
                                color: 'rgba(119, 119, 142, 0.2)'
                            },
                            scaleLabel: {
                                display: false,
                                labelString: 'Thousands',
                                fontColor: 'rgba(119, 119, 142, 0.2)'
                            }
                        }]
                    },
                    legend: {
                        labels: {
                            fontColor: "#9ba6b5"
                        },
                    },
                }
            });
        }
    });
</script>
@endpush
