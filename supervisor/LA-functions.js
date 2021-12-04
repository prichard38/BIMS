/**
 * Fetches all inspections for the given bridge between selected years.
 * Selected years are SESSION vars beginYear and endYear, which are used as params in php script load-inspections.
 * 
 * @param {string} bridgeName the bridge to get inspections for. Sent in POST request to server side for use in load-inspections.php
 * @returns Promise that is resolved with the JSON bridge inspection data, 
 *          or a JSON object with null data if no inspections exist for the given bridge
 */
const fetchInspections = async (bridgeName) => {
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

/**
 * Fetches the bridge name, number, and county for all existing bridges.
 * @returns Promise that is resolved with JSON response containing bridge names, numbers, and counties for all existing bridges
 */
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

/**
 * Fetches the earliest inspection year from among the given bridges.
 * @param {string[]} bridgeNames The array of bridge names to get the earliest inspection from among. 
 *                               Sent in POST request for use in load-earliest-year.php
 * @returns Promise that resolves with the year (number) associated with the earliest inspection
 */
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

/**
 * Extracts the ratings from inpections JSON data
 * @param {object} inspectionsJson javascript object with name/value pair wher the name is "data" and the value is an array of objects, each representing one inspection
 *                                 ( example: {"data":[{assignedBy:x, assignedTo:x, bridgeName:x, bridgeNo:x, finishedDate:x, inspectionTypeName:x, rating:x}, ...]} )
 * @returns an array of ratings (number[]), where each rating maps by index to the inspections given
 */
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

/**
 * Generates an array of hex values, each of which correspond to a rating in the ratingsArray given. Colors are determined by numeric value of each rating.
 * @param {number[]} ratingsArray the array of ratings (1 - 9) to get colors for.
 * @returns an array of colors (strings of hex color values), where each color maps by index to the ratings given.
 */
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

/**
 * Renders the Data Table asscoicated with the given bridgeId with the given inspection data.
 * @param {string} bridgeId the html id attribute for a given bridge data table (example: bridge1)
 * @param {object[]} inspectionDataJson the array of inspection data javascript objects to be inserted into the data table 
 *                                      (example: [{assignedBy:x, assignedTo:x, bridgeName:x, bridgeNo:x, finishedDate:x, inspectionTypeName:x, rating:x}, ...])
 */
function loadTable(tableId, inspectionDataJson){
    $(document).ready(function(){
        $('#'+tableId).DataTable({
            "destroy": true,
            "aaData": inspectionDataJson,
            "dataSrc": '',
            "columnDefs": [ 
                {
                    "targets": -1,
                    "data": null,
                    "defaultContent": "<a href=\"../assets/Report.pdf\" class=\"btnset btn_review2\" target=\"_blank\">PDF</a>"
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
                $(row).css('height', '50');
            },
            "responsive": true,
            "order": [[ 0, "desc" ]]
            });

            
    })
}

/**
 * Generates an array of all the necessary year values for the x axis of line Chart based on begin and end years given.
 * @param {number} beginYear "From" year => first value on x axis
 * @param {number} endYear "To" year => last value on x axis
 * @returns array of years (number[])
 */
function getChartYears(beginYear, endYear){
    years = [];
    while(beginYear <= endYear){
        years.push(''+beginYear);
        beginYear++;
    }
    return years;
}

/**
 * Dynamically creates the Bridge Table Rows (in supervisor-longitudinal-analysis.php) for each bridge. 
 * This allows for the creation of only as many rows as there are bridges selected.
 */
function setBridgeHTML(selectedBridgeNames){
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

/**
 * Modifies the styling and class list of the bridge Table Row Element given to flag that bridge as having no associated inspection data
 * @param {html element} bridgeElementWithNoInspections the bridge Table Row element that should be rendered as "inspectionless"
 */
function renderInspectionlessBridgeHTML(bridgeElementWithNoInspections){
    bridgeElementWithNoInspections.classList.add('text');
    bridgeElementWithNoInspections.classList.add('text-danger');
    bridgeElementWithNoInspections.setAttribute('style', 'font-style: italic;')
}

/**
 * Finds years for which there are missing inspections and inserts null values to be mapped to those years. 
 * This enables the line chart to map inspections to correct years when there are missing inspections (i.e. years with no associated inspection)
 * @param {string} bridgeName the bridge to fill in missing inspection data points for.
 * @param {object} bridgeInspectionsJson javascript object with name/value pair wher the name is "data" and the value is an array of objects, each representing one inspection
 *                                       ( example: {"data":[{assignedBy:x, assignedTo:x, bridgeName:x, bridgeNo:x, finishedDate:x, inspectionTypeName:x, rating:x}, ...]} ) 
 * @returns 
 */
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
    
    // fill correctedInspections, inserting null values where there are missing inspections
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

/**
 * Restores the supervisor-search-params-longitudinal-analysis page to a "saved" state using the bridge numbers, names, and counties given.
 * @param {string[]} bridgeNumbers array of bridge numbers (strings). Number at index i should map to name at bridgeNames[i] and county at bridgeCounties[i]
 * @param {string[]} bridgeNames array of bridge names. Number at index i should map to number at bridgeNumbers[i] and county at bridgeCounties[i]
 * @param {string[]} bridgeCounties array of bridge counties. Number at index i should map to number at bridgeNumbers[i] and name at bridgeNames[i]
 * @returns Promise that resolves with true after session state has been restored.
 */
function restoreSessionStateLongitudinalAnalysis(bridgeNumbers, bridgeNames, bridgeCounties){
    return new Promise(function(resolve, reject){
        // remove the 1st "default" bridge element
        document.getElementById('bridge1').remove();

        // get the html div that will contain all the of individual bridge elements
        let bridges = document.getElementById('bridges');

        // for each of the selected bridges
        for(let i = 0 ; i < bridgeNames.length ; i++){
            // build a bridge html element and append it to bridges div
            let bridgeElement = buildBridgeElement();
            bridges.appendChild(bridgeElement);
            // create the string value for the bridge search input to be populated with
            let bridgeString = bridgeNumbers[i] + " : " + bridgeNames[i] + ", " + bridgeCounties[i];
            let searchInput =  document.getElementById('search'+(nextBridgeIndex));
            searchInput.value = bridgeString;
            showValidFeedback(searchInput);
            enableButton(document.getElementById('submit-btn-bridges'));
            document.getElementsByClassName('submission-feedback')[0].hidden=true;
            document.getElementById('search-btn').remove();
            document.getElementById('confirm-search-'+nextBridgeIndex).remove();
            let removeButton = document.getElementById('remove-bridge-'+nextBridgeIndex);
            removeButton.style='margin-left: 0px;'
            awaitingConfirmation = false;
            awaitingAnyConfirmation = false;
            nextBridgeIndex++;
            numConfirmed++;            
        }
        updateConfirmationCount(bridgeNames.length);
           
        // remove <br> children 
        bridges.children[bridges.children.length -1].removeChild(bridges.children[bridges.children.length -1].lastChild);
        bridges.children[bridges.children.length -1].removeChild(bridges.children[bridges.children.length -1].lastChild);
        // hide icons from all bridge inputs so no more changes can be made
        let icons = document.getElementsByClassName("option-icon");
        for(let i = 0 ; i < icons.length ; i++){
            icons[i].hidden = true;
        }
        // hide the "submit bridge selections" button
        document.getElementById('search-instructions').hidden = true;
        document.getElementById('submit-btn-bridges').hidden = true;
        let editBridgesButton = document.getElementById('edit-btn-bridges');
        editBridgesButton.hidden=false;
        document.getElementsByClassName('edit-feedback')[0].hidden=false;

        // hide the add bridge icon and label
        let addBridge = document.getElementById('add-bridge');
        let addBridgeLabel = document.getElementById('add-bridge-label');
        addBridge.hidden = true;
        addBridgeLabel.hidden = true;

        // show the timeframe section and instructions
        document.getElementById('timeframe-section').hidden = false;
        document.getElementById('timeframe-instructions').hidden = false;

        enableButton(document.getElementById('submit-btn-years'));
        document.getElementById('timeframe-instructions').hidden = true;
        generateBeginYears(JSON.stringify(bridgeNames)).then(
            () => {
                generateEndYears();
            }
        )
        isValid = true;
        resolve(true);
    })
}

/**
 * Removes all children from html element given
 * @param {html element} parent parent html element to remove children from
 */
function removeAllChildNodes(parent) {
    while (parent.firstChild) {
        parent.removeChild(parent.firstChild);
    }
}

/**
 * Runs a php script via AJAX that sets $_SESSION['selectedBridgeNames'], $_SESSION['selectedBridgeNumbers'], and $_SESSION['selectedBridgeCounties']
 * @param {string[]} bridgeNumbers array of bridge numbers (strings). Number at index i should map to name at bridgeNames[i] and county at bridgeCounties[i]
 * @param {string[]} bridgeNames array of bridge names. Number at index i should map to number at bridgeNumbers[i] and county at bridgeCounties[i]
 * @param {string[]} bridgeCounties array of bridge counties. Number at index i should map to number at bridgeNumbers[i] and name at bridgeNames[i]
 * @returns Promise that resolves with true if session vars could be set successfully, resolves false if session vars could not be set for some reason that did not throw an error.
 */
function setBridgeSessionVars(bridgeNames, bridgeNumbers, bridgeCounties){
    return new Promise(function(resolve, reject){
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
                        resolve(false);
                    } else{
                        resolve(true);
                    }
                },
                error: function(res){
                    console.warn(res)
                    reject(res);
                }
            })
        })

    })
}

