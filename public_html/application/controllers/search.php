<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {

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

		$search = $this->input->get('s');

		$data['title'] = 'Results';
		$data['anchor'] = 'results';
		
		if($search)
		{
			$data['results'] = $this->torrentmodel->search($search);
		}
		
		$this->load->view('default/header',  ['title' => $search]);
		$this->load->view('default/search');
		

		
		$this->load->view('default/footer');
	}
	
	public function ajax($start = 0)
	{
		$this->load->model("torrentmodel");
		$data['anchor'] = $start;
		$search = $this->input->get('s');
		$data['results'] = $this->torrentmodel->search($search, $start);
		
		if(count($data['results']) > 0)
		{
			$this->load->view('default/table', $data);
		}	
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
