<?php

define('ENABLE_GZIP', TRUE); 										//Enable if you are caching large amounts of data.
define('DEFAULT_CACHE_MODE', 'AUTO');								//Automaticly find the best cache method.
define('CACHE_PREFIX', 'sc_');										//Appends this prefix to cached data id.
define('CACHE_DIR', dirname(__FILE__).'/cache/');					//SET THIS TO WHERE YOUR STORING YOUR CACHE FOLDERS.
define('IMAGE_CACHE_DIRECTORY', dirname(__FILE__).'/cache/img/');	//SET THIS TO WHERE YOUR STORING YOUR IMAGE CACHE.
define('IMAGE_CACHE_URL_LOCATION', 'http://localhost/Swiss%20Cache/cache/img/');	//SET THIS TO WHERE YOU STORE YOUR CACHED IMAGES FOR WEB ACCESS
define('IMAGE_CACHE_COMP_JPG', 50);
define('IMAGE_CACHE_COMP_PNG', 8);



class Swiss_cache
{
	private $mode = "";
	private $error_message;
	private $apc_enabled = FALSE;
	private $gd_enabled = FALSE;
	private $imagemagick_enabled = FALSE;
	private $server_memory_limit;
	private $delete_expired_cache_file = TRUE;
	
	function __construct()
	{
		$this->mode = strtolower(DEFAULT_CACHE_MODE);
		
		if(extension_loaded('apc'))
		{
			$this->apc_enabled = TRUE;
		}
		
		if($this->mode === 'auto')
		{
			$this->mode = 'file';
			if($this->apc_enabled)
			{
				$this->mode = 'apc';
			}
		}
		
		if(extension_loaded('gd') && function_exists('gd_info'))
		{
			$this->gd_enabled = TRUE;
		}
		
		if(class_exists("Imagick"))
		{
			$this->imagemagick_enabled = true;
		}
		
		$this->server_memory_limit = (int) ini_get('memory_limit');
	}	
	
	public function get($id, $mod = 'any')
	{
		$id = CACHE_PREFIX.$id;
	
		$mod = strtolower($mod);
		if($mod === 'any')
		{
			$mod = $this->mode;
		}
		
		if($mod === 'apc' && $this->apc_enabled) {
			return $this->decode_string(apc_fetch($id));
		}
		else {	//Default to file if all else fails...
			return $this->get_cache_file(CACHE_DIR.md5($id));
		}
		
		return FALSE;
	}
	
	public function set($id, $data, $life = 600, $mod = 'any', $gzip = FALSE)
	{
		$id = CACHE_PREFIX.$id;
		$mod = strtolower($mod);
		if($mod === 'any')
		{
			$mod = $this->mode;
		}
		
		if($mod === 'apc' && $this->apc_enabled) {
			if($life < 1)
			{
				$life = 604800; //Last 7 days.
			}
		
			apc_store($id, $data, $life);
			
			return TRUE;
		}
		
		//Attempt to save in file.		
		if(ENABLE_GZIP)
		{
			$gzip = TRUE;
		}
	
		return $this->save_cache_file(CACHE_DIR.md5($id), $data, $life, $gzip);
	}
	
	public function delete($id, $mod = 'any')
	{
		$id = CACHE_PREFIX.$id;
		$mod = strtolower($mod);
		
		if($mod === 'any')
		{
			$mod = $this->mode;
		}
		
		if($mod === 'file')
		{
			$this->delete_cache_file($id);
			return TRUE;
		}
		
		if($mod === 'apc' && $this->apc_enabled)
		{
			return (apc_exists($id)) ? apc_delete($id) : true;
		}
		
		return FALSE;
	}
	
	public function delete_all_images()
	{
		$this->delete_all_cache_files(TRUE);
	}
	
	public function delete_all($type = 'all')
	{
		$type = strtolower($type);
		
		
		switch($type)
		{
			case 'all':
			
				if($this->apc_enabled)
				{
					apc_clear_cache();
				}

				$this->delete_all_cache_files();
			break;
			case 'apc':
				if($this->apc_enabled)
				{
					apc_clear_cache();
				}
			break;
			case 'file':
				$this->delete_all_cache_files();
			break;	
		
		}
	}
	
	//ONLY WORKS ON CACHE FILES
	public function delete_expired_cache($del = TRUE)
	{
		$this->delete_expired_cache_file = $del;
	}
	
	
	public function is_cache_file_expired($id)
	{
		$name = md5(CACHE_PREFIX.$id);
		$result = @file_get_contents($name);
		
		if(!$result)
		{
			return TRUE;
		}
		
		$decoded = $this->decode_string($result);
		
		if($decoded !== FALSE)
		{
			$result = $decoded;
		}
		
		$file_contents = unserialize($result);
		
		//Delete file if expired...
		if(time() > $file_contents['e'] && $file_contents['e'] > 0)
		{
			return TRUE;
		}
		
		return FALSE;
	}
	
	
	public function cache_image($path)
	{
		if(!is_string($path)){
			$this->error_message = "Image source MUST be a string.";
			return FALSE;
		}
		
		if(!$this->gd_enabled && !$this->imagemagick_enabled) {
			$this->error_message = "GD nor ImageMagic is installed!";
			return $path;		
		}
		
		//Check if we already cached this image....
		

		$fileInfo = pathinfo($path);
		$mimeType = strtolower($fileInfo['extension']);
		$basename = $fileInfo['basename'];
		$enc_filename = md5($path).'.'.$mimeType;
		
		
		if(is_file(IMAGE_CACHE_DIRECTORY.$enc_filename))
		{
			return IMAGE_CACHE_URL_LOCATION.$enc_filename;
		}
		
		
		
		if(!in_array($mimeType, array( 'gif', 'jpeg', 'png', 'jpg' ) ))
		{
			$this->error_message = "The image provided has a invalid extension. .gif, .png, .jpeg/.jpg supported.";
			return $path;				
		}
		
	
		//Bump memory limit to handle large images...
		ini_set('memory_limit', '2500M');
		$this->compress_resource($path, $mimeType, IMAGE_CACHE_DIRECTORY.$enc_filename);
		//Reset memory limit...
		ini_set('memory_limit', $this->server_memory_limit);
		
		if(is_file(IMAGE_CACHE_DIRECTORY.$enc_filename))
		{
			return IMAGE_CACHE_URL_LOCATION.$enc_filename;
		}
		
		
		return $path;
	}

