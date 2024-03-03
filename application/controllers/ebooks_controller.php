<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ebooks_Controller extends CI_Controller {

	// fungsi index
	public function index()
	{
		$this->load->model('ebooks_model');
		$data['ebooks'] = $this->ebooks_model->getEbooks();

		//random token
		$token = bin2hex(random_bytes(16));
		$this->session->set_userdata('token', $token);

		$data['token'] = $token;
		$this->load->view('ebooks_list', $data);
	}
	// fungsi hapus ebook
	public function delete_ebook($id)
	{
		$this->load->model('ebooks_model');
		$this->ebooks_model->deleteEbook($id);
		redirect('ebooks_controller');
	}
}
