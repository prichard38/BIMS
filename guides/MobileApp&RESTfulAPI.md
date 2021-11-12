
# BIMS - CS 490 Capstone Fall 2021
## Mobile Application

##### This application allows inspectors to select photos from their gallery, add meta-data, and submit them to the BIMS server. 

--------------------------------------------------------
### The Initial Setup Process

Steps used to create the application:
1. Install NodeJs with npm (I used yarn instead of npm, but either should work. Just don't mix and match; pick one.)
2. Install Expo `npm install --global expo-cli`
3. Create new app (navigate into folder where you want to create the project first). `expo init bims-photos`
4. Navigate into project directory for future steps. `cd bims-photos`
5. Install all dependencies we need for the project `expo install @react-navigation/native @react-navigation/native-stack @react-navigation/stack react-native-screens react-native-safe-area-context @ant-design/icons-react-native @expo/vector-icons` 
   
To Run the Application During Development:
    `expo start`

----------------------------------------------------------
### Structure/Design
Four folders were created after initializing the app:
1. *screens*: holds javascript for each screen in the application (log-in, home, etc.)
2. *components*: holds javascript for each tool created (taskbar, buttons, etc.)
3. *images*: holds images used within application
4. *tempdata*: stores data locally for development; should be replaced with more secure storage in production

The entry point of the app is *App.js*, and React Navigation (Stack implementation) is used to manage the different screens users can visit. 
The files in *screens* folder are each one screen, and they contain all necessary implementation for that screen.
To understand the usage of React Navigation: https://reactnative.dev/docs/navigation

#### App.js
Entry point for the application holding the stack of screens. It also keeps track of which inspection the user is submitting photos for in *selected_inspection* state.

#### Initial Screen

This screen shows the user a loading circle as it runs the functions necessary to set up the application. It checks if the user has credentials saved locally (has logged in before). If so, it fetches the inspections from the server and then takes the user to the Inspections Screen. If not, the user is redirected to the Log-In Screen. Also, it checks for connection to the server. 

#### Inspections Screen

This screen displays a list of inspections from */tempdata/inspections.json*; when the user clicks on one, it updates the *selected_inspection* state in *App.js*.
  

#### Upload Screen 

This screen allows users to select an image from the gallery and add metadata. The user can then submit this photo to the server with an API call, and it will use the inspection from *selected_inspection* state in *App.js*.

#### Log-In Screen 

Allows users to enter credentials. These credentials are then verified with the server using API call and then saved locally so the user doesn't need to log in again. 

----------------------------------------------------------

## API Application

##### This application provides url API requests on the server using an ExpressJS App on port 3000. This way, the mobile application can easily communicate with the server. 

--------------------------------------------------------



#### Setup Process

1. Prerequisite: have the newest NodeJS version with npm. (check `node -v`)
2. Navigate into the project folder and create a new Node application using npm. Follow the provided installation steps by npm.
    ```
    cd /var/www
    sudo mkdir [projectName]
    sudo npm init 
    ```
3. If you used default values to generate the package-lock file, then the entry point should be *index.js*. So create it:
    `sudo nano index.js`
4. Install dependencies:
    ```
    sudo npm install express
    sudo npm install mysql2
    ```
5. Add some code to *index.js*. Here is a basic HelloWorld example to get started:
    ```
    const express = require('express')
    const app = express()
    const port = 3000

    var mysql = require('mysql2')
    var connection = mysql.createConnection({
        host: 'localhost',
        user: 'websiteuser',  
        password: 'tvcsCD_03', 
        database: 'BIMSdb'  
    })
    connection.connect()

    app.get('/', (req, res) => {
    res.send('Hello World!')
    })

    app.listen(port, () => {
    console.log(`Example app listening at http://localhost:${port}`)
    })
    ```
6. Once *index.js* is ready to test, start the app using: `sudo node index.js`. You should see HelloWorld if you visit the server at port 3000. 

*(If you can't see the app, make sure that the port your using is allowed through the firewall. If port 3000, then: `sudo ufw allow 3000/tcp` to open it.)*

#### Routes
**/get-inspection/{id}**  
gives details about an inspection given an id#

**/upload-image/{base64}**  
posts an image to the filesystem 

----------------------------------------------------------
### Helpful Links Referenced During Development
React Component Class Template: 
https://gist.github.com/jungchris/0be463b4895e79ce8dfc1f280f830861
