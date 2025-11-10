<?php
// Final Version of Supplier Controller ! Supplier Controller !
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends Admin_Controller {
  // Define Supplier Controller
  protected $role;
  // Parent Class Construct & Variable
  public function __construct(){
    parent::__construct();
    $this->role = 'supplier';
    // Supplier Model
    $this->load->model(array('supplier_model'));
  }

  public function index(){
    // Controller Supplier Main File (Single File)
    if (!get_permission($this->role, 'is_view')){
      access_denied();
    }
    // File Config
    $this->data['title'] = 'Tabel Supplier Gas Medis';
    $this->data['sub_page'] = 'supplier/index';
    $this->data['main_menu'] = 'supplier';
    $this->load->view('layout/index', $this->data);
  }

  public function supplier(){
    // Function to Return All Data of Supplier (Support Supplier Main File)
    $datatables = $this->supplier_model->TableSupplier();
    $datatables = json_decode($datatables, true);
    $datatables = $datatables['data'];
    // Supplier Array
    $supplier_array = array();
    $datarow = 1;
    // Validate the Data Table
    if (!empty($datatables)){
      foreach($datatables as $key => $value){
        // View Supplier
				$view = '<button class="btn btn-circle icon btn-info" id="view_supplier" name="view_supplier" data_view_supplier="' . $value['uuid'] . '"><i class="fas fa-info-circle"></i></button>';
        // Change Data Supplier
        $edit = '';
				if (get_permission('supplier', 'is_edit')) {
          // Detail & Update Supplier
					$edit .= '<button class="btn btn-circle icon btn-primary" id="edit_supplier" name="edit_supplier" data_edit_supplier="' . $value['uuid'] . '"><i class="fas fa-pen-nib"></i></button>';
				}
        // Define Delete Variable
        $delete = '';
				if (get_permission('supplier', 'is_delete')) {
          // Delete Supplier
					$delete .= btn_delete('supplier/delete/' . $value['uuid']);
				}
        // Insert to Array
        $row = array();
        $row[] = $datarow;
        $row[] = $value['name'];
        $row[] = $value['position'];
        $row[] = $value['company_name'];
        $row[] = $value['mobileno'];
        $row[] = $value['email'];
        $row[] = $edit . $view . $delete;
        $supplier_array[] = $row;
        $datarow += 1;
      }
    }
    // Initiate JSON Result
    $json_data = array(
      "draw" => intval($datatables->draw),
      "recordsTotal" => intval($datatables->recordsTotal),
      "recordsFiltered" => intval($datatables->recordsFiltered),
      "data" => $supplier_array,
    );
    // Return JSON
    echo json_encode($json_data);
  }

  public function publish(){
    // Function to Publish Supplier Data to Table
     if (!get_permission($this->role, 'is_add') && !get_permission($this->role, 'is_edit')) {
      access_denied();
    }
    // Perform Form Validation Against Input
    $this->form_validation->set_rules('name', 'Nama', 'trim|required');
    $this->form_validation->set_rules('position', 'Posisi', 'trim|required');
    $this->form_validation->set_rules('jenis_usaha_id', 'Jenis Usaha', 'trim|required');
    $this->form_validation->set_rules('company_name', ' Nama Perusahaan', 'trim|required');
    $this->form_validation->set_rules('address', 'Alamat', 'trim|required');
    $this->form_validation->set_rules('mobileno', 'No Telepon', 'trim|required');
    $this->form_validation->set_rules('email', 'Email', 'trim|required');
    $this->form_validation->set_rules('bank_id', 'Bank', 'trim|required');
    $this->form_validation->set_rules('bank_acc', 'No Rekening', 'trim|required');
    // Split Insert or Update
    if ($this->form_validation->run() !== false) {
      $data = $this->input->post();
			$this->supplier_model->PublishSupplier($data);
      $array = array('status' => 'success', 'error' => '', 'message' => 'Proses Berhasil !');
    } else {
      $array = array('status' => 'error', 'error' => '', 'message' => 'Proses Gagal !');
    }
    echo json_encode($array);
  }

  public function detail(){
    // Controller to Return One Supplier on Certain Id
    if (!get_permission($this->role, 'is_add') || !get_permission($this->role, 'is_edit')) {
      access_denied();
    }
    // Detail Supplier
    $uuid = $this->input->post('data_edit_supplier');
		$data = $this->supplier_model->SupplierDetail($uuid);
    echo json_encode($data);
  }

  public function delete($uuid){
    // Delete Supplier on Soft Delete
    if (!get_permission($this->role, 'is_delete')){
      access_denied();
    }
    // Perform Delete on Model
    $this->supplier_model->DeleteSupplier($uuid);
  }

  public function SupplierOnly(){
    // Return All Active Supplier on Select
    if (!get_permission($this->role, 'is_view')){
      access_denied();
    }
    // Variable
    $suppliers = $this->supplier_model->SupplierOnly();
    $result = array();
    // Make it Available on Array
    foreach($suppliers as $supplier){
      // Convert Object to Array
      $supplier = (array) $supplier;
      // Insert to Array
      $result[] = array("id" => $supplier['id'], "text" => $supplier['company_name']);
    }
    // Return Result
    echo json_encode($result);
  }
}
?>