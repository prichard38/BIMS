function fetchInspections(bridgeId){
    var inspections = (function() {
        var inspections = null;
        $.ajax({
            'async': false,
            'global': false,
            'url': bridgeId + "Data.json",
            'dataType': "json",
            'success': function(data) {
            inspections = data;
            }
            
        });
        return inspections;
    })();
    return inspections;
}

function getRatings(inspectionsJson){
    ratings = [];
    inspectionsJson.data.forEach((item, index) => {
        ratings.push(item.rating);
        
    });
    return ratings;
}

function getPointColors(ratingsArray){
    colors = [];
    ratingsArray.forEach(function(rating, index){
        switch(rating){
            case 1:
                colors.push('#E40800')
                break;
            case 2:
                colors.push('#E32925')
                break;
            case 3:
                colors.push('#F26721')
                break;
            case 4:
                colors.push('#F89E33')
                break;
            case 5:
                colors.push('#EEC200')
                break;
            case 6:
                colors.push('#ECD715')
                break;
            case 7:
                colors.push('#609B41');
                break;
            case 8:
                colors.push('#2E7A3C');
                break;
            case 9:
                colors.push('#036353');
                break;
        }
    })
    return colors;
}