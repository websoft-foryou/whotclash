jQuery(document).ready(function () {
    var data_table = $('#play_history_table').DataTable({
        responsive: true,
    });

    if ($('#view_play_date').length > 0) {
        var start = moment().subtract(29, 'days');
        var end = moment();

        $('#view_play_date').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',

            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end, label) {
            $('#playdate_from').val(start.format('YYYY-MM-DD'));
            $('#playdate_to').val(end.format('YYYY-MM-DD'));

            $('#main-form').submit();
            $('#view_play_date .form-control').val( start.format('YYYY-MM-DD') + ' ~ ' + end.format('YYYY-MM-DD'));
        });
    }


});
