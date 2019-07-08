@extends('layouts.backend')

@section('css_before')
<link rel="stylesheet" href="{{asset('js/plugins/datatables/dataTables.bootstrap4.css')}}">
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">User list</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">App</li>
                        <li class="breadcrumb-item active" aria-current="page">User list</li>
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
                <h3 class="block-title">User list</h3>
            </div>

            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->

                <div class="row col-md-12 bg-body-light py-2 mb-2">
                    <button class="dt-button buttons-copy buttons-html5 btn btn-sm btn-primary" type="button" onclick="javascript:onAddUser();"><span><i class="fa fa-plus"></i>Add</span></button>
                </div>

                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                    <tr>
                        <th class="text-center" style="width: 80px;">#</th>
                        <th>Name</th>
                        <th class="d-none d-sm-table-cell" style="width: 22%;">Email</th>
                        <th class="d-none d-sm-table-cell" style="width: 22%;">Company</th>
                        <th class="d-none d-sm-table-cell" style="width: 20%;">Role</th>
                        <th class="d-none d-sm-table-cell" style="width: 10%;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="font-w600">
                            <a href="#">{{$user->name}}</a>
                        </td>
                        <td class="d-none d-sm-table-cell">
                            <a href="#">{{$user->email}}</a>
                        </td>
                        <td class="d-none d-sm-table-cell">
                            {{$user->company->name}}
                        </td>
                        <td class="d-none d-sm-table-cell">
                            @if($user->role == "NORMAL")
                                <span class="badge badge-primary">Normal</span>
                            @else
                                <span class="badge badge-success">Super</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Edit" onclick="javascript:onEditUser({{ $user->id }});">
                                    <i class="fa fa-pencil-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Delete" onclick="javascript:onDelUser({{ $user->id }});">
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
    <div class="modal fade" id="user-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title" id="user-modal-header">Edit</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <form class="js-validation" method="post" id="user-form">
                        <div class="block-content">
                            @csrf
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val-username">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="val-username" name="val-username" placeholder="Enter a username..">
                                </div>
                                <div class="form-group">
                                    <label for="val-email">Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="val-email" name="val-email" placeholder="Your valid email..">
                                </div>
                                <div class="form-group">
                                    <label for="val-password">Password <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="val-password" name="val-password" placeholder="Choose a safe one..">
                                </div>

                                <div class="form-group">
                                    <label for="val-company">Company <span class="text-danger">*</span></label>
                                    <select class="form-control" id="val-company" name="val-company">
                                        @foreach($companies as $company)
                                        <option value="{{$company->id}}">{{$company->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="val-role">Role <span class="text-danger">*</span></label>
                                    <select class="form-control" id="val-role" name="val-role">
                                        <option value="NORMAL">Normal</option>
                                        <option value="SUPER">Super</option>
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" name="user-id">
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