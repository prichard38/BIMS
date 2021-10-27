<?php

    session_start();

    // if($_SESSION["loggedAs"] != "Supervisor"){
    //     header("Location:access_denied.php?error=supervisorsonly");
    //     die();
    // }

    // Later we will get thee bridge names by POST after user has selected bridges, then set them as session vars
   $_SESSION["selectedBridgeNames"] = ['Cane Hill Bridge over Little Red River', 'Robert C. Byrd Bridge over Ohio River', 'East Huntington Bridge over Ohio River'];
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
        <!-- init global vars -->
        <script>
            bridgeNames = [];
        </script>

        <script>
            fetchAllBridgeData().then(
                (res) => {
                    res.data.forEach(obj => {
                    bridgeNames.push(obj['bridgeName'])
                });
            })
        </script>

        <script>
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

            function updateBridgeIds(){
                var bridges = document.getElementsByClassName("bridge");
                for(var i = 0 ; i < bridges.length ; i++){
                    bridges[i].children[0].innerHTML = "Bridge " + (i+1);
                }
            }

            function updateConfirmationCount(count){
                var countElement = document.getElementById('confirmation-count');
                countElement.innerHTML = count;
                if(count > 0){
                    console.log("green")
                    countElement.classList.remove('text-danger');
                    countElement.classList.add('text-success');
                } else{
                    console.log("red")
                    countElement.classList.remove('text-success');
                    countElement.classList.add('text-danger');
                }
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
                    <li style="background-color: #5e5e5e;"><a id="RM" href='supervisor_longitudinal_analysis.php'>Report Management</a>
                        <ul class="submenu">
                            <li style="background-color: #5e5e5e;">
                                <a id="RM" href='supervisor_yearly_inspection_report.php'>Yearly Inspection Report</a>
                            </li>
                            <li style="background-color: #5e5e5e;">
                                <a id="RM" href='supervisor_longitudinal_analysis.php'>Longitudinal Analysis</a>
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
                <p><strong>First, select up to 3 bridges to analyze.</strong> </p>
                <p><strong>Then, after your bridge selections are submitted, you will be prompted to provide a time period for the analysis.</strong></p>
                <br>
                <p>* <em>To confirm a bridge selection, click the "enter" icon to the right of the selection.</em></p>
                <p>* <em>To remove a bridge selection, click the "dash" icon to the right of the selection.</em></p>
                <br>  
                <br>              
                <h6>Select Up To 3 Bridges:</h6>
                <form>
                    <div id="bridges">
                        <br>
                        <div id="bridge1" class="bridge">
                            <h6>Bridge 1</h6>
                            <p>
                                <i style="margin-right: 0.3rem;" id='search-icon-1' class="fa fa-search option-icon" aria-hidden="true"></i>
                                <input style="margin-right: 0.3rem;" type="text" class="search-box border" placeholder="Search for a bridge">
                                <i style="margin-left: 0.3rem;" id="confirm-search-1" class="fas fa-sign-in-alt option-icon"></i>
                                <i style="margin-left: 0.08rem;" hidden='true' id="remove-bridge-1" class="far fa-minus-square option-icon"></i>
                                <br><br>
                            </p>
                        </div>
                    </div>
                    <i hidden='true' id="add-bridge" class="far fa-plus-square"></i>
                    <span hidden ='true' id="add-bridge-label">&nbspAdd Another Bridge</span>
                    <br>
                    <br>
                    <p>
                        <br>
                        <h6 id="confirmation-message">You have confirmed <span id="confirmation-count" class="text-danger">0</span> bridge selections.</h6> 
                        <br>
                        <button id='submit-btn-bridges' class="btn btn-secondary btn-sm disabled" type='button'>Submit Bridge Selections</button>
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

            nextBridgeIndex = 1;
            awaitingConfirmation = true;
            isValid = false;
            numConfirmed = 0;
            
            

            var submitBridgeSelectionsButton = document.getElementById('submit-btn-bridges');
            var submitButtonYear = document.getElementById('submit-btn-years');
            var bridges = document.getElementById('bridges');
            var bridge1 = document.getElementById('bridge1');
            var confirmSearch1 = document.getElementById('confirm-search-1');
            var searchIcon1 = document.getElementById('search-icon-1');
            var addBridge = document.getElementById('add-bridge');
            var addBridgeLabel = document.getElementById('add-bridge-label');
            var removeBridge1 = document.getElementById('remove-bridge-1');
            
            submitBridgeSelectionsButton.onclick = function() {
                if(isValid){
                    // remove icons from all bridge inputs so no more changes can be made
                    var icons = document.getElementsByClassName("option-icon");
                    for(var i = 0 ; i < icons.length ; i++){
                        icons[i].hidden = true;
                    }
                    // hide the "submit bridge selections" button
                    this.remove()

                    // hide the add bridge icon and label
                    addBridge.remove()
                    addBridgeLabel.remove()
           
                    // show the begin year selector
                    document.getElementById('begin-year').hidden = false;
                    document.getElementById('timeframe-instructions').hidden = false;
                    document.getElementById('submit-btn-years').hidden = false;
                }
            }

            submitButtonYear.onclick = function(){
               // TODO: Get the bridge names, begin year, and end year and create PHP Session variables.
               // $_SESSION["selectedBridgeNames"] $_SESSION["yearBegin"] $_SESSION["yearEnd"]
            }

            confirmSearch1.onclick = function(){
                //validate user input
                isValid = bridgeNames.includes(this.parentElement.children[1].value)        
                if(isValid){
                    awaitingConfirmation = false;
                    nextBridgeIndex++;
                    numConfirmed++;
                    updateConfirmationCount(numConfirmed);
                    showValidFeedback(this.parentElement.children[1]);
                    searchIcon1.remove();
                    this.remove();
                    hasConfirmSearchIcon = false;
                    addBridge.hidden = false;
                    addBridgeLabel.hidden = false;
                    removeBridge1.hidden = false;
                    enableButton(document.getElementById('submit-btn-bridges'));
                } else {
                    showInvalidFeedback(this.parentElement.children[1]);
                }
            }

            removeBridge1.onclick = function() {
                // console.log(document.getElementsByClassName("bridge").length);
                var numBridges = document.getElementsByClassName("bridge").length
                if(numBridges > 1){
                    
                    bridge1.remove();
                    updateBridgeIds();
                    if(!awaitingConfirmation){
                        nextBridgeIndex -= 1;
                        numConfirmed -=1;
                        updateConfirmationCount(numConfirmed);
                    } else{
                        awaitingConfirmation = false;
                    }
                    if((numBridges-1) < 3 && !awaitingConfirmation){
                        addBridge.hidden = false;
                        addBridgeLabel.hidden = false;
                    } 
                } else{
                    alert("You must select at least one bridge.")
                }
            }
            
            
            addBridge.onclick = function(){
                isValid = false;
                disableButton(document.getElementById('submit-btn-bridges'))
                var numBridges = document.getElementsByClassName("bridge").length;
                if(numBridges  < 3) {

                    var bridgeDiv = document.createElement('div');
                    bridgeDiv.setAttribute('id', ('bridge'+nextBridgeIndex));
                    bridgeDiv.setAttribute('class', 'bridge');
                    
                    var bridgeHeader = document.createElement('h6');
                    bridgeHeader.innerHTML = 'Bridge ' + nextBridgeIndex;
                    console.log(bridgeHeader.innerHTML);
                    
                    var bridgeParagraph = document.createElement('p');
                    
                    var searchIcon = document.createElement('i');
                    searchIcon.setAttribute('class', 'fa fa-search option-icon')
                    searchIcon.setAttribute('aria-hidden', 'true');
                    searchIcon.setAttribute('id', 'search-icon');
                    searchIcon.setAttribute('style', 'margin-right: 0.3rem;');

                    var searchInput = document.createElement('input');
                    searchInput.setAttribute('style', 'margin-right: 0.3rem;');
                    searchInput.setAttribute('type', 'text');
                    searchInput.setAttribute('class', 'search-box border');
                    searchInput.setAttribute('placeholder', 'Search for a bridge');

                    var confirmSearchIcon = document.createElement('i');
                    confirmSearchIcon.setAttribute('id', 'confirm-search');
                    confirmSearchIcon.setAttribute('class', 'fas fa-sign-in-alt option-icon');
                    confirmSearchIcon.setAttribute('style', 'margin-left: 0.3rem; margin-right: 0.6rem');

                    var removeBridgeIcon = document.createElement('i');
                    removeBridgeIcon.setAttribute('id', ('remove-bridge-'+ nextBridgeIndex) );
                    removeBridgeIcon.setAttribute('class', 'far fa-minus-square option-icon');
                    removeBridgeIcon.setAttribute('style', 'margin-left: 0.3rem;');

                    this.hidden = true;
                    addBridgeLabel.hidden = true;
    
                    bridgeParagraph.appendChild(searchIcon);
                    bridgeParagraph.appendChild(searchInput);
                    bridgeParagraph.appendChild(confirmSearchIcon);
                    bridgeParagraph.appendChild(removeBridgeIcon);
                    bridgeParagraph.appendChild(document.createElement('br'));
                    bridgeParagraph.appendChild(document.createElement('br'));
                    
                    bridgeDiv.appendChild(bridgeHeader);
                    bridgeDiv.appendChild(bridgeParagraph);
                    bridges.append(bridgeDiv);

                    removeBridgeIcon.onclick = function() {
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
                            }
                            if((numBridges-1) < 3 && !awaitingConfirmation){
                                addBridge.hidden = false;
                                addBridgeLabel.hidden = false;
                            }
                        } else {
                            alert("You must select at least one bridge");
                        }
                    }
                    
                    awaitingConfirmation = true;

                    confirmSearchIcon.onclick = function(){
                        //validate user input
                        isValid = bridgeNames.includes(this.parentElement.children[1].value)
                        if(isValid){
                            awaitingConfirmation = false;
                            nextBridgeIndex++;
                            numConfirmed++;
                            updateConfirmationCount(numConfirmed);
                            showValidFeedback(this.parentElement.children[1]);
                            enableButton(document.getElementById('submit-btn-bridges'))
                            // bridgeDiv.removeChild(bridgeSpan.children[bridgeSpan.children.length - 1]);
                            // bridgeDiv.removeChild(bridgeSpan.children[bridgeSpan.children.length - 1]);
                            searchIcon.remove();
                            this.remove();
                            
                            var numBridges = document.getElementsByClassName("bridge").length;
                            if(numBridges < 3){
                                addBridge.hidden = false;
                                addBridgeLabel.hidden = false;
                            } 
                        } else {
                            showInvalidFeedback(this.parentElement.children[1]);
                        }
                        
                    }
                }
            }

                
            

        </script>
           
    </body>
</html>