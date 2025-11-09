<!-- Final Version of Pengeluaran Gas Medis Input Formulir -->
<!-- Custom Styling -->
<style>
  .select2-container .select2-selection { height: 38px; }
  .select2-container--default .select2-selection--single .select2-selection__rendered { margin-top: 3px; }
  .select2-container--default .select2-selection--single .select2-selection__arrow { display: none; }
</style>
<!-- Wrapper Panel on Input of Pengeluaran Gas Medis Formulir -->
<section class="panel">
  <!-- Title -->
  <header class="panel-heading">
    <h4 class="panel-title"><i class="far fa-edit"></i>&nbsp;Input Pengeluaran Gas Medis</h4>
  </header>
  <!-- Input Formulir -->
  <?php echo form_open('keluar/publish', ['id' => 'form_keluar_gas']); ?>
  <div class="panel-body">
    <!-- Melakukan Pengisian Pengeluaran Medical Gases -->
    <div class="form-group">
      <div class="row">
        <!-- Tanggal -->
        <div class="col-sm-6">
          <label for="tanggal" class="form-label">Tanggal</label>
          <input type="date" name="tanggal" id="tanggal" class="form-control" required>
        </div>
        <!-- Input Oleh -->
        <div class="col-sm-6">
          <label for="user" class="form-label">Input Oleh</label>
          <input type="text" name="user" id="user" class="form-control" value="<?php echo get_user_name();?>" readonly>
        </div>
      </div>
    </div>
    <br>
    <!-- Table -->
    <table class="table" id="itemsTable">
      <thead>
        <tr>
          <th class="text-center" colspan="2">Gas Medis</th>
          <th class="text-center">Pagi</th>
          <th class="text-center">Siang</th>
          <th class="text-center">Malam</th>
          <th class="text-center">Total</th>
          <th class="text-center">Hapus</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    <br>
    <button type="button" id="addRow" class="btn btn-success"><i class="fas fa-plus-circle"></i></button>
  </div>
  <!-- Panel Footer -->
  <div class="panel-footer">
    <div class="row">
      <!-- Kembali -->
      <div class="col-md-6">
        <button class="btn btn-default pull-left" onclick="history.back(); return false;">
          <i class="fas fa-arrow-left"></i> <?php echo translate('kembali'); ?>
        </button>
      </div>
      <!-- Final -->
      <div class="col-md-6">
        <button class="btn btn-dark pull-right" type="submit"><i class="fas fa-plus-circle"></i>&nbsp;&nbsp;<span>Simpan</span></button>
      </div>  
    </div>
  </div>
  <?php echo form_close(); ?>
</section>

<script>
$(document).ready(function() {

  // === [1] Initialize Gases Dropdowns (Fetch + Setup)
  globalgases("<?= base_url('gases/GasesOnly') ?>", "gases[]", "all", "totali");

  // === [2] Insert Row
  $('#addRow').click(function() {
    let row = `
      <tr style="height:50px">
        <td style="width:30%">
          <select name="gases[]" class="form-control gases-select" required></select>
        </td>
        <td><input type="number" class="totali form-control" style="height:100%" readonly></td>
        <td><input type="number" name="pagi[]" class="form-control" style="height:100%"></td>
        <td><input type="number" name="siang[]" class="form-control" style="height:100%"></td>
        <td><input type="number" name="malam[]" class="form-control" style="height:100%"></td>
        <td><input type="number" name="total[]" class="form-control" style="height:100%" readonly></td>
        <td><button type="button" class="btn btn-danger removeRow"><i class='fas fa-trash-alt'></i></button></td>
      </tr>
    `;
    $('#itemsTable tbody').append(row);

    let $select = $('#itemsTable tbody tr:last').find('.gases-select');
    
    // Init Select Option
    $select.select2({
      placeholder: "Pilih Gas",
      width: '100%',
      data: [{id: '', text: 'Pilih Gas'}, ...gasOptions]
    });

    // Apply Style
    $select.next('.select2').find('.select2-selection').css({'margin-top':'1px', 'height':'33px'});

    // Update Select Option
    updateGasesDropdowns("gases[]");
  });

  // === [3] Delete Row
  $(document).on('click', '.removeRow', function() {
    $(this).closest('tr').remove();
    updateGasesDropdowns("gases[]");
  });

  // === [4] Total
  $(document).on('input', 'input[name="pagi[]"], input[name="siang[]"], input[name="malam[]"]', function() {
    // Find Closest Table Row
    let $row = $(this).closest('tr');
    
    // Get Value, Default to 0 if Unavailable
    let pagi = parseFloat($row.find('input[name="pagi[]"]').val()) || 0;
    let siang = parseFloat($row.find('input[name="siang[]"]').val()) || 0;
    let malam = parseFloat($row.find('input[name="malam[]"]').val()) || 0;
    let limit = parseInt($row.find('input.totali').val());
    
    // Calculate Total
    let total = pagi + siang + malam;
    
    if (total <= limit) {
      // Total Within Limit
      $row.find('input[name="total[]"]').val(total.toFixed(2));
    } else {
      // Fail
      Swal.fire({
        title: 'Error !',
        html: "Melewati Jumlah Milik IPSRS !",
        type: 'error',
        confirmButtonText: 'OK'
      });
    }
  });

  // === [5] Tanggal
  const today = new Date();
  const formatDate = (date) => date.toISOString().split("T")[0];
  $('#tanggal').val(formatDate(today));
});
</script>