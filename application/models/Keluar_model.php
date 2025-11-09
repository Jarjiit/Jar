<?php
// Pengeluaran Controller !
defined('BASEPATH') or exit('No direct script access allowed');

class Keluar_model extends MY_Model {
  // Keluar Model Responsible on Entire Pengeluaran Gas Medis
  public function __construct(){
    parent::__construct();
  }

  public function TabelKeluar($start, $final){
    // Return Tabel Pengeluaran Gas with Only User & Tanggal
    $filter = "WHERE tanggal BETWEEN ? AND ?";
    $sql = "SELECT DISTINCT tanggal, user FROM tabel_pengeluaran $filter";
    $query = $this->db->query($sql, [$start, $final]);
    $results = $query->result();
    return $results;
  }

  public function PublishTabular($data, $primaryKey){
    // Publish on Primary Table Pengeluaran Gas Medis
    // Trans Start
    $this->db->trans_start();
    // Primary Key Selectia
    if (empty($data[$primaryKey])) {
      // Insert
      $this->db->insert('tabel_pengeluaran', $data);
    } else {
      // Update
      $this->db->where($primaryKey, $data[$primaryKey]);
      $this->db->update('tabel_pengeluaran', $data);
    }
    // Complete & Close Trans
    $this->db->trans_complete();
    // Return True if All Queries Succeeded, False Otherwise
    return $this->db->trans_status();
  }

  public function InsertContent($total, $gas){
    // Input Content on Pengeluaran Gas Medis
    $sql = "UPDATE tabel_gases SET stock = stock - ? WHERE id = ?";
    // Perform Queries
    $query = $this->db->query($sql, [$total, $gas]);
    // Return Result
    if (!$query) return false; else return $query;
  }

  public function DetailPrima($tanggal){
    // Return Main Primary Content of Distinct Metadata (Detail)
    $filter = "WHERE tanggal = ?";
    $sql = "SELECT DISTINCT tanggal, user FROM tabel_pengeluaran $filter";
    $result = $this->db->query($sql, [$tanggal]);
    return $result->row_array();
  }

  public function DetailContent($tanggal){
    // Return Content of Gases on Daily Basis (Detail)
    $column = "tp.gases as gsid, tg.gases, tp.pagi, tp.sore, tp.malam, tp.total";
    $joined = "JOIN tabel_gases tg ON tp.gases = tg.id";
    $sql = "SELECT $column FROM tabel_pengeluaran tp $joined WHERE tp.tanggal = ?";
    $result = $this->db->query($sql, [$tanggal]);
    return $result->result_array();
  }
} 