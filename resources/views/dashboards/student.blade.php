@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <style>
        .custom-btn {
            font-size: 20px;
            height: 100px;
            padding: 0 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .custom-btn i {
            font-size: 40px;
            margin-right: 10px;
        }

        .custom_div {
            font-size: 20px;
            height: 100px;
            padding: 0 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 2px solid #007bff; /* Primary color border */
            background-color: #007bff; /* Primary color background */
            color: #fff; /* Text color */
            border-radius: 8px;
        }

        .custom_div i {
            font-size: 40px;
            margin-right: 10px;
        }
        .custom_div_btn {
            color: #fff; /* Text color set to white */
            background-color: #007bff; /* Primary color background */
            border: 2px solid #007bff; /* Primary color border */
            border-radius: 8px; /* Rounded corners */
            margin-bottom: 10px; /* Add margin between buttons */
        }

        .custom_div_btn:hover {
            background-color: #0056b3; /* Change background color on hover */
            border-color: #0056b3; /* Change border color on hover */
        }

    /* Add your CSS styles for the slider and buttons here */
    .slider-container {
        position: relative;
        width: 100%;
        overflow: hidden;
    }

    .slider {
        display: flex;
        flex-wrap: nowrap;
        transition: all 0.5s ease;
        justify-content: space-between;
    }

    .slide {
        flex-grow: 1;
        flex-basis: 200;
        /* width: 50%;  */
        text-align: center;
    }

    .prev-button, .next-button {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        /* background-color: #007BFF; */
        /* color: #fff; */
        /* border: none; */
        cursor: pointer;
    }

    .next-button {
        right: 0;
    }

    /* @media (max-width: 768px) {
        .slider {
        justify-content: center;
    }
        .slide {
            flex-grow: 1;
             flex-basis: 200;
        }

    } */
    @media (max-width: 768px) {
        .slide {
            flex: 0 0 50%;
        }
    }

    @keyframes blink {
        0% { background-color: #ffa807; } /* Initial color */
        50% { background-color: #07ff30; }   /* Blinking color */
        100% { background-color: #ffa807; } /* Back to initial color */
    }

    .blinking {
        animation: blink 1s infinite;
    }

        .quick-access-btn {
            background-color: #7367f0;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 24px;
            cursor: pointer;
            outline: none;
        }
        .Graph_btn {
            background-color: #323040;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 24px;
            cursor: pointer;
            outline: none;
        }

        .Graph_btn {
    float: right;
    margin-top: -90px;
  }

  @media (max-width: 768px) {
    .Graph_btn {
      float: none;
      margin-top: 0;
      margin-bottom: 10px; /* Adjust as needed */
    }
    }
    </style>

</style>

    </style>
@endsection
@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/dashboards-crm.js') }}"></script>

@section('title', 'Home')

@section('content')
    {{-- <h4> {{ Auth::user()->role->role }} Home Page</h4> --}}
    <div class="row mt-5">
        <div class="col-sm-3 mb-2">
            <a href="syllabus-preparation" class="form-control btn btn-success custom-btn">
                <i class="menu-icon tf-icons ti ti-book"></i> My Digital Bag
            </a>
        </div>
        <div class="col-sm-3 mb-2">
            <a href="self/assessment" class="form-control btn btn-info custom-btn">
                <i class="menu-icon tf-icons ti ti-clipboard-text"></i> Self Assessment
            </a>
        </div>
        <div class="col-sm-3 mb-2 ">
            <a href="test/list" class="form-control btn btn-warning custom-btn {{ $testCount > 0 ? 'blinking' : '' }}">
              <span class="badge badge-center  bg-dark" style="position: absolute; top:1; right:1;">{{ $testCount }}</span>
                <i class="menu-icon tf-icons ti ti-file-certificate"></i> Digital Task
            </a>
        </div>
        <div class="col-sm-3 mb-2">
            <a href="suggestion/create" class="form-control btn btn-danger custom-btn">
                <i class="menu-icon tf-icons ti ti-chart-infographic"></i> Human Error & Suggestions
            </a>
        </div>
        <!-- <div class="col-sm-3 mb-2">
            <a href="#" class="form-control btn btn-primary custom-btn" id="my_teacher_button">
                <i class="menu-icon tf-icons ti ti-users"></i> My Teachers
            </a>
            <div id="buttons-container" class="hidden mt-1" style="display: none;">
                <button class="btn btn-secondary" onclick="window.location.href='myTeacher/list'">My Teacher List</button>
                <button class="btn btn-secondary" onclick="window.location.href='myComment/list'">Comment List</button>
            </div>
        </div> -->
        <div class="col-sm-3 mb-2">
            <a href="notes" class="form-control btn btn-secondary custom-btn">
                <i class="menu-icon fas fa-sticky-note"></i> Notes
            </a>
        </div>
        <div class="col-sm-3 mb-2 flex-column flex-wrap">
           <div class="custom_div " >
           <button class="btn custom_div_btn" onclick="window.location.href='myTeacher/list'">My Teachers List</button>
            <button class="btn custom_div_btn" onclick="window.location.href='myComment/list'"> <i class="menu-icon tf-icons ti ti-comments"></i>Comment List</button>
           </div>
        </div>
  
       
    </div>
   
    <button class="Graph_btn"  onclick="toggleGraphDiv()"><i class="fa fa-bar-chart"></i></button>



    <!-- Earning Reports Tabs-->
    <div class="col-12 mb-4 mt-3">
        <div class="card" id="graph_div_show">
          <div class="row p-4">
            <div class="col-12 col-sm-4">
              <h5 class="">Result Reports</h5>
              <small class="text-muted">All Subjects Result Overview</small>
            </div>
            <div class="col-12 col-sm-4">
              <div class="form-group">
                <label for="" class="form-label">From Date</label>
                <input type="date" name="from_date" id="from_date" class="form-control" onchange="showChart()">
              </div>
            </div>
            <div class="col-12 col-sm-4">
              <div class="form-group">
                <label for="" class="form-label">To Date</label>
                <input type="date" name="to_date" id="to_date" class="form-control" onchange="showChart()">
              </div>
            </div>
          </div>
            <div class="card-header d-flex justify-content-between">
                <div class="card-title mb-0">
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content p-0 ms-0 ms-sm-2">
                    <div class="tab-pane fade show active" id="navs-orders-id" role="tabpanel">
                        <div id="earningReportsTabsOrders"></div>
                    </div>
                    <div class="tab-pane fade" id="navs-sales-id" role="tabpanel">
                        <div id="earningReportsTabsSales"></div>
                    </div>
                    <div class="tab-pane fade" id="navs-profit-id" role="tabpanel">
                        <div id="earningReportsTabsProfit"></div>
                    </div>
                    <div class="tab-pane fade" id="navs-income-id" role="tabpanel">
                        <div id="earningReportsTabsIncome"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="border-left:3px solid #7367f0; border-right:3px solid #7367f0">
      <h4 class="card-header text-center "><b>Notice Board</b></h4>
      <div class="table-responsive text-nowrap mb-5">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Sr #</th>
              <th>Posted At</th>
              <th>Announcement</th>
              <th>Posted By</th>

            </tr>
          </thead>
          <tbody class="" id="tbody">
          </tbody>
        </table>
      </div>
      <nav aria-label="Page navigation" class="pagination-nav">
        <ul class="pagination"></ul>
      </nav>
    </div>

    <div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel3" >Announcement Details</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div style="border: 1px solid #7367f0; border-radius:5px" class="p-4">
                  <h5 id="announcementTitle"></h5>
                  <p id="announcementDesc"></p>
                </div>
              </div>

          </div>
      </div>
  </div>

@endsection
@section('script')
    <script>
        $(document).ready(function() {


        showChart();
        fetchAnnouncementRecords();
        $("#my_teacher_button").on('click', function() {
          if($("#buttons-container").css('display') == 'none'){
            $("#buttons-container").css('display','flex');
          }else{
            $("#buttons-container").css('display','none');

          }
        });

        $("#graph_div_show").css('display','none');

        });

        function toggleGraphDiv() {
            console.log("dsad")
        var graphDiv = document.getElementById("graph_div_show");

        if (graphDiv.style.display === 'none') {
            graphDiv.style.display = 'flex';
        } else {
            graphDiv.style.display = 'none';
        }
    }



        function updatePaginationUI() {
            var paginationContainer = $('.pagination');
            paginationContainer.empty();

            if (lastPage > 1) {
                var paginationLinks = '';
                if (currentPage > 1) {
                    paginationLinks +=
                        '<li class="page-item first"><a class="page-link pagination-link" href="#" data-page="1"><i class="ti ti-chevrons-left ti-xs"></i></a></li>';
                    paginationLinks +=
                        '<li class="page-item prev"><a class="page-link pagination-link" href="#" data-page="' + (
                            currentPage - 1) + '"><i class="ti ti-chevron-left ti-xs"></i></a></li>';
                }
                for (var i = 1; i <= lastPage; i++) {
                    var activeClass = (i === currentPage) ? 'active' : '';
                    paginationLinks += '<li class="page-item ' + activeClass +
                        '"><a class="page-link pagination-link" href="#" data-page="' + i + '">' + i + '</a></li>';
                }
                if (currentPage < lastPage) {
                    paginationLinks +=
                        '<li class="page-item next"><a class="page-link pagination-link" href="#" data-page="' + (
                            currentPage + 1) + '"><i class="ti ti-chevron-right ti-xs"></i></a></li>';
                    paginationLinks +=
                        '<li class="page-item last"><a class="page-link pagination-link" href="#" data-page="' +
                        lastPage +
                        '"><i class="ti ti-chevrons-right ti-xs"></i></a></li>';
                }
                paginationContainer.append(paginationLinks);
            }
        }

        function showModal(id){
          $.ajax({
                url: "{{ route('announcement.show', '') }}" + "/" + id,
                method: 'GET',
                success: function(response) {
                  $('#announcementTitle').html(response.announcement.title);
                  $('#announcementDesc').html(response.announcement.description);
                  $("#addAnnouncementModal").modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });

        }

        function showChart(){
          $("#earningReportsTabsOrders").html('');
          'use strict';
            (function() {
                let cardColor, labelColor, shadeColor, legendColor, borderColor;
                if (isDarkStyle) {
                    cardColor = config.colors_dark.cardColor;
                    labelColor = config.colors_dark.textMuted;
                    legendColor = config.colors_dark.bodyColor;
                    borderColor = config.colors_dark.borderColor;
                    shadeColor = 'dark';
                } else {
                    cardColor = config.colors.cardColor;
                    labelColor = config.colors.textMuted;
                    legendColor = config.colors.bodyColor;
                    borderColor = config.colors.borderColor;
                    shadeColor = '';
                }

                // Earning Reports Tabs Function
                function EarningReportsBarChart(arrayData, highlightData) {
                    const basicColor = config.colors_label.primary,
                        highlightColor = config.colors.primary;
                    var colorArr = [];

                    for (let i = 0; i < arrayData.length; i++) {
                        if (i === highlightData) {
                            colorArr.push(highlightColor);
                        } else {
                            colorArr.push(basicColor);
                        }
                    }

                    const earningReportBarChartOpt = {
                        chart: {
                            height: 258,
                            parentHeightOffset: 0,
                            type: 'bar',
                            toolbar: {
                                show: false
                            }
                        },
                        plotOptions: {
                            bar: {
                                columnWidth: '32%',
                                startingShape: 'rounded',
                                borderRadius: 7,
                                distributed: true,
                                dataLabels: {
                                    position: 'top'
                                }
                            }
                        },
                        grid: {
                            show: false,
                            padding: {
                                top: 0,
                                bottom: 0,
                                left: -10,
                                right: -10
                            }
                        },
                        colors: colorArr,
                        dataLabels: {
                            enabled: true,
                            formatter: function(val) {
                                return val + '%';
                            },
                            offsetY: -25,
                            style: {
                                fontSize: '15px',
                                colors: [legendColor],
                                fontWeight: '600',
                                fontFamily: 'Public Sans'
                            }
                        },
                        series: [{
                            data: arrayData
                        }],
                        legend: {
                            show: false
                        },
                        tooltip: {
                            enabled: false
                        },
                        xaxis: {
                            categories: {!! $bookNames !!},
                            axisBorder: {
                                show: true,
                                color: borderColor
                            },
                            axisTicks: {
                                show: false
                            },
                            labels: {
                                style: {
                                    colors: labelColor,
                                    fontSize: '13px',
                                    fontFamily: 'Public Sans'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                offsetX: -15,
                                formatter: function(val) {
                                    return parseInt(val / 1) + '%';
                                },
                                style: {
                                    fontSize: '13px',
                                    colors: labelColor,
                                    fontFamily: 'Public Sans'
                                },
                                min: 0,
                                max: 60000,
                                tickAmount: 6
                            }
                        },
                        responsive: [{
                                breakpoint: 1441,
                                options: {
                                    plotOptions: {
                                        bar: {
                                            columnWidth: '41%'
                                        }
                                    }
                                }
                            },
                            {
                                breakpoint: 590,
                                options: {
                                    plotOptions: {
                                        bar: {
                                            columnWidth: '61%',
                                            borderRadius: 5
                                        }
                                    },
                                    yaxis: {
                                        labels: {
                                            show: false
                                        }
                                    },
                                    grid: {
                                        padding: {
                                            right: 0,
                                            left: -20
                                        }
                                    },
                                    dataLabels: {
                                        style: {
                                            fontSize: '12px',
                                            fontWeight: '400'
                                        }
                                    }
                                }
                            }
                        ]
                    };
                    return earningReportBarChartOpt;
                }
                var chartJson = 'earning-reports-charts.json';

                var earningReportsChart = [];
                let to_date = $("#to_date").val();
                let from_date = $("#from_date").val();
                $.ajax({
                    url: '{{ route('getGraphDataAjax') }}',
                    method: 'get',
                    data: {
                      from_date: from_date,
                      to_date: to_date
                    },
                    dataType: 'json',
                    async: false,
                    success: function(response) {
                        earningReportsChart = response;
                    }
                });

                // Earning Reports Tabs Orders
                // --------------------------------------------------------------------
                const earningReportsTabsOrdersEl = document.querySelector('#earningReportsTabsOrders'),
                    earningReportsTabsOrdersConfig = EarningReportsBarChart(
                        earningReportsChart['chart_data'],
                        earningReportsChart['active_option']
                    );
                if (typeof earningReportsTabsOrdersEl !== undefined && earningReportsTabsOrdersEl !== null) {
                    const earningReportsTabsOrders = new ApexCharts(earningReportsTabsOrdersEl,
                        earningReportsTabsOrdersConfig);
                    earningReportsTabsOrders.render();
                }

            })();

        fetchAnnouncementRecords();

        };


        function fetchAnnouncementRecords(page = 1) {

            var title = $('#search-input').val();
            var perPage = $('#perPageSelect').val();

            $.ajax({
                url: '{{ route('notice.board.ajax') }}',
                method: 'GET',
                data: {
                    page: page,
                    perPage: perPage,
                    title: title,
                },
                success: function(response) {
                    var tableBody = $('#tbody');
                    tableBody.empty();

                    if (response.status === 'success') {
                        var announcements = response.data;
                        currentPage = response.current_page;
                        lastPage = response.last_page;

                        if (announcements && announcements.length > 0) {
                            $.each(announcements, function(index, announcement) {
                              let date = announcement.date.split(', ')
                                var row = '<tr>' +
                                    '<td>' + (index + 1) + '</td>' +
                                    '<td "> ' + date[0] + ', ' + date[1] + ' <br> '+ date[2] +'</td>' +
                                    '<td> <p class="text-primary " style="cursor:pointer" onclick="showModal('+announcement.id+')" ><b>' + announcement.title + '&nbsp;&nbsp;&nbsp;&nbsp; </b><i class="fa-solid fa-eye"></i></p></td>' +
                                    '<td>' + announcement.user + '</td>' +

                                    '</tr>';
                                tableBody.append(row);
                            });
                        }
                    } else {
                        console.error(response.message);
                    }

                    updatePaginationUI();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }


        function updatePaginationUI() {
            var paginationContainer = $('.pagination');
            paginationContainer.empty();

            if (lastPage > 1) {
                var paginationLinks = '';
                if (currentPage > 1) {
                    paginationLinks +=
                        '<li class="page-item first"><a class="page-link pagination-link" href="#" data-page="1"><i class="ti ti-chevrons-left ti-xs"></i></a></li>';
                    paginationLinks +=
                        '<li class="page-item prev"><a class="page-link pagination-link" href="#" data-page="' + (
                            currentPage - 1) + '"><i class="ti ti-chevron-left ti-xs"></i></a></li>';
                }
                for (var i = 1; i <= lastPage; i++) {
                    var activeClass = (i === currentPage) ? 'active' : '';
                    paginationLinks += '<li class="page-item ' + activeClass +
                        '"><a class="page-link pagination-link" href="#" data-page="' + i + '">' + i + '</a></li>';
                }
                if (currentPage < lastPage) {
                    paginationLinks +=
                        '<li class="page-item next"><a class="page-link pagination-link" href="#" data-page="' + (
                            currentPage + 1) + '"><i class="ti ti-chevron-right ti-xs"></i></a></li>';
                    paginationLinks +=
                        '<li class="page-item last"><a class="page-link pagination-link" href="#" data-page="' +
                        lastPage +
                        '"><i class="ti ti-chevrons-right ti-xs"></i></a></li>';
                }
                paginationContainer.append(paginationLinks);
            }
        }

        function showModal(id){
          $.ajax({
                url: "{{ route('announcement.show', '') }}" + "/" + id,
                method: 'GET',
                success: function(response) {
                  $('#announcementTitle').html(response.announcement.title);
                  $('#announcementDesc').html(response.announcement.description);
                  $("#addAnnouncementModal").modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });

        }
    </script>

<script>
    // Add your JavaScript for the slider here
    const slider = document.querySelector('.slider');
    const slides = document.querySelectorAll('.slide');
    let currentIndex = 0;

    document.querySelector('.prev-button').addEventListener('click', () => {
        currentIndex = (currentIndex - 5 + slides.length) % slides.length;
        updateSlider();
    });

    document.querySelector('.next-button').addEventListener('click', () => {
        currentIndex = (currentIndex + 5) % slides.length;
        updateSlider();
    });

    function updateSlider() {
        const translateValue = -currentIndex * 20 + '%';
        slider.style.transform = `translateX(${translateValue})`;
    }
//     window.onload = function () {
//     function toggleButtons() {
//         console.log("kjkj");
//         var buttonsContainer = document.getElementById('buttons-container');
//         buttonsContainer.classList.toggle('hidden');
//     }
// }
</script>
@endsection
