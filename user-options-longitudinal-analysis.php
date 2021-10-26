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
            lastClick = 'inspectionClick';
            selectedBridgeNames = <?php echo json_encode($_SESSION['selectedBridgeNames']); ?>;
            bridgeNames = [];
            inspectionData = [];
            ratings = [];
            pointColors = [];
            inspectionIndex = -1;
            prevInspectionIndex = -1;
            bridgeIndex = -1;
            prevBridgeIndex = -1;
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
                <h5> Select Up To 3 Bridges: </h5>
                <form action="">
                    <p id="bridges"><br>
                        <span id="bridge1">
                            <i id='search-icon' class="fa fa-search" aria-hidden="true"></i>
                            <input type="text" class="search-box border" placeholder="Search for a bridge">
                            <i id="confirm-search" class="fas fa-sign-in-alt"></i>
                            <i hidden='true' id="remove-bridge-1" class="far fa-minus-square"></i>
                            <br><br>
                        </span>
                    </p>
                    <i hidden='true' id="add-bridge" class="far fa-plus-square"></i>
                    <span hidden ='true' id="add-bridge-label">&nbspAdd Another Bridge</span>
                    <br>
                    <br>
                    <p>
                        
                        <button id='submit-btn' class="btn btn-secondary btn-sm disabled" type='button'>Submit</button>
                    </p>

                </form>
                <br><br>
                <h5 id='timeframe-label' hidden='true'>Select a Timeframe:</h5>
                <br>
                <form action="supervisor_longitudinal_analysis.php" method="POST">
                        <p>

                        <span id="begin-year" hidden='true'>
                            Begin:
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
                            End:
                            <select name="end" id="end-year" onchange="submitQueryParams();" onfocus="this.selectedIndex=-1;" required>
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
                        <button hidden=true id='submit-btn-year' class="btn btn-secondary btn-sm disabled" type='submit'>Submit</button>
                    </p>
                </form>
            </div>  
        </div>
        
        <script>


            
            

            isValid = false;
            var submitButton = document.getElementById('submit-btn');
            var submitButtonYear = document.getElementById('submit-btn-year');
            var bridges = document.getElementById('bridges');
            var bridge1 = document.getElementById('bridge1');
            var confirmSearch = document.getElementById('confirm-search');
            var searchIcon = document.getElementById('search-icon');
            var addBridge = document.getElementById('add-bridge');
            var addBridgeLabel = document.getElementById('add-bridge-label');
            var removeBridge1 = document.getElementById('remove-bridge-1');
            var inputTags = document.getElementsByTagName('input');
            var breaks = document.getElementsByTagName('br');
            removeIndex = 0;
            hasConfirmSearchIcon = true;
            
            submitButton.onclick = function() {
                if(isValid){
                    for(var i = 1 ; i < bridges.children.length ; i++){
                        console.log(bridges.children[i].children[0].value);
                    }
                    this.hidden = true;
                    addBridge.hidden = true;
                    addBridgeLabel.hidden = true;
                    removeBridge1.hidden = true;
                    var removeBridge2 = document.getElementById('remove-bridge-2');
                    var removeBridge3 = document.getElementById('remove-bridge-3');
                    if(removeBridge2){removeBridge2.hidden = true}
                    if(removeBridge3){removeBridge3.hidden = true}
                    document.getElementById('begin-year').hidden = false;
                    document.getElementById('timeframe-label').hidden = false;
                    document.getElementById('timeframe-label').hidden = false;
                    document.getElementById('submit-btn-year').hidden = false;
                }
            }

            submitButtonYear.onclick = function(){
               
            }

            confirmSearch.onclick = function(){
                //validate user input
                isValid = bridgeNames.includes(this.parentElement.children[1].value)        
                if(isValid){

                    this.parentElement.children[1].disabled=true;
                    this.parentElement.children[1].classList.remove("border-danger");
                    this.parentElement.children[1].classList.add("border-2");
                    this.parentElement.children[1].classList.add("border-success");
                    bridge1.removeChild(searchIcon);
                    bridge1.removeChild(this);
                    hasConfirmSearchIcon = false;
                    addBridge.hidden = false;
                    addBridgeLabel.hidden = false;
                    removeBridge1.hidden = false;
                    document.getElementById('submit-btn').classList.remove('disabled');
                    document.getElementById('submit-btn').classList.remove('btn-secondary');
                    document.getElementById('submit-btn').classList.add('btn-primary');
                } else {
                    this.parentElement.children[1].classList.remove("border-success");
                    this.parentElement.children[1].classList.add("border-danger");
                }
            }

            removeBridge1.onclick = function() {
                if(bridges.children.length > 2){
                    if(bridges.children.length  <= 4 && !hasConfirmSearchIcon){
                        addBridge.hidden = false;
                        addBridgeLabel.hidden = false;
                    } 
                    
                    bridges.removeChild(bridge1);
                    removeIndex -= 1;
                }
            }
            
            
            addBridge.onclick = function(){
                isValid = false;
                document.getElementById('submit-btn').classList.remove('btn-primary');
                document.getElementById('submit-btn').classList.add('disabled');
                document.getElementById('submit-btn').classList.add('btn-secondary');
                removeIndex += 1;
                if(bridges.children.length  <= 3) {

                    var bridgeSpan = document.createElement('span');
                    var searchIcon = document.createElement('icon');
                    var searchInput = document.createElement('input');
                    var confirmSearchIcon = document.createElement('icon');


                    searchIcon.setAttribute('class', 'fa fa-search')
                    searchIcon.setAttribute('aria-hidden', 'true');
                    searchIcon.setAttribute('id', 'search-icon');
                    searchInput.setAttribute('type', 'text');
                    searchInput.setAttribute('class', 'search-box border');
                    searchInput.setAttribute('placeholder', 'Search for a bridge');
                    confirmSearchIcon.setAttribute('id', 'confirm-search');
                    confirmSearchIcon.setAttribute('class', 'fas fa-sign-in-alt');
                    bridgeSpan.setAttribute('id', 'bridge' + bridges.children.length);

                    this.hidden = true;
                    addBridgeLabel.hidden = true;
    
                    bridgeSpan.appendChild(searchIcon);
                    bridgeSpan.appendChild(searchInput);
                    bridgeSpan.appendChild(confirmSearchIcon);
                    bridgeSpan.appendChild(document.createElement('br'));
                    bridgeSpan.appendChild(document.createElement('br'));
                    bridges.appendChild(bridgeSpan);
                    hasConfirmSearchIcon = true;

                    confirmSearchIcon.onclick = function(){
                        //validate user input
                        isValid = bridgeNames.includes(this.parentElement.children[1].value)
                        if(isValid){
                            this.parentElement.children[1].disabled=true;
                            this.parentElement.children[1].classList.remove("border-danger");
                            this.parentElement.children[1].classList.add("border-2");
                            this.parentElement.children[1].classList.add("border-success");
                            document.getElementById('submit-btn').classList.remove('disabled');
                            document.getElementById('submit-btn').classList.remove('btn-secondary');
                            document.getElementById('submit-btn').classList.add('btn-primary');
                            bridgeSpan.removeChild(bridgeSpan.children[bridgeSpan.children.length - 1]);
                            bridgeSpan.removeChild(bridgeSpan.children[bridgeSpan.children.length - 1]);
                            bridgeSpan.removeChild(searchIcon);
                            bridgeSpan.removeChild(this);
                            hasConfirmSearchIcon = false;
                            var removeBridgeIcon = document.createElement('icon');
                            removeBridgeIcon.setAttribute('id', 'remove-bridge-'+ (removeIndex + 1) );
                            console.log('remove-bridge'+removeIndex)
                            removeBridgeIcon.setAttribute('class', 'far fa-minus-square');
                            bridgeSpan.appendChild(removeBridgeIcon);
                            bridgeSpan.appendChild(document.createElement('br'));
                            bridgeSpan.appendChild(document.createElement('br'));
                            console.log(bridges.children.length )
                            
                            if(bridges.children.length <= 3){
                                addBridge.hidden = false;
                                addBridgeLabel.hidden = false;
                            } 
                            
                            removeBridgeIcon.onclick = function() {
                                if(bridges.children.length > 2){
                                    if(bridges.children.length  <= 4 && !hasConfirmSearchIcon){
                                        addBridge.hidden = false;
                                        addBridgeLabel.hidden = false;
                                    } 
                                    
                                    bridges.removeChild(bridgeSpan);
                                    removeIndex -= 1;
                                }
                                
                            }
                        } else {
                            this.parentElement.children[1].classList.remove("border-success");
                            this.parentElement.children[1].classList.add("border-danger");
                        }
                        
                    }
                }
            }

                
            

        </script>
           
    </body>
</html>