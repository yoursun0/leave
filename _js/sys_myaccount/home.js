function ChangePassword(){
	SubmitAction("ChangePassword",$('#frmChangePw').serialize(),{
		ok:function(){
			$('#frmChangePw').resetForm();
		}
	});
}