	public function remove_image($path)
	{
		if(!is_string($path)){
			$this->error_message = "Image source MUST be a string.";
		}
		
		$this->delete_cache_file(IMAGE_CACHE_DIRECTORY.$path);
	}
	
	private function compress_resource($path, $ext, $cachefilename)
	{
		
		if($this->gd_enabled)
		{
			getimagesize( $path );
			$image_width = $image_size[0];
			$image_height = $image_size[1];
			
			
			switch($ext)
			{
				case 'jpg':
				case 'jpeg':
					$dest = imagecreatefromjpeg ($path);
					$background = imagecolorallocate( $dest, 255, 255, 255 );
					imagefill( $dest, 0, 0, $background );				
				break;
				
				case 'png':
					$dest =  imagecreatefrompng ($path);
				break;
				case 'gif':
					$dest =  imagecreatefromgif ($path);
				break;
			
			}
			
			if($ext === 'gif' || $ext === 'png')
			{
				imagealphablending( $dest, false );
				imagesavealpha( $dest, true );
				imagealphablending( $dest, false );
				imagesavealpha( $dest, true );
			}

			//imagecopy( $dest, $dest, 0, 0, 0, 0, $image_width, $image_width );
			
			switch( $ext ) {
			case 'jpeg':
			case 'jpg':
				$created = imagejpeg( $dest, $cachefilename, IMAGE_CACHE_COMP_JPG );
				break;
			case 'png':
				$created = imagepng( $dest, $cachefilename, IMAGE_CACHE_COMP_PNG );
				break;
			case 'gif':
				$created = imagegif( $dest, $cachefilename);
				break;
			default:
				break;
			}
			
			imagedestroy( $dest );
			//imagedestroy( $cachefilename );
			
			return $cachefilename;
		}
		
		if($this->imagemagick_enabled)
		{
			 $image = new Imagick($path);
			 $catcheimage = $im->clone; 
			 $catcheimage->setImageCompression($compression_type); 
			 $catcheimage->setImageCompressionQuality(55);
			 $catcheimage->setImageCompression(55);
			 $catcheimage->writeImage($cachefilename);
			 return $cachefilename;
		}
		
		return FALSE;
	
	}
	
	
	public function get_error()
	{
		return $error_message;
	}
	
	private function decode_string($data)
	{
		if(!is_string($data))
		{
			return FALSE;
		}
	
		$result = gzuncompress($data);
		
			if($result !== FALSE) {
				return $result;
			}
			
		return $data;
	}
	
	
	protected function save_cache_file($name, $data, $expires, $gzip)
	{
		$file_data['d'] = $data;
		$file_data['c'] = time();
		$file_data['e'] = 0;
		
		if($expires > 0)
		{
			$file_data['e'] = strtotime("+".$expires." seconds");
		}
		
		$buffer = serialize($file_data);
		
		//GZIP IT?
		if($gzip)
		{
			$buffer = gzcompress($buffer, 9);
		}
		
		$result = file_put_contents($name, $buffer);
		
		if(!$result)
		{
			$this->error_message = "Saving cache file failed! Please check your paths and file permissions!";
			return FALSE;
		}
		
		return TRUE;
	}
	
	protected function get_cache_file($name)
	{	
		$result = @file_get_contents($name);
		
		if(!$result)
		{
			$this->error_message = "Reading cache file failed! Cache no longer exsists!";
			return FALSE;
		}
		
		$decoded = $this->decode_string($result);
		
		if($decoded !== FALSE)
		{
			$result = $decoded;
		}
		
		$file_contents = unserialize($result);
		
		//Delete file if expired...
		if(time() > $file_contents['e'] && $file_contents['e'] > 0 && 
		$this->delete_expired_cache_file)
		{
			$this->delete_cache_file($name);
			return FALSE;
		}
		
		return $file_contents['d'];
	}
	
	protected function delete_cache_file($path)
	{
		if(is_file($path)){
			unlink($path);
		}
	}
	
	protected function delete_all_cache_files($images = FALSE)
	{
		$basedir = CACHE_DIR;
		if(!$images){
		 $cdir = scandir(CACHE_DIR); 
		}
		else
		{
		 $cdir = scandir(IMAGE_CACHE_DIRECTORY);
		 $basedir = IMAGE_CACHE_DIRECTORY;
		}
		
		foreach ($cdir as $key => $value) 
		   { 
			  if (!in_array($value,array(".",".."))) 
			  { 
				 if (is_file($basedir . $value)) 
				 { 
					$file_parts = pathinfo($value);
					
					
					if(!isset($file_parts['extension']) || $file_parts['extension'] === "" && !$images)
					{
						unlink($basedir.$value);
						continue;
					}
					
					
					
					if(isset($file_parts['extension']) && $images)
					{
						$ext = strtolower($file_parts['extension']);
						
						switch($ext)
						{
							case 'jpg':
							case 'jpeg':
							case 'png':
							case 'gif':
								unlink($basedir.$value);
							break;
						
						}
					}					
				 } 
			  } 
		   } 
	}
}
