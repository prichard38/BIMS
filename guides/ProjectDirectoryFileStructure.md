
# BIMS - CS 490 Capstone Fall 2021
## Project Directory and File Structure

#### This guide is meant to serve as a reference for project directory and file structure, including file descriptions and other general information.

###### *NOTE: The BIMS web application is an ongoing project still in development.* 
--------------------------------------------------------
### Directory/File Structure
##### The project directory, `/opt/lampp/htdocs/BIMS-main`, contains the following top-level folders and files:
1. `admin` : Contains all files specifically related the *admin* role (admin screens, javascript, and php scripts)
2.  `assets` : Contains all image, pdf, 3D model, and custom CSS assets
3. `guides` : Contains all markdown guides related to this project (like this one)
4. `inspector` : Contains all files specifically related the *inspector* role (inspector screens, javascript, and php scripts)
5. `plugins` : Contains all third-party plugins/libraries (examples: Chart JS, DataTables, BootStrap)
6. `SQL` : Contains all SQL files, including database seed (dump) file, `BIMSdb.sql`, and all stored procedure definitions
7. `supervisor` : Contains all files specifically related the *supervisor* role (supervisor screens, javascript, and php scripts) 
8. *access-denied.php*: Shows "Access Denied" message upon attempting to access pages/files that are forbidden based upon user role pivileges
8. *dbConfig.inc.php*: Database configuration file
9. *login.php*: Login screen
 

##### Sub-folder and file descriptions for all top-level folders:
`admin`
- All files contained in `admin` are html template files for the admin role. With the exception of modifying *hrefs* for linking to the new additions the the web app, these files were not modified in any way for the Fall 2021 BIMS capstone project.


`assets`
- `css` (sub-folder): Contains *custom.css*
- Inspection report PDF files
- 3D model files (file extension *.glb*)

`guides`
- *MobileApp&RESTfulAPI*: developer markdown guide for BIMS mobile application and RESTful API for mobile app communication with server
- *WebApp*: developer markdown guide for BIMS web application (this file)
- *WebAppReportMangement*: developer markdown guide for supervisor report management features, Yearly Inspection Report Tool and Longitudinal Analysis Tool

`inspector`
- All files contained in `inspector` are html template files for the inspector role. With the exception of modifying *hrefs* for linking to the new additions the the web app, these files were not modified in any way for the Fall 2021 BIMS capstone project.

`plugins`
- `chart.js` (sub-folder): Contains all files related to the Chart JS plugin, which is used to generate pie charts and line charts within the BIMS web application
- `css` (sub-folder): Contains all CSS files related to any third-party styling libraries, such as Bootstrap
- `DataTables` (sub-folder): contains all files related to the DataTables plugin, which is used to generate the data tables throughout the BIMS web application.
- `js` (sub-folder): contains JavaScript files related to plugins/libraries, including Bootstrap and jQuery
- `yearpicker` (sub-folder): contains all files related to the yearpicker plugin

`SQL`
- *BIMS_ERD.mwb*: MySQL Workbench Document containing the ERD model of the BIMS database. This file is included because working with ERD models is much better in MySQL Workbench than in phpMyAdmin.
- *BIMS_ERD.png*: The BIMS database ERD as an image
- *BIMS_ERD.svg*: The BIMS database ERD as an image
- *BIMSdb.sql*: seed (dump) file for the BIMS database. Running this script will build the database structure, create stored procedures, and fill it with test data.
- *procedure_LA_tool.sql*: contains the stored procedure definitions for procedures used for performing database queries for the Longintudinal Anaylsis Tool
- *procedure_Login.sql*: contains the stored procedure definitions for procedures used during the login process
- *procedure_REST_API.sql*: contains the stored procedure definitions for procedures used in the REST API for mobile app integration
- *procedure_YIR_tool.sql*: contains the stored procedure definitions for procedures used for performing database queries for the Yearly Inspection Report Tool

`supervisor`
- *LA-functions.js*: Contains JavaScript function definitions related to the Longitudinal Analysis Tool. 

- `php-scripts-longitudinal-analysis` (sub-folder): contains all php scripts used for fetching data for the Longitudinal Anaylysis Tool
    - *load-bridge-data.php*: Performs a database query that selects the name, number, and county for all bridges in the database
    - *load-earliest-year.php*: Calls the stored procedure, *getEarliestYear*, to get the earliest inspection year from among selected bridges
    - *load-inspections.php* Calls the stored procedure, *selectBridgeInspectionData_BetweenYears*, to select all inspection data between the selected begin and end year for a single bridge
    - *reset-session-longitudinal-analysis.php*: Unsets all php session variables related to longitudinal anaylsis. Runs when user chooses to perform a new longitudinal analysis during the same session
    - *set-bridge-session-vars.php*: sets session variables for selected bridges to be analyzed in longitudinal analysis
    - *set-years-session-vars.php*: sets session variables for selected begin year and end year for the longitudinal analysis timeframe

- `php-scripts-yearly-inspection-report` (sub-folder): contains all php scripts used for fetching data for the Longitudinal Anaylysis Tool
    - *YIR-load-bridge-data.php*: Calls the stored procedure, *selectNewestInspectionData_ByYear*, to select the newest inspection data for every bridge for the selected year 
    - *YIR-update-session.php*: Updates the selected year session variable

- *supervisor-longitudinal-analysis.php*: Screen for the Longitudinal Analysis report. Contains HTML template and embedded JavaScript and PHP.

- *supervisor-LA-params.php*: Screen for searching for and selecting bridges and timeframe parameters for the Longitudinal Analysis report. Contains HTML template and embedded JavaScript and PHP.

- *supervisor-yearly-inspection-report.php*: Screen for the Yearly Inspection Report Tool. Contains HTML template and embedded JavaScript and PHP.
----------------------------------------------------------





