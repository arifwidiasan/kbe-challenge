<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ebooks_Controller extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
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

	public function delete_ebook($id)
	{
		$this->load->model('ebooks_model');
		$this->ebooks_model->deleteEbook($id);
		redirect('ebooks_controller');
	}
}
