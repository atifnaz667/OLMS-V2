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
        <div class="col-sm-3 mb-2">
            <a href="test/list" class="form-control btn btn-warning custom-btn">
                <i class="menu-icon tf-icons ti ti-file-certificate"></i> Digital Task
            </a>
        </div>
        <div class="col-sm-3 mb-2">
            <a href="suggestion/create" class="form-control btn btn-danger custom-btn">
                <i class="menu-icon tf-icons ti ti-chart-infographic"></i> Human Error & Suggestions
            </a>
        </div>
    </div>


    <!-- Earning Reports Tabs-->
    <div class="col-12 mb-4 mt-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="mb-0">Result Reports</h5>
                    <small class="text-muted">All Subjects Result Overview</small>
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
      <h4 class="card-header text-center text-primary">Notice Board</h4>
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
                $.ajax({
                    url: '{{ route('getGraphDataAjax') }}',
                    method: 'get',
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

        });


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
                                    '<td> <p class="text-primary" style="cursor:pointer" onclick="showModal('+announcement.id+')" >' + announcement.title + '</p></td>' +
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
@endsection
