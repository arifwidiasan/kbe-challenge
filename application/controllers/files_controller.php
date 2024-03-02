<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Files_Controller extends CI_Controller {
    
    //make function to response a file if token is in sessions

    public function download($pathfile)
    {
        $path = base64_decode($pathfile);
        $token = $this->input->get('token');
        if($token == $this->session->userdata('token')){

            //delete token from sessions
            $this->session->unset_userdata('token');

            $this->load->helper('download');
            $data = file_get_contents($path);
            force_download($path, $data);
        }else{
            //return 401 if token is not in sessions
            show_error('Unauthorized', 401);
        }
    }

    function base64_url_encode($input) {
		return strtr(base64_encode($input), '+/=', '-_.');
	}
	   
	function base64_url_decode($input) {
		return base64_decode(strtr($input, '-_.', '+/='));
	}
}

?>