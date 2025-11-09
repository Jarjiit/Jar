<?php
// Final Version of Inventory / Stock Opname Gas Medis
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory extends Admin_Controller {
  // Define Inventory Manajemen on Medical Gases
  protected $role;
  // Parent Class Construct & Role & Variable
  public function __construct(){
    parent::__construct();
    $this->role = 'gases';
    $this->load->model(array('availability_model'));
  }

  public function index(){
    // Table Kegiatan Stock Opname Medical Gases
    if (!get_permission($this->role, 'is_view')){
      access_denied();
    }
    // File Result
    $this->data['result'] = $this->availability_model->SelectAll();
    // File Config
    $this->data['title'] = 'Tabel History Stock Opname Gas Medis';
    $this->data['sub_page'] = 'inventory/index';
    $this->data['main_menu'] = 'inventory';
    $this->load->view('layout/index', $this->data);
  }

  public function insert(){
    // Insert Formulir Stock Opname
    if (!get_permission($this->role, 'is_add')){
      access_denied();
    }
    // File Result
    $this->data['result'] = $this->availability_model->SelectGases();
    // File Config
    $this->data['title'] = 'Formulir Input Stock Opname Gas Medis';
    $this->data['sub_page'] = 'inventory/insert';
    $this->data['main_menu'] = 'inventory';
    $this->load->view('layout/index', $this->data);
  }

  public function publish(){
    // Publish Data Stock Opname to Table
    if (!get_permission($this->role, 'is_add')){
      access_denied();
    }
    // Variable
    $user = $this->input->post('user');
    $tanggal = $this->input->post('tanggal');
    // Variable Detail
    $gases = $this->input->post('gases');
    $sistem = $this->input->post('sistem');
    $fisik = $this->input->post('fisik');
    $selisih = $this->input->post('selisih');
    // Auto Start Transaction
    $this->db->trans_start();
    // Publish
    $uuid = $this->availability_model->InsertTabular($tanggal, $user);
    // Publish Array
    $this->availability_model->MultiArray($uuid, $gases, $sistem, $fisik, $selisih);
    // Multi Multi
    $this->availability_model->MultiMulti($gases, $fisik);
    // Auto Complete
    $this->db->trans_complete();
    // Response Status
    if ($this->db->trans_status() === FALSE) {
      set_alert('error', "Stock Opname Gagal !");
    } else {
      set_alert('success', "Stock Opname Berhasil !");
    }
    redirect(base_url('inventory'));
  }

  public function detail($uuid){
    // Detail Controller
    if (!get_permission($this->role, 'is_view')) {
      access_denied();
    }
    // File Table
    $this->data['primary'] = $this->availability_model->DetailPrima($uuid);
    // File Result
    $this->data['result'] = $this->availability_model->DetailResult($uuid);
    // File Config
    $this->data['title'] = 'Detail Kegiatan Stock Opname Gas Medis';
    $this->data['sub_page'] = 'inventory/detail';
    $this->data['main_menu'] = 'inventory';
    $this->load->view('layout/index', $this->data);
  }
}
?>