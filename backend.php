<?php

class testtest
{
	
 	protected $host = "localhost";
	 protected $username = "root";
	 protected $password = "";
	 protected  $db = "vt_db";
	 public $filename="saa";
	
	protected function connect()
	{
		mysql_connect($this->host,$this->username,$this->password)
		or die ("Unable to connect to database.");
		
		mysql_select_db($this->db)
		or die ("Unable to select database.");
	}
	
	public function input($fname, $fpath, $srcformat, $num, $formats)
	{
		
		$this->connect();	
		$addfn="'$fname'";
		$addfp="'$fpath'";
		$src="'$srcformat'";
		$query='call p_i_insertvideodata('.$addfn.', '.$addfp.', '.$src.');';
		$rs = mysql_query($query);
			
		$query='SELECT * FROM vt_inputdata_master WHERE file_id=1;';
		$rs = mysql_query($query);
		
		$info = mysql_fetch_array($rs);
		
		$fid=$info["file_id"];
		/*
		for($i=0;$i<$num;$i++)
		{
			$rs = mysql_query('call p_i_insertprocess('.$fid.', '.$formats[1].');')
		or die ("Unable to complete query.");
		}
	*/
		$this->runhandbrake();
	
	}
	public function runhandbrake()
	{
		
		global $filename;
		$query='SELECT * FROM vt_process_master WHERE status_id=0';
		
		$rs = mysql_query($query) or die ("Unable to complete query.");
		$info = mysql_fetch_array($rs);
		$pid = $info['process_id'];
		$fid = $info['queued_file_id'];
		$formatid = $info['op_format_id'];
		
		
		$query='SELECT * FROM vt_inputdata_master WHERE file_id='.$fid.'';
		
		$rs = mysql_query($query) or die ("Unable to complete query.");
		$info = mysql_fetch_array($rs);
		$filename = $info['file_name'];
		$fpath = $info['file_path'];
		$srcformat = $info['src_format'];
		
		$ipfile=$fpath.''.$filename.'.'.$srcformat.'';
		$opfile=$fpath.'hb_output\\\\'.$filename.'.mp4';


		$query='SELECT * FROM vt_format_lookup WHERE format_id='.$formatid.'';
		
		$rs = mysql_query($query) or die ("Unable to complete query.");
		$info = mysql_fetch_array($rs);
		$format = $info['format_query'];
		
		
		$cmd='C:\\Users\\ashish\\Desktop\\HandBrake-0.9.5-Win_CLI\\HandBrakeCLI.exe -i '.$ipfile.' -o '.$opfile.' '.$format.'';
		$opfile='C:\\Users\\ashish\\Desktop\\hb_log\\'.$filename.'.txt';
		system(sprintf("%s > %s", $cmd, $opfile));

	}
	
	public function getprogress()
	{
		/*
				$logfile='C:\\Users\\ashish\\Desktop\\hb_log\\'.$this->filename.'.txt';
		
		$line = "";
		$f = fopen($logfile, 'r');
		$cursor = -1;
		fseek($f, $cursor, SEEK_END);
		$char = fgetc($f);


		while ($char === "\n" || $char === "\r") 
		{
    		fseek($f, $cursor--, SEEK_END);
    		$char = fgetc($f);
		}


		while ($char !== false && $char !== "\n" && $char !== "\r") {

			$line = $char . $line;
    
			fseek($f, $cursor--, SEEK_END);
    
			$char = fgetc($f);

		}
	
		$lastline = explode(" ", $line);
		$progress=$lastline[5];//5*/
		return $this->filename;
	}
	
	public function geteta()
	{
		
				$logfile='C:\\Users\\ashish\\Desktop\\hb_log\\2010-Awesome-Party-Songs[www.savevid.com].txt';
		
		$line = "";
		$f = fopen($logfile, 'r');
		$cursor = -1;
		fseek($f, $cursor, SEEK_END);
		$char = fgetc($f);


		while ($char === "\n" || $char === "\r") 
		{
    		fseek($f, $cursor--, SEEK_END);
    		$char = fgetc($f);
		}


		while ($char !== false && $char !== "\n" && $char !== "\r") {

			$line = $char . $line;
    
			fseek($f, $cursor--, SEEK_END);
    
			$char = fgetc($f);

		}
		
		$lastline = explode(" ", $line);
		$newstr=substr($lastline[13],0,-2);
		$eta=$newstr;
		return $eta;
	}
}
?>