<!--<script src="../https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
<script src="../https://maps.googleapis.com/maps/api/js?key=["your-google-map-api"]&libraries=places"></script>-->
<script src="{{url('')}}/dist/js/app.js"></script>
<script src="{{url('')}}/assets/jquery/jquery-3.6.1.min.js"></script>
<script src="{{url('')}}/assets/datatable/datatables.min.js"></script>
<script src="{{url('')}}/assets/toastify/toastify.js"></script>
<script src="{{url('')}}/assets/litepicker/dist/litepicker.js"></script>
<!--<script src="{{url('')}}/assets/litepicker/dist/litepicker.umd.js"></script>-->
<script src="{{url('')}}/assets/litepicker/dist/plugins/ranges.js"></script>
<script src="{{url('')}}/assets/dayjs/dayjs.min.js"></script>
{{-- <script src="{{url('')}}/js/notification.js{{GET_RES_TIMESTAMP()}}"></script>
<script src="{{url('')}}/js/notificationcore.js{{GET_RES_TIMESTAMP()}}"></script>
<script src="{{url('')}}/assets/uniupload/uniupload.js{{GET_RES_TIMESTAMP()}}"></script>
<script src="{{url('')}}/js/datepicker.js{{GET_RES_TIMESTAMP()}}"></script> --}}

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{--<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>--}}
{{--<script src="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.js"></script>--}}
{{--<script src='https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js'></script>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>--}}

<!-- Plugin Js-->
<!--
<script src="{{url('')}}/assets/bundles/dataTables.bundle.js"></script>
-->
<script src="{{url('')}}/vendor/sweetalert2/dist/sweetalert2.min.js"></script>

<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

{{-- <script src="{{BASEPATH()}}/vendor/select2/js/select2.js{{GET_RES_TIMESTAMP()}}"></script> --}}

<link rel="stylesheet" href="{{url('')}}/src/js/app.js" />

<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>

{{-- <script type="text/javascript" src="{{url('')}}/vendor/tooltipster/js/tooltipster.bundle.min.js{{GET_RES_TIMESTAMP()}}"></script> --}}

{{-- <script src="{{BASEPATH()}}/js/common.js{{GET_RES_TIMESTAMP()}}"></script> --}}

<script src="{{url('')}}/assets/bundles/apexcharts.bundle.js"></script>

<script src="{{url('')}}/assets/dayjs/dayjs.min.js"></script>

<script src="{{url('')}}/assets/filepond-master/dist/filepond.js"></script>
<script src="{{url('')}}/assets/filepond-master/dist/filepond.min.js"></script>
<script src="{{url('')}}/assets/filepond-master/dist/file-size-validation/filepond-plugin-file-validate-size.min.js"></script>
<script src="{{url('')}}/assets/filepond-master/dist/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js"></script>
<script src="{{url('')}}/assets/filepond-master/dist/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js"></script>
<script src="{{url('')}}/assets/jquery-filepond-master/filepond.jquery.js"></script>
{{--<script src="{{asset('js/app.js')}}"></script>--}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

{{-- <script src="{{BASEPATH()}}/js/global_js.js{{GET_RES_TIMESTAMP()}}"></script> --}}


 <!-- Font Awesome 6.2.1 JavaScript -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/js/all.min.js" integrity="sha512-rpLlll167T5LJHwp0waJCh3ZRf7pO6IT1+LZOhAyP6phAirwchClbTZV3iqL3BMrVxIYRbzGTpli4rfxsCK6Vw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<!-- Add this script at the bottom of your layout, just before the closing </body> tag -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>


{{-- BEGIN: Opaw Shortcuts --}}
<div id="error_modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-10 text-center" id="error_msg"> Error! If the issue persists, please reload the page. If the problem still occurs, please contact us. </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.basic_select2').select2();
    });

    function close_modal(id) {
        const myModal = tailwind.Modal.getOrCreateInstance(document.querySelector(id));
        myModal.hide();
    }
    function open_modal(id) {
        const myModal = tailwind.Modal.getOrCreateInstance(document.querySelector(id));
        myModal.show();
    }
    function error_modal(msg) {
        $('#error_msg').html(msg);
        open_modal('#error_modal');
    }
</script>
{{-- END: Opaw Shortcuts --}}

