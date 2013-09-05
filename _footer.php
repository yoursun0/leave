</body>
</html>
<script type="text/javascript" src="_js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="_js/thickbox.js"></script>
<script type="text/javascript" src="_js/lib.basic.js"></script>

<!--script type="text/javascript" src="<?=Path::Javascript?>en/FieldValidation.php"></script-->
<script>
	$(document).ready(function($) {		
		$(".InfoTable tbody td").each(function (i) {if (!this.innerHTML) {this.innerHTML = "-";}});
		/*
		$("tr.DataRow")
			//.css({"background-color":"<?=Style::RowMouseOut?>"})
			.mouseover(function(){$(this).css({"background-color":"<?=Style::RowMouseOver?>"})})
			.mouseout(function(){$(this).css({"background-color":"<?=Style::RowMouseOut?>"})});
			*/
		$(".InfoTable tbody tr")
			.mouseover(function(){$(this).css({"background-color":"#d0f9c8"})})
			.mouseout(function(){$(this).css({"background-color":"#ffffff"})});
		$(".FormTable tbody tr")
			.mouseover(function(){$(this).css({"background-color":"#d0f9c8"})})
			.mouseout(function(){$(this).css({"background-color":"#ffffff"})});
			
    });
</script>