@extends('layouts.backend')

@section('css_before')
<link rel="stylesheet" href="{{asset('js/plugins/datatables/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('js/plugins/magnific-popup/magnific-popup.css')}}">
@endsection

@section('css_after')
    <style>
        .img-fluid {
            width: 240px;
        }
    </style>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Image list</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">App</li>
                        <li class="breadcrumb-item active" aria-current="page">Image list</li>
                    </ol>
                </nav>
            </div>
       </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">

        <!-- Dynamic Table Full -->
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <h3 class="block-title">Image list</h3>
            </div>

            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->

                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                    <tr>
                        <th class="text-center" style="width: 80px;">#</th>
                        <th>Image</th>
                        <th class="d-none d-sm-table-cell" >Filename</th>
                        <th class="d-none d-sm-table-cell" >Location</th>
                        <th class="d-none d-sm-table-cell" >JobNo</th>
                        <th class="d-none d-sm-table-cell" >Route</th>
                        <th class="d-none d-sm-table-cell" >Asset</th>
                        <th class="d-none d-sm-table-cell" >Comment</th>
                        <th class="d-none d-sm-table-cell" >Urgency</th>
                        <th class="d-none d-sm-table-cell" >User</th>
                        <th class="d-none d-sm-table-cell" >Company</th>
                        <th class="d-none d-sm-table-cell" style="width: 5%;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($images as $image)
                    <tr>
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td>
                            <div class="items-push js-gallery" style="width: 120px;">
                                <div class="animated fadeIn ">
                                    <a class="img-link img-link-zoom-in img-thumb img-lightbox" href="{{url('/images').'/thumbnail/'.$image->filename}}">
                                        <img class="img-fluid" src="{{url('/images').'/thumbnail/'.$image->filename}}" alt="">
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="d-none d-sm-table-cell">
                            <a href="#">{{$image->name}}</a>
                        </td>
                        <td class="d-none d-sm-table-cell">
                            ({{$image->latitude}}, {{$image->longitude}})
                        </td>
                        <td class="d-none d-sm-table-cell">
                            {{$image->jobnumber->jobnumber}}
                        </td>
                        <td class="d-none d-sm-table-cell">
                            {{$image->route}}
                        </td>
                        <td class="d-none d-sm-table-cell">
                            {{$image->asset}}
                        </td>
                        <td class="d-none d-sm-table-cell">
                            {{$image->comment}}
                        </td>
                        <td class="d-none d-sm-table-cell">
                            {{$image->urgency}}
                        </td>
                        <td class="d-none d-sm-table-cell">
                            {{$image->user->name}}
                        </td>
                        <td class="d-none d-sm-table-cell">
                            {{$image->user->company->name}}
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
{{--                                <button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Edit" onclick="javascript:onEditImage({{ $image->id }});">--}}
{{--                                    <i class="fa fa-pencil-alt"></i>--}}
{{--                                </button>--}}
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Delete" onclick="javascript:onDelImage({{ $image->id }});">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <!-- END Dynamic Table Full -->

    </div>
    <!-- END Page Content -->

    <!-- Eidt Modal -->
    <div class="modal fade" id="image-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title" >Edit</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <form class="js-validation" method="post" id="image-form" action="{{asset('/image/edit')}}">
                        <div class="block-content">

                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val-email">Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="val-email" name="val-email" placeholder="Your valid email..">
                                </div>
                                <div class="form-group">
                                    <label for="val-password">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="val-password" name="val-password" placeholder="Choose a safe one..">
                                </div>
                                <div class="form-group">
                                    <label for="val-confirm-password">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="val-confirm-password" name="val-confirm-password" placeholder="..and confirm it!">
                                </div>
                            </div>

                            <input type="hidden" name="image-id">

                        </div>
                        <div class="block-content block-content-full text-right bg-light">
                            <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-sm btn-primary" >Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END Edit Modal -->
@endsection

@section('js_after')

    <!-- Page JS Plugins -->
    <script src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/plugins/magnific-popup/jquery.magnific-popup.min.js')}}"></script>

    <!-- Page JS Code -->
    <script src="{{asset('js/pages/be_tables_datatables.min.js')}}"></script>

    <!-- Page JS Helpers (Magnific Popup Plugin) -->
    <script>jQuery(function(){ Dashmix.helpers('magnific-popup'); });</script>
@endsection
