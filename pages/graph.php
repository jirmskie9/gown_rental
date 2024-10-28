<?php
require_once 'process/config.php';

$sql = "
    SELECT DATE_FORMAT(start_date, '%M %Y') as month, COUNT(*) as total
    FROM reservations
    WHERE status = 'completed'
    GROUP BY month
    ORDER BY month
";

$result = $conn->query($sql);

$months = [];
$counts = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $months[] = $row['month']; 
        $counts[] = $row['total']; 
    }
    $result->close(); // Close the result set
} else {
    echo "Error: " . $conn->error;
}


$monthLabels = json_encode($months);
$dataCounts = json_encode($counts);
?>


<script>
  var ctx1 = document.getElementById("chart-line").getContext("2d");

  var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
  gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
  gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
  gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');

  new Chart(ctx1, {
    type: "line",
    data: {
      labels: <?php echo $monthLabels; ?>, // Dynamic month labels
      datasets: [{
        label: "Reservations",
        tension: 0.4,
        borderWidth: 0,
        pointRadius: 0,
        borderColor: "#5e72e4",
        backgroundColor: gradientStroke1,
        borderWidth: 3,
        fill: true,
        data: <?php echo $dataCounts; ?>, // Dynamic data counts
        maxBarThickness: 6
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
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
            display: true,
            drawOnChartArea: true,
            drawTicks: false,
            borderDash: [5, 5]
          },
          ticks: {
            display: true,
            padding: 10,
            color: '#fbfbfb',
            font: {
              size: 11,
              family: "Open Sans",
              style: 'normal',
              lineHeight: 2
            },
          }
        },
        x: {
          grid: {
            drawBorder: false,
            display: false,
            drawOnChartArea: false,
            drawTicks: false,
            borderDash: [5, 5]
          },
          ticks: {
            display: true,
            color: '#ccc',
            padding: 20,
            font: {
              size: 11,
              family: "Open Sans",
              style: 'normal',
              lineHeight: 2
            },
          }
        },
      },
    },
  });
</script>