
### Site is hosted on HostGator as a subdomain of *marshallcsee.com*

To access site files, permission and credentials are required to access the HostGator CPanel. Please contact:
- Ms. Hwapyeong Song  -  song24@marshall.edu
- Dr. Wook-Sung Yoo  -  yoow@marshall.edu



### Deployment Steps using CPanel
1. Created a new subdomain under *marshallcsee.com* on HostGator by selecting *Subdomains* (The 2021 capstone deployment is the subdomain *bims.marshallcsee.com*).
2. Copied all files into the subdomain using the *File Manager*.
3. Then selected *MySQL Databases* and created a new database named *wooksung_BIMSdb*.
4. Then created a new user *wooksung_bims* and added the user to the database. *wooksung_bims* needs 2 priviledges:
    ```
    1. SELECT ON wooksung_BIMSdb;       (to grab data in some php scripts)
    2. EXECUTE ON wooksung_BIMSdb;      (for stored procedures)
    ```
5. Select *PHPMyAdmin* on CPanel. Select *wooksung_BIMSdb* and goto the **Import** tab. Import the file BIMSdb.sql to create the database structure and populate some tables with mock data. 
6. Then select the *SQL* tab and run Procedures.sql to create the Routines for the database. 

