$(document).ready(function () {
    $('#myDataTable').DataTable({
        "bPaginate": false,
        "bInfo": false
    });
});

var logout = () => {
    swal("Logout!", "Successfully", "success");
}