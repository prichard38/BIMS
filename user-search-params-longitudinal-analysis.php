<?php

    session_start();

    if($_SESSION["loggedAs"] != "Supervisor"){
        header("Location:access-denied.php?error=supervisorsonly");
        die();
    }
?>


<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="la-functions.js"></script>
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <link href="plugins/components.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/7b2b0481fc.js" crossorigin="anonymous"></script>
        
        <!-- Custom CSS -->
        <link rel="stylesheet" href="assets/css/custom.css">
        
        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>
        
        <!-- Table Design -->
        <script type="text/javascript" src="plugins/DataTables/datatables.min.js"></script>
        <link rel="stylesheet" type="text/css" href="plugins/DataTables/datatables.min.css"/>
<!--         
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
        -->
        <!-- 3D -->
        <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>

        
        <title>Bridge Management</title>
    </head>
    
    <body>
        <script>
            
            bridgeData = [];
            fetchAllBridgeData().then(
                (res) => {
                    res.data.forEach(obj => {
                    bridgeData.push(obj['bridgeNo'] + ' : ' + obj['bridgeName'] + ', ' + obj['countyName'] + ' County');
                });
            })

            bridgesSoFar = [];
        </script>

        <script>
            /******************************************************************************
            ********************** helper functions  **************************************
            ******************************************************************************/
            
            function showEndYearSelector (){
                document.getElementById('end-year').hidden = false;
            }

            function submitQueryParams() {
                document.getElementById('submit-btn-year').classList.remove('disabled');
                document.getElementById('submit-btn-year').classList.remove('btn-secondary');
                document.getElementById('submit-btn-year').classList.add('btn-primary');
            }

            function disableButton(button){
                button.classList.remove('btn-primary');
                button.classList.add('disabled');
                button.classList.add('btn-secondary');
            }

            function enableButton(button){
                button.classList.add('btn-primary');
                button.classList.remove('disabled');
                button.classList.remove('btn-secondary');
            }

            function showValidFeedback(element){
                element.disabled=true;
                element.classList.remove("border-danger");
                element.classList.add("border-2");
                element.classList.add("border-success");
            }

            function showInvalidFeedback(element, feedbackMessageElement){
                element.disabled=false;
                element.classList.add("border-danger");
                element.classList.add("border-2");
                element.classList.remove("border-success");
                feedbackMessageElement.hidden=false;
            }
            
            function removeFeedback(element){
                element.disabled=false;
                element.classList.remove("border-2");
                element.classList.remove("border-danger");
                element.classList.remove("border-success");
            }

            function updateBridgeIds(){
                let bridges = document.getElementsByClassName("bridge");
                let inputElements = document.getElementsByTagName("input");
                for(let i = 0 ; i < bridges.length ; i++){
                    // set bridge div id
                    bridges[i].setAttribute("id", "bridge"+(i+1))
                    // set the header for bridge
                    bridges[i].children[0].innerHTML = "Bridge " + (i+1);
                    // set the search input id
                    inputElements[i].setAttribute("id", "search"+(i+1))
                }
            }

            function updateConfirmationCount(count){
                let countElement = document.getElementById('confirmation-count');
                countElement.innerHTML = count;
                if(count > 0){
                    countElement.classList.remove('text-danger');
                    countElement.classList.add('text-success');
                } else{
                    countElement.classList.remove('text-success');
                    countElement.classList.add('text-danger');
                }
            }

            function buildBridgeElement(){
                let awaitingConfirmation = true;

                let bridgeDiv = document.createElement('div');
                bridgeDiv.setAttribute('id', ('bridge'+nextBridgeIndex));
                bridgeDiv.setAttribute('class', 'bridge');
                
                let bridgeHeader = document.createElement('h6');
                bridgeHeader.innerHTML = 'Bridge ' + nextBridgeIndex;
                
                let containerSearchDiv = document.createElement('div');
                containerSearchDiv.setAttribute('class', 'container-search');
                containerSearchDiv.setAttribute('id', 'container-search');

                let wrapperDiv = document.createElement('div');
                wrapperDiv.setAttribute('class', 'wrapper');
                wrapperDiv.setAttribute('id', 'wrapper'+nextBridgeIndex);

                let searchInput = document.createElement('input');
                searchInput.setAttribute('id', 'search'+nextBridgeIndex);
                searchInput.setAttribute('name', 'search'+nextBridgeIndex);
                searchInput.setAttribute('type', 'text');
                searchInput.setAttribute('class', 'border');
                searchInput.setAttribute('placeholder', 'Search for a bridge name/number');
                searchInput.setAttribute('autocomplete','chrome-off');

                let searchButton = document.createElement('button');
                searchButton.setAttribute('type','button');
                searchButton.setAttribute('id','search-btn');
                let searchIcon = document.createElement('i');
                searchIcon.setAttribute('class', 'fa fa-search');
                searchButton.appendChild(searchIcon);

                let resultsDiv = document.createElement('div');
                resultsDiv.setAttribute('class', 'results')
                resultsDiv.setAttribute('id', 'results'+nextBridgeIndex);
                resultsDiv.appendChild(document.createElement('ul'));

                wrapperDiv.appendChild(searchInput);
                wrapperDiv.appendChild(searchButton);
                wrapperDiv.appendChild(resultsDiv);
                
                containerSearchDiv.appendChild(wrapperDiv);

                //<button  type='button' class='confirm-btn btn btn-primary btn-sm' id="confirm-search-1">Confirm Selection</button>

                let confirmButton = document.createElement('button');
                confirmButton.setAttribute('type', 'button');
                confirmButton.setAttribute('class', 'confirm-btn');
                confirmButton.classList.add('btn');
                confirmButton.classList.add('btn-primary');
                confirmButton.classList.add('btn-sm');
                confirmButton.setAttribute('id', 'confirm-search-'+nextBridgeIndex);
                confirmButton.innerHTML= 'Confirm Selection'

                let removeButton = document.createElement('button');
                removeButton.setAttribute('type', 'button');
                removeButton.setAttribute('class', 'remove-btn');
                removeButton.setAttribute('id', 'remove-bridge-'+nextBridgeIndex);
                let removeIcon = document.createElement('i');
                removeIcon.setAttribute('class', 'fa fa-minus-circle option-icon');
                removeButton.appendChild(removeIcon);

                containerSearchDiv.appendChild(confirmButton);
                containerSearchDiv.appendChild(removeButton);

                let noMatchFeedback = document.createElement('span');
                noMatchFeedback.setAttribute('hidden', 'true');
                noMatchFeedback.setAttribute('class', 'input-feedback text-danger');
                noMatchFeedback.setAttribute('id', 'no-match-feedback'+nextBridgeIndex);
                noMatchFeedback.innerHTML = 'No matching records'
                
                let duplicateFeedback = document.createElement('span');
                duplicateFeedback.setAttribute('hidden', 'true');
                duplicateFeedback.setAttribute('class', 'input-feedback text-danger');
                duplicateFeedback.setAttribute('id', 'duplicate-feedback'+nextBridgeIndex);
                duplicateFeedback.innerHTML = 'Cannot confirm duplicate bridge selection'
                
                this.hidden = true;
                addBridgeLabel.hidden = true;

                bridgeDiv.appendChild(bridgeHeader);
                bridgeDiv.appendChild(containerSearchDiv);
                bridgeDiv.appendChild(noMatchFeedback);
                bridgeDiv.appendChild(duplicateFeedback);
                bridgeDiv.appendChild(document.createElement('br'));
                bridgeDiv.appendChild(document.createElement('br'));


                /******************************************************************************
                ********************** Bridge x search functionality  *************************
                ******************************************************************************/
                searchInput.addEventListener('keyup', () => {
                    let results = [];
                    let input = searchInput.value;
                    if (input.length) {
                        results = bridgeData.filter((item) => {
                        return item.toLowerCase().includes(input.toLowerCase());
                        });
                    }
                    renderResults(results);
                    let autoSuggestions = document.getElementsByTagName('li');

                    for(let i = 0 ; i < autoSuggestions.length ; i++){
                        let suggestion = autoSuggestions[i];
                        suggestion.onclick = function(){
                            searchInput.value = suggestion.innerText;
                            wrapperDiv.classList.remove('show');
                            removeFeedback(searchInput);
                            noMatchFeedback.hidden=true;
                            duplicateFeedback.hidden=true;
                        } 
                    }
                });

                function renderResults(results) {
                    if (!results.length) {
                        return wrapperDiv.classList.remove('show');
                    }

                    const content = results.slice(0,7)
                        .map((item) => {
                        return `<li class="result">${item}</li>`;
                        })
                        .join('');

                    wrapperDiv.classList.add('show');
                    resultsDiv.innerHTML = `<ul>${content}</ul>`;
                }

                /******************************************************************************
                ********************** Bridge x onclick functions  ****************************
                ******************************************************************************/
                removeButton.onclick = function() {
                    let numBridges = document.getElementsByClassName("bridge").length;
                    if(numBridges > 1){
                        bridgeDiv.remove();
                        updateBridgeIds();
                        if(!awaitingConfirmation){
                            bridgesSoFar.splice(bridgesSoFar.indexOf(searchInput.value), 1)
                            nextBridgeIndex -= 1;
                            numConfirmed -=1;
                            updateConfirmationCount(numConfirmed);
                        } else{
                            awaitingConfirmation = false;
                            awaitingAnyConfirmation = false;
                            isValid = true;
                            enableButton(document.getElementById('submit-btn-bridges'));
                            document.getElementsByClassName('submission-feedback')[0].hidden=true;
                        }
                        if((numBridges-1) < 3 && !awaitingAnyConfirmation){
                            addBridge.hidden = false;
                            addBridgeLabel.hidden = false;
                        }
                    } else {
                        alert("You must select at least one bridge");
                    }
                }
                    
                confirmButton.onclick = function(){
                    //validate user input
                    let inputElements = document.getElementsByTagName("input");
                    if(bridgeData.includes(searchInput.value)){
                        if(!bridgesSoFar.includes(searchInput.value)){
                            isValid = true;
                            hasDuplicate = false;
                        } else{
                            isValid = false;
                            hasDuplicate = true;
                        }
                    } else{
                        isValid = false;
                        hasDuplicate = false;
                    }
                    if(isValid){
                        bridgesSoFar.push(searchInput.value)
                        noMatchFeedback.hidden=true;
                        duplicateFeedback.hidden=true;
                        awaitingConfirmation = false;
                        awaitingAnyConfirmation = false;
                        nextBridgeIndex++;
                        numConfirmed++;
                        updateConfirmationCount(numConfirmed);
                        showValidFeedback(searchInput);
                        enableButton(document.getElementById('submit-btn-bridges'));
                        document.getElementsByClassName('submission-feedback')[0].hidden=true;
                        searchButton.remove();
                        this.remove();
                        removeButton.style='margin-left: 0px;'
                        let numBridges = document.getElementsByClassName("bridge").length;
                        if(numBridges < 3){
                            addBridge.hidden = false;
                            addBridgeLabel.hidden = false;
                        } 
                    } else {
                        if(hasDuplicate){
                            showInvalidFeedback(searchInput, duplicateFeedback);
                        } else{
                            showInvalidFeedback(searchInput, noMatchFeedback);
                        }
                    }
                }

                return bridgeDiv;
            }

            </script>


        <!-- Top Navbar -->
        <nav class="navbar navbar-light" style="background-color: #005cbf; width: 100vw;">
            <div class="container-fluid">
                <a class="navbar-brand" href="#" style="color: white; vertical-align: middle;">
                    <img src="img/wvdtlogo.png" alt="" width="30" height="30" class="d-inline-block align-text-middle">
                    Bridge Inspection Management System
                </a>
                <span class="float-right" style="color: white; font-size: 0.9em;">
                    <i class="fas fa-user-circle"></i>&nbsp;
                    Logged in as <?=$_SESSION["loggedAs"]?>&nbsp;|&nbsp; <a href="login.php" style="color: white; text-decoration: none;"> sign out</a>
                </span>
            </div>
        </nav>

        <!-- Sidebar Menu -->
        <div class="sidebar">
            <div class="menubar">
                <ul class="menu">
                    <li style="background-color: #5e5e5e;"><a id="RM" href='supervisor-yearly-inspection-report.php'>Report Management</a>
                        <ul class="submenu">
                            <li style="background-color: #5e5e5e;">
                                <a id="RM" href='supervisor-yearly-inspection-report.php'>Yearly Inspection Report</a>
                            </li>
                            <li style="background-color: #5e5e5e;">
                                <a id="RM" href='user-search-params-longitudinal-analysis.php'>Longitudinal Analysis</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Search Params -->
        <div class="container">
            <div id='main' class="main_title">
                <h5> Report Management</h5>
                <h5 class="card-title" style="font-size: 0.95em; margin-top: 0.3rem;">Longitudinal Analysis - Bridge and Timeframe Selection</h5>
                <br>

                <hr>
                <div id='search-instructions'>
                    <p><strong>First, select up to 3 bridges to analyze. Search by bridge name or number.</strong> </p>
                    <p><strong>Then, after your bridge selections are submitted, you will be prompted to provide a time period for the analysis.</strong></p>
                    <br>
                    <p>* <em>Selections must be confirmed to add additional bridges.</em></p>
                    <p>* <em>To remove a bridge selection, click the "minus" icon to the right of the selection.</em></p>
                    <br>  
                    <br>              
                </div>
                <h6 id="search-header">Select Up To 3 Bridges:</h6>
                <form id='search-form'>
                    <div id="bridges">
                        <br>
                        <div id="bridge1" class="bridge">
                            <h6>Bridge 1</h6>

                            <div class="container-search" id="container-search">
                                <div class="wrapper">
                                    <input class="border" type="text" name="search1" id="search1" placeholder="Search for a bridge name or number" autocomplete="chrome-off">
                                    <button type='button' id="search-btn"><i class="fa fa-search"></i></button>
                                    <div class="results">
                                        <ul>
                                        </ul>
                                    </div>
                                        
                                </div>
                                <button  type='button' class='confirm-btn btn btn-primary btn-sm' id="confirm-search-1">Confirm Selection</button>
                                <button  type='button' class='remove-btn' id="remove-bridge-1" ><i class="fa fa-minus-circle option-icon"></i></button>
                            </div>
                            <span hidden='true' class='input-feedback text-danger' id='no-match-feedback-1'>No matching records</span>
                            <span hidden='true' class='input-feedback text-danger' id='duplicate-feedback-1'>Cannot confirm duplicate bridge selection</span>
                            <br><br>
                            
                        </div>
                    </div>
                    <button type='button' hidden='true' class='add-bridge' id="add-bridge"><i class="far fa-plus-square"></i></button>
                    <span  hidden='true' id="add-bridge-label">&nbspAdd Another Bridge</span>
                    <br>
                    <br>
                    <p>
                        <br>
                        <h6 id="confirmation-message">You have confirmed <span id="confirmation-count" class="text-danger">0</span> bridge selections.</h6> 
                        <br>
                        <button hidden='true' id='edit-btn-bridges' class="btn btn-primary btn-sm" type='button'>Edit Bridge Selections</button>
                        <button id='submit-btn-bridges' class="btn btn-secondary btn-sm disabled" type='button'>Submit Bridge Selections</button>
                        <span hidden='true' style="font-size: 0.85em;" class='edit-feedback text-danger'><em>&nbsp&nbspChanging your bridge selections will reset the selected timeframe</em></span>
                        <span hidden='true' class='submission-feedback text-danger'><em>&nbsp&nbspTo submit your selections, confirm or delete any unconfirmed selections</em></span>
                    </p>

                </form>
                <hr>
                <div id="timeframe-instructions" hidden='true'>
                    <p><strong>Select a timeframe that you want to analyze by choosing "From" and "To" years.</strong> </p>
                    <p><strong>Note that a maximum range of 10 years is allowed.</strong></p>
                    <br>
                    <p>* <em>Earliest selectable "From" year is determined by the oldest existing inspection among selected bridges.</em></p>
                    <p>* <em>When more than one bridge is selected, it is possible that not all selected bridges have inspection data for the selected timeframe.</em></p>
                    <br>  
                    <br>              
                </div>
                <h6 id="timeframe-header" hidden='true'>Select a Timeframe:</h6>
                <br>
                <form action="" method="">
                        <p>

                        <span id="begin-year" hidden='true'>
                            From:
                            <select name="begin" id="begin-year-select" onchange="generateEndYears()" onfocus="this.selectedIndex=-1;" required></select>
                        </span>
                        &nbsp&nbsp
                        <span id='end-year' hidden='true'>
                            To:
                            <select name="end" id="end-year-select" onchange="enableButton(document.getElementById('submit-btn-years'));" onfocus="this.selectedIndex=-1;" required></select>
                        </span>
                        <br>
                        <br>
                        <br>
                    </p>
                    <button hidden=true id='submit-btn-years' class="btn btn-secondary btn-sm disabled" type='button'>Submit Timeframe Selection</button>
                </form>
            </div>  
        </div>
        
        <script>

            
            /******************************************************************************
            ********************** Bridge 1 search functionality  *************************
            ******************************************************************************/
            const searchInput = document.getElementById('search1');
            const searchWrapper = document.querySelector('.wrapper');
            const resultsWrapper = document.querySelector('.results');

            searchInput.addEventListener('keyup', () => {
                let results = [];
                let input = searchInput.value;
                if (input.length) {
                    results = bridgeData.filter((item) => {
                    return item.toLowerCase().includes(input.toLowerCase());
                    });
                }
                renderResults(results);
                let autoSuggestions = document.getElementsByTagName('li');

                for(let i = 0 ; i < autoSuggestions.length ; i++){
                    let suggestion = autoSuggestions[i];
                    suggestion.onclick = function(){
                        searchInput.value = suggestion.innerText;
                        searchWrapper.classList.remove('show');
                        removeFeedback(searchInput);
                        document.getElementById('no-match-feedback-1').hidden=true;
                        document.getElementById('duplicate-feedback-1').hidden=true;
                    } 
                }
            });

            function renderResults(results) {
                if (!results.length) {
                    return searchWrapper.classList.remove('show');
                }

                const content = results.slice(0,7)
                    .map((item) => {
                    return `<li class="result">${item}</li>`;
                    })
                    .join('');

                searchWrapper.classList.add('show');
                resultsWrapper.innerHTML = `<ul>${content}</ul>`;
            }
            /**************************************************************************
            **************************************************************************/

            // global tracker lets for control flow
            nextBridgeIndex = 1;
            isValid = false;
            hasDuplicate = false;
            numConfirmed = 0;
            
            
            // submit buttons
            let submitBridgeSelectionsButton = document.getElementById('submit-btn-bridges');
            let submitButtonYear = document.getElementById('submit-btn-years');
            let editBridgesButton = document.getElementById('edit-btn-bridges');

            
            // bridges element. parent to all bridge divs
            let bridges = document.getElementById('bridges');

            // elements for bridge 1
            let bridge1 = document.getElementById('bridge1');
            let confirmSearch1 = document.getElementById('confirm-search-1');
            let searchButton1 = document.getElementById('search-btn');
            let addBridge = document.getElementById('add-bridge');
            let addBridgeLabel = document.getElementById('add-bridge-label');
            let removeBridge1 = document.getElementById('remove-bridge-1');
            let awaitingConfirmation1 = true;
            awaitingAnyConfirmation = true;

            
            submitBridgeSelectionsButton.onclick = function() {
                if(isValid){
                    document.getElementById('search-instructions').hidden = true;
                    bridges.children[bridges.children.length -1].removeChild(bridges.children[bridges.children.length -1].lastChild);
                    bridges.children[bridges.children.length -1].removeChild(bridges.children[bridges.children.length -1].lastChild);
                    // hide icons from all bridge inputs so no more changes can be made
                    let icons = document.getElementsByClassName("option-icon");
                    for(let i = 0 ; i < icons.length ; i++){
                        icons[i].hidden = true;
                    }
                    // hide the "submit bridge selections" button
                    this.hidden = true;
                    editBridgesButton.hidden=false;
                    document.getElementsByClassName('edit-feedback')[0].hidden=false;

                    // hide the add bridge icon and label
                    addBridge.hidden = true;
                    addBridgeLabel.hidden = true;
           
                    // show the timeframes selectors
                    document.getElementById('begin-year').hidden = false;
                    document.getElementById('end-year').hidden = false;
                    document.getElementById('timeframe-instructions').hidden = false;
                    document.getElementById('timeframe-header').hidden = false;
                    document.getElementById('submit-btn-years').hidden = false;

                    let inputElements = document.getElementsByTagName("input");
                    let bridgeNames = [];
                    let bridgeNumbers = [];
                    let bridgeCounties = [];
                    for(let i = 0 ; i < inputElements.length ; i++){
                        let splitData = inputElements[i].value.split(":");
                        bridgeNumbers.push(splitData[0].trim());
                        let nameAndCounty = splitData[1].split(",");
                        bridgeNames.push(nameAndCounty[0].trim());
                        bridgeCounties.push(nameAndCounty[1].trim());
                    }
                    setBridgeSessionVars(bridgeNames, bridgeNumbers, bridgeCounties);

                }
            }

            editBridgesButton.onclick = function(){
                document.getElementById('search-instructions').hidden = false;
                bridges.children[bridges.children.length -1].appendChild(document.createElement('br'));
                bridges.children[bridges.children.length -1].appendChild(document.createElement('br'));
                // show icons from all bridge inputs so changes can be made
                let icons = document.getElementsByClassName("option-icon");
                for(let i = 0 ; i < icons.length ; i++){
                    icons[i].hidden = false;
                }
                // show the "submit bridge selections" button
                this.hidden = true;
                submitBridgeSelectionsButton.hidden=false;
                document.getElementsByClassName('edit-feedback')[0].hidden=true;


                // show the add bridge icon and label if less than 3 bridges
                let numBridges = document.getElementsByClassName("bridge").length
                if(numBridges < 3){
                    addBridge.hidden = false;
                    addBridgeLabel.hidden = false;
                }
        
                // hide the begin year selector
                document.getElementById('begin-year').hidden = true;
                document.getElementById('end-year').hidden = true;
                document.getElementById('timeframe-instructions').hidden = true;
                document.getElementById('timeframe-header').hidden = true;
                document.getElementById('submit-btn-years').hidden = true;
            }

            submitButtonYear.onclick = function(){
                var beginSelect = document.getElementById('begin-year-select');
                var endSelect = document.getElementById('end-year-select');
                setYearsSessionVars(beginSelect.options[beginSelect.selectedIndex].value, endSelect.options[endSelect.selectedIndex].value);      
            }



            /******************************************************************************
            ********************** Bridge 1 onclick functions  ****************************
            ******************************************************************************/
            confirmSearch1.onclick = function(){
                //validate user input
                if(bridgeData.includes(searchInput.value)){
                        if(!bridgesSoFar.includes(searchInput.value)){
                            isValid = true;
                            hasDuplicate = false;
                        } else{
                            isValid = false;
                            hasDuplicate = true;
                        }
                } else{
                    isValid = false;
                }      
                if(isValid){
                    bridgesSoFar.push(searchInput.value)
                    awaitingConfirmation1 = false;
                    awaitingAnyConfirmation = false;
                    nextBridgeIndex++;
                    numConfirmed++;
                    updateConfirmationCount(numConfirmed);
                    showValidFeedback(searchInput);
                    document.getElementById('no-match-feedback-1').hidden=true;
                    document.getElementById('duplicate-feedback-1').hidden=true;
                    searchButton1.remove();
                    this.remove();
                    removeBridge1.style='margin-left: 0px;'
                    addBridge.hidden = false;
                    addBridgeLabel.hidden = false;
                    removeBridge1.hidden = false;
                    enableButton(document.getElementById('submit-btn-bridges'));
                } else {
                    if(hasDuplicate){
                        showInvalidFeedback(searchInput, document.getElementById('duplicate-feedback-1'));
                    } else{
                        showInvalidFeedback(searchInput, document.getElementById('no-match-feedback-1'));
                    }
                }
            }

            removeBridge1.onclick = function() {
                let numBridges = document.getElementsByClassName("bridge").length
                if(numBridges > 1){
                    
                    bridgesSoFar.splice(bridgesSoFar.indexOf(document.getElementById('search1').value), 1)
                    bridge1.remove();
                    updateBridgeIds();
                    nextBridgeIndex -= 1;
                    numConfirmed -=1;
                    updateConfirmationCount(numConfirmed);
                    
                    if((numBridges-1) < 3 && !awaitingAnyConfirmation){
                        addBridge.hidden = false;
                        addBridgeLabel.hidden = false;
                    } 
                } else{
                    alert("You must select at least one bridge.")
                }
            }
            /**************************************************************************
            **************************************************************************/

            addBridge.onclick = function(){
                awaitingAnyConfirmation = true;
                isValid = false;
                disableButton(document.getElementById('submit-btn-bridges'));
                document.getElementsByClassName('submission-feedback')[0].hidden=false;
                let numBridges = document.getElementsByClassName("bridge").length;
                if(numBridges  < 3) {
                    let bridgeDiv = buildBridgeElement();
                    bridges.append(bridgeDiv);
                    this.hidden = true;
                    addBridgeLabel.hidden = true;
                }
            }

        </script>

        <script>
            // restore previous state using session vars if there are any
            if(<?php echo json_encode($_SESSION['hasSavedState']); ?>){
                let bridgeNumbers = <?php echo json_encode($_SESSION['selectedBridgeNumbers']); ?>;
                let bridgeNames = <?php echo json_encode($_SESSION['selectedBridgeNames']); ?>;
                let bridgeCounties = <?php echo json_encode($_SESSION['selectedBridgeCounties']); ?>;
                restoreSessionStateLongitudinalAnalysis(bridgeNumbers, bridgeNames, bridgeCounties);
            }
            
        </script>
        
    </body> 
</html>