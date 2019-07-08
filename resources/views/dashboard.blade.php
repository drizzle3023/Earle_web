@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Dashboard</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">App</li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
       </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <h3 class="block-title">Map</h3>
            </div>
            <div id="map" style="height: 600px;"></div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection

@section('js_after')
    <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUEIV1ntWNv-ByrxninSRc6Wdsu8jEXrA">
    </script>

    <script>
        var mainMap = new google.maps.Map(
            document.getElementById('map'), {zoom: 4, center: {lat: 0, lng: 0}});
    </script>
@endsection