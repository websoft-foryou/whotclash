jQuery(document).ready(function () {
    var data_table = $('#main_table').DataTable({
        responsive: true,
    });

    $('#main_table tbody tr .edit').on('click', function () {
        var row_index = $(this).attr('row-index')- 1;
        var row_data = data_table.row(row_index).data();

        $('#user_name').val(row_data[1]);
        if (row_data[2] == 'male' || row_data[2] == 'Male') {
            $('#gender_male').prop('checked', true);
            $('#gender_female').prop('checked', false);
        }
        else {
            $('#gender_male').prop('checked', false);
            $('#gender_female').prop('checked', true);
        }
        $('#birthday').val(row_data[3]);
        $('#phone').val(row_data[4]);
        $('#data_id').val($(this).data('id'));

        $('#edit_user_modal').modal('show');
    });

    $('#main_table tbody tr .block').on('click', function () {
        $('#data_id').val($(this).data('id'));
        $('#main-form').attr('method', 'post');
        $('#main-form').attr('action', $('#block_url').val());
        $('#main-form').submit();
    });

    $('#main_table tbody tr .remove').on('click', function () {

        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this user!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.value) {
                $('#data_id').val($(this).data('id'));
                $('#main-form').attr('method', 'post');
                $('#main-form').attr('action', $('#remove_url').val());
                $('#main-form').submit();
            }
        });
    });

    $('#main_table tbody tr .history').on('click', function () {
        $('#data_id').val($(this).data('id'));
        $('#main-form').attr('method', 'post');
        $('#main-form').attr('action', $('#history_url').val());
        $('#main-form').submit();
    });

});
