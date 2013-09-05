<?php
class User
{
	public $DEBUG = false;
	public $LastErrorMessage = "";
	
	public $Id 		= 0;
	public $Name 	= "";
	public $Status 	= "";

	public $Roles	= array();
	public $Pages	= array();
	
	public function CheckModules($module){
		return isset($_SESSION["acl"][$module]);
	}
	public function CheckPages($module,$page){
		return isset($_SESSION["acl"][$module][$page]);
	}
	public function IsAdmin(){
		return array_key_exists(1,$_SESSION["roles"]);
	}
	public function User(&$db = null, &$session = null) {
    }
    public function CheckSession($redirect_on_error = true, $redirect_url = "login.php") {
    	if(isset($_SESSION['userid'])){
    		$this->Id = $_SESSION['userid'];
			$this->Name = $_SESSION['username'];
		} else {
			if ($redirect_on_error == true) {
	  			header("Location: $redirect_url");
	  			exit;
			}
			return false;
		}
		return true;
    }
    public function Logout() {
    	session_destroy();
    }
	public function Login($login_name, $password) {
		$login = trim($login_name);
		$pw	= trim($password);
		if (empty($login) || empty($pw)) {
			$this->ErrorMessage("登入名称及密码不能空白");
			return false;
		}
		$sql = "SELECT user_id,user_name,user_status FROM ac_users WHERE user_login = '".addslashes($login)."' AND user_pw = '".md5($pw)."' AND user_status='A'";
		$this->DebugMessage("Login",$sql);
		if ($row = Q::GetRow($sql)) {
			$this->Status = $row['user_status'];
			$_SESSION['userid'] = $this->Id = $row['user_id'];
			$_SESSION['username'] = $this->Name = $row['user_name'];
			$_SESSION["roles"] = $this->Roles = $this->GetRoles();
			$_SESSION['acl'] = $this->Pages = $this->GetPages();
			return true;
		} else {
			$this->ErrorMessage("登入失败");
			return false;
		}
	}
	public function ShowErrorMessage($clear_msg = true,$msg = ''){
		if (empty($msg)) {
			if (!empty($this->LastErrorMessage)) {echo Html::ErrorMessage($this->LastErrorMessage);};
		} else {
			echo Html::ErrorMessage($msg);
		}
		if ($clear_msg){$this->LastErrorMessage = '';}
	}
	private function GetRoles()	{
		return Q::ToArray("SELECT ar.role_id,ar.role_name FROM ac_users_roles a, ac_roles ar WHERE a.user_id = '".$this->Id."' AND ar.role_id=a.role_id");
	}
	private function GetPages()	{	
		if (empty($this->Roles)){return ;}
		$sql = "SELECT m.mod_call, p.page_call FROM ac_roles_pages rp, ac_pages p ,ac_modules m
				WHERE rp.role_id IN ( ".join(",",array_keys($this->Roles)).") AND rp.page_id = p.page_id AND p.mod_id = m.mod_id";
		$acl = array();
		if ($rs = Q::GetArray($sql)) {
			foreach ($rs as $row){
				$acl[$row['mod_call']][$row['page_call']] = true;
			}
			return $acl;
		}
		return $acl;
	}
	protected function ErrorMessage($message){
		$this->LastErrorMessage = trim($message);
	}
	protected function DebugMessage($func, $text, $show = false){
		if ($this->DEBUG || $show) {
			echo get_class($this)."->$func() : $text<br />\n";
		}
	}
}
?>
