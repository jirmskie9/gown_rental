<?php
require_once 'process/config.php';

// Get payment method counts
$sql_gcash = "SELECT COUNT(*) as total FROM payments WHERE payment_method='Gcash'";
$result_gcash = $conn->query($sql_gcash);
$gcash_count = 0;
if ($result_gcash) {
    $row = $result_gcash->fetch_assoc();
    $gcash_count = $row['total'];
}

$sql_cash = "SELECT COUNT(*) as total FROM payments WHERE payment_method='Cash on Pick Up'";
$result_cash = $conn->query($sql_cash);
$cash_count = 0;
if ($result_cash) {
    $row = $result_cash->fetch_assoc();
    $cash_count = $row['total'];
}

// Calculate total for percentage
$total_payments = $gcash_count + $cash_count;
$gcash_percentage = $total_payments > 0 ? round(($gcash_count / $total_payments) * 100) : 0;
$cash_percentage = $total_payments > 0 ? round(($cash_count / $total_payments) * 100) : 0;
?>

<script>
  var ctx2 = document.getElementById("chart-pie").getContext("2d");

  new Chart(ctx2, {
    type: "pie",
    data: {
      labels: ["Gcash", "Cash on Pick Up"],
      datasets: [{
        label: "Payment Methods",
        tension: 0.4,
        borderWidth: 0,
        borderRadius: 4,
        borderSkipped: false,
        backgroundColor: ['#17a37f', '#fbcf33'],
        data: [<?php echo $gcash_count; ?>, <?php echo $cash_count; ?>],
        maxBarThickness: 6
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
        }
      },
      interaction: {
        intersect: false,
        mode: 'index',
      },
      scales: {
        y: {
          grid: {
            drawBorder: false,
            display: false,
            drawOnChartArea: false,
            drawTicks: false,
            borderDash: [5, 5],
          },
          ticks: {
            display: false,
          }
        },
        x: {
          grid: {
            drawBorder: false,
            display: false,
            drawOnChartArea: false,
            drawTicks: false,
            borderDash: [5, 5],
          },
          ticks: {
            display: false,
          }
        },
      },
    },
  });
</script> 