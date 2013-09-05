<?php
	echo Html::PageTitle("Access Control - Manage Area");

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
            colNames: ['Dept. ID', 'Name', 'Display Order'],
            colModel: [{
                name: 'act',
                index: 'act',
                width: 120,
                sortable: false
            }, {
                name: 'area_name',
                index: 'area_name',
                width: 400,
                editable: true
            }, {
                name: 'sort_order',
                index: 'sort_order',
                width: 100,
                editable: true
            }
            ],
            pager: $('#pager2'),
			postData: params2json($('#appForm').serializeArray()),
            sortname: 'sort_order',
            viewrecords: true,
            sortorder: "asc",
            caption: "Area"
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
