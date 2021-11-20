# BIMS - CS 490 Capstone Fall 2021
## Web Application - Report Management for Supervisors - Developer Reference

#### This guide is meant to serve as a reference and provide helpul information related to the development process for BIMS Report Management for supervisors, including the Yearly Inspection Report Tool and the Longitudinal Analysis Tool 

###### *NOTE: The BIMS web application is an ongoing project still in development.* 
--------------------------------------------------------

### Contribution of the Fall 2021 Capstone Team
The Fall 2021 Capstone team contribution to the BIMS web application includes database integration, creation of distinct user roles(admin, inspector, and supervisor), implementation of the login process for each role, and implementation of Report Management features for the supervisor role, including the Yearly Inspection Report Tool and the Longitudinal Analysis Tool.

### Report Management Features
The Report Management features in this web application allow supervisors to view compiled bridge inspection report data. There are two main Report Management features: the ***Yearly Inspection Report Tool*** and the ***Logitudinal Analysis Tool***. 

##### Yearly Inspection Report Tool 
This tool provides a summary of all bridge inspection data for the selected year. 

##### Longitudinal Analysis Tool 
This tool provides a breakdown of bridge inspection data over a given timeframe of up to 10 years, with visual line chart representations of inspection ratings and the ability to compare up to three bridges simultaneously.

--------------------------------------------------------
### Use of Chart.js Library

Both the Yearly Inspection Report Tool (YIRT) and the Longitudinal Analysis Tool (LAT) make use of the Chart.js library to render interactive charts for displaying bridge inspection report data. The YIRT makes use of the Chart.js Pie Chart and the LAT makes use of the Chart.js Line Chart.

#### Helpul Links:
- Chart.js: https://www.chartjs.org/
- Line Chart Documentation: https://www.chartjs.org/docs/2.9.4/charts/line.html 
- Pie Chart Documentation: https://www.chartjs.org/docs/2.9.4/charts/doughnut.html

### Use of DataTables Plugin

Both the Yearly Inspection Report Tool (YIRT) and the Longitudinal Analysis Tool (LAT) make use of the DataTables plugin for jQuery to render interactive data tables for displaying bridge inspection report records.

#### Helpful Links:
- DataTables Documentation: https://datatables.net/manual/index
- Initializing DataTables in the LAT (see function *loadTable* in *la-functions.js*): https://datatables.net/reference/option/data


--------------------------------------------------------

### Data Flow

In general, the flow of data within the web application can be seen in the diagram below: 

![Data Flow](/guides/DataFlowWebApp.png)

--------------------------------------------------------

### A Note About the Use of PHP Session Variables in Report Management Features

For the YIRT and LAT, php session variables are used to store current user input parameters for generating reports. These session variables are used to maintain the state of report screens throughout the session, and are updated as the user makes new selections or enters new report parameters. For example, selecting a new year in the YIRT or selecting new bridges or a new timeframe in the LAT. 

#### Longitudinal Analysis Tool Session Variables

- ***selectedBridgeNames****
- ***selectedBridgeNumbers****
- ***selectedBridgeCounties****
- ***yearBegin***
- ***yearEnd*** 

###### *Bridge names, numbers, and counties map to one another by index. This means that the bridge name at *selectedBridgeNames[i]* corresponds to the bridge number at *selectedBridgeNumbers[i]* and the county at *selectedBridgeCounties[i]*.

#### Yearly Inspection Report Tool Session Variable
- ***YIR_SelectedYear***

