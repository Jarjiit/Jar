<?php
// Final Version of Supplier Model ! Supplier Model !
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_model extends MY_Model {
  // Define Supplier Model
  public function __construct(){
    // Parent Class Construct & Data Table
    parent::__construct();
    $this->load->library('datatables');
  }

  public function TableSupplier(){
    // All of Medical Gases Supplier
    $column = "uuid, name, position, company_name, mobileno, email";
    $this->datatables->select($column)->from('available_vendor');
    return $this->datatables->generate();
  }

  public function PublishSupplier($data){
    // Supplier Model to Publish Supplier Input to Table
    // Variable
    $uuid = $this->uuid->v4();
    // Input Array
    $insert_data = array(
			'uuid' => $data['uuid'],
			'name' => $data['name'],
			'position' => $data['position'],
			'company_name' => $data['company_name'],
			'address' => $data['address'],
			'mobileno' => $data['mobileno'],
			'email' => $data['email'],
			'bank_acc' => $data['bank_acc'],
			'jenis_usaha_id' => $data['jenis_usaha_id'],
			'bank_id' => $data['bank_id']
    );
    // Split Insert & Update Module on Availability of Id (Update Module)
    if (isset($data['uuid']) && !empty($data['uuid'])) {
      $insert_data['updated_at'] = date("Y-m-d H:i:s");
			$insert_data['updated_by'] = html_escape($this->session->userdata('name'));
			$this->db->where('uuid', $data['uuid']);
			$this->db->update('kontrak_penyedia', $insert_data);
    } else {
      // Split Insert & Update Module on Availability of Id (Insert Module)
      $insert_data['uuid'] = $uuid;
			$insert_data['is_active'] = 1;
			$insert_data['created_at'] = date("Y-m-d H:i:s");
			$insert_data['created_by'] = html_escape($this->session->userdata('name'));
			$this->db->insert('kontrak_penyedia', $insert_data);
    }
    return $uuid;
  }

  public function SupplierDetail($uuid){
    // Deliver Certain Data of Supplier on Certain Id
    $this->db->from('available_vendor')->where('uuid', $uuid);
    // Return Single Array
    return $this->db->get()->row_array();
  }

  public function DeleteSupplier($uuid){
    // Soft Delete on Certain Supplier Data
    if (!isset($uuid) || empty($uuid)) {
      return false;
    }
    // Soft Delete Variable
    $data = array(
      'is_active' => 0,
      'deleted_at' => date("Y-m-d H:i:s"),
      'deleted_by' => html_escape($this->session->userdata('name'))
    );
    // Soft Delete on Filter (I)
    $this->db->where('uuid', $uuid);
    // Return True or False & Update (II)
    return $this->db->update('kontrak_penyedia', $data);
  }

  public function SupplierOnly(){
    // All of Available Supplier to Initiate Kontrak
    $column = "id, company_name";
    $sqlquery = "SELECT $column FROM kontrak_penyedia WHERE is_active = ?";
    $query = $this->db->query($sqlquery, [1]);
    return $query->result();
  }
}
?>