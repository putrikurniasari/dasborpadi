/*!

 =========================================================
 * Black Dashboard - v1.0.0
 =========================================================

 * Product Page: https://www.creative-tim.com/product/black-dashboard
 * Licensed under MIT
 =========================================================
 
 */

var transparent = 'rgba(0,0,0,0)';

var gradientChartOptionsConfigurationWithTooltipPurple = {
  maintainAspectRatio: false,
  legend: {
    display: false
  },
  tooltips: {
    backgroundColor: '#f5f5f5',
    titleFontColor: '#333',
    bodyFontColor: '#666',
    bodySpacing: 4,
    xPadding: 12,
    mode: "nearest",
    intersect: 0,
    position: "nearest"
  },
  responsive: true,
  scales: {
    yAxes: [{
      barPercentage: 1.6,
      gridLines: {
        drawBorder: false,
        color: 'rgba(29,140,248,0.0)',
        zeroLineColor: "transparent",
      },
      ticks: {
        suggestedMin: 0,
        suggestedMax: 100,
        padding: 20,
        fontColor: "#9e9e9e"
      }
    }],

    xAxes: [{
      barPercentage: 1.6,
      gridLines: {
        drawBorder: false,
        color: 'rgba(29,140,248,0.1)',
        zeroLineColor: "transparent",
      },
      ticks: {
        padding: 20,
        fontColor: "#9e9e9e"
      }
    }]
  }
};

// ============================================================
// Custom Dashboard Chart
// ============================================================
function initDashboardPageCharts() {
  if ($('#chartBig1').length != 0) {
    var ctx = document.getElementById("chartBig1").getContext('2d');

    var gradientStroke = ctx.createLinearGradient(0, 230, 0, 50);
    gradientStroke.addColorStop(1, 'rgba(72,72,176,0.1)');
    gradientStroke.addColorStop(0.4, 'rgba(72,72,176,0.0)');
    gradientStroke.addColorStop(0, 'rgba(119,52,169,0)'); // purple colors

    var config = {
      type: 'line',
      data: {
        labels: chart_labels, // ambil dari Blade
        datasets: [{
          label: "Volume Padi",
          fill: true,
          backgroundColor: gradientStroke,
          borderColor: '#d346b1',
          borderWidth: 2,
          pointBackgroundColor: '#d346b1',
          pointBorderColor: 'rgba(255,255,255,0)',
          pointHoverBackgroundColor: '#d346b1',
          pointBorderWidth: 20,
          pointHoverRadius: 4,
          pointHoverBorderWidth: 15,
          pointRadius: 4,
          data: chart_data, // ambil dari Blade
        }]
      },
      options: gradientChartOptionsConfigurationWithTooltipPurple
    };

    var myChartData = new Chart(ctx, config);
  }
}

// ============================================================
// Document Ready
// ============================================================
$(document).ready(function () {
  // Inisialisasi chart custom
  initDashboardPageCharts();
});
