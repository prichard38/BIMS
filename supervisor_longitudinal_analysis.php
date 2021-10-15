<?php

    session_start();

    if($_SESSION["loggedAs"] != "Supervisor"){
        header("Location:access_denied.php?error=supervisorsonly");
        die();
    }

    // Later we will get thee bridge names by POST after user has selected bridges, then set them as session vars
   $_SESSION["bridgeNames"] = ['Cane Hill Bridge over Little Red River', 'Robert C. Byrd Bridge over Ohio River', 'East Huntington Bridge over Ohio River'];
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
        <script src="load-table.js"></script>
        <script src="fetch-inspections.js"></script>

        <title>Bridge Management</title>
    </head>
    
    <body>

        <!-- Load inspection data for each bridge after user has selected bridges -->
        <script type="text/javascript">
            $(document).ready(function () {
                console.log("loading inspeections from db")
                console.log(<?php echo $_SESSION['hasData_bridge1'];?>)
                console.log(<?php echo $_SESSION['hasData_bridge2'];?>)
                console.log(<?php echo $_SESSION['hasData_bridge3'];?>)
                $.ajax({
                    type: "POST",
                    url: "load-inspections-test.php",
                })
            });
         </script>

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
                    <!-- <li><a id="UM" href='#'>User Management</a></li> -->
                </ul>
            </div>
        </div>

        <!-- <div class="box">
            <input type="text" id="formGroupExampleInput" placeholder="Search...">
            <i class="fa fa-search" aria-hidden="true"></i>
        </div> -->
        

        <div class="container">
            <div class="main_title">
                <h5> Report Management </h5>
                <p><br>
                    Bridge: 
                    <i class="fa fa-search" aria-hidden="true"></i>
                    <input type="text" class="search-box" placeholder="Search for a bridge...">
                    Year:
                    <select name="year" id="year_selector" required>
                        <option value="2021" selected="selected">2021</option>
                        <option value="2020">2020</option>
                        <option value="2019">2019</option>
                        <option value="2018">2018</option>
                        <option value="2017">2017</option>
                    </select>
                    Year:
                    <select name="year" id="year_selector" required>
                        <option value="2021" selected="selected">2021</option>
                        <option value="2020">2020</option>
                        <option value="2019">2019</option>
                        <option value="2018">2018</option>
                        <option value="2017">2017</option>
                    </select>
                </p>
            </div>  

            <!-- Main contents -->
            <section class="content cbox" id="c2021">
                <div class="container-fluid">
                    <div class="contents">
                        <div class="row">
                            <div class="col-md-12">
                              <div class="card">
                                <div class="card-header">
                                  <h5 class="card-title">Longitudinal Analysis (2016 - 2021)</h5>
                                  
                                  <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                      <i class="fas fa-minus"></i>
                                    </button>
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-wrench"></i>
                                      </button>
                                      <div class="dropdown-menu dropdown-menu-right" role="menu">
                                        <a href="#" class="dropdown-item">Action</a>
                                        <a href="#" class="dropdown-item">Another action</a>
                                        <a href="#" class="dropdown-item">Something else here</a>
                                        <a class="dropdown-divider"></a>
                                        <a href="#" class="dropdown-item">Separated link</a>
                                      </div>
                                    </div>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                      <i class="fas fa-times"></i>
                                    </button>
                                  </div>
                                 
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                  <div class="row" style="align-items: center;">
                                    <div class="col-sm-4 col-md-4">
                                        <div class="chart-responsive">
                                            <canvas id="lineChart" height="200"></canvas>
                                        </div>
                                        <div>
                                            <table id="InspectionStatus" class="table table-sm"> 
                                                <tr><td></td><td></td><td></td></tr>                            
                                                <tr>
                                                    <td class="txtl"><i class="fas fa-circle" style="color: red;"></i> High Risk (1 - 3) </td>
                                                    <td class="txtl"><i class="fas fa-circle" style="color: #ffea00;"></i> Middle Risk (4 - 6) </td>
                                                    <td class="txtl"><i class="fas fa-circle" style="color: green;"></i> Low Risk (7 - 9) </td>
                                                </tr>
                                             </table>
                                        </div>
                                        <div style="font-size: 0.8em; text-align: center; margin: 5px 0;">
                                            Click a node on the graph above to see inspection details.
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-8 col-md-8">
                                        <div class="table-responsive">
                                            <table id="InspectionStatus" class="table table-sm">
                                                <h6>Selected Bridges</h6>
                                                <tr>
                                                    <th class="txtl">Bridge Name</th>
                                                    <th class="txtl">Bridge Number</th>
                                                    <th class="txtl">County</th>

                                                </tr>
                                                <tr id="bridge1">
                                                    <td class="bridge-name txtl"><i class="fas fa-circle" style="color: darkgrey;"></i> Cane Hill Bridge over Little Red River </td>
                                                    <td class="bridge-no txtl">001-4/5-2.95(01810)</td>
                                                    <td class="txtl">Wyoming</td>
                                                </tr>
                                                <tr id="bridge2">
                                                    <td class="bridge-name txtl"><i class="fas fa-circle" style="color: navy;"></i> Robert C. Byrd Bridge over Ohio River </td>
                                                    <td class="bridge-no txtl">006-3/4-8.65(03148)</td>
                                                    <td class="txtl">Cabell</td>
                                                </tr>
                                                <tr id="bridge3">
                                                    <td class="bridge-name txtl"><i class="fas fa-circle" style="color: steelblue;"></i> East Huntington Bridge over Ohio River </td>
                                                    <td class="bridge-no txtl">004-4/5-2.95(01210)</td>
                                                    <td class="txtl">Cabell</td>
                                                </tr>
                                                <tr class="ttlcolor">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr class="ttlcolor">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                
                                            </table>
                                            
                                            <div style="font-size: 0.8em; text-align: center; margin: 5px 0;">
                                                Click on a bridge to see a list of its inspections
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                  </div>
                                  <!-- /.row -->
                                </div>
                                <!-- ./card-body -->
                              </div>
                              <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->


                        <!-- High Risk (1-3) -->
                       
                        <div class="row tbox" id="rm_t1">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Inspection List</h5>
                                        <br>
                                        <h6 style="font-size: small; font-weight: bold; color: darkgrey"><span id="bridgeName1"></span></h6>
                                        <script>
                                        var bridgeName = $('#bridge1 .bridge-name').text();
                                        document.getElementById('bridgeName1').innerHTML = bridgeName;
                                        </script>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div style="padding: 10px; overflow: auto; min-width: 400px;">
                                                    <table style="width:100%" class="table table-sm" id="tbl_bridge_insp_t1">
                                                        <thead>
                                                            <tr>
                                                                <th data-orderable="true">Completed on</th>
                                                                <th>Bridge Number</th>
                                                                <th>Bridge Name</th>
                                                                <th>Type</th>
                                                                <th>Assigned To</th>
                                                                <th>Assigned By</th>
                                                                <th>Rate</th>
                                                                <th data-orderable="false">Bridge<br>Elements</th>
                                                                <th data-orderable="false">Report</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="inspection-list">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                        <!-- /.row -->
                                    </div>
                                    <!-- ./card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                        


                        <!-- Middle Risk (4-6) -->
                        <div class="row tbox" id="rm_t2">
                          <div class="col-md-12">
                              <div class="card">
                                  <div class="card-header">
                                      <h5 class="card-title">Inspection List</h5>
                                      <br>
                                        <h6 style="font-size: small; font-weight: bold; color: navy"><span id="bridgeName2"></span></h6>
                                        <script>
                                        var bridgeName = $('#bridge2 .bridge-name').text();
                                        document.getElementById('bridgeName2').innerHTML = bridgeName;
                                        </script>
                                  </div>
                                  <!-- /.card-header -->
                                  <div class="card-body">
                                      <div class="row">
                                          <div class="col-md-12">
                                              <div style="padding: 10px; overflow: auto; min-width: 400px;">
                                                  <table style="width:100%" class="table table-sm" id="tbl_bridge_insp_t2">
                                                  <thead>
                                                            <tr>
                                                                <th data-orderable="true">Completed on</th>
                                                                <th>Bridge Number</th>
                                                                <th>Bridge Name</th>
                                                                <th>Type</th>
                                                                <th>Assigned To</th>
                                                                <th>Assigned By</th>
                                                                <th>Rate</th>
                                                                <th data-orderable="false">Bridge<br>Elements</th>
                                                                <th data-orderable="false">Report</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="inspection-list">
                                                        </tbody>
                                                  </table>
                                              </div>
                                          </div>
                                          <!-- /.col -->
                                      </div>
                                      <!-- /.row -->
                                  </div>
                                  <!-- ./card-body -->
                              </div>
                              <!-- /.card -->
                          </div>
                          <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- Low Risk (6-9) -->
                        <div class="row tbox" id="rm_t3">
                          <div class="col-md-12">
                              <div class="card">
                                  <div class="card-header">
                                      <h5 class="card-title">Inspection List</h5>
                                      <br>
                                        <h6 style="font-size: small; font-weight: bold; color: steelblue"><span id="bridgeName3"></span></h6>
                                        <script>
                                        var bridgeName = $('#bridge3 .bridge-name').text();
                                        document.getElementById('bridgeName3').innerHTML = bridgeName;
                                        </script>
                                  </div>
                                  <!-- /.card-header -->
                                  <div class="card-body">
                                      <div class="row">
                                          <div class="col-md-12">
                                              <div style="padding: 10px; overflow: auto; min-width: 400px;">
                                                  <table style="width:100%" class="table table-sm" id="tbl_bridge_insp_t3">
                                                  <thead>
                                                            <tr>
                                                                <th data-orderable="true">Completed on</th>
                                                                <th>Bridge Number</th>
                                                                <th>Bridge Name</th>
                                                                <th>Type</th>
                                                                <th>Assigned To</th>
                                                                <th>Assigned By</th>
                                                                <th>Rate</th>
                                                                <th data-orderable="false">Bridge<br>Elements</th>
                                                                <th data-orderable="false">Report</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="inspection-list">
                                                        </tbody>
                                                  </table>
                                              </div>
                                          </div>
                                          <!-- /.col -->
                                      </div>
                                      <!-- /.row -->
                                  </div>
                                  <!-- ./card-body -->
                              </div>
                              <!-- /.card -->
                          </div>
                          <!-- /.col -->
                        </div>
                        <!-- /.row -->




            <!-- Modals -->
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="BridgeElementModalLabel">Bridge Elements Overview</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="row">
                            <div class="col-12" style="display: flex; justify-content: center;">
                                <model-viewer src="assets/Model060721.glb" id="mdl" camera-controls exposure="0.72" shadow-intensity="0" camera-orbit="27.23deg 78.62deg 181.9m" min-camera-orbit="auto auto auto" max-camera-orbit="auto auto 181.9m" style="width: 100%;">
                                    <button class="Hotspot hs4" id="hts2" slot="hotspot-1" data-position="-10.1739862999297m 7.2626670004309535m 45.891232591660675m" data-normal="-0.32664745787836463m 0.017188132570477133m 0.9449899504017711m" data-visibility-attribute="visible" onmouseover="highlightElement('2')" onmouseout="unhighlightElement('2')">
                                        <!-- <div class="HotspotAnnotation">FLOOR BEAM</div> --></button>
                                    <button class="Hotspot hs4" id="hts3" slot="hotspot-2" data-position="3.088437165177318m 6.05021350274236m 16.747897238153026m" data-normal="0.9438577140777109m 0.05812376415448013m 0.32519877554583854m" data-visibility-attribute="visible" onmouseover="highlightElement('3')" onmouseout="unhighlightElement('3')">
                                        <!-- <div class="HotspotAnnotation">BEARINGS</div> --></button>
                                    <button class="Hotspot hs3" id="hts5" slot="hotspot-3" data-position="-3.82146871327482m 8.02698021550583m 15.617830659215763m" data-normal="-0.04933677729065374m 0.9981614058038522m -0.03520923700182004m" data-visibility-attribute="visible" onmouseover="highlightElement('5')" onmouseout="unhighlightElement('5')">
                                        <!-- <div class="HotspotAnnotation">DECK 1</div> --></button>
                                    <button class="Hotspot hs2" id="hts6" slot="hotspot-4" data-position="2.262199517661566m 7.769528697696511m -0.20548165891285208m" data-normal="-0.04933677729065374m 0.9981614058038522m -0.03520923700182004m" data-visibility-attribute="visible" onmouseover="highlightElement('6')" onmouseout="unhighlightElement('6')">
                                        <!-- <div class="HotspotAnnotation">DECK 2</div> --></button>
                                    <button class="Hotspot hs1" id="hts4" slot="hotspot-5" data-position="-0.16873490245083111m 7.198973951171171m 26.630652624436223m" data-normal="0.9085017010904776m 0.28141701591106116m 0.30891604404987133m" data-visibility-attribute="visible" onmouseover="highlightElement('4')" onmouseout="unhighlightElement('4')">
                                        <!-- <div class="HotspotAnnotation">GIRDER</div> --></button>
                                    <button class="Hotspot hs3" id="hts1" slot="hotspot-6" data-position="13.879840339710691m 3.0224059784682638m -13.145880733396405m" data-normal="0.9438577140777108m 0.05812376415448009m 0.3251987755458384m" data-visibility-attribute="visible" onmouseover="highlightElement('1')" onmouseout="unhighlightElement('1')">
                                        <!-- <div class="HotspotAnnotation">PIER</div> --></button>
                                </model-viewer>
                            </div>
                            <div class="modal_3D_desc">
                                Please resize and rotate 3D bridge model using your mouse/finger above.<br>You can click any colored dot in 3D bridge model  or any element in the table to highlight each other.
                            </div>
                        </div>

                        <div class="row" style="margin: 10px 10px; overflow-x:scroll;">
                            <div class="col-md-12">
                                <h6 style="color: rgb(13, 60, 121);">Element List</h6>
                                <div>
                                    <table class="table table-sm table-hover myTable" id="insp_bridge_ele2">
                                        <thead class="thead">
                                            <tr>
                                                <th>Class</th> 
                                                <th>Category</th>
                                                <th>Material</th>
                                                <th>Type</th>
                                                <th>Name</th>
                                                <th>Rate</th>
                                            </tr>
                                        </thead>
                                        <tr data-bs-toggle="modal" data-bs-target="" data-bs-id="1" onmouseover="highlightHotspot('1')" onmouseout="unhighlightHotspot('1')">
                                            <td> NBE </td>
                                            <td> Substructure </td>
                                            <td> PSC </td>
                                            <td> Closed Web/Box Girder (102) LF </td>
                                            <td> PIER </td>
                                            <td> 5 </td>
                                        </tr>
                                        <tr data-bs-toggle="modal" data-bs-target="" data-bs-id="2" onmouseover="highlightHotspot('2')" onmouseout="unhighlightHotspot('2')">
                                            <td> NBE </td>
                                            <td> Substructure </td>
                                            <td> PSC </td>
                                            <td> Floor Beam (152) LF </td>
                                            <td> FLOOR BEAM </td>
                                            <td> 9 </td>
                                        </tr>
                                        <tr data-bs-toggle="modal" data-bs-target="" data-bs-id="3" onmouseover="highlightHotspot('3')" onmouseout="unhighlightHotspot('3')">
                                            <td> NBE </td>
                                            <td> Superstructure </td>
                                            <td> Steel </td>
                                            <td> Closed Web/Box Girder (102) LF </td>
                                            <td> BEARINGS </td>
                                            <td> 8 </td>
                                        </tr>
                                        <tr data-bs-toggle="modal" data-bs-target="" data-bs-id="4" onmouseover="highlightHotspot('4')" onmouseout="unhighlightHotspot('4')">
                                            <td> NBE </td>
                                            <td> Superstructure </td>
                                            <td> PSC </td>
                                            <td> Girder Beam (107) LF </td>
                                            <td> GIRDER </td>
                                            <td> 1 </td>
                                        </tr>
                                        <tr data-bs-toggle="modal" data-bs-target="" data-bs-id="5" onmouseover="highlightHotspot('5')" onmouseout="unhighlightHotspot('5')">
                                            <td> NBE </td>
                                            <td> Deck </td>
                                            <td> PSC </td>
                                            <td> Closed Web/Box Girder (102) LF </td>
                                            <td> ONE </td>
                                            <td> 5 </td>
                                        </tr>
                                        <tr data-bs-toggle="modal" data-bs-target="" data-bs-id="6" onmouseover="highlightHotspot('6')" onmouseout="unhighlightHotspot('6')">
                                            <td> NBE </td>
                                            <td> Deck </td>
                                            <td> PSC </td>
                                            <td> Closed Web/Box Girder (102) LF </td>
                                            <td> TWO </td>
                                            <td> 4 </td>
                                        </tr>
                                    </table>
                                </div>
                                <br>
                            </div>
                            <!--
                            <div class="modal-footer">
                                <button type="button" class="btnset_insp btn_back" data-bs-dismiss="modal">Close</button>
                            </div>
                            -->
                      </div>
                        
                    </div>
                </div>
            </div>
            <!-- Inspector Contact Details Modal -->
            <div class="modal fade" tabindex="-1" role="dialog" id="inspector_contact_modal">
              <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                      <div class="modal-header"><h5 class="modal-title">Inspector Contact Details</h5></div>
                      <div class="modal-body">
                          <div class="form-group txtleft">
                              <label>Name</label><br>
                              <label for="inspector_name"><input type="text" id="inspector_name" class="form-control" value="" disabled></label>
                          </div>
                          <div class="form-group txtleft">
                              <label>Phone</label><br>
                              <label for="inspector_phone"><input type="text" id="inspector_phone" class="form-control" value="304-000-0000" disabled></label>
                          </div>
                          <div class="form-group txtleft">
                              <label>Email</label><br>
                              <label for="inspector_email"><input type="text" id="inspector_email" class="form-control" value="" disabled></label>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btnset btn_ccl" data-bs-dismiss="modal">Close</button>
                      </div>
                  </div>
              </div>
            </div>
        </div>

        <!-- Cards -->
        <!--<script src="plugins/CardWidget.js"></script>-->

        <!-- Charts -->
        <script>
            var jitterEffectPlugin = {
                        beforeDatasetDraw: function (ctx, args) {
                            if (ctx.animating) {
                                var _args = args,
                                dataIndex = _args.index,
                                meta = _args.meta;
                                var points = meta.data.map(function (el) {
                                  return {
                                    x: el._model.x,
                                    y: el._model.y
                                  };
                                });
                                var dsLength = ctx.data.datasets.length;
                                var adjustedMap = []; // keeps track of adjustments to prevent double offsets
        
                                for (var datasetIndex = 0; datasetIndex < dsLength; datasetIndex += 1) {
                                  if (dataIndex !== datasetIndex) {
                                    var datasetMeta = ctx.getDatasetMeta(datasetIndex);
                                    datasetMeta.data.forEach(function (el) {
                                      var overlapFilter = points.filter(function (point) {
                                        return point.x === el._model.x && point.y === el._model.y;
                                      });

                                      var overlap = false;
                                      var overObj = JSON.parse(JSON.stringify(overlapFilter));
                                      for (var i = 0; i < overObj.length; i++) {
                                          if(overObj[i]['x'] === el._model.x && overObj[i]['y'] === el._model.y){
                                            overlap = true;
                                            break;
                                          }
                                      }
                                      if (overlap) {
                                        var adjusted = false;
                                        var adjustedFilter = adjustedMap.filter(function (item) {
                                            return item.datasetIndex === datasetIndex && item.dataIndex === dataIndex;
                                        });
                                        var adjObj = JSON.parse(JSON.stringify(adjustedFilter));
                                          for (var i = 0; i < adjObj.length; i++) {
                                              if(adjObj[i]['datasetIndex'] === datasetIndex && adjObj[i]['dataIndex'] === dataIndex){
                                                adjusted = true;
                                                break;
                                              }
                                          }

                                        if (!adjusted && datasetIndex % 2) {
                                          el._model.x += 6;
                                        } else {
                                          el._model.x -= 6;
                                        }
        
                                        adjustedMap.push({
                                          datasetIndex: datasetIndex,
                                          dataIndex: dataIndex
                                        });
                                      }
                                    });
                                  }
                                }
                            }
                        }
                    }
            $(function () {
                'use strict'

                //var origHeight = "calc(100vh - 58px)";
                var origHeight = $('.sidebar').height();
                var contHeight_before = "";
                var contHeight_after = "";
                var sideHeight = "";

                /* LINE CHART */
                // 2021
                var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
                var bridge1Inspections;
                var bridge1Ratings;
                var bridge2Inspections
                var bridge2Ratings;
                var bridge3Inspections;
                var bridge3Ratings;
                if(<?php echo $_SESSION['hasData_bridge1'];?>){
                    bridge1Inspections = fetchInspections('bridge1');
                    bridge1Ratings = getRatings(bridge1Inspections);
                }
                if(<?php echo $_SESSION['hasData_bridge2'];?>){
                    bridge2Inspections = fetchInspections('bridge2');
                    bridge2Ratings = getRatings(bridge2Inspections);
                }
                if(<?php echo $_SESSION['hasData_bridge3'];?>){
                    bridge3Inspections = fetchInspections('bridge3');
                    bridge3Ratings = getRatings(bridge3Inspections);
                }

                var inspectionIndex;
                var prevInspectionIndex;
                var bridgeIndex;
                var prevBridgeIndex;
                var bridgeLabels = <?php echo json_encode($_SESSION['bridgeNames']); ?>;
                console.log(bridgeLabels)
                var lineData = {
                    // need to get labels from SESSION variable that is created after user selects years
                    labels: [
                        '2016',
                        '2017',
                        '2018',
                        '2019',
                        '2020',
                        '2021'
                    ],
                    datasets: [
                    {
                        label: bridgeLabels[0],
                        data: bridge1Ratings,
                        backgroundColor: 'rgba(255, 255, 255, 0)',
                        pointBackgroundColor: ['green', '#32b502', '#ffea00', '#ff0000', '#32b502', '#32b502'],
                        borderColor: 'darkgrey',
                        radius: 6
                    },
                    {
                        label: bridgeLabels[1],
                        data: bridge2Ratings,
                        backgroundColor: 'rgba(255, 255, 255, 0)',
                        pointBackgroundColor: ['#ffea00','#ffea00','#ffea00','#ffea00','#ffea00','#ffea00'],
                        borderColor: 'navy',
                        radius: 6
                    },
                    {
                        label: bridgeLabels[2],
                        data: bridge3Ratings,
                        backgroundColor: 'rgba(255, 255, 255, 0)',
                        pointBackgroundColor: ['#ffea00','#ffea00','#32b502','#32b502','#32b502','#32b502'],
                        borderColor: 'steelblue',
                        radius: 6
                    }
                    ]
                }
                var lineOptions = {
                    legend: {
                        display: false,
                    },
                    'onClick' : function (evt, item) {
                        
                        bridgeIndex = lineChart.getDatasetAtEvent(evt)[0]._datasetIndex + 1;
                        inspectionIndex = item[0]._index;
                        var inspection;

                        if(bridgeIndex == 1 && <?php echo $_SESSION['hasData_bridge1'];?>){
                            inspection = [bridge1Inspections.data[item[0]._index]];
                            loadTable('bridge1', inspection);
                        }
                        else if (bridgeIndex == 2 && <?php echo $_SESSION['hasData_bridge2'];?>){
                            inspection = [bridge2Inspections.data[item[0]._index]];
                            loadTable('bridge2', inspection);
                        }
                        else if (bridgeIndex == 3 && <?php echo $_SESSION['hasData_bridge3'];?>){
                            inspection = [bridge3Inspections.data[item[0]._index]];
                            loadTable('bridge3', inspection);
                        }
                        $(".tbox").not("#rm_t" + bridgeIndex).hide();
                        if(prevInspectionIndex == inspectionIndex || prevBridgeIndex != bridgeIndex){
                            $('#rm_t' + bridgeIndex).toggle();
                        }
                        
                        prevInspectionIndex = inspectionIndex;
                        prevBridgeIndex = bridgeIndex;


                        contHeight_after = $('.container').height();
                        sideHeight = $('.sidebar').height();
                        if (contHeight_after >= sideHeight) {
                            $('.sidebar').height(contHeight_after);
                            contHeight_before = $('.sidebar').height();
                        } else if (contHeight_after < sideHeight && contHeight_before == sideHeight) {
                            if (contHeight_after < origHeight) {
                                $('.sidebar').height(origHeight);
                            } else {
                                $('.sidebar').height(contHeight_after);
                            }
                            contHeight_before = $('.sidebar').height();
                        } else {
                            $('.sidebar').height(origHeight);
                            contHeight_before = $('.sidebar').height();
                        }
                    },
                    scales: {
                        yAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: "Inspection Rating"
                            }
                        }]

                        
                    }
                }
                var lineChart = new Chart(lineChartCanvas, {
                    type: 'line',
                    data: lineData,
                    options: lineOptions,
                    plugins: [jitterEffectPlugin]
                })

                // 2020
                // var pieChartCanvas2 = $('#pieChart2').get(0).getContext('2d')
                // var pieData2 = {
                //     labels: [
                //         'High Risk (1-3)',
                //         'Middle Risk (4-6)',
                //         'Low Risk (7-9)',
                //         'In-Progress',
                //         'Not Started'
                //     ],
                //     datasets: [
                //     {
                //         data: [2096, 1996, 3001, 71, 131],
                //         backgroundColor: ['#ff0000', '#ffea00', '#32b502', '#999999', '#f7f7f7']
                //     }
                //     ]
                // }
                // var pieOptions2 = {
                //     legend: {
                //         display: false
                //     },
                //     'onClick' : function (evt, item) {
                //         // //console.log('legend onClick', evt);
                //         // //console.log('legd item', item);
                //         // var e = item[0];
                //         // var e_idx = e._index + 1;
                //         // if(e_idx > 0){
                //         //   $(".tbox").not("#rm_tt" + e_idx).hide();
                //         //   $("#rm_tt" + e_idx).toggle();
                //         // } else{
                //         //   $(".tbox").hide();
                //         // }

                //         // contHeight_after = $('.container').height();
                //         // sideHeight = $('.sidebar').height();
                //         // if (contHeight_after >= sideHeight) {
                //         //     $('.sidebar').height(contHeight_after);
                //         //     contHeight_before = $('.sidebar').height();
                //         // } else if (contHeight_after < sideHeight && contHeight_before == sideHeight) {
                //         //     if (contHeight_after < origHeight) {
                //         //         $('.sidebar').height(origHeight);
                //         //     } else {
                //         //         $('.sidebar').height(contHeight_after);
                //         //     }
                //         //     contHeight_before = $('.sidebar').height();
                //         // } else {
                //         //     $('.sidebar').height(origHeight);
                //         //     contHeight_before = $('.sidebar').height();
                //         // }
                //     }
                // }
                // var pieChart2 = new Chart(pieChartCanvas2, {
                //     type: 'pie',
                //     data: pieData2,
                //     options: pieOptions2
                // })
            })
        </script>
        <!-- ChartJS -->
        <script src="plugins/chart.js/Chart.js"></script>
        <script src="plugins/adminlte.js"></script>
        
        <!-- sidebar size -->   
        <script language="JavaScript" type="text/javascript">
            $(document).ready(function(){ 
                $(".tbox").hide();

                var origHeight = "calc(100vh - 58px)";
                var contHeight = $('section').height();
                var sideHeight = $('.sidebar').height();

                if (contHeight > sideHeight) {
                    $('.sidebar').height(contHeight);
                } else {
                    $('.sidebar').height(origHeight);
                }
            });
        </script>

        <!-- Tables -->
        <script>
            $(document).ready(function(){
                $('#tbl_bridge_insp').DataTable({"order": [[ 6, "asc" ]]});
                $('#tbl_bridge_insp_t1').DataTable({"order": [[ 6, "asc" ]]});
                $('#tbl_bridge_insp_t2').DataTable({"order": [[ 6, "asc" ]]});
                $('#tbl_bridge_insp_t3').DataTable({"order": [[ 6, "asc" ]]});
                // $('#tbl_bridge_insp_t4').DataTable({"order": [[ 5, "asc" ]]});
                // $('#tbl_bridge_insp_t5').DataTable({"order": [[ 5, "asc" ]]});
                // $('#tbl_bridge_insp2').DataTable({"order": [[ 6, "asc" ]]});
                // $('#tbl_bridge_insp2_t1').DataTable({"order": [[ 6, "asc" ]]});
                // $('#tbl_bridge_insp2_t2').DataTable({"order": [[ 6, "asc" ]]});
                // $('#tbl_bridge_insp2_t3').DataTable({"order": [[ 6, "asc" ]]});
                // $('#tbl_bridge_insp2_t4').DataTable({"order": [[ 5, "asc" ]]});
                // $('#tbl_bridge_insp2_t5').DataTable({"order": [[ 5, "asc" ]]});
            });
        </script>

        <!-- Open Modal Event Listener -->
        <script>
          var insContactElementModal = document.getElementById('inspector_contact_modal')
          insContactElementModal.addEventListener('show.bs.modal', function (event) {
              var button = event.relatedTarget.closest('tr')
              var tr_id = button.getAttribute('data-bs-id')

              var td = [];
              for (var i = 0; i < 6; ++i) {
                  td[i] = button.getElementsByTagName("td")[i];
              }             
              var td_bridge_no = td[0].innerHTML; 
              var td_bridge_name = td[1].innerHTML; 
              var td_type = td[2].innerHTML; 
              var td_inspector = td[3].innerHTML; 
              var td_admin = td[4].innerHTML; 
              var td_due = td[5].innerHTML; 

              var email = td_inspector.toLowerCase();;
              email = email.split(" ");
              var e_address = "";
              for (var j = 0; j < email.length; j++ ) {
                e_address = e_address + email[j];
                if (j < email.length-1) {
                  e_address = e_address + ".";
                }
              }
              e_address = e_address + "@marshall.bridge.edu";
              
              document.getElementById("inspector_name").value = td_inspector;
              document.getElementById("inspector_email").value = e_address;
          })
        </script>

        <!-- Change Year Contents -->
        <script>  
            $(document).ready(function(){
                $("#year_selector").change(function(){
                    $(this).find("option:selected").each(function(){
                        var optionValue = $(this).attr("value");
                        if(optionValue){
                            $(".cbox").not("#c" + optionValue).hide();
                            $("#c" + optionValue).show();
                        } else{
                            $(".cbox").hide();
                        }
                    });

                    $(".tbox").hide();
                    var origHeight = "calc(100vh - 58px)";
                    var contHeight = $('section').height();
                    var sideHeight = $('.sidebar').height();

                    if (contHeight > sideHeight) {
                        $('.sidebar').height(contHeight);
                    } else {
                        $('.sidebar').height(origHeight);
                    }
                }).change();
            });
        </script>

        <!-- Highlight/Unhighlight Element -->
        <script language="javascript">
          function highlightElement(id) {
              var tblrow = $('#insp_bridge_ele2 tr[data-bs-id="' + id + '"]');
              tblrow.css({backgroundColor: 'rgba(0, 0, 0, 0.075)'});
              tblrow.css("font-weight", "bold");
              var hotspot = $('model-viewer button[id="hts' + id + '"]');
              hotspot.addClass("hsOutline");
          }
          function unhighlightElement(id) {
              var tblrow = $('#insp_bridge_ele2 tr[data-bs-id="' + id + '"]');
              tblrow.css({backgroundColor: 'transparent'});
              tblrow.css("font-weight", "normal");
              var hotspot = $('model-viewer button[id="hts' + id + '"]');
              hotspot.removeClass("hsOutline");
          }
          function highlightHotspot(id) {
              var tblrow = $('#insp_bridge_ele2 tr[data-bs-id="' + id + '"]');
              tblrow.css("font-weight", "bold");
              var hotspot = $('model-viewer button[id="hts' + id + '"]');
              hotspot.addClass("hsOutline");
          }
          function unhighlightHotspot(id) {
                var tblrow = $('#insp_bridge_ele2 tr[data-bs-id="' + id + '"]');
              tblrow.css("font-weight", "normal");
              var hotspot = $('model-viewer button[id="hts' + id + '"]');
              hotspot.removeClass("hsOutline");
          }
        </script>


