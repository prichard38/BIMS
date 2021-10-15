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