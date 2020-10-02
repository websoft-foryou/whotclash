 </div>
        
        <!-- Bootstrap -->
        <script src="{{url('public/adminAssets')}}/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- FastClick -->
        <script src="{{url('public/adminAssets')}}/vendors/fastclick/lib/fastclick.js"></script>
        <!-- NProgress -->
        <script src="{{url('public/adminAssets')}}/vendors/nprogress/nprogress.js"></script>
        <!-- Chart.js -->
        <script src="{{url('public/adminAssets')}}/vendors/Chart.js/dist/Chart.min.js"></script>
        <!-- gauge.js -->
        <script src="{{url('public/adminAssets')}}/vendors/gauge.js/dist/gauge.min.js"></script>
        <!-- bootstrap-progressbar -->
        <script src="{{url('public/adminAssets')}}/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
        <!-- iCheck -->
        <script src="{{url('public/adminAssets')}}/vendors/iCheck/icheck.min.js"></script>
        <!-- Skycons -->
        <script src="{{url('public/adminAssets')}}/vendors/skycons/skycons.js"></script>
        <!-- Flot -->
        <script src="{{url('public/adminAssets')}}/vendors/Flot/jquery.flot.js"></script>
        <script src="{{url('public/adminAssets')}}/vendors/Flot/jquery.flot.pie.js"></script>
        <script src="{{url('public/adminAssets')}}/vendors/Flot/jquery.flot.time.js"></script>
        <script src="{{url('public/adminAssets')}}/vendors/Flot/jquery.flot.stack.js"></script>
        <script src="{{url('public/adminAssets')}}/vendors/Flot/jquery.flot.resize.js"></script>
        <!-- Flot plugins -->
        <script src="{{url('public/adminAssets')}}/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
        <script src="{{url('public/adminAssets')}}/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
        <script src="{{url('public/adminAssets')}}/vendors/flot.curvedlines/curvedLines.js"></script>
        <!-- DateJS -->
        <script src="{{url('public/adminAssets')}}/vendors/DateJS/build/date.js"></script>
        <!-- JQVMap -->
        <script src="{{url('public/adminAssets')}}/vendors/jqvmap/dist/jquery.vmap.js"></script>
        <script src="{{url('public/adminAssets')}}/vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
        <script src="{{url('public/adminAssets')}}/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
        <!-- bootstrap-daterangepicker -->
        <script src="{{url('public/adminAssets')}}/vendors/moment/min/moment.min.js"></script>
        <script src="{{url('public/adminAssets')}}/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

        <!-- Custom Theme Scripts -->
        <script src="{{url('public/adminAssets')}}/build/js/custom.min.js"></script>
        <script src="{{url('public/adminAssets')}}/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{url('public/adminAssets')}}/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="{{url('public/adminAssets')}}/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{url('public/adminAssets')}}/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="{{url('public/adminAssets')}}/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
        <script>
            $('.navbar-right .user-profile.dropdown-toggle').click(function() {
                $("body").removeClass("nav-sm").addClass('nav-md');
            });

            setTimeout(function(){
                $(".alert").hide();    
            },5000);

        </script>
</body>

</html>