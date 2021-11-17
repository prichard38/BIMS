function fetchInspections(bridgeName) {
    return new Promise(function(resolve, reject) {
        data = [];
        
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // console.log(this.responseText);
            }
        };
        xhr.open('POST', 'php-scripts-longitudinal-analysis/load-inspections.php', true);
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
        xhr.open('POST', 'php-scripts-longitudinal-analysis/load-bridge-data.php', true);
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

function fetchEarliestYear(bridgeNames) {
    return new Promise(function(resolve, reject) {
        
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // console.log(this.responseText);
            }
        };
        xhr.open('POST', 'php-scripts-longitudinal-analysis/load-earliest-year.php', true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
            resolve(this.responseText);
        };

        xhr.onerror = function() {
            reject(new Error("Network Error"));
        };
        
        xhr.send('bridgeNames=' + bridgeNames);
        
    })
}

function getRatings(inspectionsJson){
    var ratings = [];
    inspectionsJson.data.forEach((inspection, index) => {
        // if the inspection exists, get its rating and push it to ratings
        if(inspection != null ){
            ratings.push(inspection.rating);
        } else{
            ratings.push(null);
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

function getChartYears(beginYear, endYear){
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

function restoreSessionStateLongitudinalAnalysis(bridgeNumbers, bridgeNames, bridgeCounties){
    document.getElementById('search-instructions').hidden=true;
    document.getElementById('bridge1').remove();
    let bridges = document.getElementById('bridges');
    for(let i = 0 ; i < bridgeNames.length ; i++){
        setTimeout(() => {
            let bridgeElement = buildBridgeElement();
            bridges.appendChild(bridgeElement);
            document.getElementById('search'+(nextBridgeIndex)).value = bridgeNumbers[i] + " : " + bridgeNames[i] + ", " + bridgeCounties[i];
            document.getElementById('confirm-search-'+(nextBridgeIndex)).click();
            if(document.getElementsByClassName("bridge").length > 2){
                document.getElementById('add-bridge').hidden = true;
                document.getElementById('add-bridge-label').hidden = true;
            }
        }, 50);
    }
    setTimeout(() => {
        document.getElementById('submit-btn-bridges').click();
        enableButton(document.getElementById('submit-btn-years'));
        document.getElementById('timeframe-instructions').hidden = true;
    }, 50);
}


function removeAllChildNodes(parent) {
    while (parent.firstChild) {
        parent.removeChild(parent.firstChild);
    }
}

function setBridgeSessionVars(bridgeNames, bridgeNumbers, bridgeCounties){
    $(document).ready(function() {
        $.ajax({
            type: 'POST',
            url: 'php-scripts-longitudinal-analysis/set-bridge-session-vars.php',
            data: {selectedBridgeNames : JSON.stringify(bridgeNames), 
                   selectedBridgeNumbers : JSON.stringify(bridgeNumbers), 
                   selectedBridgeCounties: JSON.stringify(bridgeCounties),},
            dataType: "json",
            success: function(res){
                if(!res){
                    console.warn("Could not submit selected bridge data");
                } else{
                    generateBeginYears(JSON.stringify(bridgeNames));
                }
            },
            error: function(res){
                console.warn(res)
            }
        })
    })
}

function setYearsSessionVars(beginYear, endYear){
    $(document).ready(function() {
        $.ajax({
            type: 'POST',
            url: 'php-scripts-longitudinal-analysis/set-years-session-vars.php',
            data: {yearBegin : JSON.stringify(beginYear), 
                   yearEnd : JSON.stringify(endYear)},
            success: function(res){
                window.location.href = "supervisor-longitudinal-analysis.php";
            },
            error: function(res){
                console.warn("");
            }
        }) 
    })
}

function generateEndYears (){
    let beginSelect = document.getElementById('begin-year-select');
    let beginYear = beginSelect.options[beginSelect.selectedIndex].value;
    
    let endSelect = document.getElementById('end-year-select');
    beginYear = parseInt(beginYear);
    
    let endYears = [];
    let nextYear = beginYear;
    let currentYear = new Date().getFullYear();
    
    while(!(nextYear >= currentYear) && nextYear < beginYear + 10){
        nextYear ++;
        endYears.push(nextYear);
        
    }

    let yearOption;

    removeAllChildNodes(endSelect);
    
    for(let i = 0 ; i < endYears.length ; i++){
        yearOption= document.createElement('option');
        yearOption.setAttribute('value', endYears[i]);
        yearOption.innerHTML = endYears[i];
        endSelect.appendChild(yearOption);
    }
}

function generateBeginYears (bridgeNames){
    let beginSelect = document.getElementById('begin-year-select');
    removeAllChildNodes(beginSelect);
    let beginYears = [];
    fetchEarliestYear(bridgeNames).then(
        (response) => {
            let beginYear = parseInt(response);
            let nextYear = beginYear;
            let currentYear = new Date().getFullYear();

            while(nextYear < currentYear){
                beginYears.push(nextYear);
                nextYear ++;
            }

            let yearOption;
            for(let i = 0 ; i < beginYears.length ; i++){
                yearOption= document.createElement('option');
                yearOption.setAttribute('value', beginYears[i]);
                yearOption.innerHTML = beginYears[i];
                beginSelect.appendChild(yearOption);
            }

            generateEndYears();
        }
    )
}