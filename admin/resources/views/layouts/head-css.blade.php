    <!-- [Google Font : Public Sans] icon -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ URL::asset('build/fonts/tabler-icons.min.css') }}" >
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ URL::asset('build/fonts/feather.css') }}" >
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ URL::asset('build/fonts/fontawesome.css') }}" >
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ URL::asset('build/fonts/material.css') }}" >
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ URL::asset('build/css/style.css') }}" id="main-style-link" >
    <link rel="stylesheet" href="{{ URL::asset('build/css/style-preset.css') }}" >
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 26px;
            position: absolute;
            top: 9px !important;
            right: 3px !important;
            width: 20px;
        }

        .select2-container .select2-selection--single{
            height: 46px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            height: 46px !important;
            min-height: 46px !important; /* Adjust as needed */
            padding-top: 10px; /* Adjust as needed */
            padding-bottom: 10px; /* Adjust as needed */
        }
    </style>