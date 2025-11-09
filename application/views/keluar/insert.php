<!-- Final OK -->
<!-- Custom Styling -->
<style>
  .select2-container .select2-selection { height: 38px; }
  .select2-container--default .select2-selection--single .select2-selection__rendered { margin-top: 3px; }
  .select2-container--default .select2-selection--single .select2-selection__arrow { display: none; }
</style>
<!-- Wrapper Panel Table Input -->
<section class="panel">
  <header class="panel-heading">
    <h4 class="panel-title"><i class="far fa-edit"></i>&nbsp;Input Pengeluaran Gas Medis</h4>
  </header>
  <!-- Form Open -->
  <?php echo form_open('keluar/publish', ['id' => 'form_keluar_gas']); ?>
  <div class="panel-body">
    <!-- Melakukan Pemilihan Instalasi Minta Linen -->
    <div class="form-group">
      <div class="row">
        <!-- Input Tanggal -->
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
          <th>Gas Medis</th>
          <th>Pagi</th>
          <th>Siang</th>
          <th>Malam</th>
          <th>Total</th>
          <th>Hapus</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    <br>
    <button type="button" id="addRow" class="btn btn-success"><i class="fas fa-plus-circle"></i></button>
  </div>
  <!-- Footer Panel -->
  <div class="panel-footer">
    <div class="row">
      <div class="col-md-6">
        <button class="btn btn-default pull-left" onclick="history.back(); return false;">
          <i class="fas fa-arrow-left"></i> <?php echo translate('kembali'); ?>
        </button>
      </div>
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
  globalgases("<?= base_url('gases/GasesOnly') ?>", "gases[]", "all");

  // === [2] Add New Row to Gases Table
  $('#addRow').click(function() {
    let row = `
      <tr style="height:50px">
        <td style="width:50%">
          <select name="gases[]" class="form-control gases-select" required></select>
        </td>
        <td><input type="number" name="pagi[]" class="form-control" style="height:100%"></td>
        <td><input type="number" name="siang[]" class="form-control" style="height:100%"></td>
        <td><input type="number" name="malam[]" class="form-control" style="height:100%"></td>
        <td><input type="number" name="total[]" class="form-control" style="height:100%" readonly></td>
        <td><button type="button" class="btn btn-danger removeRow"><i class='fas fa-trash-alt'></i></button></td>
      </tr>
    `;
    $('#itemsTable tbody').append(row);

    let $select = $('#itemsTable tbody tr:last').find('.gases-select');
    
    // Initialize the Select
    $select.select2({
      placeholder: "Pilih Gas",
      width: '100%',
      data: [{id: '', text: 'Pilih Gas'}, ...gasOptions]
    });

    // Apply Consistent Style
    $select.next('.select2').find('.select2-selection').css({'margin-top':'1px', 'height':'33px'});

    // Update All Dropdown
    updateGasesDropdowns("gases[]");
  });

  // === [3] Delete Row
  $(document).on('click', '.removeRow', function() {
    $(this).closest('tr').remove();
    updateGasesDropdowns("gases[]");
  });

  // === [4] Total
  $(document).on('input', 'input[name="pagi[]"], input[name="siang[]"], input[name="malam[]"]', function() {
    // Find the Closest Table Row
    let $row = $(this).closest('tr');
    
    // Get the Values, Convert, Default to 0
    let pagi = parseFloat($row.find('input[name="pagi[]"]').val()) || 0;
    let siang = parseFloat($row.find('input[name="siang[]"]').val()) || 0;
    let malam = parseFloat($row.find('input[name="malam[]"]').val()) || 0;
    
    // Calculate the Total
    let total = pagi + siang + malam;
    
    // Update the Total Field
    $row.find('input[name="total[]"]').val(total.toFixed(2)); 
  });

  // === [5] Tanggal
  const today = new Date();
  const formatDate = (date) => date.toISOString().split("T")[0];
  $('#tanggal').val(formatDate(today));
});
</script>