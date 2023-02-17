
$(document).ready(function () {
    viewData();
});

var viewData = () => {
    $.ajax({
        type: "POST",
        url: "include/router.php",
        data: { choice: 'displayData' },
        success: function (data) {
            var json = JSON.parse(data);
            var str = "";
            json.forEach(element => {
                str += "<tr>";
                str += "<td>" + element.username + "</td>";
                str += "<td>" + element.email + "</td>";
                str += "<td>" + element.joined + "</td>";
                str += "<td>" + element.type + "</td>";
                str += "<td>" + element.status + "</td>";
                str += "</tr>";
            });
            $('#tbl_Data').append(str);
            displayTable();
        },
        error: function (error) {
            console.log(error);
        }
    });
}

var displayTable=()=>
{
    $('#myDataTable').DataTable({
        "bPaginate": false,
        "bInfo": false,
        // responsive: true,
        // "autoWidth": false,
        // dom: 'Bfrtip',
        // buttons: [
        //     {
        //         extend: 'csvHtml5',
        //         text: 'Export to CSV'
        //     }
        // ]
    });
}

var logout =()=> 
{

    $.ajax({
        type: "POST",
        url: "include/router.php",
        data:{choice:'logout'},
        success: function(res)
        {
            if(res==200)
            {
                swal("Logout!", "Successfully", "success");
                setTimeout(()=>{location.replace("index.html");}, 1500);
                var status = 0;
                isOffline(status);
            }
        },
        error: function(error)
        {
            console.log(error);
        }
    })
}

var isOffline=(flag)=>
{
    $.ajax({
        type: "POST",
        url: "include/router.php",
        data: {choice: 'offStatus', flag:flag},
        success: function(data)
        {
            alert(data);
        },
        error: function(error)
        {
            alert(error);
        }
    })
}