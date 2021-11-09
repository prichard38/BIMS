<?php

/** -----------------------------------------------------------------
 * PHP Code for YIR Tool
 * ----------------------------------------------------------------- */

    /* start/continue session */
    session_start();

    /* make sure user is supervisor to continue */
    if($_SESSION["loggedAs"] != "Supervisor"){
        header("Location:access_denied.php?error=supervisorsonly");
        die();
    }

    /* On first page load, current year is selected.
    If user has visited this page already, load on the last year they had selected */
    if (!isset($_SESSION["YIR_SelectedYear"])){
        $_SESSION["YIR_SelectedYear"] = date("Y");
    }
    
    /* connect to database */
    include 'dbConfig.inc.php';


    

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
        <link href="plugins/components.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/7b2b0481fc.js" crossorigin="anonymous"></script>
        <!-- Custom CSS -->
        <link rel="stylesheet" href="assets/css/custom.css">
        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>
        <!--Custom JavaScript-->
        <script src="yir-functions.js"></script>
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
        <nav class="navbar navbar-light" style="background-color: #005cbf; width: 100vw;">
            <div class="container-fluid">
                <a class="navbar-brand" href="#" style="color: white; vertical-align: middle;">
                    <img src="img/wvdtlogo.png" alt="" width="30" height="30" class="d-inline-block align-text-middle">
                    Bridge Inspection Management System
                </a>
                <span class="float-right" style="color: white; font-size: 0.9em;">
                    <i class="fas fa-user-circle"></i>&nbsp;
                    Logged in as <?php echo $_SESSION['loggedAs']; ?>&nbsp;|&nbsp; <a href="login-test.php" style="color: white; text-decoration: none;"> sign out</a>
                </span>
            </div>
        </nav>

        <div class="sidebar">
            <div class="menubar">
                <ul class="menu">
                    <!-- <li><a id="Home" href='#'>Admin Home</a></li>
                    <li><a id="IM" href='admin_inspection_management.html'>Inspection Management</a></li>
                    <li><a id="BM" href='admin_bridge_management.html'>Bridge Management</a></li> -->
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
                    <!-- <li><a id="UM" href='#'>User Management</a></li> -->
                </ul>
            </div>
        </div>

        <div class="container">
            <div class="main_title">
                <h5> Report Management </h5>
                <p><br>
                    Year:
                    <select name="year" id="year_selector" required>
                        <option value="<?php echo $_SESSION['YIR_SelectedYear']; ?>" selected="selected"> <?php echo $_SESSION['YIR_SelectedYear']; ?></option>
                        <option value="<?php echo date("Y")-1; ?>"><?php echo date("Y")-1; ?></option>
                        <option value="<?php echo date("Y")-2; ?>"><?php echo date("Y")-2; ?></option>
                        <option value="<?php echo date("Y")-3; ?>"><?php echo date("Y")-3; ?></option>
                        <option value="<?php echo date("Y")-4; ?>"><?php echo date("Y")-4; ?></option>
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
                                  <h5 class="card-title">Yearly Inspection Report (2021)</h5>
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
                                                    <td class="txtr">2,290</td>
                                                </tr>
                                                <tr>
                                                    <td class="txtl">&emsp;&nbsp;<i class="fas fa-circle text-danger"></i> High Risk </td>
                                                    <td class="txtr">597</td>
                                                </tr>
                                                <tr>
                                                    <td class="txtl">&emsp;&nbsp;<i class="fas fa-circle text-warning"></i> Middle Risk </td>
                                                    <td class="txtr">619</td>
                                                </tr>
                                                <tr>
                                                    <td class="txtl">&emsp;&nbsp;<i class="fas fa-circle text-success"></i> Low Risk </td>
                                                    <td class="txtr">1,074</td>
                                                </tr>
                                                <tr>
                                                    <td class="txtl"><i class="fas fa-circle text-secondary"></i> Inspection in Progress </td>
                                                    <td class="txtr">2,087</td>
                                                </tr>
                                                <tr>
                                                    <td class="txtl"><i class="fas fa-circle" style="color: #f7f7f7;"></i> Inspection Not Started </td>
                                                    <td class="txtr">2,918</td>
                                                </tr>
                                                <tr class="ttlcolor">
                                                    <td class="txtc"><strong> Inspection Total </strong></td>
                                                    <td class="txtr">7,295</td>
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
                                        <h5 class="description-header">2,290</h5>
                                        <span class="description-text">COMPLETED INSPECTIONS</span>
                                      </div>
                                      <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-3 col-6">
                                      <div class="description-block border-right">
                                        <!--<span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>-->
                                        <h5 class="description-header">597</h5>
                                        <span class="description-text text-danger">HIGH RISK</span>
                                      </div>
                                      <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-3 col-6">
                                      <div class="description-block border-right">
                                        <!--<span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>-->
                                        <h5 class="description-header">619</h5>
                                        <span class="description-text text-warning">MIDDLE RISK</span>
                                      </div>
                                      <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-3 col-6">
                                      <div class="description-block">
                                        <!--<span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>-->
                                        <h5 class="description-header">1,074</h5>
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
                                        <h5 class="card-title">Inspection List (High Risk)</h5>
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
                                                            <tr>
                                                                <td>001-4/5-2.95(01810)</td>
                                                                <td>Cane Hill Bridge over Little Red River</td>
                                                                <td>In-Depth</td>
                                                                <td>William Jones</td>
                                                                <td>Irene Song</td>
                                                                <td>2021-05-20</td>
                                                                <td><span style="color: #E40800">1</span></td>
                                                                <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                                <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                            </tr>
                                                            <tr>
                                                                <td>006-3/4-8.65(03148)</td>
                                                                <td>Robert C. Byrd Bridge over Ohio River</td>
                                                                <td>Periodic</td>
                                                                <td>Liam Davis</td>
                                                                <td>Irene Song</td>
                                                                <td>2021-04-21</td>
                                                                <td><span style="color: #E40800">1</span></td>
                                                                <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                                <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                            </tr>
                                                            <tr>
                                                                <td>004-4/5-2.95(01210)</td>
                                                                <td>East Huntington Bridge over Ohio River</td>
                                                                <td>Periodic</td>
                                                                <td>Rebecca Johnson</td>
                                                                <td>Irene Song</td>
                                                                <td>2021-05-01</td>
                                                                <td><span style="color: #E32925">2</span></td>
                                                                <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                                <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                            </tr>
                                                            <tr>
                                                                <td>001-4/5-2.95(01210)</td>
                                                                <td>Alderson Bridge over Greenbrier River</td>
                                                                <td>Interim-Condition</td>
                                                                <td>Randy Jane</td>
                                                                <td>John Marshall</td>
                                                                <td>2021-04-01</td>
                                                                <td><span style="color: #E32925">2</span></td>
                                                                <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                                <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                            </tr>
                                                            <tr>
                                                                <td>002-4/5-2.95(01211)</td>
                                                                <td>New River Gorge Bridge over New River</td>
                                                                <td>In-Depth</td>
                                                                <td>Randy Jane</td>
                                                                <td>John Marshall</td>
                                                                <td>2021-04-09</td>
                                                                <td><span style="color: #F26721">3</span></td>
                                                                <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                                <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                            </tr>
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
                                      <h5 class="card-title">Inspection List (Middle Risk) </h5>
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
                                                          <tr>
                                                              <td>001-4/5-2.95(01810)</td>
                                                              <td>Cane Hill Bridge over Little Red River</td>
                                                              <td>In-Depth</td>
                                                              <td>William Jones</td>
                                                              <td>Irene Song</td>
                                                              <td>2021-05-20</td>
                                                              <td><span style="color:#F89E33">4</span></td>
                                                              <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                              <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                          </tr>
                                                          <tr>
                                                              <td>006-3/4-8.65(03148)</td>
                                                              <td>Robert C. Byrd Bridge over Ohio River</td>
                                                              <td>Periodic</td>
                                                              <td>Liam Davis</td>
                                                              <td>Irene Song</td>
                                                              <td>2021-04-21</td>
                                                              <td><span style="color: #EEC200">5</span></td>
                                                              <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                              <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                          </tr>
                                                          <tr>
                                                              <td>004-4/5-2.95(01210)</td>
                                                              <td>East Huntington Bridge over Ohio River</td>
                                                              <td>Periodic</td>
                                                              <td>Rebecca Johnson</td>
                                                              <td>Irene Song</td>
                                                              <td>2021-05-01</td>
                                                              <td><span style="color: #EEC200">5</span></td>
                                                              <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                              <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                          </tr>
                                                          <tr>
                                                              <td>001-4/5-2.95(01210)</td>
                                                              <td>Alderson Bridge over Greenbrier River</td>
                                                              <td>Interim-Condition</td>
                                                              <td>Randy Jane</td>
                                                              <td>John Marshall</td>
                                                              <td>2021-04-01</td>
                                                              <td><span style="color:#ECD715">6</span></td>
                                                              <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                              <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                          </tr>
                                                          <tr>
                                                              <td>002-4/5-2.95(01211)</td>
                                                              <td>New River Gorge Bridge over New River</td>
                                                              <td>In-Depth</td>
                                                              <td>Randy Jane</td>
                                                              <td>John Marshall</td>
                                                              <td>2021-04-09</td>
                                                              <td><span style="color:#ECD715">6</span></td>
                                                              <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                              <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                          </tr>
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
                                      <h5 class="card-title">Inspection List (Low Risk) </h5>
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
                                                          <tr>
                                                              <td>001-4/5-2.95(01810)</td>
                                                              <td>Cane Hill Bridge over Little Red River</td>
                                                              <td>In-Depth</td>
                                                              <td>William Jones</td>
                                                              <td>Irene Song</td>
                                                              <td>2021-05-20</td>
                                                              <td><span style="color: #609B41">7</span></td>
                                                              <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                              <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                          </tr>
                                                          <tr>
                                                              <td>006-3/4-8.65(03148)</td>
                                                              <td>Robert C. Byrd Bridge over Ohio River</td>
                                                              <td>Periodic</td>
                                                              <td>Liam Davis</td>
                                                              <td>Irene Song</td>
                                                              <td>2021-04-21</td>
                                                              <td><span style="color: #2E7A3C">8</span></td>
                                                              <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                              <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                          </tr>
                                                          <tr>
                                                              <td>004-4/5-2.95(01210)</td>
                                                              <td>East Huntington Bridge over Ohio River</td>
                                                              <td>Periodic</td>
                                                              <td>Rebecca Johnson</td>
                                                              <td>Irene Song</td>
                                                              <td>2021-05-01</td>
                                                              <td><span style="color: #2E7A3C">8</span></td>
                                                              <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                              <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                          </tr>
                                                          <tr>
                                                              <td>001-4/5-2.95(01210)</td>
                                                              <td>Alderson Bridge over Greenbrier River</td>
                                                              <td>Interim-Condition</td>
                                                              <td>Randy Jane</td>
                                                              <td>John Marshall</td>
                                                              <td>2021-04-01</td>
                                                              <td><span style="color: #036353">9</span></td>
                                                              <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                              <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                          </tr>
                                                          <tr>
                                                              <td>002-4/5-2.95(01211)</td>
                                                              <td>New River Gorge Bridge over New River</td>
                                                              <td>In-Depth</td>
                                                              <td>Randy Jane</td>
                                                              <td>John Marshall</td>
                                                              <td>2021-04-09</td>
                                                              <td><span style="color: #036353">9</span></td>
                                                              <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                              <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                          </tr>
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
                                                          <tr data-bs-id="1">
                                                              <td>001-4/5-2.95(01810)</td>
                                                              <td>Cane Hill Bridge over Little Red River</td>
                                                              <td>In-Depth</td>
                                                              <td>William Jones</td>
                                                              <td>Irene Song</td>
                                                              <td>2021-09-20</td>
                                                              <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                            </tr>
                                                          <tr data-bs-id="2">
                                                              <td>006-3/4-8.65(03148)</td>
                                                              <td>Robert C. Byrd Bridge over Ohio River</td>
                                                              <td>Periodic</td>
                                                              <td>Liam Davis</td>
                                                              <td>Irene Song</td>
                                                              <td>2021-11-21</td>
                                                              <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                          </tr>
                                                          <tr data-bs-id="3">
                                                              <td>004-4/5-2.95(01210)</td>
                                                              <td>East Huntington Bridge over Ohio River</td>
                                                              <td>Periodic</td>
                                                              <td>Rebecca Johnson</td>
                                                              <td>Irene Song</td>
                                                              <td>2021-11-01</td>
                                                              <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                          </tr>
                                                          <tr data-bs-id="4">
                                                              <td>001-4/5-2.95(01210)</td>
                                                              <td>Alderson Bridge over Greenbrier River</td>
                                                              <td>Interim-Condition</td>
                                                              <td>Randy Jane</td>
                                                              <td>John Marshall</td>
                                                              <td>2021-10-05</td>
                                                              <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                          </tr>
                                                          <tr data-bs-id="5">
                                                              <td>002-4/5-2.95(01211)</td>
                                                              <td>New River Gorge Bridge over New River</td>
                                                              <td>In-Depth</td>
                                                              <td>Randy Jane</td>
                                                              <td>John Marshall</td>
                                                              <td>2021-10-09</td>
                                                              <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                          </tr>
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
                                                          <tr data-bs-id="1">
                                                              <td>001-4/5-2.95(01810)</td>
                                                              <td>Cane Hill Bridge over Little Red River</td>
                                                              <td>In-Depth</td>
                                                              <td>William Jones</td>
                                                              <td>Irene Song</td>
                                                              <td>2021-09-20</td>
                                                              <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                            </tr>
                                                          <tr data-bs-id="2">
                                                              <td>006-3/4-8.65(03148)</td>
                                                              <td>Robert C. Byrd Bridge over Ohio River</td>
                                                              <td>Periodic</td>
                                                              <td>Liam Davis</td>
                                                              <td>Irene Song</td>
                                                              <td>2021-11-21</td>
                                                              <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                          </tr>
                                                          <tr data-bs-id="3">
                                                              <td>004-4/5-2.95(01210)</td>
                                                              <td>East Huntington Bridge over Ohio River</td>
                                                              <td>Periodic</td>
                                                              <td>Rebecca Johnson</td>
                                                              <td>Irene Song</td>
                                                              <td>2021-11-01</td>
                                                              <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                          </tr>
                                                          <tr data-bs-id="4">
                                                              <td>001-4/5-2.95(01210)</td>
                                                              <td>Alderson Bridge over Greenbrier River</td>
                                                              <td>Interim-Condition</td>
                                                              <td>Randy Jane</td>
                                                              <td>John Marshall</td>
                                                              <td>2021-10-05</td>
                                                              <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                          </tr>
                                                          <tr data-bs-id="5">
                                                              <td>002-4/5-2.95(01211)</td>
                                                              <td>New River Gorge Bridge over New River</td>
                                                              <td>In-Depth</td>
                                                              <td>Randy Jane</td>
                                                              <td>John Marshall</td>
                                                              <td>2021-10-09</td>
                                                              <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                          </tr>
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

            <section class="content cbox" id="c2020">
                <div class="container-fluid">
                  <div class="contents">
                    <div class="row">
                        <div class="col-md-12">
                          <div class="card">
                            <div class="card-header">
                              <h5 class="card-title">Yearly Inspection Report (2020)</h5>
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
                                        <canvas id="pieChart2" height="200"></canvas>
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
                                                <td class="txtr">7,093</td>
                                            </tr>
                                            <tr>
                                                <td class="txtl">&emsp;&nbsp;<i class="fas fa-circle text-danger"></i> High Risk </td>
                                                <td class="txtr">2,096</td>
                                            </tr>
                                            <tr>
                                                <td class="txtl">&emsp;&nbsp;<i class="fas fa-circle text-warning"></i> Middle Risk </td>
                                                <td class="txtr">1,996</td>
                                            </tr>
                                            <tr>
                                                <td class="txtl">&emsp;&nbsp;<i class="fas fa-circle text-success"></i> Low Risk </td>
                                                <td class="txtr">3,001</td>
                                            </tr>
                                            <tr>
                                                <td class="txtl"><i class="fas fa-circle text-secondary"></i> Inspection in Progress </td>
                                                <td class="txtr">71</td>
                                            </tr>
                                            <tr>
                                                <td class="txtl"><i class="fas fa-circle" style="color: #f7f7f7;"></i> Inspection Not Started </td>
                                                <td class="txtr">131</td>
                                            </tr>
                                            <tr class="ttlcolor">
                                                <td class="txtc"><strong> Inspection Total </strong></td>
                                                <td class="txtr">7,295</td>
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
                                    <h5 class="description-header">7,093</h5>
                                    <span class="description-text">COMPLETED INSPECTIONS</span>
                                  </div>
                                  <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-3 col-6">
                                  <div class="description-block border-right">
                                    <!--<span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>-->
                                    <h5 class="description-header">2,096</h5>
                                    <span class="description-text text-danger">HIGH RISK</span>
                                  </div>
                                  <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-3 col-6">
                                  <div class="description-block border-right">
                                    <!--<span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>-->
                                    <h5 class="description-header">1,996</h5>
                                    <span class="description-text text-warning">MIDDLE RISK</span>
                                  </div>
                                  <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-3 col-6">
                                  <div class="description-block">
                                    <!--<span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>-->
                                    <h5 class="description-header">3,001</h5>
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
                    <div class="row tbox" id="rm_tt1">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Inspection List (High Lisk)</h5>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div style="padding: 10px; overflow: auto; min-width: 400px;">
                                                <table class="table table-sm" id="tbl_bridge_insp2_t1">
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
                                                        <tr>
                                                            <td>001-4/5-2.95(01810)</td>
                                                            <td>Cane Hill Bridge over Little Red River</td>
                                                            <td>In-Depth</td>
                                                            <td>William Jones</td>
                                                            <td>Irene Song</td>
                                                            <td>2021-05-20</td>
                                                            <td><span style="color: #E40800">1</span></td>
                                                            <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                            <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                        </tr>
                                                        <tr>
                                                            <td>006-3/4-8.65(03148)</td>
                                                            <td>Robert C. Byrd Bridge over Ohio River</td>
                                                            <td>Periodic</td>
                                                            <td>Liam Davis</td>
                                                            <td>Irene Song</td>
                                                            <td>2021-04-21</td>
                                                            <td><span style="color: #E40800">1</span></td>
                                                            <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                            <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                        </tr>
                                                        <tr>
                                                            <td>004-4/5-2.95(01210)</td>
                                                            <td>East Huntington Bridge over Ohio River</td>
                                                            <td>Periodic</td>
                                                            <td>Rebecca Johnson</td>
                                                            <td>Irene Song</td>
                                                            <td>2021-05-01</td>
                                                            <td><span style="color: #E32925">2</span></td>
                                                            <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                            <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                        </tr>
                                                        <tr>
                                                            <td>001-4/5-2.95(01210)</td>
                                                            <td>Alderson Bridge over Greenbrier River</td>
                                                            <td>Interim-Condition</td>
                                                            <td>Randy Jane</td>
                                                            <td>John Marshall</td>
                                                            <td>2021-04-01</td>
                                                            <td><span style="color: #E32925">2</span></td>
                                                            <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                            <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                        </tr>
                                                        <tr>
                                                            <td>002-4/5-2.95(01211)</td>
                                                            <td>New River Gorge Bridge over New River</td>
                                                            <td>In-Depth</td>
                                                            <td>Randy Jane</td>
                                                            <td>John Marshall</td>
                                                            <td>2021-04-09</td>
                                                            <td><span style="color: #F26721">3</span></td>
                                                            <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                            <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                        </tr>
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
                    <div class="row tbox" id="rm_tt2">
                      <div class="col-md-12">
                          <div class="card">
                              <div class="card-header">
                                  <h5 class="card-title">Inspection List (Middle Risk) </h5>
                              </div>
                              <!-- /.card-header -->
                              <div class="card-body">
                                  <div class="row">
                                      <div class="col-md-12">
                                          <div style="padding: 10px; overflow: auto; min-width: 400px;">
                                              <table class="table table-sm" id="tbl_bridge_insp2_t2">
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
                                                      <tr>
                                                          <td>001-4/5-2.95(01810)</td>
                                                          <td>Cane Hill Bridge over Little Red River</td>
                                                          <td>In-Depth</td>
                                                          <td>William Jones</td>
                                                          <td>Irene Song</td>
                                                          <td>2021-05-20</td>
                                                          <td><span style="color:#F89E33">4</span></td>
                                                          <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                          <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                      </tr>
                                                      <tr>
                                                          <td>006-3/4-8.65(03148)</td>
                                                          <td>Robert C. Byrd Bridge over Ohio River</td>
                                                          <td>Periodic</td>
                                                          <td>Liam Davis</td>
                                                          <td>Irene Song</td>
                                                          <td>2021-04-21</td>
                                                          <td><span style="color: #EEC200">5</span></td>
                                                          <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                          <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                      </tr>
                                                      <tr>
                                                          <td>004-4/5-2.95(01210)</td>
                                                          <td>East Huntington Bridge over Ohio River</td>
                                                          <td>Periodic</td>
                                                          <td>Rebecca Johnson</td>
                                                          <td>Irene Song</td>
                                                          <td>2021-05-01</td>
                                                          <td><span style="color: #EEC200">5</span></td>
                                                          <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                          <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                      </tr>
                                                      <tr>
                                                          <td>001-4/5-2.95(01210)</td>
                                                          <td>Alderson Bridge over Greenbrier River</td>
                                                          <td>Interim-Condition</td>
                                                          <td>Randy Jane</td>
                                                          <td>John Marshall</td>
                                                          <td>2021-04-01</td>
                                                          <td><span style="color:#ECD715">6</span></td>
                                                          <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                          <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                      </tr>
                                                      <tr>
                                                          <td>002-4/5-2.95(01211)</td>
                                                          <td>New River Gorge Bridge over New River</td>
                                                          <td>In-Depth</td>
                                                          <td>Randy Jane</td>
                                                          <td>John Marshall</td>
                                                          <td>2021-04-09</td>
                                                          <td><span style="color:#ECD715">6</span></td>
                                                          <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                          <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                      </tr>
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
                    <div class="row tbox" id="rm_tt3">
                      <div class="col-md-12">
                          <div class="card">
                              <div class="card-header">
                                  <h5 class="card-title">Inspection List (Low Risk) </h5>
                              </div>
                              <!-- /.card-header -->
                              <div class="card-body">
                                  <div class="row">
                                      <div class="col-md-12">
                                          <div style="padding: 10px; overflow: auto; min-width: 400px;">
                                              <table class="table table-sm" id="tbl_bridge_insp2_t3">
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
                                                      <tr>
                                                          <td>001-4/5-2.95(01810)</td>
                                                          <td>Cane Hill Bridge over Little Red River</td>
                                                          <td>In-Depth</td>
                                                          <td>William Jones</td>
                                                          <td>Irene Song</td>
                                                          <td>2021-05-20</td>
                                                          <td><span style="color: #609B41">7</span></td>
                                                          <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                          <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                      </tr>
                                                      <tr>
                                                          <td>006-3/4-8.65(03148)</td>
                                                          <td>Robert C. Byrd Bridge over Ohio River</td>
                                                          <td>Periodic</td>
                                                          <td>Liam Davis</td>
                                                          <td>Irene Song</td>
                                                          <td>2021-04-21</td>
                                                          <td><span style="color: #2E7A3C">8</span></td>
                                                          <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                          <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                      </tr>
                                                      <tr>
                                                          <td>004-4/5-2.95(01210)</td>
                                                          <td>East Huntington Bridge over Ohio River</td>
                                                          <td>Periodic</td>
                                                          <td>Rebecca Johnson</td>
                                                          <td>Irene Song</td>
                                                          <td>2021-05-01</td>
                                                          <td><span style="color: #2E7A3C">8</span></td>
                                                          <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                          <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                      </tr>
                                                      <tr>
                                                          <td>001-4/5-2.95(01210)</td>
                                                          <td>Alderson Bridge over Greenbrier River</td>
                                                          <td>Interim-Condition</td>
                                                          <td>Randy Jane</td>
                                                          <td>John Marshall</td>
                                                          <td>2021-04-01</td>
                                                          <td><span style="color: #036353">9</span></td>
                                                          <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                          <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                      </tr>
                                                      <tr>
                                                          <td>002-4/5-2.95(01211)</td>
                                                          <td>New River Gorge Bridge over New River</td>
                                                          <td>In-Depth</td>
                                                          <td>Randy Jane</td>
                                                          <td>John Marshall</td>
                                                          <td>2021-04-09</td>
                                                          <td><span style="color: #036353">9</span></td>
                                                          <td><a class="btnset btn_overview" data-bs-toggle="modal" data-bs-target="#myModal">3D</a></td>
                                                          <td><a href="assets/Report.pdf" class="btnset btn_review2" target="_blank">PDF</a></td>
                                                      </tr>
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
                    <div class="row tbox" id="rm_tt4">
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
                                              <table class="table table-sm" id="tbl_bridge_insp2_t4">
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
                                                      <tr data-bs-id="1">
                                                          <td>001-4/5-2.95(01810)</td>
                                                          <td>Cane Hill Bridge over Little Red River</td>
                                                          <td>In-Depth</td>
                                                          <td>William Jones</td>
                                                          <td>Irene Song</td>
                                                          <td>2021-09-20</td>
                                                          <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                        </tr>
                                                      <tr data-bs-id="2">
                                                          <td>006-3/4-8.65(03148)</td>
                                                          <td>Robert C. Byrd Bridge over Ohio River</td>
                                                          <td>Periodic</td>
                                                          <td>Liam Davis</td>
                                                          <td>Irene Song</td>
                                                          <td>2021-11-21</td>
                                                          <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                      </tr>
                                                      <tr data-bs-id="3">
                                                          <td>004-4/5-2.95(01210)</td>
                                                          <td>East Huntington Bridge over Ohio River</td>
                                                          <td>Periodic</td>
                                                          <td>Rebecca Johnson</td>
                                                          <td>Irene Song</td>
                                                          <td>2021-11-01</td>
                                                          <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                      </tr>
                                                      <tr data-bs-id="4">
                                                          <td>001-4/5-2.95(01210)</td>
                                                          <td>Alderson Bridge over Greenbrier River</td>
                                                          <td>Interim-Condition</td>
                                                          <td>Randy Jane</td>
                                                          <td>John Marshall</td>
                                                          <td>2021-10-05</td>
                                                          <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                      </tr>
                                                      <tr data-bs-id="5">
                                                          <td>002-4/5-2.95(01211)</td>
                                                          <td>New River Gorge Bridge over New River</td>
                                                          <td>In-Depth</td>
                                                          <td>Randy Jane</td>
                                                          <td>John Marshall</td>
                                                          <td>2021-10-09</td>
                                                          <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                      </tr>
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
                    <div class="row tbox" id="rm_tt5">
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
                                              <table class="table table-sm" id="tbl_bridge_insp2_t5">
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
                                                      <tr data-bs-id="1">
                                                          <td>001-4/5-2.95(01810)</td>
                                                          <td>Cane Hill Bridge over Little Red River</td>
                                                          <td>In-Depth</td>
                                                          <td>William Jones</td>
                                                          <td>Irene Song</td>
                                                          <td>2021-09-20</td>
                                                          <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                        </tr>
                                                      <tr data-bs-id="2">
                                                          <td>006-3/4-8.65(03148)</td>
                                                          <td>Robert C. Byrd Bridge over Ohio River</td>
                                                          <td>Periodic</td>
                                                          <td>Liam Davis</td>
                                                          <td>Irene Song</td>
                                                          <td>2021-11-21</td>
                                                          <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                      </tr>
                                                      <tr data-bs-id="3">
                                                          <td>004-4/5-2.95(01210)</td>
                                                          <td>East Huntington Bridge over Ohio River</td>
                                                          <td>Periodic</td>
                                                          <td>Rebecca Johnson</td>
                                                          <td>Irene Song</td>
                                                          <td>2021-11-01</td>
                                                          <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                      </tr>
                                                      <tr data-bs-id="4">
                                                          <td>001-4/5-2.95(01210)</td>
                                                          <td>Alderson Bridge over Greenbrier River</td>
                                                          <td>Interim-Condition</td>
                                                          <td>Randy Jane</td>
                                                          <td>John Marshall</td>
                                                          <td>2021-10-05</td>
                                                          <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                      </tr>
                                                      <tr data-bs-id="5">
                                                          <td>002-4/5-2.95(01211)</td>
                                                          <td>New River Gorge Bridge over New River</td>
                                                          <td>In-Depth</td>
                                                          <td>Randy Jane</td>
                                                          <td>John Marshall</td>
                                                          <td>2021-10-09</td>
                                                          <td><button class="btnset btn_contact" onclick="" data-bs-toggle="modal" data-bs-target="#inspector_contact_modal">Contact Inspector</button></td>
                                                      </tr>
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

        <!-- Chart -->
        <script>
            $(function () {
                'use strict'

                //var origHeight = "calc(100vh - 58px)";
                var origHeight = $('.sidebar').height();
                var contHeight_before = "";
                var contHeight_after = "";
                var sideHeight = "";

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
                        data: [597, 619, 1074, 2087, 2918],
                        //data:[]
                        backgroundColor: ['#ff0000', '#ffea00', '#32b502', '#999999', '#f7f7f7']
                    }
                    ]
                }

                /**
                 * Retreive data for table and create dataset for pie chart
                 */
                const fetchData = async () => {

                    //create a data set for a pie chart
                    var dataset = {
                        data: [0,0,0,0,0],
                        backgroundColor: ['#ff0000', '#ffea00', '#32b502', '#999999', '#f7f7f7']
                    }

                    const bridges = await fetchNewestBridgeData(2021);
                    for (var i=0; i<bridges.data.length; i++){
                        console.log(bridges.data[i]);

                        //check if inspection is complete, in progress, or not started
                        if(bridges.data[i].finishedDate != null)
                            dataset.data[0]++;
                        else if(bridges.data[i].dueDate != null)
                            dataset.data[5]++;
                        else
                            dataset.data[6]++;
                        
                        //check rating of reports
                        if 
                    }
                    console.log(dataset);



                }

                fetchData();



                /* PIE CHART */
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

                const buildPieChart = async (pieOptions) => {
                    //const datasetsBuilt = await buildChartDatasets();
                    var pieChart = new Chart(pieChartCanvas, {
                        type: 'pie',
                        data: pieData,
                        options: pieOptions
                    });
                    return pieChart;
                }

                var pieChart = buildPieChart(pieOptions); 
                pieChart.then(function(response){
                    pieChart = response;
                })

               

                // 2020
                /*
                var pieChartCanvas2 = $('#pieChart2').get(0).getContext('2d')
                var pieData2 = {
                    labels: [
                        'High Risk (1-3)',
                        'Middle Risk (4-6)',
                        'Low Risk (7-9)',
                        'In-Progress',
                        'Not Started'
                    ],
                    datasets: [
                    {
                        data: [2096, 1996, 3001, 71, 131],
                        backgroundColor: ['#ff0000', '#ffea00', '#32b502', '#999999', '#f7f7f7']
                    }
                    ]
                }
                var pieOptions2 = {
                    legend: {
                        display: false
                    },
                    'onClick' : function (evt, item) {
                        //console.log('legend onClick', evt);
                        //console.log('legd item', item);
                        var e = item[0];
                        var e_idx = e._index + 1;
                        if(e_idx > 0){
                          $(".tbox").not("#rm_tt" + e_idx).hide();
                          $("#rm_tt" + e_idx).toggle();
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
                var pieChart2 = new Chart(pieChartCanvas2, {
                    type: 'pie',
                    data: pieData2,
                    options: pieOptions2
                })*/
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
                $('#tbl_bridge_insp_t4').DataTable({"order": [[ 5, "asc" ]]});
                $('#tbl_bridge_insp_t5').DataTable({"order": [[ 5, "asc" ]]});
                
                $('#tbl_bridge_insp2').DataTable({"order": [[ 6, "asc" ]]});
                $('#tbl_bridge_insp2_t1').DataTable({"order": [[ 6, "asc" ]]});
                $('#tbl_bridge_insp2_t2').DataTable({"order": [[ 6, "asc" ]]});
                $('#tbl_bridge_insp2_t3').DataTable({"order": [[ 6, "asc" ]]});
                $('#tbl_bridge_insp2_t4').DataTable({"order": [[ 5, "asc" ]]});
                $('#tbl_bridge_insp2_t5').DataTable({"order": [[ 5, "asc" ]]});
                
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

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    </body>
</html>