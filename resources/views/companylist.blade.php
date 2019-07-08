@extends('layouts.backend')

@section('css_before')
<link rel="stylesheet" href="{{asset('js/plugins/datatables/dataTables.bootstrap4.css')}}">
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Company list</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">App</li>
                        <li class="breadcrumb-item active" aria-current="page">Company list</li>
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
                <h3 class="block-title">Company list</h3>
            </div>

            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->

                <div class="row col-md-12 bg-body-light py-2 mb-2">
                    <button class="dt-button buttons-copy buttons-html5 btn btn-sm btn-primary" type="button" onclick="javascript:onAddCompany();"><span><i class="fa fa-plus"></i>Add</span></button>
                </div>

                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                    <tr>
                        <th class="text-center" style="width: 80px;">#</th>
                        <th>Name</th>
                        <th class="d-none d-sm-table-cell" style="width: 20%;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($companies as $company)
                    <tr>
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="font-w600">
                            <a href="#">{{$company->name}}</a>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Edit" onclick="javascript:onEditCompany({{ $company->id }});">
                                    <i class="fa fa-pencil-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Delete" onclick="javascript:onDelCompany({{ $company->id }});">
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
    <div class="modal fade" id="company-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title" id="company-modal-header">Edit</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <form class="js-validation" method="post" id="company-form">
                        <div class="block-content">
                            @csrf
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val-companyname">Company name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="val-companyname" name="val-companyname" placeholder="Enter a companyname..">
                                </div>
                            </div>

                            <input type="hidden" name="company-id">
                        </div>

                        <div class="block-content block-content-full text-right bg-light">
                            <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-sm btn-primary" >Save</button>
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

    <!-- Page JS Code -->
    <script src="{{asset('js/pages/be_tables_datatables.min.js')}}"></script>

@endsection