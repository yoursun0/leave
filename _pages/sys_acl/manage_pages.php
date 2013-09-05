<?php
	echo Html::PageTitle("Access Control - Manage Pages");
	
	$sql = "SELECT mod_id,mod_name
			FROM ac_modules ";
	
	$rs = Sql::ToArray($sql,2,&$DB);
	
	$MODULES = "";
	foreach ($rs as $key => $val) {
		if ($MODULES <> "") {
			$MODULES .= ";";
		}
		$MODULES .= $key.":".$val;
	}
?>

<!-- the grid definition in html is a table tag with class 'scroll' -->
<table id="list2" class="jqGridScroll" cellpadding="0" cellspacing="0">
</table>
<!-- pager definition. class scroll tels that we want to use the same theme as grid -->
<div id="pager2" class="jqGridScroll" style="text-align:center;">
</div>
<script>
    $(document).ready(function(){
        $('#list2').jqGrid({
            url: "ajax.php?gMethod=Data",
            editurl: "ajax.php?gMethod=Edit",
            colNames: ['Module', 'Call', 'Name', 'Description', 'Status'],
            colModel: [{
                name: 'mod_id',
                index: 'mod_id',
                width: 150,
                editable: true,
                edittype: "select",
                editoptions: {
                    value: "<?=$MODULES?>"
                }
            }, {
                name: 'page_call',
                index: 'page_call',
                width: 150,
                editable: true,
                editoptions: {
                    size: 30
                }
            }, {
                name: 'page_name',
                index: 'page_name',
                width: 200,
                editable: true,
                editoptions: {
                    size: 30
                }
            }, {
                name: 'description',
                index: 'description',
                width: 100,
                editable: true,
                editoptions: {
                    size: 30
                }
            }, {
                name: 'page_status',
                index: 'page_status',
                width: 80,
                sortable: false,
                editable: true,
                edittype: "select",
                editoptions: {
                    value: "A:Active;C:Close"
                }
            }],
            pager: $('#pager2'),
			postData: params2json($('#appForm').serializeArray()),
            sortname: 'page_id',
            viewrecords: true,
            sortorder: "desc",
            caption: "Pages"
        });
		
        $('#list2').navGrid('#pager2', {}, //options
        {
            height: 280,
            reloadAfterSubmit: true
        }, // edit options
        {
            height: 280,
            reloadAfterSubmit: true
        }, // add options
        {}, // del options
        {} // search options
    );
    });
</script>
