<?php
	F::GetSubmit("role");
	
	if (empty($role)) {
		exit();
	}

//get access list	
	$sql = "SELECT m.mod_call, p.page_call,rp.page_id
			FROM ac_roles_pages rp, ac_pages p ,ac_modules m
			WHERE rp.role_id = ".$role." AND rp.page_id = p.page_id AND p.mod_id = m.mod_id
			ORDER BY m.mod_call,p.page_call";
	$user_acl = array();
	if ($rs = $DB->GetArray($sql)){
		foreach ($rs as $row){
			$user_acl[] = $row['page_id'];
		}
	}

//get all pages	
	$sql = "SELECT m.mod_name, p.page_call,p.page_id,p.page_name,p.description
			FROM ac_pages p ,ac_modules m
			WHERE p.mod_id = m.mod_id
			ORDER BY m.mod_call,p.page_call";
	$pages = array();
	if ($rs = $DB->GetArray($sql)) {
		foreach ($rs as $row){
			$tmp = "";
			$tmp->id 			= $row['page_id'];
			$tmp->call 			= $row['page_call'];
			$tmp->name 			= $row['page_name'];
			$tmp->description 	= $row['description'];
			$pages[$row['mod_name']][$row['page_call']] = $tmp;
		}
	}
	

//print result
	echo F::Button("SaveConfig()","Save");
	if($role > 10) echo " ".F::Button("DeleteRole()","Delete Role");
	

	?>
<div class='toggle' title='Role Information'>
<table class="InputForm">
<?php
	echo "";
	$row = Q::GetRow("SELECT * FROM ac_roles WHERE role_id = $role");
	echo F::Tr("Name", 			F::Text("role_name",$row['role_name'],50,50,FV::Required())).
		F::Tr("Description", 	F::TextArea("description",5,50,"",$row['description']));
?>
</table>
</div>
	<div  id="divTreeControl" class='toggle opened' title='Access Control List'>
		<a title="Collapse the entire tree below" href="#">Collapse All</a> | 
		<a title="Expand the entire tree below" href="#">Expand All</a> | 
		<a title="Toggle the tree below, opening closed branches, closing open branches" href="#">Toggle All</a>
	<?php
	echo '<ul id="Acl" class="treeview-famfamfam">';
	foreach ($pages as $name=>$content) {
		//F::CheckBox("group","value")
		echo '<li><span><strong>'.$name.'</strong></span><ul>';
		foreach ($content as $p) {
			echo "<li>".F::CheckBox("ids",$p->id,$user_acl)."<b>$p->name ($p->call)</b>";
			if ($p->description != "&nbsp;" && !empty($p->description)) {
				echo " - <i>$p->description</i>";
			}			
			echo "</li>";
		}
		echo "</ul></li>";
	}
	echo "</ul></div>";
	
	echo B::Toggle();
?>