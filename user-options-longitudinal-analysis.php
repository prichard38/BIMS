<?php

    session_start();

    // if($_SESSION["loggedAs"] != "Supervisor"){
    //     header("Location:access_denied.php?error=supervisorsonly");
    //     die();
    // }

    // Later we will get thee bridge names by POST after user has selected bridges, then set them as session vars
   $_SESSION["yearBegin"] = [2016];
   $_SESSION["yearEnd"] = [2021];
?>


<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <script src="functions.js"></script>

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

            function showInvalidFeedback(element){
                element.disabled=false;
                element.classList.add("border-danger");
                element.classList.add("border-2");
                element.classList.remove("border-success");
            }
            
            function removeFeedback(element){
                element.disabled=false;
                element.classList.remove("border-2");
                element.classList.remove("border-danger");
                element.classList.remove("border-success");
            }

            function updateBridgeIds(){
                var bridges = document.getElementsByClassName("bridge");
                var inputElements = document.getElementsByTagName("input");
                for(var i = 0 ; i < bridges.length ; i++){
                    // set bridge div id
                    bridges[i].setAttribute("id", "bridge"+(i+1))
                    // set the header for bridge
                    bridges[i].children[0].innerHTML = "Bridge " + (i+1);
                    // set the search input id
                    inputElements[i].setAttribute("id", "search"+(i+1))
                }
            }

            function updateConfirmationCount(count){
                var countElement = document.getElementById('confirmation-count');
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
                var awaitingConfirmation = true;

                var bridgeDiv = document.createElement('div');
                bridgeDiv.setAttribute('id', ('bridge'+nextBridgeIndex));
                bridgeDiv.setAttribute('class', 'bridge');
                
                var bridgeHeader = document.createElement('h6');
                bridgeHeader.innerHTML = 'Bridge ' + nextBridgeIndex;
                
                var containerSearchDiv = document.createElement('div');
                containerSearchDiv.setAttribute('class', 'container-search');
                containerSearchDiv.setAttribute('id', 'container-search');

                var wrapperDiv = document.createElement('div');
                wrapperDiv.setAttribute('class', 'wrapper');
                wrapperDiv.setAttribute('id', 'wrapper'+nextBridgeIndex);

                var searchInput = document.createElement('input');
                searchInput.setAttribute('id', 'search'+nextBridgeIndex);
                searchInput.setAttribute('name', 'search'+nextBridgeIndex);
                searchInput.setAttribute('type', 'text');
                searchInput.setAttribute('class', 'border');
                searchInput.setAttribute('placeholder', 'Search for a bridge name/number');
                searchInput.setAttribute('autocomplete','chrome-off');

                var searchButton = document.createElement('button');
                searchButton.setAttribute('type','button');
                searchButton.setAttribute('id','search-btn');
                var searchIcon = document.createElement('i');
                searchIcon.setAttribute('class', 'fa fa-search');
                searchButton.appendChild(searchIcon);

                var resultsDiv = document.createElement('div');
                resultsDiv.setAttribute('class', 'results')
                resultsDiv.setAttribute('id', 'results'+nextBridgeIndex);
                resultsDiv.appendChild(document.createElement('ul'));

                wrapperDiv.appendChild(searchInput);
                wrapperDiv.appendChild(searchButton);
                wrapperDiv.appendChild(resultsDiv);
                
                containerSearchDiv.appendChild(wrapperDiv);

                var confirmButton = document.createElement('button');
                confirmButton.setAttribute('type', 'button');
                confirmButton.setAttribute('class', 'confirm-btn');
                confirmButton.setAttribute('id', 'confirm-btn'+nextBridgeIndex);
                var confirmIcon = document.createElement('i');
                confirmIcon.setAttribute('id', 'confirm-search-'+nextBridgeIndex);
                confirmIcon.setAttribute('class', 'fas fa-sign-in-alt option-icon');
                confirmButton.appendChild(confirmIcon);

                var removeButton = document.createElement('button');
                removeButton.setAttribute('type', 'button');
                removeButton.setAttribute('class', 'remove-btn');
                removeButton.setAttribute('id', 'remove-btn'+nextBridgeIndex);
                var removeIcon = document.createElement('i');
                removeIcon.setAttribute('id', 'remove-bridge-'+nextBridgeIndex);
                removeIcon.setAttribute('class', 'fa fa-minus-circle option-icon');
                removeButton.appendChild(removeIcon);

                containerSearchDiv.appendChild(confirmButton);
                containerSearchDiv.appendChild(removeButton);

                var inputFeedback = document.createElement('span');
                inputFeedback.setAttribute('hidden', 'true');
                inputFeedback.setAttribute('class', 'input-feedback text-danger');
                inputFeedback.setAttribute('id', 'input-feedback'+nextBridgeIndex);
                inputFeedback.innerHTML = 'No matching records'
                
                this.hidden = true;
                addBridgeLabel.hidden = true;

                bridgeDiv.appendChild(bridgeHeader);
                bridgeDiv.appendChild(containerSearchDiv);
                bridgeDiv.appendChild(inputFeedback);
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
                            inputFeedback.hidden=true;
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
                    var numBridges = document.getElementsByClassName("bridge").length;
                    if(numBridges > 1){
                        bridgeDiv.remove();
                        updateBridgeIds();
                        if(!awaitingConfirmation){
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
                    isValid = bridgeData.includes(searchInput.value);
                    if(isValid){
                        inputFeedback.hidden=true;
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
                        var numBridges = document.getElementsByClassName("bridge").length;
                        if(numBridges < 3){
                            addBridge.hidden = false;
                            addBridgeLabel.hidden = false;
                        } 
                    } else {
                        showInvalidFeedback(searchInput);
                        inputFeedback.hidden=false;
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
                    Logged in as <?=$_SESSION["loggedAs"]?>&nbsp;|&nbsp; <a href="login-test.php" style="color: white; text-decoration: none;"> sign out</a>
                </span>
            </div>
        </nav>

        <!-- Sidebar Menu -->
        <div class="sidebar">
            <div class="menubar">
                <ul class="menu">
                    <li style="background-color: #5e5e5e;"><a id="RM" href='supervisor_yearly_inspection_report.php'>Report Management</a>
                        <ul class="submenu">
                            <li style="background-color: #5e5e5e;">
                                <a id="RM" href='supervisor_yearly_inspection_report.php'>Yearly Inspection Report</a>
                            </li>
                            <li style="background-color: #5e5e5e;">
                                <a id="RM" href='user-options-longitudinal-analysis.php'>Longitudinal Analysis</a>
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
                    <p>* <em>To confirm a bridge selection, click the "enter" icon to the right of the selection.</em></p>
                    <p>* <em>To remove a bridge selection, click the "dash" icon to the right of the selection.</em></p>
                    <br>  
                    <br>              
                    <h6>Select Up To 3 Bridges:</h6>
                </div>
                <form>
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
                                <button  type='button' class='confirm-btn'><i id="confirm-search-1" class="fas fa-sign-in-alt option-icon"></i></button>
                                <button  type='button' class='remove-btn' id="remove-bridge-1" ><i class="fa fa-minus-circle option-icon"></i></button>
                            </div>
                            <span hidden='true' class='input-feedback text-danger' id='input-feedback-1'>No matching records</span>
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
                        <button id='submit-btn-bridges' class="btn btn-secondary btn-sm disabled" type='button'>Submit Bridge Selections</button>
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
                    <h6>Select a Timeframe:</h6>
                </div>
                <br>
                <form action="supervisor_longitudinal_analysis.php" method="POST">
                        <p>

                        <span id="begin-year" hidden='true'>
                            From:
                            <select name="begin" id="begin-year" onchange="showEndYearSelector();" onfocus="this.selectedIndex=-1;" required>
                                <option value="2001">2001</option>
                                <option value="2002">2002</option>
                                <option value="2003">2003</option>
                                <option value="2004">2004</option>
                                <option value="2005">2005</option>
                                <option value="2006">2006</option>
                                <option value="2007">2007</option>
                                <option value="2008">2008</option>
                                <option value="2009">2009</option>
                                <option value="2010">2010</option>
                                <option value="2011">2011</option>
                                <option value="2012">2012</option>
                                <option value="2013">2013</option>
                                <option value="2014">2014</option>
                                <option value="2015">2015</option>
                                <option value="2016">2016</option>
                                <option value="2017">2017</option>
                                <option value="2018">2018</option>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                            </select>
                        </span>
                        &nbsp&nbsp
                        <span id='end-year' hidden='true'>
                            To:
                            <select name="end" id="end-year" onchange="enableButton(document.getElementById('submit-btn-years'));" onfocus="this.selectedIndex=-1;" required>
                                <option value="2001">2001</option>
                                <option value="2002">2002</option>
                                <option value="2003">2003</option>
                                <option value="2004">2004</option>
                                <option value="2005">2005</option>
                                <option value="2006">2006</option>
                                <option value="2007">2007</option>
                                <option value="2008">2008</option>
                                <option value="2009">2009</option>
                                <option value="2010">2010</option>
                                <option value="2011">2011</option>
                                <option value="2012">2012</option>
                                <option value="2013">2013</option>
                                <option value="2014">2014</option>
                                <option value="2015">2015</option>
                                <option value="2016">2016</option>
                                <option value="2017">2017</option>
                                <option value="2018">2018</option>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                            </select>
                        </span>
                        <br>
                        <br>
                        <button hidden=true id='submit-btn-years' class="btn btn-secondary btn-sm disabled" type='submit'>Submit Timeframe Selection</button>
                    </p>
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
                        document.getElementById('input-feedback-1').hidden=true;
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

            // global tracker vars for control flow
            nextBridgeIndex = 1;
            isValid = false;
            numConfirmed = 0;
            
            
            // submit buttons
            var submitBridgeSelectionsButton = document.getElementById('submit-btn-bridges');
            var submitButtonYear = document.getElementById('submit-btn-years');
            
            // bridges element. parent to all bridge divs
            var bridges = document.getElementById('bridges');

            // elements for bridge 1
            var bridge1 = document.getElementById('bridge1');
            var confirmSearch1 = document.getElementById('confirm-search-1');
            var searchButton1 = document.getElementById('search-btn');
            var addBridge = document.getElementById('add-bridge');
            var addBridgeLabel = document.getElementById('add-bridge-label');
            var removeBridge1 = document.getElementById('remove-bridge-1');
            var awaitingConfirmation1 = true;
            awaitingAnyConfirmation = true;

            
            submitBridgeSelectionsButton.onclick = function() {
                if(isValid){
                    document.getElementById('search-instructions').hidden = 'true';
                    bridges.children[bridges.children.length -1].removeChild(bridges.children[bridges.children.length -1].lastChild);
                    bridges.children[bridges.children.length -1].removeChild(bridges.children[bridges.children.length -1].lastChild);
                    // hide icons from all bridge inputs so no more changes can be made
                    var icons = document.getElementsByClassName("option-icon");
                    for(var i = 0 ; i < icons.length ; i++){
                        icons[i].hidden = true;
                    }
                    // remove the "submit bridge selections" button
                    this.remove()

                    // remove the add bridge icon and label
                    addBridge.remove()
                    addBridgeLabel.remove()
           
                    // show the begin year selector
                    document.getElementById('begin-year').hidden = false;
                    document.getElementById('timeframe-instructions').hidden = false;
                    document.getElementById('submit-btn-years').hidden = false;

                    var inputElements = document.getElementsByTagName("input");
                    var bridgeNames = [];
                    var bridgeNumbers = [];
                    var bridgeCounties = [];
                    for(var i = 0 ; i < inputElements.length ; i++){
                        var splitData = inputElements[i].value.split(":");
                        bridgeNumbers.push(splitData[0].trim());
                        var nameAndCounty = splitData[1].split(",");
                        bridgeNames.push(nameAndCounty[0].trim());
                        bridgeCounties.push(nameAndCounty[1].trim());
                    }
                    $(document).ready(function() {
                        $.ajax({
                            type: 'POST',
                            url: 'set-bridge-session-vars.php',
                            data: {selectedBridgeNames : JSON.stringify(bridgeNames), selectedBridgeNumbers : JSON.stringify(bridgeNumbers), selectedBridgeCounties: JSON.stringify(bridgeCounties)},
                            dataType: "json",
                            success: function(res){
                                if(!res){
                                    console.warn("Could not submit selected bridge data");
                                }
                            },
                            error: function(res){
                                console.warn(res)
                            }
                        })
                    })
                }
            }

            submitButtonYear.onclick = function(){
                //TODO: use AJAX to POST From and To years selections and call php script to set session vars for beginYear and endYear         
            }



            /******************************************************************************
            ********************** Bridge 1 onclick functions  ****************************
            ******************************************************************************/
            confirmSearch1.onclick = function(){
                //validate user input
                isValid = bridgeData.includes(searchInput.value)        
                if(isValid){
                    awaitingConfirmation1 = false;
                    awaitingAnyConfirmation = false;
                    nextBridgeIndex++;
                    numConfirmed++;
                    updateConfirmationCount(numConfirmed);
                    showValidFeedback(searchInput);
                    document.getElementById('input-feedback-1').hidden=true;
                    searchButton1.remove();
                    this.remove();
                    removeBridge1.style='margin-left: 0px;'
                    addBridge.hidden = false;
                    addBridgeLabel.hidden = false;
                    removeBridge1.hidden = false;
                    enableButton(document.getElementById('submit-btn-bridges'));
                } else {
                    showInvalidFeedback(searchInput);
                    document.getElementById('input-feedback-1').hidden=false;
                }
            }

            removeBridge1.onclick = function() {
                var numBridges = document.getElementsByClassName("bridge").length
                if(numBridges > 1){
                    
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
                var numBridges = document.getElementsByClassName("bridge").length;
                if(numBridges  < 3) {
                    var bridgeDiv = buildBridgeElement();
                    bridges.append(bridgeDiv);
                    this.hidden = true;
                    addBridgeLabel.hidden = true;
                }
            }

        </script>
  
    </body> 
</html>