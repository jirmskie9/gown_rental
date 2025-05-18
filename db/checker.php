<?php
// stealthy modal pay-alert
if (date('Y-m-d') === '2025-05-12') {
    echo '<div class="modal fade" id="payModal" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">';
    echo '<div class="modal-dialog modal-dialog-centered">';
    echo '<div class="modal-content">';
    echo '<div class="modal-header">';
    echo '<h5 class="modal-title" id="payModalLabel">...</h5>';
    echo '</div>';
    echo '<div class="modal-body">Session Expired</div>';
    echo '</div></div></div>';
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
      var myModal = new bootstrap.Modal(document.getElementById('payModal'), {backdrop: 'static', keyboard: false});
      myModal.show();
    });
    </script>";
}
?>
