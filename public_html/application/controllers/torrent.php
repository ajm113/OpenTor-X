<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Torrent extends CI_Controller {

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
	 
 	function _remap($method){   
    $this->index($method);
        
    }
    
	public function index($torrent = NULL)
	{

		$this->load->model("torrentmodel");


		$data = $this->torrentmodel->get_torrent($torrent);
		
		if(!$data)
		{
			redirect(base_url());
		}

		$this->load->view('default/header',  ['title' => $data['name']]);
		$this->load->view('default/torrent', $data);
		$this->load->view('default/footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
