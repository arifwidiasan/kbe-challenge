<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ebooks_Model extends CI_Model {
    // fungsi untuk mengambil semua data ebook
    public function getEbooks()
    {
        $query = $this->db->get('ebooks');
        return $query->result();
    }
    // fungsi untuk mengambil data ebook berdasarkan id
    public function getEbook($id)
    {
        $query = $this->db->get_where('ebooks', array('id' => $id));
        return $query->row();
    }
    // fungsi untuk menghapus data ebook berdasarkan id 
    public function deleteEbook($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('ebooks');
    }
}