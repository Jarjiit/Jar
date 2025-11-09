<?php
// Final Version of Medical Gases Controller !
defined('BASEPATH') or exit('No direct script access allowed');

class Gases extends Admin_Controller {
  // Define Medical Gases Master Controller
  protected $role;
  // Parent Class Construct & Variable
  public function __construct(){
    parent::__construct();
    $this->role = 'ipsrs';
    // Master Model
    $this->load->model(array('master_model'));
  }

  public function index(){
    // Function to Handle Main File of Gases & Show All Available Gases
    if (!get_permission($this->role, 'is_view')){
      access_denied();
    }
    // File Config
    $this->data['title'] = 'Tabel Master Gas Medis';
    $this->data['sub_page'] = 'gases/index';
    $this->data['main_menu'] = 'gases';
    $this->load->view('layout/index', $this->data);
  }

  public function gases(){
    // Data Table Support on Gases Table
    // Multi Variable
    $filter = ['deleted_at' => NULL]; $column = 'id, gases, unit, stock';
    // Retrieve All Available Gases on Filter
    $datatables = $this->master_model->GlobalSelect('tabel_gases', $column, true, $filter);
    $datatables = json_decode($datatables, true);
    $datatables = $datatables['data'];
    $gases_array = array();
    $datarow = 1;
    // Validate on Datatable
    if (!empty($datatables)){
      // Single Value
      foreach($datatables as $key => $value){
        // Define Detail Variable
        $detail = '';
        if (get_permission($this->role, 'is_view') || get_permission($this->role, 'is_edit')) {
          // Detail & Update Gases
          $detail .= '<button class="btn btn-circle icon btn-primary" id="edit_gases" name="edit_gases" data_edit_gases="' . $value['id'] . '"><i class="fas fa-pen"></i></button>';
        }
        // Define Delete Variable
        $delete = '';
        if (get_permission($this->role, 'is_delete')) {
          // Delete Gases
					$delete .= btn_delete('gases/delete/' . $value['id']);
        }
        // Insert to Array
        $row = array();
        $row[] = $datarow;
        $row[] = $value['gases'];
        $row[] = number_format($value['stock']);
        $row[] = $value["unit"];
        $row[] = $detail.' '.$delete;
        $gases_array[] = $row;
        $datarow += 1;
      }
    }
    // Initiate JSON Result
    $json_data = array(
      "draw" => intval($datatables->draw),
      "recordsTotal" => intval($datatables->recordsTotal),
      "recordsFiltered" => intval($datatables->recordsFiltered),
      "data" => $gases_array,
    );
    // Return JSON
    echo json_encode($json_data);
  }

  public function publish(){
    // Controller to Handle Insert & Update on Gases
    if (!get_permission($this->role, 'is_add') && !get_permission($this->role, 'is_edit')) {
      access_denied();
    }
    // Variable Post
    $posts = $this->input->post();
    // Validate Against Column
    $column = ['id', 'gases', 'unit', 'stock']; 
    $inputs = array_intersect_key($posts, array_flip($column));
		$result = $this->master_model->InsertUpdateData('tabel_gases', $inputs, 'id');
    // Select Output
    if ($result) {
      $response = array('status' => 'success', 'message' => 'Proses Berhasil !');
    } else {
      $response = array('status' => 'error', 'message' => 'Proses Gagal !');
    }
    // Return Output
    echo json_encode($response);
  }

  public function detail(){
    // Controller to Handle Gases Detail
    if (!get_permission($this->role, 'is_view')) {
      access_denied();
    }
    // Content on Gases
    $id = $this->input->post('data_edit_gases');
    // Detail Column
    $column = 'id, gases, unit, stock';
    $data = $this->master_model->GlobalSelect('tabel_gases', $column, false, ['id' => $id]);
    echo json_encode($data);
  }

  public function delete($id){
    // Controller to Perform Soft Delete on Gases Table
    if (!get_permission($this->role, 'is_delete')){
      access_denied();
    }
    // Variable of Soft Delete
    $time = date('Y-m-d H:i:s');
    $user = html_escape($this->session->userdata('name'));
    $inputs = ['deleted_at' => $time, 'deleted_by' => $user];
    // Delete the Data on Model
    $this->master_model->DeleteData('tabel_gases', 'id', $id, $inputs);
  }

  public function GasesOnly(){
    // Controller to Handle Gases Option
    if (!get_permission($this->role, 'is_view')){
      access_denied();
    }
    // Column to Detail
    $column = "id, gases, stock";
    $gases = $this->master_model->GlobalSelect("tabel_gases", $column, false, ['deleted_at' => NULL]);
    $result = array();
    // Insert Single to Array
    foreach ($gases as $value) {
      // Set Array
      $value = (array) $value;
      // Insert to Array
      $result[] = array("id" => $value['id'], "text" => $value['gases'], "jumlah" => $value["stock"]);
    }
    echo json_encode($result);
  }
}
?>