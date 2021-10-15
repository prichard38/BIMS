function loadTable(bridgeId, jsonObject){
    $(document).ready(function(){
        var index = bridgeId.charAt(bridgeId.length-1);
        if(!jsonObject){
            var filename = "bridge" + index + "Data.json";
            $.ajax({
                url: filename,
                type: 'HEAD',
                error: function() 
                {
                    console.warn("No inspection data found for bridge: " + index);
                },
                success: function() 
                {
                    $('#tbl_bridge_insp_t' + index).DataTable({
                        "destroy": true,
                        "ajax": filename,
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
                            $('td', row).eq(6).css("color", getPointColors([data.rating])[0]);
                        },
                        "responsive": true,
                        "order": [[ 6, "asc" ]]
                        });
                }
            });
        }
        else{
            $('#tbl_bridge_insp_t' + index).DataTable({
                "destroy": true,
                "aaData": jsonObject,
                "dataSrc": '',
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
                'aoColumns': [
                    {'mData': 'finishedDate'},
                    {'mData': 'bridgeNo'},
                    {'mData': 'bridgeName'},
                    {'mData': 'inspectionTypeName'},
                    {'mData': 'assignedTo'},
                    {'mData': 'assignedBy'},
                    {'mData': 'rating'},
                    {'mData': 'bridgeElements'},
                    {'mData': 'report'}
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
                "responsive": true,
                "order": [[ 6, "asc" ]]
                });
        }
        
        
            
    
            // index = bridgeId.charAt(bridgeId.length-1);
            // $(".tbox").not("#rm_t" + index).hide();
            // $('#rm_t' + index).toggle();
            
    })

    
}
