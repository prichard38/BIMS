function fetchInspections(bridgeName) {
    return new Promise(function(resolve, reject) {
        data = [];
        
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // console.log(this.responseText);
            }
        };
        xhr.open('POST', 'load-inspections.php', true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        

        xhr.onload = function() {
            if(!this.responseText || this.responseText.trim().length === 0){
                resolve({data: null})
            }else{
                resolve(JSON.parse(this.responseText));
            }
        };
        
        xhr.onerror = function() {
            reject(new Error("Network Error"));
        };
        
        xhr.send('selectedBridgeName=' + bridgeName);
        
    })
}

function fetchAllBridgeData() {
    return new Promise(function(resolve, reject) {
        
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // console.log(this.responseText);
            }
        };
        xhr.open('POST', 'load-bridge-data.php', true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
            resolve(JSON.parse(this.responseText));
        };

        xhr.onerror = function() {
            reject(new Error("Network Error"));
        };
        
        xhr.send();
        
    })
}

function getRatings(inspectionsJson){
    var ratings = [];
    inspectionsJson.data.forEach((inspection, index) => {
        // if the inspection exists, get its rating and push it to ratings
        if(inspection != null ){
            ratings.push(inspection.rating);
        } 
    });
    return ratings;
}

function getPointColors(ratingsArray){
    colors = [];
    ratingsArray.forEach(function(rating, index){
        switch(rating){
            case 1:
                colors.push('#E40800')
                break;
            case 2:
                colors.push('#E32925')
                break;
            case 3:
                colors.push('#F26721')
                break;
            case 4:
                colors.push('#F89E33')
                break;
            case 5:
                colors.push('#EEC200')
                break;
            case 6:
                colors.push('#ECD715')
                break;
            case 7:
                colors.push('#609B41');
                break;
            case 8:
                colors.push('#2E7A3C');
                break;
            case 9:
                colors.push('#036353');
                break;
            default:
                colors.push(null);
        }
    })
    return colors;
}

function loadTable(bridgeId, jsonObject){
    $(document).ready(function(){
        var index = bridgeId.charAt(bridgeId.length-1);
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
                $('td', row).eq(6).css("color", getPointColors([data.rating])[0]);
            },
            "responsive": true,
            "order": [[ 0, "desc" ]]
            });

            
    })
}

function getYears(beginYear, endYear){
    years = [];
    while(beginYear <= endYear){
        years.push(''+beginYear);
        beginYear++;
    }
    return years;
}

function setBridgeHTML(){
    let colors = ['darkgrey', 'navy', 'steelblue'];
    for(let i = 0 ; i < selectedBridgeNames.length ; i++){
        let nameId = 'bridge-name-'+(i+1);
        let numberId = 'bridge-number-'+(i+1);
        let countyId = 'bridge-county-'+(i+1);
        let rowId = 'bridge'+(i+1);
        document.getElementById(`${nameId}`).innerHTML = `<i id="bridge-icon-${i+1}" class="fas fa-circle" style="color: ${colors[i]};"></i> ${selectedBridgeNames[i]}`;
        document.getElementById(`${rowId}`).hidden=false;
        document.getElementById(`${numberId}`).innerHTML = `${selectedBridgeNumbers[i]}`;
        document.getElementById(`${countyId}`).innerHTML = `${selectedBridgeCounties[i]}`;
    }
}

function renderInspectionlessBridgeHTML(bridgeElementWithNoInspections){
    bridgeElementWithNoInspections.classList.add('text');
    bridgeElementWithNoInspections.classList.add('text-danger');
    bridgeElementWithNoInspections.setAttribute('style', 'font-style: italic;')
}

function fillMissingInspections(bridgeName, bridgeInspectionsJsonObject){
    var inspectionYears = [];
    /* The "corrected" inspections data with null insertion for missing inspections. 
        * This is required for chart.js line chart to render line correctly with missing inspections.*/
    var correctedInspections = {data:[]};
    
    // Get all inspection years that exist in inspection data for this bridge
    for(var i = 0 ; i < bridgeInspectionsJsonObject.data.length ; i++){
        inspectionYears.push(parseInt(bridgeInspectionsJsonObject.data[i]['finishedDate'].slice(0,4)));
    }

    // get the years for which there are missing inspections by filtering against selected years timeframe array
    var difference = years.filter(year => !inspectionYears.includes(parseInt(year)));
    
    // fill correctedInspections, filling in null values where there are missing inspections
    for(var j = 0 ; j < years.length ; j++){
        if(difference.includes(years[j])){
            var index = difference.indexOf(years[j]);
            if(index != -1){
                difference.splice(index, 1)
            }
            correctedInspections.data[j] = null;
        } else{
            var nextInsp = bridgeInspectionsJsonObject.data.shift();
            correctedInspections.data[j] = nextInsp;
        }
    }
    return correctedInspections;
}