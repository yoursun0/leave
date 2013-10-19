<?php
    echo Html::PageTitle("Access Control - Manage Departments");

    $sql = "SELECT a.area_id, a.area_name FROM ac_area a order by a.area_id";
    $rs = Sql::ToArray($sql,2,$DB);
    
    $AREAS = "";
    foreach ($rs as $key => $val) {
        if ($AREAS <> "") {
            $AREAS .= ";";
        }
        $AREAS .= $key.":".$val;
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
            colNames: ['Area', 'Department'],
            colModel: [{
                name: 'area_id',
                index: 'area_id',
                width: 150,
                editable: true,
                edittype: "select",
                editoptions: {
                    value: "<?=$AREAS?>"
                }
            }, {
                name: 'dept_name',
                index: 'dept_name',
                width: 250,
                editable: true,
                editoptions: {
                    size: 30
                }
            }],
            pager: $('#pager2'),
            postData: params2json($('#appForm').serializeArray()),
            sortname: 'dept_id',
            viewrecords: true,
            sortorder: "desc",
            caption: "Departments"
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
