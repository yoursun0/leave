$(document).ready(function($) {

});

function ShowRolesControl(id)
{
	OpenDialog("Roles Control","dlgRolesControl",{uid : id});
}
function Test(id){
	
}
function SaveRoles(id)
{
	/*
    SubmitDialog("SaveRoles", {
        uid: id
    }, {
        failure: function(json){
            WarningDialog(json.msg);            
        }
    });
*/
	$.ajax({url: "ajax.php?gMethod=SaveRoles",
		type		: 'POST',
		dataType	: 'json',
		timeout		: 10000,
		data		: $('#appForm').serialize()+"&"+$('#dlgForm').serialize() + "&uid=" + id,
		error		: function(){
		},
		success		: function(json){
			if(json.type == "success"){
				tb_remove();
			}else {
				WarningDialog(json.msg);
			}
		}
	});//end of ajax

}
function RoleChanged(obj)
{
	$('#divDetail').html("");
	$.ajax({url: "ajax.php?gMethod=RoleChanged",
		type		: 'POST',
		dataType	: 'html',
		timeout		: 10000,
		data		: $('#appForm').serialize(),
		error		: function(){
		},
		success		: function(html){
			$('#divDetail').html(html);
			
			$('#Acl').treeview({
				collapsed: true,
				control:"#divTreeControl"			
			});
		}
	});//end of ajax
}
