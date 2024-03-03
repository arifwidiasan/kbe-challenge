<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Files_Controller extends CI_Controller {
    
    // membuat fungsi untuk respon file bila token ada di sessions, mencegah download file
    public function file($pathfile)
    {
        $path = base64_decode($pathfile);
        $token = $this->input->get('token');
        if($token == $this->session->userdata('token')){

            // hapus token dari sessions
            $this->session->unset_userdata('token');

            $this->load->helper('download');
            $data = file_get_contents($path);
            force_download($path, $data);
        }else{
            //return 401 jika token tidak ada di sessions
            show_error('Unauthorized', 401);
        }
    }
}

?>