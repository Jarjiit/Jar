<!-- Custom Styling -->
<style>
	.dt-buttons, #table-export_filter, div.dataTables_wrapper div.dataTables_paginate {
    display: none !important;
  }
  label { color : black }
  .select2-container .select2-selection { height: 38px; }
  .select2-container--default .select2-selection--single .select2-selection__rendered { margin-top: 3px; }
  .select2-container--default .select2-selection--single .select2-selection__arrow { display: none; }
</style>
<!-- Main Panel -->
<section class="panel">
  <div class="tabs-custom">
    <div class="tab-content">
      <div class="tab-pane box active">
        <?php echo form_open('/kerja/adendum/publish', ['id' => 'form_kerja_sama']); ?>
        
        <!-- Panel Meta Title -->
        <div class="form-group">
          <div class="row">
            <!-- Pelaku -->
            <div class="col-md-6">
              <label for="user" class="form-label">Pelaku</label>
              <input type="text" name="user" id="user" class="form-control" value="<?php echo get_user_name();?>" readonly>
            </div>
            <!-- Tanggal -->
            <div class="col-md-6">
              <label for="tanggal" class="form-label">Tanggal Revisi</label>
              <input type="date" name="tanggal" id="tanggal" class="form-control" readonly>
            </div>
          </div>
        </div>
        <!-- Kenapa Revisi ? -->
        <div class="form-group">
          <label for="serial" class="form-label">Sebut Alasan Revisi Perjanjian Kerja Sama</label>
          <input type="text" name="alasan" id="alasan" class="form-control">
        </div>

        <!-- Pilih Perjanjian -->
        <div class="form-group">
          <label for="serial" class="form-label">Pilih Perjanjian Kerja Sama</label>
          <input type="text" name="serial" id="serial" class="form-control" value="<?php echo $content['uuid'];?>" readonly>
        </div>
        <!-- Judul -->
        <div class="form-group">
          <label for="judul" class="form-label">Judul Perjanjian Kerja Sama</label>
          <input type="text" name="judul" id="judul" class="form-control" value="<?php echo $content['judul'];?>" readonly>
        </div>

        <div class="form-group">
          <div class="row">
            <div class="col-sm-6">
              <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
              <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="<?php echo $content["tanggal_mulai"];?>">
            </div>
            <div class="col-sm-6">
              <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
              <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="<?php echo $content["tanggal_selesai"];?>">
            </div>
          </div>
        </div>
        <!-- Serial Kontrak -->
        <div class="form-group">
          <div class="row">
            <div class="col-sm-6">
              <label for="tanggal_mulai" class="form-label">Nomor Hukum Kontrak</label>
              <input type="text" name="nomor_hukum" id="nomor_hukum" class="form-control" value="<?php echo $content["nomor_hukum"];?>" readonly>
            </div>
            <div class="col-sm-6">
              <label for="tanggal_selesai" class="form-label">Nomor Kontrak Supplier</label>
              <input type="text" name="nomor_kontrak" id="nomor_kontrak" class="form-control" value="<?php echo $content["nomor_kontrak"];?>" readonly>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="row">
            <div class="col-sm-6">
              <label for="tanggal_mulai" class="form-label">Gas Medis</label>
              <input type="text" value="Liquid Oxygen" class="form-control" readonly>
            </div>
            <div class="col-sm-6">
              <label for="tanggal_selesai" class="form-label">Harga Satuan Disepakati</label>
              <input type="number" name="harga_satuan" id="harga_satuan" class="form-control" value="<?php echo $content["harga_satuan"];?>">
            </div>
          </div>
        </div>

        <!-- Footer Panel -->
        
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</section>

<script>
  $(document).ready(function() {
    const today = new Date();
    const formatDate = (date) => date.toISOString().split("T")[0];
    $('#tanggal').val(formatDate(today));
  })
</script>