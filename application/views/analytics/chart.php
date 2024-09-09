<style>
	.select2-container {
		z-index: 99;
	}

	.select2-selection {
		padding-top: 4px !important;
		height: 38px !important;
	}
</style>

<section class="section dashboard">
    <div class="card">
        <div class="card-body">
          <div class="row mt-4 justify-content-center">
            <div class="col-12 col-md-2 d-flex align-items-center text-end mb-3 mb-md-0">
                <span><strong>Period</strong></span>
            </div>
            <div class="col-12 col-md-4 mb-3 mb-md-0">
              <select class="form-select material_id_edit" id="inputYear" name="inputYear">
                <option value="" disabled selected>Select Year</option>
                  <?php
                    $startYear = 2024;
                    $endYear = date("Y") + 500; 
                    for ($year = $startYear; $year <= $endYear; $year++) {
                        echo "<option value=\"$year\">$year</option>";
                    }
                  ?>
              </select>
            </div>
            <div class="col-12 col-md-2">
                <button id="searchBtn" class="btn btn-success w-100">Search</button>
            </div>
          </div>
            <hr>
            <div class="chart-content">
              <div class="row mt-4">
                  <div class="col-md-6">
                    <div id="columnChartBox" style="width: 100%; height: 350px;"></div>
                  </div>
                  <div class="col-md-6">
                    <div id="columnChartKanban" style="width: 100%; height: 350px;"></div>
                  </div>
              </div>
              <div class="row mt-4">
                  <div class="col-md-6">
                    <div id="columnChartProductionRequest" style="width: 100%; height: 350px;"></div>
                  </div>
                  <div class="col-md-6">
                    <div id="columnChartQualityRequest" style="width: 100%; height: 350px;"></div>
                  </div>
              </div>
            </div>
        </div>
    </div>
</section>

