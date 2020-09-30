$(function () {

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    //dataTables
    $("#admin_datable").DataTable({
        "responsive": true,
        "autoWidth": false,
    });

    $("#client_datable").DataTable({
        "responsive": true,
        "autoWidth": false,
    });

    //client dataTable check all check box
    $(document).on( "click", "#client_table_check_all", function() {
        var all_checkbox = $(this).closest("table").find("input[type=checkbox]");
        if ($(this).is(":checked")) {
            all_checkbox.prop("checked", true);
        } else {
            all_checkbox.prop("checked", false);
        }
    })

    //Toast success
    $(document).on( "click", ".toastsSuccess", function() {
        Toast.fire({
            icon: 'success',
            title: 'Successfully added new client. '
        })
    });

    //Toast error
    $(document).on( "click", ".toastError", function() {
        Toast.fire({
          icon: 'error',
          title: 'Failed to add new client. '
        })
    });

    // $(".datepicker").datetimepicker({
    //     format: 'L'
    // });


})