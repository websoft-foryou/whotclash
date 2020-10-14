jQuery(document).ready(function () {
    var data_table = $('#main_table').DataTable({
        responsive: true,
    });

    $( "#data-form" ).validate({
        rules: {
            coins: { required: true, digits: true },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $('#main_table tbody tr .add-coins').on('click', function () {
        $('#add_coins_modal #data_id').val($(this).data('id'));
        $('#add_coins_modal #coins').val('');
        $('#add_coins_modal').modal('show');
    });

    $('#main_table tbody tr .coin-history').on('click', function () {
        $('#data_id').val($(this).data('id'));
        $('#main-form').attr('method', 'post');
        $('#main-form').attr('action', $('#history_url').val());
        $('#main-form').submit();
    });
});
