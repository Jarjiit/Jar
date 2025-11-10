<?php
// Final Version of Stock Opname Model !
defined('BASEPATH') or exit('No direct script access allowed');

class Availability_model extends MY_Model {
  // Availability Model Responsible on Inventory Controller
  public function __construct(){
    parent::__construct();
  }

  public function SelectAll(){
    // Select All History of Medical Gases Stock Opname
    $column = "uuid, tanggal, user";
    $sqlquery = "SELECT $column FROM tabel_logging";
    $query = $this->db->query($sqlquery, []);
    return $query->result_array();
  }

  public function SelectGases(){
    // Support Formulir of Medical Gases Stock Opname
    $column = "id, gases, stock";
    $sqlquery = "SELECT $column FROM tabel_gases";
    $query = $this->db->query($sqlquery, []);
    return $query->result_array();
  }

  public function InsertTabular($tanggal, $user){
    // Insert Meta Data of Stock Opname Activity
    // Variable
    $uuid = $this->uuid->v4();
    // Insert Array
    $singular = array("uuid" => $uuid, "tanggal" => $tanggal, "user" => $user);
    // Single Input Array
    $this->db->insert('tabel_logging', $singular);
    // Return Result
    return $uuid;
  }

  public function MultiArray($uuid, $gases, $sistem, $fisik, $selisih){
    // Publish Multi Row of Medical Gases Opname
    // Variable Array
    $arrays = [];
    // Insert Array
    foreach ($gases as $i => $gas) {
        $arrays[] = [
        'logging' => $uuid,
        'gases'   => $gas,
        'sistem'  => $sistem[$i],
        'fisik'   => $fisik[$i],
        'selisih' => $selisih[$i]
      ];
    }
    // Insert Array
    return $this->db->insert_batch('tabel_logging_details', $arrays);
  }

  public function MultiMulti($items, $stocks){
    // Multi Jumlah
    foreach ($items as $i => $item) {
      $stock = $stocks[$i];
      $sql = "UPDATE tabel_gases SET stock = ? WHERE id = ?";
      $this->db->query($sql, [$stock, $item]);
    }
    return true;
  }

  public function DetailPrima($uuid){
    // Detail on Meta Data Stock Opname Table
    $column = "uuid, tanggal, user";
    $sqlquery = "SELECT $column FROM tabel_logging WHERE uuid = ?";
    $query = $this->db->query($sqlquery, [$uuid]);
    return $query->row_array();
  }

  public function DetailResult($uuid){
    // Detail on Stock Opname Content Medical Gases
    $column = "tld.gases as gsid, tg.gases, tld.sistem, tld.fisik, tld.selisih";
    $joined = "JOIN tabel_gases tg ON tld.gases = tg.id";
    $sqlquery = "SELECT $column FROM tabel_logging_details tld $joined WHERE tld.logging = ?";
    $query = $this->db->query($sqlquery, [$uuid]);
    return $query->result_array();
  }
}
?>