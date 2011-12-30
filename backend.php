<?php


class backend
{
	
 	protected $host = "localhost";
	 protected $username = "root";
	 protected $password = "root";
	 protected  $db = "vt_db";

	protected $logs=array();
		
	protected function connect()
	{
		mysql_connect($this->host,$this->username,$this->password)
		or die ("Unable to connect to database.");
		
		mysql_select_db($this->db)
		or die ("Unable to select database.");
	}
	
	public function runhandbrake($ind)
	{
		
		$this->connect();
		$query='SELECT * FROM vt_process_master WHERE status_id=0 LIMIT 1';
		
		$rs = mysql_query($query) or die ("Unable to complete query.");
		$info = mysql_fetch_array($rs);
		if($info==null)
		{
		$this->logs[$ind][0]='';
		$this->logs[$ind][1]=-1;
		}
		else
		{
		$pid = $info['process_id'];
		$fid = $info['queued_file_id'];
		$formatid = $info['op_format_id'];
		
				
		$query='update vt_process_master set status_id=1 WHERE process_id='.$pid;		
		$rs = mysql_query($query) or die ("Unable to complete query.");


		$query='SELECT * FROM vt_inputdata_master WHERE file_id='.$fid.'';
		
		$rs = mysql_query($query) or die ("Unable to complete query.");
		$info = mysql_fetch_array($rs);
		$filename = $info['file_name'];
		$fpath = $info['file_path'];
		$srcformat = $info['src_format'];
		
		$ipfile=$fpath.''.$filename.'.'.$srcformat.'';
		$opfile=$fpath.'hboutput/'.$filename.'.mp4';

		
		$query='insert into vt_process_info (pid, sid) values('.$pid.',1)';		
		$rs = mysql_query($query) or die ("Unable to complete query.");



//		$query='SELECT * FROM vt_format_lookup WHERE format_id='.$formatid.'';		
//		$rs = mysql_query($query) or die ("Unable to complete query.");
//		$info = mysql_fetch_array($rs);
//		$format = $info['format_query'];
		
		$this->logs[$ind][0]=$filename.'.txt';
		$this->logs[$ind][1]=$pid;


		$cmd='/usr/bin/HandBrakeCLI -i '.$ipfile.' -o '.$opfile;
		$logfile='/home/aashish/Desktop/hblog/'.$filename.'.txt &';
		system(sprintf("%s > %s", $cmd, $logfile));
		
		}//else
	}

	public function update()
	{
	
	$count = 0;
	
	while($count<2)
	{
	$count++;
	$this->runhandbrake($count-1);
	}


	$cnt = 2;

	$done=array();
	$initial=array();
	$etf=array();
	$percent=array();
	
	$done[0] = 0;
	$initial[0] = 1;
	$etf[0] = '';
	$percent[0] = '';
	
	$done[1] = 0;
	$initial[1] = 1;
	$etf[1] = '';
	$percent[1] = '';
		
	for($i=0;$i<100;$i++)
	{
	echo 'parent';
	}
	echo count($this->logs[0]);

	while($count==2&&$cnt>0)
	{
	for($li=0;$li<2;$li++)
	{
	if($this->logs[$li][0]!='')
	{
	
	$logfile='/home/aashish/Desktop/hblog/'.$this->logs[$li][0];
		
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



		if($initial[$li]==1)
		{
		if(count($lastline)<14)
		{
		continue;
		}
		else
		{
		$initial[$li] = 0;
		}
		}



		if(count($lastline)==14||count($lastline)>14)
		{
		$newstr=substr($lastline[13],0,-2);
		$eta[$li]=$newstr;
		$percent[$li]=$lastline[5];
		echo $this->logs[$li][0].' - '.$eta[$li].' - '.$percent[$li].' - '.$this->logs[$li][1];
		print("\n");
		
		$query="update vt_process_info set progress='".$percent[$li]."', eta='".$eta[$li]."' WHERE pid=".$this->logs[$li][1];
		$rs = mysql_query($query) or die ("Unable to complete query of update info 1.".mysql_query());


		continue;
		}
		else{
		$eta[$li]='000000s';
		$percent[$li]='100%';
		$done[$li]=1;
		$count--;
		$query="update vt_process_info set progress='".$percent[$li]."', eta='".$eta[$li]."', sid=2 WHERE pid=".$this->logs[$li][1];
		$rs = mysql_query($query) or die ("Unable to complete query of update info 2.".mysql_query());
		
		$query="update vt_process_master set status_id=2 WHERE process_id=".$this->logs[$li][1];
		$rs = mysql_query($query) or die ("Unable to complete query of update info 2.".mysql_query());


		}

		echo $this->logs[$li][1].' - '.$eta[$li].' - '.$percent[$li].' - '.$this->logs[$li][1];
		print("\n");

	}
	}

	if($count<2)
	{
	while($count<2)
	{
	
	for($tind=0;$tind<2;$tind++)
	{
	if($done[$tind]==1)
	{
	$count++;
	$this->runhandbrake($tind);
	$done[$tind] = 0;
	$initial[$tind] = 1;
	$etf[$tind] = '';
	$percent[$tind] = '';
	
	}
	}

	$cnt=0;
	for($tind=0;$tind<2;$tind++)
	{
	if($this->logs[0][$tind]!='')
	{
	$cnt++;
	}
	}


	}
	}
	
	sleep(3);
	}//while	
	}//function
	
}
$class = new backend();
$class->update();

?>
