$(document).ready(function () {
    $('#myDataTable').DataTable({
        "bPaginate": false,
        "bInfo": false
    });
});

var logout = () => {

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
            }
        },
        error: function(error)
        {
            console.log(error);
        }
    })
}