function loadTable(bridgeId){
    $(document).ready(function(){
        index = bridgeId.charAt(bridgeId.length-1);

        var origHeight = "calc(100vh - 58px)";
        var contHeight = $('section').height();
        var sideHeight = $('.sidebar').height();
    
        if (contHeight > sideHeight) {
            $('.sidebar').height(contHeight);
        } else {
            $('.sidebar').height(origHeight);
        }
        var table;
    
        var bridgeNo = $(bridgeId + ' .bridge-no').text();
        var bridgeName = $(bridgeId + ' .bridge-name').text();
        try{
            
            $('#inspection-list').load("load-inspections.php", {
                selectedBridgeNo: bridgeNo.trim(),
                selectedBridgeName: bridgeName.trim()
            },
            function(response, status, xhr){
                if(response.includes("No Inspections Found")){
                    $('#tbl_bridge_insp_t' + index).DataTable().clear();
                    return;
                }
                else {
                        if (!table){
                        table = $('#tbl_bridge_insp_t' + index).DataTable({
                            "destroy": true,
                            "ajax": "bridgeData.json",
                            "columnDefs": [ 
                                {
                                    "targets": -1,
                                    "data": null,
                                    "defaultContent": "<a href=\"assets/Report.pdf\" class=\"btnset btn_review2\" target=\"_blank\">PDF</a>"
                                },
                                {
                                    "targets": -2,
                                    "data": null,
                                    "defaultContent": "<a class=\"btnset btn_overview\" data-bs-toggle=\"modal\" data-bs-target=\"#myModal\">3D</a>"
                                },
                                {
                                    "targets": -3,
                                    "data": null,
                                    "defaultContent": "<a class=\"btnset btn_overview\" data-bs-toggle=\"modal\" data-bs-target=\"#myModal\">3D</a>"
                                },
                                {
                                    "targets": "_all",
                                    "autoWidth": true
                                }
                            ],
                            'columns': [
                                {'data': 'finishedDate'},
                                {'data': 'bridgeNo'},
                                {'data': 'bridgeName'},
                                {'data': 'inspectionTypeName'},
                                {'data': 'assignedTo'},
                                {'data': 'assignedBy'},
                                {'data': 'rating'},
                                {'data': 'bridgeElements'},
                                {'data': 'report'}
                            ], 
                            "rowCallback": function(row, data){
                                if(data.rating <= 3){
                                    $('td', row).eq(6).addClass('text-danger');
                                }
                                else if (data.rating <= 6){
                                    $('td', row).eq(6).addClass('text-warning');
                                }
                                else{
                                    $('td', row).eq(6).addClass('text-success');
                                }
                            },
                            "order": [[ 6, "asc" ]]
                            });
                        }
                        else{
                            table.ajax.reload();
                        }
    
                }
            });
        }
        catch(err){
        }
        index = bridgeId.charAt(bridgeId.length-1);
        $(".tbox").not("#rm_t" + index).hide();
        $('#rm_t' + index).toggle();
    })

    
}
