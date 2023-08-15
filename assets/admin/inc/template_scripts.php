<?php
/**
 * template_scripts.php
 *
 * Author: pixelcave
 *
 * All vital JS scripts are included here
 *
 */
?>

<!-- Include Jquery library from Google's CDN but if something goes wrong get Jquery from local file -->
<script src="<?php echo ASSETS_JS; ?>vendor/jquery-1.11.2.min.js"></script>
<!-- Bootstrap.js, Jquery plugins and Custom JS code -->
<script src="<?php echo ASSETS_JS; ?>vendor/bootstrap.min.js"></script>
<script src="<?php echo ASSETS_JS; ?>plugins.js"></script>
<script src="<?php echo ASSETS_JS; ?>app.js"></script>

 <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.69/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.6/js/responsive.bootstrap4.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?region=JP&key=AIzaSyC45H8puiP7Gj6ZTyNT0Hobx8RyPLeyYiY"></script><!-- &key=AIzaSyC1z8cxwh0IKQhvHl-Cezi7lt1ARoFkJWo -->
<script src="<?php echo ASSETS_JS; ?>helpers/gmaps.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/js/bootstrap-datetimepicker.min.js"></script>

<!-- Add below custom javascriipt with common dunctions-->
<script src="<?php echo ASSETS_JS; ?>common.js"></script>

<script src="<?php echo ASSETS_JS; ?>orgchart.js"></script>
<script src="<?php echo ASSETS_JS; ?>highcharts.js"></script>  