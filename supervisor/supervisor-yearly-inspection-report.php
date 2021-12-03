<?php

/** -----------------------------------------------------------------
 * PHP Code for YIR Tool
 * ----------------------------------------------------------------- */

    /* start/continue session */
    session_start();

    /* make sure user is supervisor to continue */
    if($_SESSION["loggedAs"] != "Supervisor"){
        header("Location:access-denied.php?error=supervisorsonly");
        die();
    }

    /* On first page load, current year is selected.
    If user has visited this page already, load on the last year they had selected */
    if (!isset($_SESSION["YIR_SelectedYear"])){
        $_SESSION["YIR_SelectedYear"] = date("Y");
    }
    
    /* connect to database */
    include '../dbConfig.inc.php';


    

    /* Global totals for the chart 

    echo "<script type='text/javascript'>functionName('$var1', '$var2', '$var3');</script>";*/
?>


<!-- ----------------------------------------------------------------
    HTML Doc for YIR Tool
-------------------------------------------------------------------- -->
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <link href="../plugins/components.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/7b2b0481fc.js" crossorigin="anonymous"></script>
        <!-- Custom CSS -->
        <link rel="stylesheet" href="../assets/css/custom.css">
        <link rel="stylesheet" href="../plugins/yearpicker/yearpicker.css">
        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>
        <!--Custom JavaScript-->
        <script src="../plugins/yearpicker/yearpicker.js"></script>
        <!-- Table Design -->
        <script type="text/javascript" src="../plugins/DataTables/datatables.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../plugins/DataTables/datatables.min.css"/>
        <!--
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
        -->
        <!-- 3D -->
        <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
        <title>Yearly Inspection Report</title>
    </head>
    
    <body>
        <nav class="navbar navbar-light" style="background-color: #005cbf; width: 100vw;">
            <div class="container-fluid">
                <a class="navbar-brand" href="#" style="color: white; vertical-align: middle;">
                    <img src="../img/wvdtlogo.png" alt="" width="30" height="30" class="d-inline-block align-text-middle">
                    Bridge Inspection Management System
                </a>
                <span class="float-right" style="color: white; font-size: 0.9em;">
                    <i class="fas fa-user-circle"></i>&nbsp;
                    Logged in as <?php echo $_SESSION['loggedAs']; ?>&nbsp;|&nbsp; <a href="../login.php" style="color: white; text-decoration: none;"> sign out</a>
                </span>
            </div>
        </nav>

        <div class="sidebar" style="background-color: rgb(13, 60, 121);">
            <div class="menubar">
                <ul class="menu">
                    <li><a id="Home" href='#'>Supervisor Home</a></li>
                    <li><a id="RM" href='supervisor-yearly-inspection-report.php' style="color: #f8ea09;">Report Management</a>
                        <ul class="submenu">
                            <li style="background-color: #5e5e5e; border-top-left-radius: 5px; border-bottom-left-radius: 5px;">
                                <a id="RM" href='supervisor-yearly-inspection-report.php'>Yearly Inspection Report</a>
                            </li>
                            <li>
                                <a id="RM" href='supervisor-search-params-longitudinal-analysis.php'>Longitudinal Analysis</a>
                            </li>
                        </ul>
                    </li>
                    <!-- <li><a id="UM" href='#'>User Management</a></li> -->
                </ul>
            </div>
        </div>

        <div class="container">
            <div class="main_title">
                <h5> Report Management </h5>
                <p><br>
                    Year:
                    <input type="text" class="yearpicker" id="yearpicker" value="">
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
                                  <h5 class="card-title" id="page-title">Yearly Inspection Report <?php echo $_SESSION['YIR_SelectedYear']?></h5>
                                  <!--
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
                                  -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                  <div class="row" style="align-items: center;">
                                    <div class="col-sm-4 col-md-4">
                                        <div class="chart-responsive">
                                            <!--Pie chart declared here-->
                                            <canvas id="pieChart" height="200"></canvas>
                                        </div>
                                        <div style="font-size: 0.8em; text-align: center; margin: 5px 0;">
                                            Click a piece of pie above to see details.
                                        </div>
                                        <!-- ./chart-responsive -->
                                        <!--
                                        <div>
                                            <ul class="chart-legend clearfix">
                                                <li><i class="far fa-circle text-danger"></i> Bridge replacement</li>
                                                <li><i class="far fa-circle text-success"></i> Widening & rehabilitation</li>
                                                <li><i class="far fa-circle text-warning"></i> Rehabilitation</li>
                                                <li><i class="far fa-circle text-info"></i> Deck rehabilitation/replacement</li>
                                                <li><i class="far fa-circle text-secondary"></i> Other work</li>

                                            </ul>
                                        </div>
                                        -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-8 col-md-8">
                                        <div class="table-responsive">
                                            <table id="InspectionStatus" class="table table-sm">
                                                <tr>
                                                    <th>Inspection Status</th>
                                                    <th>Number</th>
                                                </tr>
                                                <tr>
                                                    <td class="txtl"><i class="fas fa-circle text-dark"></i> Inspection Completed </td>
                                                    <td class="txtr">0</td>
                                                </tr>
                                                <tr>
                                                    <td class="txtl">&emsp;&nbsp;<i class="fas fa-circle text-danger"></i> High Risk </td>
                                                    <td class="txtr">0</td>
                                                </tr>
                                                <tr>
                                                    <td class="txtl">&emsp;&nbsp;<i class="fas fa-circle text-warning"></i> Middle Risk </td>
                                                    <td class="txtr">0</td>
                                                </tr>
                                                <tr>
                                                    <td class="txtl">&emsp;&nbsp;<i class="fas fa-circle text-success"></i> Low Risk </td>
                                                    <td class="txtr">0</td>
                                                </tr>
                                                <tr>
                                                    <td class="txtl"><i class="fas fa-circle text-secondary"></i> Inspection in Progress </td>
                                                    <td class="txtr">0</td>
                                                </tr>
                                                <tr>
                                                    <td class="txtl"><i class="fas fa-circle" style="color: #f7f7f7;"></i> Inspection Not Started </td>
                                                    <td class="txtr">0</td>
                                                </tr>
                                                <tr class="ttlcolor">
                                                    <td class="txtc"><strong> Inspection Total </strong></td>
                                                    <td class="txtr">0</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                  </div>
                                  <!-- /.row -->
                                </div>
                                <!-- ./card-body -->
                                <div class="card-footer">
                                  <div class="row">
                                    <div class="col-sm-3 col-6">
                                      <div class="description-block border-right">
                                        <!--<span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span>-->
                                        <h5 class="description-header">0</h5>
                                        <span class="description-text">COMPLETED INSPECTIONS</span>
                                      </div>
                                      <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-3 col-6">
                                      <div class="description-block border-right">
                                        <!--<span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>-->
                                        <h5 class="description-header">0</h5>
                                        <span class="description-text text-danger">HIGH RISK</span>
                                      </div>
                                      <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-3 col-6">
                                      <div class="description-block border-right">
                                        <!--<span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>-->
                                        <h5 class="description-header">0</h5>
                                        <span class="description-text text-warning">MIDDLE RISK</span>
                                      </div>
                                      <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-3 col-6">
                                      <div class="description-block">
                                        <!--<span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>-->
                                        <h5 class="description-header">0</h5>
                                        <span class="description-text text-success">LOW RISK</span>
                                      </div>
                                      <!-- /.description-block -->
                                    </div>
                                  </div>
                                  <!-- /.row -->
                                </div>
                                <!-- /.card-footer -->
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
                                        <h5 class="card-title">Inspection List&nbsp&nbsp-&nbsp&nbsp<span class='text-danger'>High Risk</span></h5>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div style="padding: 10px; overflow: auto; min-width: 400px;">
                                                    <table class="table table-sm" id="tbl_bridge_insp_t1">
                                                        <thead>
                                                            <tr>
                                                                <th>Bridge no.</th>
                                                                <th>Bridge name</th>
                                                                <th>Type</th>
                                                                <th>Assigned to</th>
                                                                <th>Assigned by</th>
                                                                <th>Completed on</th>
                                                                <th>Rate</th>
                                                                <th data-orderable="false">Bridge<br>Elements</th>
                                                                <th data-orderable="false">Report</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Generated in Javascript -->
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
                                      <h5 class="card-title">Inspection List&nbsp&nbsp-&nbsp&nbsp<span class='text-warning'>Middle Risk</span> </h5>
                                  </div>
                                  <!-- /.card-header -->
                                  <div class="card-body">
                                      <div class="row">
                                          <div class="col-md-12">
                                              <div style="padding: 10px; overflow: auto; min-width: 400px;">
                                                  <table class="table table-sm" id="tbl_bridge_insp_t2">
                                                      <thead>
                                                          <tr>
                                                              <th>Bridge no.</th>
                                                              <th>Bridge name</th>
                                                              <th>Type</th>
                                                              <th>Assigned to</th>
                                                              <th>Assigned by</th>
                                                              <th>Completed on</th>
                                                              <th>Rate</th>
                                                              <th data-orderable="false">Bridge<br>Elements</th>
                                                              <th data-orderable="false">Report</th>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                          <!-- Generated in Javascript -->
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
                                      <h5 class="card-title">Inspection List&nbsp&nbsp-&nbsp&nbsp<span class='text-success'>Low Risk</span> </h5>
                                  </div>
                                  <!-- /.card-header -->
                                  <div class="card-body">
                                      <div class="row">
                                          <div class="col-md-12">
                                              <div style="padding: 10px; overflow: auto; min-width: 400px;">
                                                  <table class="table table-sm" id="tbl_bridge_insp_t3">
                                                      <thead>
                                                          <tr>
                                                              <th>Bridge no.</th>
                                                              <th>Bridge name</th>
                                                              <th>Type</th>
                                                              <th>Assigned to</th>
                                                              <th>Assigned by</th>
                                                              <th>Completed on</th>
                                                              <th>Rate</th>
                                                              <th data-orderable="false">Bridge<br>Elements</th>
                                                              <th data-orderable="false">Report</th>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                          <!-- Generated in Javascript -->
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

                        <!-- In-Progress -->
                        <div class="row tbox" id="rm_t4">
                          <div class="col-md-12">
                              <div class="card">
                                  <div class="card-header">
                                      <h5 class="card-title">Inspection List (In-Progress)</h5>
                                  </div>
                                  <!-- /.card-header -->
                                  <div class="card-body">
                                      <div class="row">
                                          <div class="col-md-12">
                                              <div style="padding: 10px; overflow: auto; min-width: 400px;">
                                                  <table class="table table-sm" id="tbl_bridge_insp_t4">
                                                      <thead>
                                                          <tr>
                                                              <th>Bridge no.</th>
                                                              <th>Bridge name</th>
                                                              <th>Type</th>
                                                              <th>Assigned to</th>
                                                              <th>Assigned by</th>
                                                              <th>Due</th>
                                                              <th data-orderable="false">Action</th>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                          <!-- Generated in Javascript -->
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

                        <!-- Not Started -->
                        <div class="row tbox" id="rm_t5">
                          <div class="col-md-12">
                              <div class="card">
                                  <div class="card-header">
                                      <h5 class="card-title">Inspection List (Not Started)</h5>
                                  </div>
                                  <!-- /.card-header -->
                                  <div class="card-body">
                                      <div class="row">
                                          <div class="col-md-12">
                                              <div style="padding: 10px; overflow: auto; min-width: 400px;">
                                                  <table class="table table-sm" id="tbl_bridge_insp_t5">
                                                      <thead>
                                                          <tr>
                                                              <th>Bridge no.</th>
                                                              <th>Bridge name</th>
                                                              <th>Type</th>
                                                              <th>Assigned to</th>
                                                              <th>Assigned by</th>
                                                              <th>Due</th>
                                                              <th data-orderable="false">Action</th>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                          <!-- Generated in Javascript -->
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
                    </div>
                </div><!--/. container-fluid -->
            </section>
            <!-- /.content -->

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
                                <model-viewer src="../assets/Model060721.glb" id="mdl" camera-controls exposure="0.72" shadow-intensity="0" camera-orbit="27.23deg 78.62deg 181.9m" min-camera-orbit="auto auto auto" max-camera-orbit="auto auto 181.9m" style="width: 100%;">
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
        <!--<script src="../plugins/CardWidget.js"></script>-->

        <script src="../plugins/chart.js/Chart.js"></script>
        <script src="../plugins/adminlte.js"></script>
        <!-- Chart -->
        <script>
           

            function fetchNewestBridgeData(year) {
                return new Promise(function(resolve, reject) {
                    data = [];
                    
                    const xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            // console.log(this.responseText);
                        }
                    };
                    xhr.open('POST', 'php-scripts-yearly-inspection-report/YIR-load-bridge-data.php', true);
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
                    xhr.send('selectedYear=' + year);
                })
            }

            /* this function passes the given year to the session variable 'YIR_SelectedYear' by posting to php */
            function updateSession(year){
                return new Promise(function(resolve, reject) {
                    data = [];
                    
                    const xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            // console.log(this.responseText);
                        }
                    };
                    xhr.open('POST', 'php-scripts-yearly-inspection-report/YIR-update-session.php', true);
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
                    xhr.send('selectedYear=' + year);
                })
            }

            //var origHeight = "calc(100vh - 58px)";
            var origHeight = $('.sidebar').height();
            var contHeight_before = "";
            var contHeight_after = "";
            var sideHeight = "";
            

            /* PIE CHART */
            var pieChart;
            var pieData = {
                labels: [
                    'High Risk (1-3)',
                    'Middle Risk (4-6)',
                    'Low Risk (7-9)',
                    'In-Progress',
                    'Not Started'
                ],
                datasets: [
                {
                    data: [0,0,0,0,0],
                    backgroundColor: ['#ff0000', '#ffea00', '#32b502', '#999999', '#f7f7f7']
                }
                ]
            }
            var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
            var pieOptions = {
                legend: {
                    display: false
                },
                'onClick' : function (evt, item) {
                    //console.log('legend onClick', evt);
                    //console.log('legd item', item);
                    var e = item[0];
                    var e_idx = e._index + 1;

                    if(e_idx > 0){
                        $(".tbox").not("#rm_t" + e_idx).hide();
                        $("#rm_t" + e_idx).toggle();
                    } else{
                        $(".tbox").hide();
                    }

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
                }
            }
            




            /* This function updates the values around the pie chart.
                * Input data should be formatted as a list of numbers:
                * [high risk, middle risk, low risk, in progress, not started]
                */
            function updateTotals(totals){
                // Update table to the right of the pie chart
                var i=0; 
                $('.txtr').each(function(){
                    // first is completed inspections, which is the total of high,mid,low risk
                    if (i==0){
                        $(this).html(totals[0]+totals[1]+totals[2]);
                        i++;
                    }
                    else {
                        $(this).html(totals[i-1]);
                        i++;
                    }
                    // inspection total 
                    if (i==7){
                        $(this).html(totals[0]+totals[1]+totals[2]+totals[3]+totals[4]);
                    }
                });
                // update table under pie chart
                i=0; 
                $('.description-header').each(function(){
                    // first is completed inspections, which is the total of high,mid,low risk
                    if (i==0){
                        $(this).html(totals[0]+totals[1]+totals[2]);
                        i++;
                    }
                    else {
                        $(this).html(totals[i-1]);
                        i++;
                    }
                });
            }

        
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
            
            // Instances of data tables
            var initialized = false;
            var lowRiskTable;
            var highRiskTable;
            var middleRiskTable;
            var inProgressTable;
            var notStartedTable;
            /**
             * This function initializes the data tables to variables.
             * This way, the variables can bee accessed to add and delete rows easily without having to manually create DOM elements in populateTables()
             */
            function bindTables(){
               // $('#tbl_bridge_insp').DataTable({"order": [[ 6, "asc" ]]});
                highRiskTable = $('#tbl_bridge_insp_t1').DataTable({
                    "order": [[ 6, "asc" ]], 
                    "rowCallback": function(row, data){
                        $('td', row).eq(6).css("color", '#d9534f');
                        $(row).css('height', '50');
                    }
                });
                middleRiskTable = $('#tbl_bridge_insp_t2').DataTable({
                    "order": [[ 6, "asc" ]],
                    "rowCallback": function(row, data){
                        $('td', row).eq(6).css("color", '#f0ad4e');
                        $(row).css('height', '50');
                    }
                });
                lowRiskTable= $('#tbl_bridge_insp_t3').DataTable({
                    "order": [[ 6, "asc" ]],
                    "rowCallback": function(row, data){
                        $('td', row).eq(6).css("color", '#5cb85c');
                        $(row).css('height', '50');
                    }
                });
                inProgressTable = $('#tbl_bridge_insp_t4').DataTable({
                    "order": [[ 5, "asc" ]],
                    "rowCallback": function(row, data){
                        $(row).css('height', '50');
                    }
                });
                notStartedTable = $('#tbl_bridge_insp_t5').DataTable({
                    "order": [[ 5, "asc" ]],
                    "rowCallback": function(row, data){
                        $(row).css('height', '50');
                    }
                });
            }

            /**
             * This function deletes all entries in all 5 DataTables
             */
            function cleanTables(){

                //reload the DataTables with new data
                highRiskTable.clear();
                middleRiskTable.clear();
                lowRiskTable.clear();
                inProgressTable.clear();
                notStartedTable.clear();

                //reload the DataTables with new data
                highRiskTable.draw();
                middleRiskTable.draw();
                lowRiskTable.draw();
                inProgressTable.draw();
                notStartedTable.draw();
            }

             /**
             *  Given list of inspections, populate the 5 data tables.
             *  Also, tally the amount of entries for each table as they are added
             *  Return the finished tallies [high risk, middle risk, low risk, in progress, not started] for the pie chart
             */
            function populateTables(bridges){

                var dataset = [0,0,0,0,0]; // generate dataset for pie chart
                var newRow;
                if (bridges.data != null){           
                    //iterate through each row of data (bridge) returned from database
                    bridges.data.forEach(bridge => {

                        //create a new row for the current entry (only first 5 columns, others are added conditionally below)
                        newRow = [
                            bridge.bridgeNo,
                            bridge.bridgeName,
                            bridge.inspectionTypeName,
                            bridge.assignedTo,
                            bridge.assignedBy
                        ];

                        //check if inspection is complete
                        if(bridge.status == "complete"){

                            //add the appropriate columns for completed reports
                            newRow.push(bridge.finishedDate);
                            newRow.push("<span>"+bridge.rating+"</span>");
                            newRow.push("<a class='btnset btn_overview' data-bs-toggle='modal' data-bs-target='#myModal'>3D</a>");
                            newRow.push("<a href='../assets/Report.pdf' class='btnset btn_review2' target='_blank'>PDF</a>");

                            // high risk...
                            if(bridge.rating >= 1 && bridge.rating <= 3){ 
                                highRiskTable.row.add(newRow);
                                dataset[0]++; 
                            }
                            //middle risk...
                            else if(bridge.rating >= 4 && bridge.rating <= 6){ 
                                middleRiskTable.row.add(newRow);
                                dataset[1]++; 
                            }
                            //low risk...
                            else{
                                lowRiskTable.row.add(newRow);
                                dataset[2]++;
                            }
                        }
                        
                        // if not complete, then is is progress or not started
                        else{

                            //add the appropriate columns for non-completed reports
                            newRow.push(bridge.dueDate);
                            newRow.push("<button class='btnset btn_contact' onclick='' data-bs-toggle='modal' data-bs-target='#inspector_contact_modal'>Contact Inspector</button>");

                            //if in progress...
                            if(bridge.status == "in progress"){
                                inProgressTable.row.add(newRow);
                                dataset[3]++;
                            }
                            //if not started...
                            else{
                                notStartedTable.row.add(newRow);
                                dataset[4]++;
                            }
                        }
                    });

                    //update the data tables

                    highRiskTable.draw();
                    middleRiskTable.draw();
                    lowRiskTable.draw();
                    inProgressTable.draw();
                    notStartedTable.draw();

                    return dataset;
                }

                //if bridge data was null, then clean the tables and return the empty dataset
                cleanTables();
                //return dataset for pie chart
                return dataset;
            }

        //Open Modal Event Listener 
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

         /**
             * This method retreives a list of each newest bridge inspection for each bridge over one year
             */
            let fetchData = async (year) => {
                const bridgeData = await fetchNewestBridgeData(year);
                return bridgeData;
            }
            const buildChart = async (pieOptions,year) => {
                const bridgeData = await fetchData(year);
                bindTables();
                const tallies = populateTables(bridgeData);
                updateTotals(tallies);
                pieData.datasets[0].data = tallies;
                var pieChart = new Chart(pieChartCanvas, {
                    type: 'pie',
                    data: pieData,
                    options: pieOptions
                });
                return pieChart;
            }
            
            // build pie chart using session variable on first page load
            pieChart = buildChart(pieOptions, <?php echo $_SESSION['YIR_SelectedYear']; ?>);
            pieChart.then(function(response){
                pieChart = response;
            })

            //Change Year Contents 
            $('.yearpicker').yearpicker({

                year: <?php echo $_SESSION['YIR_SelectedYear']; ?>,
                startYear: null,
                endYear: new Date().getFullYear(),

                // Element tag
                itemTag: 'li',

                // Default CSS classes
                selectedClass: 'selected',
                disabledClass: 'disabled',
                hideClass: 'hide',

                onShow: null,
                onHide: null 
            });
            $('.yearpicker').on('change', async function() {
                selectedYear = $(this).val();
                //$(".tbox").hide();

                cleanTables();
                const newBridgeData = await fetchNewestBridgeData(selectedYear);
                var tallies = populateTables(newBridgeData);
                updateTotals(tallies);
                pieData.datasets[0].data = tallies;
                pieChart.update();

                updateSession(selectedYear);
                $('#page-title').html('Yearly Inspection Report (' + selectedYear + ')');

                var origHeight = "calc(100vh - 58px)";
                var contHeight = $('section').height();
                var sideHeight = $('.sidebar').height();

                if (contHeight > sideHeight) {
                    $('.sidebar').height(contHeight);
                } else {
                    $('.sidebar').height(origHeight);
                }
            });


        //Highlight/Unhighlight Element
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

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    </body>
</html>
