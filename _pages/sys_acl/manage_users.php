<?php
	echo Html::PageTitle("Access Control - Manage Users");

	$sql = "SELECT d.dept_id, concat(a.area_name, ' - ', d.dept_name) as dept_name FROM ac_dept d left join ac_area a on d.area_id = a.area_id order by d.dept_id";
	$rs = Sql::ToArray($sql,2,&$DB);
	
	$DEPTS = "";
	foreach ($rs as $key => $val) {
		if ($DEPTS <> "") {
			$DEPTS .= ";";
		}
		$DEPTS .= $key.":".$val;
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
            colNames: ['Option', 'Username', 'Login', 'Dept.', 'Type', 'Status', 'Email'],
            colModel: [{
                name: 'act',
                index: 'act',
                width: 120,
                sortable: false
            }, {
                name: 'user_name',
                index: 'user_name',
                width: 200,
                editable: true
            }, {
                name: 'user_login',
                index: 'user_login',
                width: 150,
                editable: true,
                editoptions: {
                    size: 30
                }
			}, {
                name: 'dept_id',
                index: 'dept_id',
                width: 150,
                editable: true,
				edittype: "select",
                editoptions: {
                    value: "<?=$DEPTS?>"
                }
            }, {
                name: 'user_type',
                index: 'user_type',
                width: 80,
                editable: true,
                editoptions: {
                    size: 30
                }
            }, {
                name: 'user_status',
                index: 'user_status',
                width: 80,
                editable: true,
                editoptions: {
                    size: 30
                }
            }, {
                name: 'email',
                index: 'email',
                width: 200,
                editable: true,
                editoptions: {
                    size: 30
                }
            }],
            pager: $('#pager2'),
			postData: params2json($('#appForm').serializeArray()),
            sortname: 'user_id',
            viewrecords: true,
            sortorder: "desc",
            caption: "Users"
        });
        
        $('#list2').navGrid('#pager2', {}, //options
        {
            height: 280,
            reloadAfterSubmit: false
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
