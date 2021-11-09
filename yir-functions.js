function fetchNewestBridgeData(year) {
    return new Promise(function(resolve, reject) {
        data = [];
        
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // console.log(this.responseText);
            }
        };
        xhr.open('POST', 'yir-load-bridge-data.php', true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        

        xhr.onload = function() {
            console.log(this.responseText);
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