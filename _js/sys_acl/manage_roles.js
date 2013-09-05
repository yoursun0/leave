$(function(){
	RoleChanged();
});

function CreateRole(){
	$('#divDetail').html("");
	SubmitAction("CreateRole",$("#new_role").serialize(),{
		ok:function(json){
			$('#role').addOption(json.id,json.name);
			RoleChanged($('#role'));
			$('#new_role').val('');
		}
	});
}
function DeleteRole(){
	if (confirm("Confirm Delete?")) {
		$('#divDetail').html("");
		SubmitAction("DeleteRole", $("#role").serialize(), {
			ok: function(json){
				val = $('#role').val();
				$('#role').removeOption(val);
				RoleChanged($('#role'));
				$('#new_role').val('');
			}
		});
	}
}
function SaveConfig()
{
	SubmitPages("SaveConfig",{ok: function(json){
		$("#role > option[value='"+$('#role').val()+"']").text($('#role_name').val());
	}});
}
function RoleChanged(obj)
{
	SubmitOptions("RoleChanged","#divDetail",$('#role').serialize(),{
		ok:function(){
			$('#Acl').treeview({
				collapsed: false,
				control:"#divTreeControl"			
			});			
		}
	});
}