<!-- SCRIPTS FOR TOGGLING INSPECTIONS (DRILL DOWN) -->

    <script>
        
        $(document).ready(function(){
            
            $('#bridge1').on('click', function(){
                $.ajax({
                    type: "POST",
                    url: "load-inspections-test.php",
                })
                .done(function (msg) {
                    $(".tbox").not("#rm_t1").hide();
                    
                    if(!$('#rm_t1').is(':visible')){
                        if(<?php echo $_SESSION['hasData_bridge1'];?>){
                            loadTable('bridge1');
                        }
                        
                        setTimeout(function(){
                            $('#rm_t1').toggle();
                        }, 70)
                        
                    } else{
                        $('#rm_t1').toggle();
                    }
                });
                
                    
            });


            $('#bridge2').on('click', function(){                
                $.ajax({
                    type: "POST",
                    url: "load-inspections-test.php",
                })
                .done(function (msg) {
                    $(".tbox").not("#rm_t2").hide();
                    
                    if(!$('#rm_t2').is(':visible')){
                        
                        if(<?php echo $_SESSION['hasData_bridge2'];?>){
                            loadTable('bridge2');
                        }
                        
                        setTimeout(function(){
                            $('#rm_t2').toggle();
                        }, 70)
                        
                    } else{
                        $('#rm_t2').toggle();
                    }
                });
                
            });


            $('#bridge3').on('click', function(){
                $.ajax({
                    type: "POST",
                    url: "load-inspections-test.php",
                })
                .done(function (msg) {
                    $(".tbox").not("#rm_t3").hide();
                    
                    if(!$('#rm_t3').is(':visible')){
                        
                        if(<?php echo $_SESSION['hasData_bridge3'];?>){
                            loadTable('bridge3');
                        }
                        
                        setTimeout(function(){
                            $('#rm_t3').toggle();
                        }, 70)
    
                    } else{
                        $('#rm_t3').toggle();
                    }
                });
                
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

       
    </body>
</html>