<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Share extends CI_Controller {

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
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->model("torrentmodel");
		$this->load->helper('form');
		$this->load->view('default/header', ['title' => 'Share Torrents']);
		$this->load->view('default/search');
		
		$options = $this->torrentmodel->get_categories();
		asort($options);
		
		$flip = array();
		foreach($options as $key => $value)
		{
			$flip[$value] = $value;
		}
		
		$this->load->view('default/upload_form', ['options' => $flip]);
		
		
		$this->load->view('default/footer');
	}
	
	public function upload()
	{
		$this->load->model("torrentmodel");
		$response = $this->torrentmodel->upload_torrent('file');
		var_dump($response);
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
