<?php
// Final Version of Pengeluaran Gas Medis Controller !
defined('BASEPATH') or exit('No direct script access allowed');

class Keluar extends Admin_Controller {
  // Define Role & Construct
  protected $role;
  public function __construct(){
    // Construct & Model
    parent::__construct();
    $this->role = 'ipsrs';
    $this->load->model(array('keluar_model'));
  }

  public function index(){
    // Main File Function to All Pengeluaran Gas Medis History
    if (!get_permission($this->role, 'is_view')){
      access_denied();
    }
    // File Config
    $this->data['title'] = 'Tabel Pengeluaran Gas Medis IPSRS';
    $this->data['sub_page'] = 'keluar/index';
    $this->data['main_menu'] = 'keluar';
    $this->load->view('layout/index', $this->data);
  }

  public function tabel_historia(){
    // Tabel Historia on Keluar Gas Medis
    if (!get_permission($this->role, 'is_view')) {
      access_denied();
    }
    // Variable
    $start = date('Y-m-d 00:00:00', strtotime($this->input->get('start')));
    $final = date('Y-m-d 23:59:59', strtotime($this->input->get('final')));
    // Table Content
    $results = $this->keluar_model->TabelKeluar($start, $final);
    // Return Result on Tabel
    echo json_encode($results);
  }

  public function insert(){
    // Controller Insert Gas Medis Keluar
    if (!get_permission($this->role, 'is_add')){
      access_denied();
    }
    // File Config
    $this->data['title'] = 'Input Pengeluaran Gas Medis';
    $this->data['sub_page'] = 'keluar/insert';
    $this->data['main_menu'] = 'keluar';
    $this->load->view('layout/index', $this->data);
  }

  public function publish(){
    // Controller Publish Data Pengeluaran Gas Medis
    if (!get_permission($this->role, 'is_add')){
      access_denied();
    }
    // Variable Setup Primary
    $user = $this->input->post('user');
    $tanggal = $this->input->post('tanggal');
    // Variable Setup Content
    $gases = $this->input->post('gases');
    $pagi = $this->input->post('pagi');
    $siang = $this->input->post('siang');
    $malam = $this->input->post('malam');
    $total = $this->input->post('total');
    // Start Trans
    $this->db->trans_begin();
    // Insert Single Array
    foreach ($gases as $i => $gas) {
      // Input Pengeluaran
      $inputs = [
        'tanggal' => $tanggal, 
        'user' => $user,
        'gases' => $gas,
        'pagi' => $pagi[$i],
        'sore' => $siang[$i],
        'malam' => $malam[$i],
        'total' => $total[$i]
      ];
      // Insert Data
      $this->keluar_model->PublishTabular($inputs, 'id');
      // Single Content
      $this->keluar_model->InsertContent($total[$i], $gas);
    }
    // Close Trans
    $this->db->trans_complete();
    // Validate Result
    if ($this->db->trans_status() === FALSE) {
      // Fail Alert
      set_alert('error', 'Gagal !');
    } else {
      // OK Alert
      set_alert('success', 'Sukses !');
    }
    redirect(base_url('keluar'));
  }

  public function detail($tanggal){
    // Melihat Detail Pengeluaran Gas Medis
    if (!get_permission($this->role, 'is_view')){
      access_denied();
    }
    // File Result Meta Data
    $this->data['primary'] = $this->keluar_model->DetailPrima($tanggal);
    // File Result Content of Pengeluaran
    $this->data['content'] = $this->keluar_model->DetailContent($tanggal);
    // File Config
    $this->data['title'] = "Detail Pengeluaran Gas Medis $tanggal";
    $this->data['sub_page'] = 'keluar/detail';
    $this->data['main_menu'] = 'keluar';
    $this->load->view('layout/index', $this->data);
  }
}
?>