 {{-- <link href="{{ asset('assets/css/loader.css') }}" rel="stylesheet" type="text/css" />
 <script src="{{ asset('assets/js/loader.js') }}"></script> 
 <link href="{{ asset('assets/css/structure.css') }}" rel="stylesheet" type="text/css" class="structure" />
 <link href="{{ asset('assets/css/elements/avatar.css') }}" rel="stylesheet" type="text/css" /> 
 <link href="{{ asset('plugins/notification/snackbar/snackbar.min.css') }}" rel="stylesheet" type="text/css" />
 <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/widgets/modules-widgets.css') }}">
 <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/forms/theme-checkbox-radio.css') }}">
  --}}

 <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
 <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" /> 

 <link href="{{ asset('plugins/font-icons/fontawesome/css/fontawesome.css') }}" rel="stylesheet" type="text/css" />
 <link href="{{ asset('css/fontawesome.css') }}" rel="stylesheet" type="text/css" />
 <link href="{{ asset('assets/css/plugins.css') }}" rel="stylesheet" type="text/css" />
 <link href="{{ asset('plugins/sweetalerts/sweetalert.css') }}" rel="stylesheet" type="text/css" />
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
 <!-- END GLOBAL MANDATORY STYLES -->
 @livewireStyles

 <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->

 <style>
     .layout-px-spacing {
         min-height: calc(100vh - 170px) !important;
     }

     aside {
         display: none !important;
     }

     .page-item.active .page-link {
         z-index: 3;
         color: #fff;
         background-color: #0654a1;
         border-color: #ffffff;
     }

     @media (max-width: 480px) {
         .mtmobile {
             margin-bottom: 20px !important;
         }

         .mbmobile {
             margin-bottom: 10px !important;
         }

         .hideonsm {
             display: none !important;
         }

         .inblock {
             display: block;
         }
     }

     .page-title {
         float: none;
         margin-top: 0;
         margin-bottom: 0;
         align-self: center;
         padding-right: 15px;
         border-right: 1px solid #ffffff;
         margin-right: 10px;
     }

     .page-title h3 {
         margin-bottom: 0;
         font-size: 15px;
     }

     .page-header {
         display: flex;
         padding: 0;
         margin-bottom: 20px;
         padding-top: 16px;
     }

     .header-navbar {
         background: #0654a1 !important;
     }


     @media(max-width: 575px) {
         .page-header {
             display: block;
         }

         .page-title {
             margin-bottom: 20px;
             border: none;
             padding-right: 0;
             margin-right: 0;
         }
     }

     .widget-one {}

     .widget-one h6 {
         font-size: 20px;
         font-weight: 600;
         letter-spacing: 0px;
         margin-bottom: 22px;
     }

     .widget-one p {
         font-size: 15px;
         margin-bottom: 0;
     }
 </style>
