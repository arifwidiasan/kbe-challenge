<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ebooks_Model extends CI_Model {

    public function getEbooks()
    {
        $query = $this->db->get('ebooks');
        return $query->result();
    }

    public function getEbook($id)
    {
        $query = $this->db->get_where('ebooks', array('id' => $id));
        return $query->row();
    }
    
    public function deleteEbook($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('ebooks');
    }
}