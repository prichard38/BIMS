
# BIMS - CS 490 Capstone Fall 2021
## Local Development Environment Setup

#### This guide is meant to assist in setting up the local development environment for the BIMS web application.

#### *NOTE: The BIMS web application is an ongoing project still in development.* 

--------------------------------------------------------
### Development Environment Setup

#### Setting up your local environment:
1. Install XAMPP Version 7.4.23 or above. Download: https://www.apachefriends.org/download.html  (* *Note that XAMPP contains the database administration tool, phpMyAdmin, for MariaDB and MySQL.*)
2. Install Visual Studio Code. Download: https://code.visualstudio.com/download

##### XAMPP FAQs:
* Mac: https://www.apachefriends.org/faq_osx.html
* Windows: https://www.apachefriends.org/faq_windows.html

##### Visual Studio Code Installation Manual:
* Mac: https://code.visualstudio.com/docs/setup/mac 
* Windows: https://code.visualstudio.com/docs/setup/windows

#### Installation of the BIMS Codebase on your local machine (Mac):
1. Download the source code ZIP file from GitHub: https://github.com/prichard38/BIMS
2. Open XAMPP
3. In  **[General]** tab of XAMPP panel, click **[Start]** button.
4. In  **[Services]** tab of XAMPP panel, select *Apache* and click **[Start]**. Then select *MySQL* and click **[Start]**.
5. In **[Network]** tab of XAMPP panel, select **[localhost:8080->80(OverSSH)]** and
click **[Enable]** button.
6. In **[Volumes]** tab of XAMPP panel, click **[Mount]** button.
7. Un-compress the source code zip file, `BIMS-main`, in XAMPP’s `htdocs` directory (usual folder path: `/opt/lampp/htdocs`). If you cannot find the folder, click the **[Explore]** button in the **[Volumes]** tab of XAMPP panel. You will be moved to `lampp` directory and can find the `htdocs` folder. ***It is suggested to rename the folder from `BIMS-main` to `BIMS`.***
8. Now you are ready to use it. You can access the web application by typing in the web browser http://localhost:8080/BIMS/login.html (The port number 8080 may be different depending on the MAMP setting.)

#### Installation of the BIMS Codebase on your local machine (Windows):

#### Installation of the BIMS Database and Procedures on your local machine:

1. With XAMPP services started (see steps 2 - 5 above), navigate to phpMyAdmin in your web browser by entering the url `localhost8080/dashboard`, or just simply `localhost8080`, then clicking on **[phpMyAdmin]** in the top nav bar.
2. Inside phpMyAdmin, click on **[Import]** in the top nav bar.
3. Click the **[Choose File]** button. Navigate to the directory `/opt/lampp/htdocs/BIMS-main/SQL_localhost` and select the file `BIMSdb.sql` to attach.
4. Click the **[Go]** button. This will build the BIMS database structure and fill it with test data.
5. Repeat steps 1 and 2
6. Click the **[Choose File]** button. Navigate to the directory `/opt/lampp/htdocs/BIMS-main/SQL_localhost` and select the file `Procedures.sql` to attach.
4. Click the **[Go]** button. This will build create the stored procedures in your database.


Now you may open the directory `/opt/lampp/htdocs/BIMS-main` in Visual Studio Code and begin development.




