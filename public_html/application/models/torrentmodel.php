<?php

class Torrentmodel extends CI_Model
{

	function __constrcut()
	{
		parent::__construct();
	}

	function get($category = '', $limit = 12, $start=0, $search="", $limitcharacters=TRUE)
	{		
		$this->load->helper('text');

		$this->db->start_cache();
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

		$this->db->stop_cache();
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

		return $data;
	}
	/*
		Used to help automate the search query. i.e Knowing 
	*/
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
	

	static public function get_categories()
	{
		return array('anime', 'movies', 'music', 'other', 'applications', 'games', 'tv', 'xxx', 'books');
	}

}
?>
