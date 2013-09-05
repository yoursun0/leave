<?php
	echo Html::PageTitle("Approve");
?>

<div id="leaveList" style="padding: 0;"></div>

<script>
$(function(){
	current_user_id = '<?=$USER->Id?>';
	getApproveListByUserId('<?=$USER->Id?>');
});
</script>