/**
 * Runs a php script vai AJAX that sets $_SESSION['beginYear'] and $_SESSION['endYear'].
 * @param {number} beginYear "From" year
 * @param {number} endYear "To" year
 */
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

/**
 * Generates up to a max of 10 "To" (end) year select options based on the selected begin year option
 */
function generateEndYears (){
    let beginSelect = document.getElementById('begin-year-select');
    let beginYear = beginSelect.options[beginSelect.selectedIndex].value;
    
    let endSelect = document.getElementById('end-year-select');
    beginYear = parseInt(beginYear);
    
    let endYears = [];
    let nextYear = beginYear;
    let currentYear = new Date().getFullYear();
    
    // append a max of 10 years or up to the current year, whichever is earlier
    while(!(nextYear >= currentYear) && nextYear < beginYear + 10){
        nextYear ++;
        endYears.push(nextYear);
    }

    let yearOption;

    removeAllChildNodes(endSelect);
    
    // create html option element for each year in endYears and append to the endSelect element
    for(let i = 0 ; i < endYears.length ; i++){
        yearOption= document.createElement('option');
        yearOption.setAttribute('value', endYears[i]);
        yearOption.innerHTML = endYears[i];
        endSelect.appendChild(yearOption);
    }
}

/**
 * Generates begin year select options for all years between the current year and the earliest inspection year from among bridgeNames
 * @param {string[]} bridgeNames selected bridge names for which to generate "From" (begin) year select options
 * @returns Promise that resolves with true after all begin year options have been generated
 */
function generateBeginYears (bridgeNames){
    return new Promise(function(resolve, reject){
        let beginSelect = document.getElementById('begin-year-select');
        removeAllChildNodes(beginSelect);
        let beginYears = [];

        // get the earliest inspection year from among selected bridges
        fetchEarliestYear(bridgeNames).then(
            // after the earliest year has been fetched...
            (response) => {
                let beginYear = parseInt(response);
                let nextYear = beginYear;
                let currentYear = new Date().getFullYear();
    
                // append a year for each year between the earliest year and current year
                while(nextYear < currentYear){
                    beginYears.push(nextYear);
                    nextYear ++;
                }
    
                let yearOption;
                // create html option elements for each year in beginYears and append to beginSelect element
                for(let i = 0 ; i < beginYears.length ; i++){
                    yearOption= document.createElement('option');
                    yearOption.setAttribute('value', beginYears[i]);
                    yearOption.innerHTML = beginYears[i];
                    beginSelect.appendChild(yearOption);
                }
                resolve(true);
            }
        )
    })
}