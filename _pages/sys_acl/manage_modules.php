<?php
	echo Html::PageTitle("Access Control - Manage Modules");
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
            colNames: ['Module ID', 'Call', 'Name', 'Description', 'Status'],
            colModel: [{
                name: 'mod_id',
                index: 'mod_id',
                width: 100,
                editable: false
            }, {
                name: 'mod_call',
                index: 'mod_call',
                width: 150,
                editable: true,
                editoptions: {
                    size: 30
                }
            }, {
                name: 'mod_name',
                index: 'mod_name',
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
                name: 'mod_status',
                index: 'mod_status',
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
            sortname: 'mod_id',
            viewrecords: true,
            sortorder: "desc",
            caption: "Module"
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
