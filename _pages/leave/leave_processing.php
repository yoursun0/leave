<?php

	$titleBar = F::CssButton('addLeave()','New Application Form');
	echo Html::PageTitle("Processing / Leave Application",$titleBar);
?>
<?php
/*echo F::CssButton("addLeave()","New Application Form")*/
?>

<div id="leaveList" style="padding: 0;"></div>

<script>
$(function(){
	getLeaveListByUserId('<?=$USER->Id?>');
});
</script>
