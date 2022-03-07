$(document).ready(function() {
    var table = $('#example').DataTable({

        //add color if temp is too hot
        "createdRow": function(row, data, dataIndex) {
            if (data[1] > data[3]) {        
                $(row).css('background-color', 'rgba(255, 222, 222, 1)');
                $(row).css('color', 'red');
            }
            else if (data[1] < data[2]){
                $(row).css('background-color', 'rgba(212, 227, 255, 1)');
                $(row).css('color', 'blue');
            }
            else{
                $(row).css('background-color', 'rgba(220, 255, 211, 1)');
                $(row).css('color', 'green');
            }
        },

        //order by id, descending
        "order": [[0, "desc"]],        

        //hide id
        "columnDefs": [
            { "targets": [0], "visible": false, "searchable": false },
            { targets: [1, 2, 3], render: function(data) {
                return data + '%';
            }},
            { targets: [4], render: function(data) {
                return data + '°C';
            }
        }],

        //get data from web service
        ajax: "WebService/getTempHumRecords.php"
    });
    
    //refresh table every second
    setInterval(function() {
        table.ajax.reload(null, false); // user paging is not reset on reload
    }, 1000);

    getAverageRecords();
    getHumidityRange();
    updateEmailShown();
    updateDeviceStatus();

    setInterval(getAverageRecords, 1000);
    setInterval(getHumidityRange, 1000);
    setInterval(updateEmailShown, 1000);
    setInterval(updateDeviceStatus, 1000);
});

function getAverageRecords() {
    $.ajax({
        type: 'POST',
        url: 'WebService/getAverageTempHum.php',
        success: function(data) {
            const split_data = data.split(',');
            $('#averageTemp').html(split_data[0] + "°C");
            $('#averageHum').html(split_data[1] + "%");
        }
    });
}

function getHumidityRange() {
    $.ajax({
        type: 'POST',
        url: 'WebService/getHumidityRange.php',
        success: function(data) {
            $('#humidityRange').html(data);
        }
    });
}

function getCurrentHumidity() {
    $.ajax({
        type: 'POST',
        url: 'WebService/getCurrentHumidity.php',
        success: function(data) {
            $('#currentHumidity').html(data + "%");
        }
    });
}

function updateHumidifierStatus() {
    $.ajax({
        type: 'POST',
        url: 'WebService/getDehumidifierStatus.php',
        success: function(data) {
            var dehumidifierStatus;
            if(data == "LOW") {
                dehumidifierStatus = "OFF : ";
            }
            else {
                dehumidifierStatus = "ON : "
            } 
            $('#dehumidifierStatus').html(dehumidifierStatus + data);
        }
    });    
}

function updateDeviceStatus() {
    $.ajax({
        type: 'POST',
        url: 'WebService/getDeviceStatus.php',
        success: function(data) {
            $('#deviceStatus').html(data);
            if(data == "OFFLINE") {
                $('#dehumidifierStatus').html("OFF");
                $('#currentHumidity').html("N/A");
            }
            else {
                getCurrentHumidity();
                updateHumidifierStatus();
            }
        }
    });
}

function updateEmailShown() {
    $.ajax({
        type: 'POST',
        url: 'Email/getEmail.php',
        success: function(data) {
            $('#emailNotification').html(data);
        }
    });
}