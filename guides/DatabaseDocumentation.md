
# BIMS - CS 490 Capstone Fall 2021
## BIMS Database Documentation

#### This guide is meant to serve as a reference for information related to the BIMS database as implemented by the Fall 2021 Capstone Team.

###### *NOTE: The BIMS database is part of an ongoing project still in development.* 
--------------------------------------------------------
### Contribution of the Fall 2021 Capstone Team
The Fall 2021 Capstone team integrated the BIMS database, in part, with the BIMS web application and mobile photography application. Because not all tables were required for the Fall 2021 Capstone project, most tables in the database are not accessed and currently contain no test data. The tables that are relevant to the Fall 2021 Capstone contribution are outlined in the following section.

--------------------------------------------------------
### Database Tables of Interest

The following tables contain test data and are accessed by either the web application, the mobile application, or both:

- Bridges
- County
- DroneImages
- DroneImageSet
- Inspections
- InspectionTypeCode
- UserRole
- Users

### Important Notes About Tables
#### Inspections Table
- Primary Key ***InspectionID*** is auto-increment.
- Every inspection record requires (cannot be null) ***AssignedDate*** and ***DueDate*** at the time of insertion.
- The ***Status*** column in the ***Inspections*** table should contain one of three possible values for any given inspection record: "not started", "in progress", or "complete". 
- For records with ***Status*** "not started" or "in progress", ***FinishedDate***, ***Report***, and ***OverallRating*** should be null.
- For records with ***Status*** "complete", ***FinishedDate***, ***Report***, and ***OverallRating*** should NOT be null.
- The ***Report*** column currently contains "filler" notes about the inspection, but in the future this column may contain the filepath for the inspection report PDF file corresponding to the inspection record. The filepath may then be used to generate dynamic links to report PDFs from data tables within the web application. 

#### County Table
The integer value in the ***CountyNo*** column for each country is assigned based on the alphabetical ordering of the counties.

--------------------------------------------------------

### Naming Conventions
#### Foreign Keys (Column Name)
Foreign Keys in any given table follow the naming convention *PrimaryKeyTable_PrimaryKeyTableColumn*. 

###### Example: The **Inspections** table has a foreign key ***County_CountyNo*** that references the primary key, ***CountyNo***, of the **County** table.

#### Foreign Key Constraints
Foreign Key Constraints follow the naming convention *fk_ForeignKeyTable_PrimaryKeyTable*. 

###### Example: The **Bridges** table has a foreign key constraint *fk_Bridges_County* because the **Bridges** table contains the foreign key, ***County_CountyNo***, that references the primary key, ***CountyNo***, in the **County** table. 

--------------------------------------------------------
### Entity Relationship Diagram (ERD)

The ERD for the BIMS Database is also available in the `SQL` folder of this project in PNG, SVG, and MWB (MySQL Workbench Document) formats.
![Data Flow](/SQL/BIMS_ERD.png)

