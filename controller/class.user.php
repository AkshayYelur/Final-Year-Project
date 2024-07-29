<?php
require_once('config/dbconfig.php');
class USER
{	
	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function userfetch($csalt)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM users WHERE salt=:csalt");
			$stmt->execute(array(':csalt'=>$csalt));
			$userfetch=$stmt->fetch(PDO::FETCH_ASSOC);
			return $userfetch;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
			public function party($org_id)
	{
		try
		{
	      $stmt = $this->conn->prepare("SELECT p.*, s.state_name, c.city_name FROM party p,state_master s,city_master c where p.org_id=:org_id AND p.state_id=s.state_id AND  p.city_id=c.city_id");
			$stmt->execute(array(':org_id'=>$org_id));
			$party=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $party;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
			public function partysun()
	{
		try
		{
	      $stmt = $this->conn->prepare("SELECT DISTINCT pr_name, pr_id, agent
FROM party");
			$stmt->execute();
			$party=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $party;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
				
				public function partysunser($q)
	{
		try
		{
	      $stmt = $this->conn->prepare("SELECT DISTINCT p.pr_name, p.pr_id, p.agent
FROM party p, blt b
WHERE b.pr_id = p.pr_id AND p.agent LIKE :search");
			$stmt->execute(array(':search' => '%'.$q.'%'));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $result;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
					public function partysunserprt($q)
	{
		try
		{
	      $stmt = $this->conn->prepare("SELECT DISTINCT p.pr_name, p.pr_id, p.agent
FROM party p, blt b
WHERE b.pr_id = p.pr_id AND p.pr_name LIKE :search");
			$stmt->execute(array(':search' => '%'.$q.'%'));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $result;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function partysunentry($entry)
	{
		try
		{
	      $stmt = $this->conn->prepare("SELECT DISTINCT p.pr_name, p.pr_id, p.agent
FROM party p, blt b
WHERE b.pr_id = p.pr_id limit $entry ");
			$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $result;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function searchpr($q,$org_id)
	{
		try
		{
	      $stmt = $this->conn->prepare("SELECT * FROM party WHERE org_id =:org_id AND pr_name LIKE :search OR pr_cont LIKE  :search OR agent LIKE  :search");
			$stmt->execute(array(':search' => '%'.$q.'%', ':org_id' => $org_id));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $result;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function userchk($uname,$umail)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT user_id, user_name, user_cont, user_email, user_pass FROM users WHERE user_cont=:uname OR user_email=:umail ");
			$stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
			$userchk=$stmt->fetch(PDO::FETCH_ASSOC);
			return $userchk;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function register($uname,$umail,$ucont,$upass,$urole,$u_flag)
	{
		try
		{
			$new_password = password_hash($upass, PASSWORD_DEFAULT);

			$stmt = $this->conn->prepare("INSERT INTO users (user_name, user_email, user_cont, user_pass, urole, org_id, u_flag) VALUES (:uname, :umail, :ucont, :new_password, :urole, 1, :u_flag)");

			$stmt->bindparam(":uname", $uname);
			$stmt->bindparam(":umail", $umail);
			$stmt->bindparam(":ucont", $ucont);	
			$stmt->bindparam(":new_password", $new_password);	
			$stmt->bindparam(":urole", $urole);	
			$stmt->bindparam(":u_flag", $u_flag);
			$stmt->execute();	

			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
		public function userall()
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT u.*,r.r_name
			FROM users u, role r
			WHERE u.u_flag=1
			AND u.urole=r.r_id");
			$stmt->execute();
			$userfetch=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $userfetch;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function doLogin($uname,$umail,$upass)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT user_id, user_name, user_cont, user_email, user_pass, urole FROM users WHERE user_cont=:uname OR user_email=:umail ");
			$stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() == 1)
			{
				if(password_verify($upass, $userRow['user_pass']))
				{
				$myusername=$userRow['user_name'];
				$myuser_id=$userRow['user_id'];
				$urole=$userRow['urole'];
				$salt=hash("sha512", rand() . rand() . rand());
				setcookie("c_user", hash("sha512", $myusername), time() + 24 * 60 * 60, "/");
				setcookie("c_salt", $salt,  time() + 24 * 60 * 60, "/");
				//return $salt;
				return array('salt'=>$salt, 'userid'=>$myuser_id, 'urole'=>$urole);
				}
				else
				{
					return false;
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function updsalt($slt,$sluid)
	{
		try
		{
			$stmt = $this->conn->prepare("UPDATE users SET  salt =:slt WHERE user_id =:sluid");
			$stmt->bindparam(":slt", $slt);
			$stmt->bindparam(":sluid", $sluid);	
			$stmt->execute();
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	public function is_loggedin()
	{
		if(isset($_COOKIE["c_user"])  && isset($_COOKIE["c_salt"]))
		{
			return true;
		}
	}
	
	public function redirect($url)
	{
		header("Location: $url");
	}
	
	public function doLogout()
	{
		$cuser=$_COOKIE["c_user"]; 
		$csalt=$_COOKIE["c_salt"];
		setcookie("c_user", $cuser, time() - 24 * 60 * 60, "/");
		setcookie("c_salt", $csalt,  time() - 24 * 60 * 60, "/");
		return true;
	}
	
			public function shbltgsr($org_id,$tp)
	{
		try
		{
	      $stmt = $this->conn->prepare("SELECT s.state_name, b.*,p.pr_name, p.pr_cont FROM blt b, state_master s,party p WHERE b.org_id=:org_id AND b.state_id=s.state_id AND p.pr_id=b.pr_id AND b.test_flag=5 AND b.blt_type=:tp ORDER BY b.blt_id DESC");
			$stmt->execute(array(':org_id'=>$org_id, ':tp'=>$tp));
			$shblt=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $shblt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
		public function shbltdlt($org_id,$tp)
	{
		try
		{
	      $stmt = $this->conn->prepare("SELECT s.state_name, b.*,p.pr_name, p.pr_cont FROM blt b, state_master s,party p WHERE b.org_id=:org_id AND b.state_id=s.state_id AND p.pr_id=b.pr_id AND b.test_flag=11 AND b.blt_type=:tp ORDER BY b.blt_id DESC");
			$stmt->execute(array(':org_id'=>$org_id, ':tp'=>$tp));
			$shbltdlt=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $shbltdlt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
			public function shbltcmp($org_id,$tp)
	{
		try
		{
	      $stmt = $this->conn->prepare("SELECT s.state_name, b.*,p.pr_name FROM blt b, state_master s,party p WHERE b.org_id=:org_id AND b.state_id=s.state_id AND p.pr_id=b.pr_id AND blt_type=:tp AND b.test_flag=9 order by b.blt_id DESC");
			$stmt->execute(array(':org_id'=>$org_id, ':tp'=>$tp));
			$shbltcmp=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $shbltcmp;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
		public function shbltcouthgsr($org_id,$blt)
	{
		try
		{
	    $stmt = $this->conn->prepare("SELECT p.pr_name, p.pr_cont, b.*, st.state_name, p.gst_no, p.pr_addr FROM blt b, party p,state_master st WHERE b.org_id=:org_id AND b.pr_id=p.pr_id AND st.state_id = b.state_id  AND b.blt_id=:blt ");
			$stmt->execute(array(':org_id'=>$org_id, ':blt'=>$blt));
			$shbltouth=$stmt->fetch(PDO::FETCH_ASSOC);
			return $shbltouth;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function shbltcouthgsrnew($org_id,$pr_id)
	{
		try
		{
	    $stmt = $this->conn->prepare("SELECT p.pr_name, p.gst_no, p.pr_addr, p.pr_cont, p.agent, b.*, st.state_name FROM blt b, party p,state_master st WHERE b.org_id=:org_id AND b.pr_id=p.pr_id AND st.state_id = b.state_id  AND b.pr_id=:pr_id ");
			$stmt->execute(array(':org_id'=>$org_id, ':pr_id'=>$pr_id));
			$shbltouth=$stmt->fetch(PDO::FETCH_ASSOC);
			return $shbltouth;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function validcont($cont)
	{
		try
		{
	      $stmt = $this->conn->prepare("SELECT * FROM users where user_cont=:u_cont");
			$stmt->execute(array(':u_cont'=>$cont));
			$validcont=$stmt->fetch(PDO::FETCH_ASSOC);
			$count=$stmt->rowCount();
			return $count;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function fetchorderhisgsr($blt_id,$pr_id)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT o . *, itm.*, h.ch_id, ch.ch_code, h.resv_date, w.wb_gross, w.wb_tare, w.gunit
FROM orders o, chm_history h, chembers ch, co_item itm, wb w
WHERE o.blt_id =:blt_id
AND o.ord_id = h.ord_id
AND o.pr_id =:pr_id
AND o.blt_id = w.blt_id
AND w.wb_in_out = 1
AND o.itm_id = itm.itm_id
AND h.ch_id=ch.ch_id");
			$stmt->execute(array(':blt_id'=>$blt_id, ':pr_id'=>$pr_id));
			$fetchorder=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $fetchorder;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
		public function fetchchmhis($ch_id)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT o. * , itm . * , h.ch_id,  h.resv_date, w.wb_gross, w.wb_tare, w.gunit, h.blt_id, o.bal
FROM orders o, chm_history h, chembers ch, co_item itm, wb w
WHERE o.blt_id = h.blt_id
AND o.ord_id = h.ord_id
AND o.pr_id = h.pr_id
AND o.blt_id = w.blt_id
AND w.wb_in_out =1
AND o.itm_id = itm.itm_id
AND h.ch_id = ch.ch_id
AND h.ch_id =:ch_id");
			$stmt->execute(array(':ch_id'=>$ch_id));
			$fetchorder=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $fetchorder;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function fetchchtp()
	{
		try 
		{
	        $stmt = $this->conn->prepare("SELECT * FROM ch_type");
			$stmt->execute();
			$fetchorder=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $fetchorder;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	
	
		public function adddata($d_title, $d_desc, $d_img, $user_id, $d_time)
	{
		try 
		{
	        $stmt = $this->conn->prepare("INSERT INTO dataset (d_title, d_desc, d_img, user_id, d_time) VALUES (:d_title, :d_desc, :d_img, :user_id, :d_time)");
			$stmt->bindparam(":d_title", $d_title);
			$stmt->bindparam(":d_desc", $d_desc);
			$stmt->bindparam(":d_img", $d_img);
			$stmt->bindparam(":user_id", $user_id);
			$stmt->bindparam(":d_time", $d_time);
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function save_cht($cht_name, $cht_desc)
	{
		try 
		{
	        $stmt = $this->conn->prepare("INSERT INTO ch_type (cht_name, cht_desc) VALUES (:cht_name, :cht_desc)");
			$stmt->bindparam(":cht_name", $cht_name);
			$stmt->bindparam(":cht_desc", $cht_desc);
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
			public function fetchchcnt($ch_flag)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT * FROM chembers WHERE ch_flag=:ch_flag");
			$stmt->execute(array(':ch_flag'=>$ch_flag));
			$fetchorder=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $fetchorder;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
		public function fetchdataset($user_id)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM dataset WHERE user_id=:user_id");
			$stmt->execute(array(':user_id'=>$user_id));
			$fetchdataset=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $fetchdataset;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function fetchdatade($imdata)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT d.*,u.user_name,u.user_cont FROM dataset d, users u WHERE d.user_id=u.user_id AND d.d_img=:imdata");
			$stmt->execute(array(':imdata'=>$imdata));
			$fetchdataset=$stmt->fetch(PDO::FETCH_ASSOC);
			return $fetchdataset;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
		public function city($state_id)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM city_master WHERE state_id = :state_id order by city_name asc");
			$stmt->execute(array(':state_id'=>$state_id));
			$state=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $state;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
		public function cs($city_id,$state_id)
	{
		try
		{
	      $stmt = $this->conn->prepare("SELECT s.state_name, c.city_name FROM state_master s,city_master c where  c.state_id=:state_id AND  c.city_id=:city_id");
			$stmt->execute(array(':state_id' => $state_id, ':city_id' => $city_id));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function fetchchmtype()
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT * FROM ch_type");
			$stmt->execute();
			$fetchorder=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $fetchorder;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
		public function fetchch($cht_id)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT c.*,ct.cht_name FROM chembers c, ch_type ct WHERE c.ch_type=ct.cht_id AND c.ch_type=:cht_id");
			$stmt->execute(array(':cht_id'=>$cht_id));
			$fetchorder=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $fetchorder;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
		public function save_itm($itm_name, $itm_desc, $m_hsn, $mkt_rate, $rate_month, $itm_gst, $itm_flag, $org_id, $rating, $tp)
	{
		try 
		{
	        $stmt = $this->conn->prepare("INSERT INTO co_item (itm_name, itm_desc, m_hsn, mkt_rate, rate_month, itm_gst, itm_flag, org_id, rating, itm_type) VALUES (:itm_name, :itm_desc, :m_hsn, :mkt_rate, :rate_month, :itm_gst, :itm_flag, :org_id, :rating, :tp)");
			$stmt->bindparam(":itm_name", $itm_name);
			$stmt->bindparam(":itm_desc", $itm_desc);
			$stmt->bindparam(":m_hsn", $m_hsn);
			$stmt->bindparam(":mkt_rate", $mkt_rate);
			$stmt->bindparam(":rate_month", $rate_month);
			$stmt->bindparam(":itm_gst", $itm_gst);
			$stmt->bindparam(":itm_flag", $itm_flag);
			$stmt->bindparam(":org_id", $org_id);
			$stmt->bindparam(":rating", $rating);
			$stmt->bindparam(":tp", $tp);
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
			public function save_hitm($itm_name, $itm_desc, $m_hsn, $mkt_rate, $rate_month, $itm_gst, $itm_flag, $org_id)
	{
		try 
		{
	        $stmt = $this->conn->prepare("INSERT INTO coh_item (hitm_name, hitm_desc, hm_hsn, hmkt_rate, hrate_month, hitm_gst, hitm_flag, org_id) VALUES (:itm_name, :itm_desc, :m_hsn, :mkt_rate, :rate_month, :itm_gst, :itm_flag, :org_id)");
			$stmt->bindparam(":itm_name", $itm_name);
			$stmt->bindparam(":itm_desc", $itm_desc);
			$stmt->bindparam(":m_hsn", $m_hsn);
			$stmt->bindparam(":mkt_rate", $mkt_rate);
			$stmt->bindparam(":rate_month", $rate_month);
			$stmt->bindparam(":itm_gst", $itm_gst);
			$stmt->bindparam(":itm_flag", $itm_flag);
			$stmt->bindparam(":org_id", $org_id);
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function fetchitm($tp)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT * FROM co_item WHERE itm_type=:tp ORDER BY itm_id DESC");
			$stmt->execute(array(':tp'=>$tp));
			$fetchitm=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $fetchitm;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
		public function prchk($pr_name)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT pr_cont FROM party WHERE pr_name LIKE :pr_name");
			$stmt->execute(array(':pr_name'=>$pr_name));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() == 1){
					return false;
			}else{
					return true;
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function chkbltcsv($grnno,$tp)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM blt WHERE  grnno =:grnno AND blt_type=:tp");
			$stmt->execute(array(':grnno'=>$grnno, ':tp'=>$tp));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() >= 1){
					return false;
			}else{
					return true;
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
		public function prnm($pr_name)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT pr_id FROM party WHERE pr_name LIKE :pr_name");
			$stmt->execute(array(':pr_name' => '%'.$pr_name.'%'));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			return $userRow;
	}
	catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function ckhprnm($pr_name)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT count(pr_id) as cnt, pr_id FROM party WHERE pr_name = :pr_name");
			$stmt->execute(array(':pr_name' => $pr_name));
			$cnt=$stmt->fetch(PDO::FETCH_ASSOC);
			return $cnt;
	}
	catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
		public function prst($state_name)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT state_id FROM state_master WHERE state_name LIKE :state_name");
			$stmt->execute(array(':state_name'=>$state_name));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			return $userRow;
	}
	catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function itmchk($tp, $itm_name)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT itm_id FROM co_item WHERE itm_type=:tp AND itm_name LIKE :itm_name");
			$stmt->execute(array(':tp'=>$tp, ':itm_name'=>$itm_name));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() == 1){
					return false;
			}else{
					return true;
			}
		}
	catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	
	public function chmchk($ch_code)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT ch_code FROM chembers WHERE ch_code LIKE :ch_code");
			$stmt->execute(array(':ch_code'=>$ch_code));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() == 1){
					return false;
			}else{
					return true;
			}
	}
	catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function uppr($pr_email, $pr_cont, $pr_alt, $gst_no,  $agent, $pr_name)
	{
		try 
		{  
	        $stmt = $this->conn->prepare("UPDATE party SET pr_email=:pr_email, pr_cont=:pr_cont, pr_alt=:pr_alt, gst_no=:gst_no, agent=:agent WHERE pr_name LIKE  :pr_name");
			
			
			$stmt->bindparam(":pr_email", $pr_email);
			$stmt->bindparam(":pr_cont", $pr_cont);
			$stmt->bindparam(":pr_alt", $pr_alt);
			$stmt->bindparam(":gst_no", $gst_no);
			$stmt->bindparam(":agent", $agent);
			$stmt->bindparam(":pr_name", $pr_name);
			
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
			public function upitm($itm_desc, $m_hsn, $mkt_rate, $rate_month, $itm_gst,$rating, $itm_name, $tp)
	{
		try 
		{  
	        $stmt = $this->conn->prepare("UPDATE co_item SET   itm_desc=:itm_desc, m_hsn=:m_hsn, mkt_rate=:mkt_rate, rate_month=:rate_month, itm_gst=:itm_gst, rating=:rating WHERE itm_name LIKE :itm_name AND itm_type=:tp");
			
			$stmt->bindparam(":itm_desc", $itm_desc);
			$stmt->bindparam(":m_hsn", $m_hsn);
			$stmt->bindparam(":mkt_rate", $mkt_rate);
			$stmt->bindparam(":rate_month", $rate_month);
			$stmt->bindparam(":itm_gst", $itm_gst);
			$stmt->bindparam(":rating", $rating);
			$stmt->bindparam(":itm_name", $itm_name);
			$stmt->bindparam(":tp", $tp);
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
		public function addpr($pr_name, $pr_email, $state_id, $city_id, $pr_cont, $pr_alt, $pr_flag, $org_id, $user_id, $pr_pass, $gst_no, $agent)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO party (pr_name, pr_email, state_id, city_id, pr_cont, pr_alt, pr_flag, org_id, user_id, pr_pass, gst_no, agent) VALUES (:pr_name, :pr_email, :state_id, :city_id, :pr_cont, :pr_alt, :pr_flag, :org_id, :user_id, :pr_pass, :gst_no, :agent)");
												  
			$stmt->bindparam(":pr_name", $pr_name);
			$stmt->bindparam(":pr_email", $pr_email);
			$stmt->bindparam(":state_id", $state_id);	
			$stmt->bindparam(":city_id", $city_id);
			$stmt->bindparam(":pr_cont", $pr_cont);
			$stmt->bindparam(":pr_alt", $pr_alt);
			$stmt->bindparam(":pr_flag", $pr_flag);
			$stmt->bindparam(":org_id", $org_id);
			$stmt->bindparam(":user_id", $user_id);
			$stmt->bindparam(":pr_pass", $pr_pass);
			$stmt->bindparam(":gst_no", $gst_no);
			$stmt->bindparam(":agent", $agent);
			$stmt->execute();
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
			public function cmpgrnrecipt($blt_id,$pr_id)
	{
		try 
		{
	        $stmt = $this->conn->prepare("SELECT h. * , o.parti, o.rdesc, o.unit,o.bal, b.arvdate, w. * , p.pr_name, p.pr_cont, b.test_flag, p.pr_id, s.state_name, b.outh, b.type, c.ch_code, o.size, o.sunit, i.m_hsn, i.rate_month, i.itm_gst,i.mkt_rate
FROM chm_history h, orders o, blt b, wb w, party p, state_master s, co_item i, chembers c
WHERE h.ord_id = o.ord_id
AND h.blt_id = b.blt_id
AND h.blt_id =:blt_id
AND h.ch_id = c.ch_id
AND h.blt_id = w.blt_id
AND b.pr_id = p.pr_id
AND o.itm_id = i.itm_id
AND b.pr_id =:pr_id
AND b.state_id = s.state_id
AND w.wb_gross !=''
AND w.wb_in_out =1");
			$stmt->execute(array(':blt_id'=>$blt_id, ':pr_id'=>$pr_id));
			$outhisdetail=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $outhisdetail;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function cmpgrnreciptnew($pr_id)
	{
		try 
		{
	        $stmt = $this->conn->prepare("SELECT h. * , o.parti, o.rdesc, o.unit,o.bal, b.arvdate, w. * , p.pr_name, p.pr_cont, b.test_flag, p.pr_id, s.state_name, b.outh, b.type, c.ch_code, o.size, o.sunit, i.m_hsn, o.orate_month, o.oitm_gst,o.omkt_rate,o.bal_size
FROM chm_history h, orders o, blt b, wb w, party p, state_master s, co_item i, chembers c
WHERE h.ord_id = o.ord_id
AND h.blt_id = b.blt_id
AND h.ch_id = c.ch_id
AND h.blt_id = w.blt_id
AND b.pr_id = p.pr_id
AND o.itm_id = i.itm_id
AND b.pr_id =:pr_id
AND b.state_id = s.state_id
AND w.wb_gross !=''
AND w.wb_in_out =1");
			$stmt->execute(array(':pr_id'=>$pr_id));
			$cmpgrnreciptnew=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $cmpgrnreciptnew;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function outnet($blt_id)
	{
		try{
	        $stmt = $this->conn->prepare("SELECT (wb_gross-wb_tare) AS netwt
FROM wb
WHERE wb_in_out =2 
AND wb_gross !=''
AND blt_id=:blt_id");
			$stmt->execute(array(':blt_id'=>$blt_id));
			$outhisdetail=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $outhisdetail;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function auditpay($blt_id,$pr_id)
	{
		try 
		{
	        $stmt = $this->conn->prepare("SELECT * FROM audit WHERE blt_id=:blt_id AND pr_id=:pr_id");
			$stmt->execute(array(':blt_id'=>$blt_id, ':pr_id'=>$pr_id));
			$outhisdetail=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $outhisdetail;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function save_bltcsv($pr_id, $vno, $org_id, $arvdate, $outh, $state_id, $test_flag, $qty, $unit, $nob, $bunit, $in_time, $out_time, $spa, $iqqc, $sall, $wcrf, $pc, $grnno, $tp)
	{
		try 
		{
	        $stmt = $this->conn->prepare("INSERT INTO blt (pr_id, vno, org_id, arvdate, outh, state_id, test_flag, qty, unit, nob, bunit,in_time, out_time, spa, iqqc, sall, wcrf, pc, grnno, blt_type) VALUES (:pr_id, :vno, :org_id, :arvdate, :outh, :state_id, :test_flag, :qty, :unit, :nob, :bunit, :in_time, :out_time, :spa, :iqqc, :sall, :wcrf, :pc, :grnno, :tp)");
			$stmt->bindparam(":pr_id", $pr_id);
			$stmt->bindparam(":vno", $vno);
			$stmt->bindparam(":org_id", $org_id);
			$stmt->bindparam(":arvdate", $arvdate);
			$stmt->bindparam(":outh", $outh);
			$stmt->bindparam(":state_id", $state_id);
			$stmt->bindparam(":test_flag", $test_flag);
			$stmt->bindparam(":qty", $qty);
			$stmt->bindparam(":unit", $unit);
			$stmt->bindparam(":nob", $nob);
			$stmt->bindparam(":bunit", $bunit);
			$stmt->bindparam(":in_time", $in_time);
			$stmt->bindparam(":out_time", $out_time);
			$stmt->bindparam(":spa", $spa);
			$stmt->bindparam(":iqqc", $iqqc);
			$stmt->bindparam(":sall", $sall);
			$stmt->bindparam(":wcrf", $wcrf);
			$stmt->bindparam(":pc", $pc);
			$stmt->bindparam(":grnno", $grnno);
			$stmt->bindparam(":tp", $tp);
			$stmt->execute();
			$last = $this->conn->lastInsertId();
			return $last;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
			public function uptare($blt_id, $wb_vno, $wb_in_out, $wb_gross, $gdate, $gunit, $wb_tare, $tdate, $tunit, $wb_flag)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO wb (blt_id, wb_vno, wb_in_out, wb_gross, gdate, gunit, wb_tare, tdate, tunit, wb_flag) VALUES (:blt_id, :wb_vno, :wb_in_out, :wb_gross, :gdate, :gunit, :wb_tare, :tdate, :tunit, :wb_flag)");
			$stmt->bindparam(":blt_id", $blt_id);
			$stmt->bindparam(":wb_vno", $wb_vno);
			$stmt->bindparam(":wb_in_out", $wb_in_out);
			$stmt->bindparam(":wb_gross", $wb_gross);
			$stmt->bindparam(":gdate", $gdate);
			$stmt->bindparam(":gunit", $gunit);
			$stmt->bindparam(":wb_tare", $wb_tare);
			$stmt->bindparam(":tdate", $tdate);
			$stmt->bindparam(":tunit", $tunit);
			$stmt->bindparam(":wb_flag", $wb_flag);
			$stmt->execute();
			$last = $this->conn->lastInsertId();
			return $last;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
			public function addorder($blt_id, $ord_flag, $user_id, $parti, $itm_id, $type, $pr_id, $qty, $size, $bal, $rdesc, $unit, $sunit, $auth, $grnno, $mkt_rate, $rate_month, $itm_gst, $bal_size,$odate)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO orders (blt_id, ord_flag, user_id, parti, itm_id, type, pr_id, qty, size, bal, rdesc, unit, sunit, auth, grnno, omkt_rate, orate_month, oitm_gst, bal_size, odate) VALUES (:blt_id, :ord_flag, :user_id, :parti, :itm_id, :type, :pr_id, :qty, :size, :bal, :rdesc, :unit, :sunit, :auth, :grnno, :mkt_rate, :rate_month, :itm_gst, :bal_size, :odate)");
			$stmt->bindparam(":blt_id", $blt_id);
			$stmt->bindparam(":ord_flag", $ord_flag);
			$stmt->bindparam(":user_id", $user_id);
			$stmt->bindparam(":parti", $parti);
			$stmt->bindparam(":itm_id", $itm_id);
			$stmt->bindparam(":type", $type);
			$stmt->bindparam(":pr_id", $pr_id);
			$stmt->bindparam(":qty", $qty);	
			$stmt->bindparam(":size", $size);	
			$stmt->bindparam(":bal", $bal);
			$stmt->bindparam(":rdesc", $rdesc);	
			$stmt->bindparam(":unit", $unit);
			$stmt->bindparam(":sunit", $sunit);
			$stmt->bindparam(":auth", $auth);
			$stmt->bindparam(":grnno", $grnno);
			$stmt->bindparam(":mkt_rate", $mkt_rate);
			$stmt->bindparam(":rate_month", $rate_month);
			$stmt->bindparam(":itm_gst", $itm_gst);
			$stmt->bindparam(":bal_size", $bal_size);
			$stmt->bindparam(":odate", $odate);
			$stmt->execute();
			$last = $this->conn->lastInsertId();
			return $last;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
		public function getbltcsv($grnno,$tp)
	{
		try
		{
		$stmt = $this->conn->prepare("SELECT * FROM  blt WHERE  grnno =:grnno AND blt_type=:tp");
			$stmt->execute(array(':grnno'=>$grnno,':tp'=>$tp));
			$getbltcsv=$stmt->fetch(PDO::FETCH_ASSOC);
			return $getbltcsv;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function itmget($itm_name)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM co_item WHERE itm_name LIKE :itm_name AND itm_type=1");
			$stmt->execute(array(':itm_name'=>$itm_name));
			$itmget=$stmt->fetch(PDO::FETCH_ASSOC);
			return $itmget;
	}
	catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function itmgetnew($itm_name,$tp)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM co_item WHERE itm_name LIKE :itm_name AND itm_type=:tp");
			$stmt->execute(array(':itm_name'=>$itm_name, ':tp'=>$tp));
			$itmget=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() != 1)
			{
			 return $count=0;
			}else{
				return $itmget;
			}
	}
	catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	
	public function chmhis($ch_id, $blt_id, $pr_id, $ord_id, $resv_date, $in_time, $ch_his_flag)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO chm_history (ch_id, blt_id, pr_id, ord_id, resv_date, in_time, ch_his_flag) VALUES (:ch_id, :blt_id, :pr_id, :ord_id, :resv_date, :in_time, :ch_his_flag)");
			$stmt->bindparam(":ch_id", $ch_id);
			$stmt->bindparam(":blt_id", $blt_id);
			$stmt->bindparam(":pr_id", $pr_id);
			$stmt->bindparam(":ord_id", $ord_id);
			$stmt->bindparam(":resv_date", $resv_date);
			$stmt->bindparam(":in_time", $in_time);
			$stmt->bindparam(":ch_his_flag", $ch_his_flag);
			$stmt->execute();
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	public function chupdt($chflag,$chid)
	{
		try
		{
			$stmt = $this->conn->prepare("UPDATE chembers SET ch_flag =:chflag WHERE ch_id =:chid");
			$stmt->bindparam(":chflag", $chflag);
			$stmt->bindparam(":chid", $chid);
			$stmt->execute();
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	public function chmget($ch_code)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM chembers WHERE ch_code LIKE :ch_code");
			$stmt->execute(array(':ch_code'=>$ch_code));
			$chmget=$stmt->fetch(PDO::FETCH_ASSOC);
				return $chmget;
		}
	catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function chmgetnew($ch_code)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM chembers WHERE ch_code LIKE :ch_code");
			$stmt->execute(array(':ch_code'=>$ch_code));
			$chmget=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() != 1)
			{
			 return $count=0;
			}else{
				return $chmget;
			}
		}
	catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
		public function chkitmblt($blt_id,$parti)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM orders WHERE blt_id=:blt_id AND parti LIKE :parti");
			$stmt->execute(array(':blt_id'=>$blt_id, ':parti'=>$parti));
			$chkitmblt=$stmt->fetch(PDO::FETCH_ASSOC);
			return $chkitmblt;
		}
	catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function savsent($blt_id, $wb_id, $qty, $out_size, $se_date, $ord_id, $ch_code, $brd)
	{
		try 
		{
	        $stmt = $this->conn->prepare("INSERT INTO sent_his (blt_id, wb_id, qty, out_size, se_date, ord_id, ch_code, brd) VALUES (:blt_id, :wb_id, :qty, :out_size, :se_date, :ord_id, :ch_code, :brd)");
			$stmt->bindparam(":blt_id", $blt_id);
			$stmt->bindparam(":wb_id", $wb_id);
			$stmt->bindparam(":qty", $qty);
			$stmt->bindparam(":out_size", $out_size);
			$stmt->bindparam(":se_date", $se_date);
			$stmt->bindparam(":ord_id", $ord_id);
			$stmt->bindparam(":ch_code", $ch_code);
			$stmt->bindparam(":brd", $brd);
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
		public function ordupdtbal($bal,$bal_size,$ord_id)
	{
		try
		{
			$stmt = $this->conn->prepare("UPDATE orders SET bal=:bal, bal_size=:bal_size  WHERE ord_id =:ord_id");
			$stmt->bindparam(":bal", $bal);
			$stmt->bindparam(":bal_size", $bal_size);
			$stmt->bindparam(":ord_id", $ord_id);
			$stmt->execute();
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
		
	public function fetchouthis()
	{
		try 
		{
	        $stmt = $this->conn->prepare("SELECT h. * , o.parti, o.unit, b.arvdate, w . *, p.pr_name, p.pr_cont,b.test_flag
FROM sent_his h, orders o, blt b, wb w, party p
WHERE  h.ord_id = o.ord_id
AND h.blt_id = b.blt_id
AND h.wb_id = w.wb_id
AND b.pr_id = p.pr_id
AND w.wb_gross!=''");
			$stmt->execute();
			$fetchorder=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $fetchorder;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function outhisdetail($se_id)
	{
		try 
		{
	        $stmt = $this->conn->prepare("SELECT h. * , o.parti, o.rdesc, o.unit, b.arvdate, w . *, p.pr_name, p.pr_cont,b.test_flag, p.pr_id,s.state_name, b.outh, b.type, h.ch_code, o.size,o.sunit,o.orate_month, o.oitm_gst,o.omkt_rate,o.bal_size
FROM sent_his h, orders o, blt b, wb w, party p, state_master s
WHERE  h.ord_id = o.ord_id
AND h.blt_id = b.blt_id
AND h.wb_id = w.wb_id
AND b.pr_id = p.pr_id
AND b.state_id = s.state_id
AND h.se_id = :se_id
AND w.wb_gross!=''");
			$stmt->execute(array(':se_id'=>$se_id));
			$outhisdetail=$stmt->fetch(PDO::FETCH_ASSOC);
			return $outhisdetail;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
			public function shbltproducts()
	{
		try
		{
	      $stmt = $this->conn->prepare("SELECT o. * , h.ch_id, ch.ch_code, h.resv_date, b.blt_id,b.arvdate
FROM orders o, chm_history h, chembers ch, blt b
WHERE o.blt_id = b.blt_id
AND o.ord_id = h.ord_id
AND h.ch_id = ch.ch_id
AND b.test_flag BETWEEN 5 AND 7");
			$stmt->execute();
			$shblt=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $shblt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
		public function dltdatablt()
	{
		try 
		{
	        $stmt = $this->conn->prepare("TRUNCATE TABLE blt");
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
		public function dltdatawb()
	{
		try 
		{
	        $stmt = $this->conn->prepare("TRUNCATE TABLE wb");
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
		public function dltdatachm_history()
	{
		try 
		{
	        $stmt = $this->conn->prepare("TRUNCATE TABLE chm_history");
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
		public function dltdataorders()
	{
		try 
		{
	        $stmt = $this->conn->prepare("TRUNCATE TABLE orders");
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
		public function dltdatasent_his()
	{
		try 
		{
	        $stmt = $this->conn->prepare("TRUNCATE TABLE sent_his");
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
		public function outhisdetailout($pr_id)
	{
		try 
		{
	        $stmt = $this->conn->prepare("SELECT h. * , o.parti, o.rdesc, o.unit, b.arvdate, w . *, p.pr_name, p.pr_cont,b.test_flag, p.pr_id,s.state_name, b.outh, b.type, h.ch_code, o.size,o.sunit,o.orate_month, o.oitm_gst,o.omkt_rate,o.bal_size
FROM sent_his h, orders o, blt b, wb w, party p, state_master s
WHERE  h.ord_id = o.ord_id
AND h.blt_id = b.blt_id
AND h.wb_id = w.wb_id
AND b.pr_id = p.pr_id
AND b.state_id = s.state_id
AND b.pr_id = :pr_id
AND w.wb_gross!=''");
			$stmt->execute(array(':pr_id'=>$pr_id));
			$outhisdetail=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $outhisdetail;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
			public function getmon($pr_id)
	{
		try 
		{
	        $stmt = $this->conn->prepare("SELECT odate as arvdate
FROM orders 
WHERE pr_id =:pr_id
ORDER BY odate ASC limit 1");
			$stmt->execute(array(':pr_id'=>$pr_id));
			$getmon=$stmt->fetch(PDO::FETCH_ASSOC);
			return $getmon;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
		public function cmpgrnreciptmnt($pr_id,$sedate)
	{
		try 
		{
	        $stmt = $this->conn->prepare("SELECT o . * , b.blt_id, b.arvdate, i.itm_name
FROM orders o, blt b, co_item i
WHERE o.blt_id = b.blt_id
AND o.itm_id = i.itm_id
AND b.blt_type= 1
AND b.test_flag IN (5,9)
AND b.pr_id=:pr_id
AND o.odate<=:sedate");
			$stmt->execute(array(':pr_id'=>$pr_id,':sedate'=>$sedate));
			$cmpgrnreciptnew=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $cmpgrnreciptnew;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function outmonthly($pr_id,$stdate,$edate)
	{
		try 
		{
	        $stmt = $this->conn->prepare("SELECT sh.* , o.rdesc, o.type, b.blt_id, i.itm_name, o.parti, o.omkt_rate, o.orate_month, o.oitm_gst,o.odate 
FROM orders o, blt b, co_item i,sent_his sh
WHERE o.blt_id = b.blt_id
AND o.itm_id = i.itm_id
AND o.ord_id=sh.ord_id
AND b.blt_id=sh.blt_id
AND b.blt_type=1
AND b.pr_id=:pr_id
AND b.test_flag IN (5,9)
AND sh.se_date >=:stdate AND sh.se_date <=:edate");
			$stmt->execute(array(':pr_id'=>$pr_id,':stdate'=>$stdate,':edate'=>$edate));
			$outhisdetail=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $outhisdetail;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function audbill($pr_id)
	{
		try 
		{
	        $stmt = $this->conn->prepare("SELECT a.*,p.pr_name FROM audit a,party p WHERE a.pr_id=p.pr_id AND a.pr_id=:pr_id AND a.aud_flag=1");
			$stmt->execute(array(':pr_id'=>$pr_id));
			$audbill=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $audbill;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function audbilldate($pr_id,$sdate,$edate)
	{
		try 
		{
	        $stmt = $this->conn->prepare("SELECT a.*,p.pr_name FROM audit a,party p WHERE a.pr_id=p.pr_id AND a.pr_id=:pr_id 
			AND a.aud_flag=1
			AND a.aud_date >=:sdate AND a.aud_date <=:edate");
			$stmt->execute(array(':pr_id'=>$pr_id,':sdate'=>$sdate,':edate'=>$edate));
			$audbill=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $audbill;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function audbillsun($pr_id)
	{
		try 
		{
	        $stmt = $this->conn->prepare("SELECT sum(a.aud_credit) as aud_credit,p.pr_name FROM audit a,party p WHERE a.pr_id=p.pr_id AND a.pr_id=:pr_id AND a.aud_flag=1");
			$stmt->execute(array(':pr_id'=>$pr_id));
			$audbill=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $audbill;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	public function adpay($aud_head, $aud_credit, $aud_flag, $aud_date, $pr_id)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO audit (aud_head, aud_credit, aud_flag, aud_date, pr_id) VALUES (:aud_head, :aud_credit, :aud_flag, :aud_date, :pr_id)");
			$stmt->bindparam(":aud_head", $aud_head);
			$stmt->bindparam(":aud_credit", $aud_credit);
			$stmt->bindparam(":aud_flag", $aud_flag);
			$stmt->bindparam(":aud_date", $aud_date);
			$stmt->bindparam(":pr_id", $pr_id);
			$stmt->execute();
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	public function getitm($itm_id)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT * FROM co_item WHERE itm_id=:itm_id");
			$stmt->execute(array(':itm_id'=>$itm_id));
			$getitm=$stmt->fetch(PDO::FETCH_ASSOC);
			return $getitm;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
		
		public function getinstock($tp)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT o . * , b.blt_id, b.arvdate, i.itm_name, p.pr_name, ch.ch_code
FROM orders o, blt b, co_item i, party p, chembers ch, chm_history h
WHERE o.blt_id = b.blt_id
AND o.pr_id=p.pr_id
AND o.itm_id = i.itm_id
AND o.ord_id = h.ord_id
AND h.ch_id=ch.ch_id
AND b.test_flag IN (5,9)
AND o.type=:tp
AND b.blt_type=:tp");
			$stmt->execute(array(':tp'=>$tp));
			$getinstock=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $getinstock;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
public function getoutstock($tp)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT sh.* , o.rdesc, o.type, b.blt_id, i.itm_name, p.pr_name
FROM orders o, blt b, co_item i, sent_his sh, party p
WHERE o.blt_id = b.blt_id
AND o.pr_id=p.pr_id
AND o.itm_id = i.itm_id
AND o.ord_id=sh.ord_id
AND b.blt_id=sh.blt_id
AND b.blt_type=:tp
AND o.type=:tp
AND b.test_flag IN (5,9)");
			$stmt->execute(array(':tp'=>$tp));
			$getoutstock=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $getoutstock;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
		public function getinoutstock($tp)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT o.* , b.arvdate, b.blt_id, i.itm_name, p.pr_name
FROM orders o, blt b, co_item i,party p
WHERE o.blt_id = b.blt_id
AND o.pr_id=p.pr_id
AND o.itm_id = i.itm_id
AND b.test_flag IN (5,9)
AND b.blt_type=:tp
AND o.type=:tp");
			$stmt->execute(array(':tp'=>$tp));
			$getinstock=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $getinstock;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function getoutstk($blt_id,$itm_id)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT sum(sh.qty) as out_qty,sum(sh.out_size) as out_size, sh.ch_code
FROM sent_his sh, orders o
WHERE sh.blt_id = :blt_id
AND o.ord_id = sh.ord_id
AND o.itm_id=:itm_id
");
		$stmt->execute(array(':blt_id'=>$blt_id,':itm_id'=>$itm_id));
			$getinstock=$stmt->fetch(PDO::FETCH_ASSOC);
			return $getinstock;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function getoutstkmonth($blt_id,$itm_id,$pr_id,$invdate)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT sum(sh.qty) as out_qty,sum(sh.out_size) as out_size
FROM sent_his sh, orders o
WHERE sh.blt_id = :blt_id
AND o.ord_id = sh.ord_id
AND o.itm_id=:itm_id
AND o.pr_id=:pr_id
AND o.type= 1
AND sh.se_date<=:invdate
");
		$stmt->execute(array(':blt_id'=>$blt_id,':itm_id'=>$itm_id,':pr_id'=>$pr_id,':invdate'=>$invdate));
			$getinstock=$stmt->fetch(PDO::FETCH_ASSOC);
			return $getinstock;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
		public function getpr($pr_id)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT * FROM party WHERE pr_id=:pr_id");
			$stmt->execute(array(':pr_id'=>$pr_id));
			$getitm=$stmt->fetch(PDO::FETCH_ASSOC);
			return $getitm;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	
		public function edtpr($pr_name, $pr_email, $pr_cont, $pr_alt, $gst_no, $agent, $pr_addr, $pr_id)
	{
		try
		{
			$stmt = $this->conn->prepare("UPDATE party SET pr_name=:pr_name ,pr_email=:pr_email , pr_cont=:pr_cont , pr_alt=:pr_alt , gst_no=:gst_no , agent=:agent, pr_addr=:pr_addr  WHERE pr_id=:pr_id;
");
			$stmt->bindparam(":pr_name", $pr_name);
			$stmt->bindparam(":pr_email", $pr_email);
			$stmt->bindparam(":pr_cont", $pr_cont);
			$stmt->bindparam(":pr_alt", $pr_alt);
			$stmt->bindparam(":gst_no", $gst_no);
			$stmt->bindparam(":agent", $agent);
			$stmt->bindparam(":pr_addr", $pr_addr);
			$stmt->bindparam(":pr_id", $pr_id);
			$stmt->execute();
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	public function add($date_str, $months)
{
    $date = new DateTime($date_str);

    // We extract the day of the month as $start_day
    $start_day = $date->format('j');

    // We add 1 month to the given date
    $date->modify("+{$months} month");

    // We extract the day of the month again so we can compare
    $end_day = $date->format('j');

    if ($start_day != $end_day)
    {
        // The day of the month isn't the same anymore, so we correct the date
        $date->modify('last day of last month');
    }

    return $date;
}

public function getinstockdatewise($tp,$sdate,$edate)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT o . * , b.blt_id, b.arvdate, i.itm_name, p.pr_name, ch.ch_code
FROM orders o, blt b, co_item i, party p, chembers ch, chm_history h
WHERE o.blt_id = b.blt_id
AND o.pr_id=p.pr_id
AND o.itm_id = i.itm_id
AND o.ord_id = h.ord_id
AND h.ch_id=ch.ch_id
AND b.test_flag='5'
AND o.type=:tp
AND b.blt_type=:tp
AND b.arvdate >=:sdate AND b.arvdate <=:edate");
			$stmt->execute(array(':tp'=>$tp,':sdate'=>$sdate,':edate'=>$edate));
			$getinstock=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $getinstock;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function getoutstockdatewise($tp,$sdate,$edate)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT sh.* , o.rdesc, o.type, b.blt_id, i.itm_name, p.pr_name
FROM orders o, blt b, co_item i, sent_his sh, party p
WHERE o.blt_id = b.blt_id
AND o.pr_id=p.pr_id
AND o.itm_id = i.itm_id
AND o.ord_id=sh.ord_id
AND b.blt_id=sh.blt_id
AND b.blt_type=:tp
AND o.type=:tp
AND b.test_flag in (5,9)
AND sh.se_date >=:sdate AND sh.se_date <=:edate");
			$stmt->execute(array(':tp'=>$tp,':sdate'=>$sdate,':edate'=>$edate));
			$getoutstock=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $getoutstock;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
			public function getord($ord_id)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE ord_id=:ord_id");
			$stmt->execute(array(':ord_id'=>$ord_id));
			$getord=$stmt->fetch(PDO::FETCH_ASSOC);
			return $getord;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
		public function audbillsundatewise($pr_id,$sdate,$edate)
	{
		try 
		{
	        $stmt = $this->conn->prepare("SELECT sum(a.aud_credit) as aud_credit,p.pr_name 
			FROM audit a,party p 
			WHERE a.pr_id=p.pr_id 
			AND a.pr_id=:pr_id AND a.aud_flag=1 
			AND a.aud_date >=:sdate AND a.aud_date <=:edate");
			$stmt->execute(array(':pr_id'=>$pr_id,':sdate'=>$sdate,':edate'=>$edate));
			$audbillsundatewise=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $audbillsundatewise;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function edtindata($newqty, $newsize, $bal, $rdesc, $bal_size, $ord_id)
	{
		try
		{
			$stmt = $this->conn->prepare("UPDATE orders SET  qty =:newqty, size=:newsize, bal=:bal, rdesc=:rdesc, bal_size=:bal_size  WHERE  ord_id =:ord_id");
			$stmt->bindparam(":newqty", $newqty);
			$stmt->bindparam(":newsize", $newsize);
			$stmt->bindparam(":bal", $bal);
			$stmt->bindparam(":rdesc", $rdesc);
			$stmt->bindparam(":bal_size", $bal_size);
			$stmt->bindparam(":ord_id", $ord_id);
			$stmt->execute();
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
		public function getsenthis($se_id)
		{
		try{
	        $stmt = $this->conn->prepare("SELECT sh.*,o.parti FROM sent_his sh, orders o WHERE sh.se_id=:se_id
			AND sh.ord_id=o.ord_id");
			$stmt->execute(array(':se_id'=>$se_id));
			$getsenthis=$stmt->fetch(PDO::FETCH_ASSOC);
			return $getsenthis;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function edtoutdata($newqty, $newsize, $brd, $se_id)
	{
		try
		{
			$stmt = $this->conn->prepare("UPDATE sent_his SET qty =:newqty, out_size=:newsize, brd=:brd WHERE se_id =:se_id");
			$stmt->bindparam(":newqty", $newqty);
			$stmt->bindparam(":newsize", $newsize);
			$stmt->bindparam(":brd", $brd);
			$stmt->bindparam(":se_id", $se_id);
			$stmt->execute();
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	public function reciptdetails()
	{
		try 
		{
	        $stmt = $this->conn->prepare("SELECT a.*,p.pr_name FROM audit a,party p WHERE a.pr_id=p.pr_id AND a.aud_flag=1");
			$stmt->execute();
			$reciptdetails=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $reciptdetails;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function uprecit($aud_flag,$aud_id)
	{
		try
		{
			$stmt = $this->conn->prepare("UPDATE audit SET aud_flag =:aud_flag WHERE aud_id =:aud_id");
			$stmt->bindparam(":aud_flag", $aud_flag);
			$stmt->bindparam(":aud_id", $aud_id);
			$stmt->execute();
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	public function getmbillot($blt_id,$itm_id,$pr_id,$invdate)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT sh.*
FROM sent_his sh, orders o
WHERE sh.blt_id = :blt_id
AND o.ord_id = sh.ord_id
AND o.itm_id=:itm_id
AND o.pr_id=:pr_id
AND sh.se_date<=:invdate
");
		$stmt->execute(array(':blt_id'=>$blt_id,':itm_id'=>$itm_id,':pr_id'=>$pr_id,':invdate'=>$invdate));
			$getinstock=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $getmbillot;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function invoiceadd($i_code, $blt_type, $pr_id, $i_flag, $i_total, $i_gst, $i_sdate, $i_edate, $outh)
	{
		try
		{
		$stmt = $this->conn->prepare("INSERT INTO invoice (i_code, blt_type, pr_id, i_flag, i_total, i_gst, i_sdate, i_edate, outh) VALUES (:i_code, :blt_type, :pr_id, :i_flag, :i_total, :i_gst, :i_sdate, :i_edate, :outh)");

			$stmt->bindparam(":i_code", $i_code);
			$stmt->bindparam(":blt_type", $blt_type);
			$stmt->bindparam(":pr_id", $pr_id);
			$stmt->bindparam(":i_flag", $i_flag);	
			$stmt->bindparam(":i_total", $i_total);	
			$stmt->bindparam(":i_gst", $i_gst);	
			$stmt->bindparam(":i_sdate", $i_sdate);
			$stmt->bindparam(":i_edate", $i_edate);
			$stmt->bindparam(":outh", $outh);
			$stmt->execute();	

			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
		public function getinvoice($tp)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT i.*,p.pr_name,p.gst_no FROM invoice i, party p WHERE i.pr_id=p.pr_id AND i.blt_type=:tp limit 50");
			$stmt->execute(array(':tp'=>$tp));
			$getinvoice=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $getinvoice;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function getgstdatewise($tp,$sdate,$edate)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT i.*,p.pr_name,p.gst_no FROM invoice i, party p WHERE i.pr_id=p.pr_id AND i.blt_type=:tp
			AND i.i_edate >=:sdate AND i.i_edate <=:edate");
			$stmt->execute(array(':tp'=>$tp,':sdate'=>$sdate,':edate'=>$edate));
			$getinstock=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $getinstock;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	
		public function getgstpagi($start, $per_page, $tp)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT i.* ,p.pr_name,p.gst_no FROM invoice i, party p WHERE i.pr_id=p.pr_id AND i.blt_type=:tp LIMIT $start,$per_page");
			$stmt->execute(array(':tp'=>$tp));
			$getinoutstock=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $getinoutstock;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
			public function getdeb($tp,$pr_id)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT sum(i_total) as fdeb FROM invoice WHERE blt_type=:tp AND pr_id=:pr_id");
			$stmt->execute(array(':tp'=>$tp, ':pr_id'=>$pr_id));
			$getdeb=$stmt->fetch(PDO::FETCH_ASSOC);
			return $getdeb['fdeb'];
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
			public function getcre($pr_id)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT sum(aud_credit) as fcre FROM audit WHERE aud_flag=1 AND pr_id=:pr_id ");
			$stmt->execute(array(':pr_id'=>$pr_id));
			$getcre=$stmt->fetch(PDO::FETCH_ASSOC);
			return $getcre['fcre'];
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function validicode($pr_id, $i_code, $tp)
	{
		try
		{
	      $stmt = $this->conn->prepare("SELECT * FROM invoice WHERE pr_id=:pr_id AND i_code=:i_code AND blt_type=:tp");
			$stmt->execute(array(':pr_id'=>$pr_id, ':i_code'=>$i_code, ':tp'=>$tp));
			$validcont=$stmt->fetchAll(PDO::FETCH_ASSOC);
			$count=$stmt->rowCount();
			return $count;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
		public function partygst($tp)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT DISTINCT pr_id FROM blt WHERE blt_type=:tp");
			$stmt->execute(array(':tp'=>$tp));
			$partygst=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $partygst;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function partygstagnt($tp,$q)
	{
		try
		{
	        $stmt = $this->conn->prepare("SELECT DISTINCT b.pr_id FROM blt b, party p WHERE b.blt_type=:tp AND p.pr_id=b.pr_id AND p.agent LIKE :search");
			$stmt->execute(array(':tp'=>$tp, ':search' => $q));
			$partygst=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $partygst;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	
		public function invoiceupd($i_total, $i_gst, $i_sdate, $i_edate, $outh, $pr_id, $i_code, $blt_type)
	{
		try
		{
		$stmt = $this->conn->prepare("UPDATE invoice SET i_total =:i_total, i_gst=:i_gst,i_sdate=:i_sdate, i_edate=:i_edate, outh=:outh  WHERE pr_id=:pr_id AND i_code=:i_code AND blt_type=:blt_type");

			$stmt->bindparam(":i_total", $i_total);	
			$stmt->bindparam(":i_gst", $i_gst);	
			$stmt->bindparam(":i_sdate", $i_sdate);
			$stmt->bindparam(":i_edate", $i_edate);
			$stmt->bindparam(":outh", $outh);
			$stmt->bindparam(":pr_id", $pr_id);
			$stmt->bindparam(":i_code", $i_code);
			$stmt->bindparam(":blt_type", $blt_type);
			
			$stmt->execute();	

			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
}

?>