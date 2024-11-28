<!--ALL THIRD PART JAVASCRIPTS-->
<script src="public/vendor/js/vendor.footer.js?v={{ config('system.versioning') }}"></script>

<!--nextloop.core.js-->
<script src="public/js/core/ajax.js?v={{ config('system.versioning') }}"></script>

<!--MAIN JS - AT END-->
<script src="public/js/core/boot.js?v={{ config('system.versioning') }}"></script>

<!--EVENTS-->
<script src="public/js/core/events.js?v={{ config('system.versioning') }}"></script>

<!--CORE-->
<script src="public/js/core/app.js?v={{ config('system.versioning') }}"></script>

<!--SEARCH-->
<script src="public/js/core/search.js?v={{ config('system.versioning') }}"></script>

<!--BILLING-->
<script src="public/js/core/billing.js?v={{ config('system.versioning') }}"></script>

<!--project page charts-->
@if (@config('visibility.projects_d3_vendor'))
    <script src="public/vendor/js/d3/d3.min.js?v={{ config('system.versioning') }}"></script>
    <script src="public/vendor/js/c3-master/c3.min.js?v={{ config('system.versioning') }}"></script>
@endif

<!--form builder-->
@if (@config('visibility.web_form_builder'))
    <script src="public/vendor/js/formbuilder/form-builder.min.js?v={{ config('system.versioning') }}"></script>
    <script src="public/js/webforms/webforms.js?v={{ config('system.versioning') }}"></script>
@endif

<!--export js (https://github.com/hhurz/tableExport.jquery.plugin)-->
<script src="public/js/core/export.js?v={{ config('system.versioning') }}"></script>
<script type="text/javascript"
    src="public/vendor/js/exportjs/libs/FileSaver/FileSaver.min.js?v={{ config('system.versioning') }}"></script>
<script type="text/javascript"
    src="public/vendor/js/exportjs/libs/js-xlsx/xlsx.core.min.js?v={{ config('system.versioning') }}"></script>
<script type="text/javascript" src="public/vendor/js/exportjs/tableExport.min.js?v={{ config('system.versioning') }}">
</script>

<!--printing-->
<script type="text/javascript" src="public/vendor/js/printthis/printthis.js?v={{ config('system.versioning') }}">
</script>

<!--table sorter-->
<script type="text/javascript"
    src="public/vendor/js/tablesorter/js/jquery.tablesorter.min.js?v={{ config('system.versioning') }}"></script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- jQuery (necessary for Select2) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- HTML2PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

<script>
    function printContentPDF($content, $fileName) {
            const element = document.getElementById($content); // Ensure this matches your element ID
            const opt = {
                margin: 1,
                filename: $fileName,
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'portrait'
                }
            };
            html2pdf().set(opt).from(element).save();
        }
</script>