<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="election">
<meta name="keywords" content="election">
<meta name="author" content="">



<!-- BEGIN: CSS Assets-->
<link rel="stylesheet" href="{{url('')}}/assets/datatable/datatables_1.13.1/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="{{url('')}}/dist/css/app.css" />
<link rel="stylesheet" href="{{url('')}}/assets/toastify/toastify.css" />

<!-- Font Awesome 6.2.1 CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Font Awesome 6.2.1 Thin CSS -->
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/thin.min.css" integrity="sha512-G/T7HQJXSeNV7mKMXeJKlYNJ0jrs8RsWzYG7rVACye+qrcUhEAYKYzaa+VFy6eFzM2+/JT1Q+eqBbZFSHmJQew==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}

<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<!-- Include FilePond CSS -->
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">

<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>


<!-- Include FilePond JS -->


{{-- selec2 cdn --}}
{{-- <link href="{{BASEPATH()}}/vendor/select2/css/select2.min.css{{GET_RES_TIMESTAMP()}}" rel="stylesheet" />
<link href="{{BASEPATH()}}/vendor/select2/css/select2.single-error.css{{GET_RES_TIMESTAMP()}}" rel="stylesheet" />
<link href="{{BASEPATH()}}/vendor/select2/css/select2.multiple-error.css{{GET_RES_TIMESTAMP()}}" rel="stylesheet" />
<link href="{{BASEPATH()}}/vendor/select2/css/select2.custom.css{{GET_RES_TIMESTAMP()}}" rel="stylesheet" /> --}}



<!-- plugin css file  -->
<link rel="stylesheet" href="{{url('')}}/assets/plugin/datatables/responsive.dataTables.min.css">
<link rel="stylesheet" href="{{url('')}}/assets/plugin/datatables/dataTables.bootstrap5.min.css">


<!--  NEW VERSION OF SWEET ALERT  -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.css">
<!--  NEW VERSION OF SWEET ALERT  -->


<link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">


<!-- chart plugs  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>


<!--Material datatable design-->
{{-- <link rel="stylesheet" href="{{url('')}}/css/datatable_custom.css{{GET_RES_TIMESTAMP()}}"> --}}


{{-- <script src="{{url('')}}/dist/js/vanilla/zoom-vanilla.js{{GET_RES_TIMESTAMP()}}"></script> --}}


  <!-- Include Dropzone.js -->
  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script> --}}


<!--Custom Calendar-->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>

<!-- Include Popper.js and Tooltip.js -->
<script src="https://unpkg.com/popper.js/dist/umd/popper.min.js"></script>
<script src="https://unpkg.com/tooltip.js/dist/umd/tooltip.min.js"></script>

<!--Custom calendar-->