<script>
  $(document).ready(function() {
    $('#inputYear').select2();
    
    $('#searchBtn').on('click', function() {
      var year = $('#inputYear').val();
      
      fetchChartDataBox(year);
      fetchChartDataKanban(year);
      fetchChartDataProductionRequest(year);
      fetchChartDataQualityRequest(year);
    });

    fetchChartDataBox();
    fetchChartDataKanban();
    fetchChartDataProductionRequest();
    fetchChartDataQualityRequest();
  });


  // BOX
  function fetchChartDataBox(year = new Date().getFullYear()) {
    $.ajax({
        url: '<?php echo site_url("analytics/getChartDataBox"); ?>',
        type: 'POST',
        data: { year: year },
        success: function(response) {
            var data = JSON.parse(response);
            renderChartBox(data.highData, data.mediumData, data.year);
        },
        error: function(xhr, status, error) {
            console.error("An error occurred while fetching chart data: ", error);
        }
    });
  }

  function renderChartBox(highData, mediumData, year) {
    var chartOptions = {
      series: [
          { name: 'High', data: highData },
          { name: 'Medium', data: mediumData }
      ],
      chart: {
          type: 'bar',
          height: 350
      },
      plotOptions: {
          bar: {
              horizontal: false,
              columnWidth: '60%',
              endingShape: 'rounded'
          }
      },
      dataLabels: {
          enabled: false
      },
      stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
      },
      xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
      },
      yaxis: {
        title: { text: 'Box' },
        // tickAmount: 10,
        // labels: {
        //   formatter: function (val) {
        //     return Math.floor(val);
        //   }
        // }
      },
      title: {
          text: `Box ${year}`,
          align: 'center'
      },
      fill: {
          opacity: 1
      },
      tooltip: {
          y: {
              formatter: function(val) { return val + " box"; }
          }
      }
    };

    $('#columnChartBox').empty(); // Clear previous chart
    new ApexCharts(document.querySelector("#columnChartBox"), chartOptions).render();
  }
  
  
  // KANBAN
  function fetchChartDataKanban(year = new Date().getFullYear()) {
      $.ajax({
          url: '<?php echo site_url("analytics/getChartDataKanban"); ?>',
          type: 'POST',
          data: { year: year },
          success: function(response) {
              var data = JSON.parse(response);
              renderChartKanban(data.kanbanData, data.year);
          },
          error: function(xhr, status, error) {
              console.error("An error occurred while fetching chart data: ", error);
          }
      });
  }

  function renderChartKanban(kanbanData, year) {
      var chartOptions = {
          series: [
              { name: 'Kanban Box', data: kanbanData },
          ],
          chart: {
              type: 'bar',
              height: 350
          },
          plotOptions: {
              bar: {
                  horizontal: false,
                  columnWidth: '60%',
                  endingShape: 'rounded'
              }
          },
          dataLabels: {
              enabled: false
          },
          stroke: {
              show: true,
              width: 2,
              colors: ['transparent']
          },
          xaxis: {
              categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
          },
          yaxis: {
            title: { text: 'Kanban Box' },
            // labels: {
            //   formatter: function (val) {
            //       return Math.floor(val); // Ensure the y-axis labels are integers
            //   }
            // }
          },
          title: {
              text: `Kanban Box ${year}`,
              align: 'center'
          },
          fill: {
              opacity: 1
          },
          tooltip: {
              y: {
                  formatter: function(val) { return val + " kanban box"; }
              }
          }
      };

      $('#columnChartKanban').empty(); // Clear previous chart
      new ApexCharts(document.querySelector("#columnChartKanban"), chartOptions).render();
  }


  // PRODUCTION REQUEST
  function fetchChartDataProductionRequest(year = new Date().getFullYear()) {
      $.ajax({
          url: '<?php echo site_url("analytics/getChartDataProductionRequest"); ?>',
          type: 'POST',
          data: { year: year },
          success: function(response) {
              var data = JSON.parse(response);
              renderChartProductionRequest(data.productionRequest, data.year);
          },
          error: function(xhr, status, error) {
              console.error("An error occurred while fetching chart data: ", error);
          }
      });
  }

  function renderChartProductionRequest(productionRequest, year) {
      var chartOptions = {
          series: [
              { name: 'Production Request', data: productionRequest },
          ],
          chart: {
              type: 'bar',
              height: 350
          },
          plotOptions: {
              bar: {
                  horizontal: false,
                  columnWidth: '60%',
                  endingShape: 'rounded'
              }
          },
          dataLabels: {
              enabled: false
          },
          stroke: {
              show: true,
              width: 2,
              colors: ['transparent']
          },
          xaxis: {
              categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
          },
          yaxis: {
            title: { text: 'Production Request' },
            // labels: {
            //   formatter: function (val) {
            //       return Math.floor(val); // Ensure the y-axis labels are integers
            //   }
            // }
          },
          title: {
              text: `Production Request ${year}`,
              align: 'center'
          },
          fill: {
              opacity: 1
          },
          tooltip: {
              y: {
                  formatter: function(val) { return val + " production request"; }
              }
          }
      };

      $('#columnChartProductionRequest').empty(); // Clear previous chart
      new ApexCharts(document.querySelector("#columnChartProductionRequest"), chartOptions).render();
  }


  // QUALITY REQUEST
  function fetchChartDataQualityRequest(year = new Date().getFullYear()) {
      $.ajax({
          url: '<?php echo site_url("analytics/getChartDataQualityRequest"); ?>',
          type: 'POST',
          data: { year: year },
          success: function(response) {
              var data = JSON.parse(response);
              renderChartQualityRequest(data.qualityRequest, data.year);
          },
          error: function(xhr, status, error) {
              console.error("An error occurred while fetching chart data: ", error);
          }
      });
  }

  function renderChartQualityRequest(qualityRequest, year) {
      var chartOptions = {
          series: [
              { name: 'Quality Request', data: qualityRequest },
          ],
          chart: {
              type: 'bar',
              height: 350
          },
          plotOptions: {
              bar: {
                  horizontal: false,
                  columnWidth: '60%',
                  endingShape: 'rounded'
              }
          },
          dataLabels: {
              enabled: false
          },
          stroke: {
              show: true,
              width: 2,
              colors: ['transparent']
          },
          xaxis: {
              categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
          },
          yaxis: {
            title: { text: 'Quality Request' },
            // labels: {
            //   formatter: function (val) {
            //       return Math.floor(val); // Ensure the y-axis labels are integers
            //   }
            // }
          },
          title: {
              text: `Quality Request ${year}`,
              align: 'center'
          },
          fill: {
              opacity: 1
          },
          tooltip: {
              y: {
                  formatter: function(val) { return val + " quality request"; }
              }
          }
      };

      $('#columnChartQualityRequest').empty(); // Clear previous chart
      new ApexCharts(document.querySelector("#columnChartQualityRequest"), chartOptions).render();
  }
</script>
