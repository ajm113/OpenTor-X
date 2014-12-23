<?php

class Torrentmodel extends CI_Model
{

	function __constrcut()
	{
		parent::__construct();
	}

	function get($category = '', $limit = 12, $start=0, $search="", $limitcharacters=TRUE)
	{

		$this->load->library("Swiss_cache");

		$cache_name = $category.'_'.$limit.'_'.$start.'_'.$search;

		$data = $this->swiss_cache->get($cache_name);
		
		if($data){
			return $data;
		}		

		$this->load->helper('text');

		//If we failed to get cached data lets get new results!

		$this->db->select('*');

		if(in_array(strtolower($category), $this->get_categories()) && !empty($category))
		{
			$this->db->where('category', $category);
		}
		
		if($limit > 1000)
		{
			$limit = 1000;
		}

		$this->db->limit($limit, $start);

		if(!empty($search))
		{
			$this->db->like('name', $search);
		}

		$query = $this->db->get('torrents');
		$this->db->flush_cache();

		$data = array();

		if($query->num_rows() == 0)
		{
			return $data;
		}

		foreach($query->result_array() as $row)
		{
			
			$row['name'] = character_limiter($row['name'], 40);
			$data[] = $row;
		}

		$this->swiss_cache->set($cache_name, $data, 18000);

		return $data;
	}

	function search($search, $start = 0)
	{
		$parts = explode(":", $search);
		$category = '';
		$qsearch = '';		

		$isCat = FALSE;
		if(count($parts) > 0)
		{
			if(in_array(strtolower($parts[0]), $this->get_categories()))
			{
				$category = $parts[0];
				$isCat = TRUE;
			}
		}

		if(count($parts) == 1 && !$isCat)
		{
			$qsearch = $parts[0];
		}
		else if(count($parts) > 1)
		{
			$qsearch = $parts[1];
		}

		return $this->get($category, 11, $start, $qsearch);
	}

	function get_torrent($hash)
	{
		$this->load->library("Swiss_cache");
		$cache_name = 'torrent_'.$hash;
		$data = $this->swiss_cache->get($cache_name);

		if($data){
			return $data;
		}
		
		//Get info from database...
		$this->db->select('*');
		$this->db->where('hash', $hash);
		$this->db->limit(1);
		$q = $this->db->get('torrents');
		$this->db->flush_cache();
		
		$data = NULL;
		
		if($q->num_rows() == 0)
		{
			return $data;
		}
		
		
		$data = $q->row_array();
		
		//Save the data for later...
		$this->swiss_cache->set($cache_name, $data, 60);
		
		return $data;
	}
	
	function upload_torrent($fieldname)
	{
	
		if(!isset($_FILES[$fieldname]))
		{
			return "FILE DATA NOT FOUND!";
		}
		
		//Validate the form...
		$name = trim(htmlentities($this->input->post('name')));
		$category = trim(htmlentities(strtolower($this->input->post('category'))));


		if($category === FALSE || $name === FALSE)
		{
			return "Invalid passed data!";
		}

		if(!in_array($category, $this->get_categories()))
		{
			return "Invalid category...";
		}		
		

		//Check file info...
		$fileinfo = pathinfo($_FILES[$fieldname]['name']);
		
		if(strtolower($fileinfo['extension']) !== 'torrent')
		{
			return "Invalid extension...";
		}
		
		if($_FILES[$fieldname]['size'] > 500000)
		{
			return "Torrent file exceeds max size!";
		}
		
		$new_path = FCPATH.'assets/tmp_torrents/';
		
		$new_name = $this->generate_random_name($new_path);
		
		$new_full = $new_path.$new_name.'torrent';
		
		//Upload file to server..
		move_uploaded_file($_FILES[$fieldname]["tmp_name"], $new_full);
		
		//Upload torrent to torrent cache...
		
		$post_data = array(
			"upload" => true, // Don't change
			"torrent" => base_url()."assets/tmp_torrents/".$new_name.'.torrent'
		);

		$ch = curl_init("http://torrentcaching.com/api/upload.php");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$result = curl_exec($ch);
		curl_close($ch);
		$json = json_decode($result);

		//If something went wrong, tell the user...
		if($json->error){
			@unlink($new_full);
			return $json->message;
		}
		
		$fileInfo = pathinfo($json->url);
		
		$idata['hash'] = basename($json->url);
		$idata['name'] = $name;
		$idata['category'] = $category;
		$idata['download'] = $json->url;
		
		return $idata;
	
	}
	
    private function generate_random_name($filepath)
    {
    	while(true)
    	{
    		$new_name = md5(rand(1,99999999));
    		
    		if(!file_exists($filepath.$new_name.'.jpg'))
    		{
    			return $new_name;
    		}
    	}
    }
	

	function get_categories()
	{
		return array('anime', 'movies', 'music', 'other', 'applications', 'games', 'tv', 'xxx', 'books');
	}

}
?>
