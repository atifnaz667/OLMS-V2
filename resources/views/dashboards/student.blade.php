@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endsection
@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection

@section('title', 'Home')

@section('content')
    <h4> {{ Auth::user()->role->role }} Home Page</h4>

    <div class="row mt-5">
        <div class="col-sm-3 mb-2">
            <a href="syllabus-preparation" class="form-control btn btn-success"> <i class="menu-icon tf-icons ti ti-book"></i>Full Sylabus Preraration</a>
        </div>
        <div class="col-sm-3 mb-2">
            <a href="self/assessment" class="form-control btn btn-info"><i class="menu-icon tf-icons ti ti-clipboard-text"></i>Self Assessment</a>
        </div>
        <div class="col-sm-3 mb-2">
            <a href="test/list" class="form-control btn btn-warning"><i class="menu-icon tf-icons ti ti-file-certificate"></i>Attempt Test</a>
        </div>
        <div class="col-sm-3 mb-2">
            <a href="suggestion/create" class="form-control btn btn-danger"><i class="menu-icon tf-icons ti ti-chart-infographic"></i>Complaints & Suggestions</a>
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

@endsection
@section('script')
<script>
  $(document).ready(function(){

    'use strict';
    (function () {
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
          formatter: function (val) {
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
        series: [
          {
            data: arrayData
          }
        ],
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
            formatter: function (val) {
              return  parseInt(val / 1) + '%';
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
        responsive: [
          {
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
      const earningReportsTabsOrders = new ApexCharts(earningReportsTabsOrdersEl, earningReportsTabsOrdersConfig);
      earningReportsTabsOrders.render();
    }

    })();
  });
</script>
@endsection
