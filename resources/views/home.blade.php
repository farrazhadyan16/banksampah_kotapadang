@extends('layouts.admin')

@section('main-content')

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    @if (session('success'))
        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success border-left-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="row">
        <!-- Total Users Card -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow border-left-primary">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total User</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $widget['users'] }}</div>
                    <div class="small text-gray-500">8.5% Up from yesterday</div>
                </div>
            </div>
        </div>

        <!-- Total Orders Card -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow border-left-success">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Order</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">10,293</div>
                    <div class="small text-gray-500">1.3% Up from past week</div>
                </div>
            </div>
        </div>

        <!-- Total Sales Card -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow border-left-warning">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Sales</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp 10,000</div>
                    <div class="small text-gray-500">4.3% Down from yesterday</div>
                </div>
            </div>
        </div>

        <!-- Total Pending Card -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow border-left-danger">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Pending</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">2,040</div>
                    <div class="small text-gray-500">1.8% Up from yesterday</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order List Table -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order List</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nama Nasabah</th>
                                    <th>Piece</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Andi</td>
                                    <td>423</td>
                                    <td>Rp 1000</td>
                                    <td><span class="badge badge-success">Delivered</span></td>
                                </tr>
                                <tr>
                                    <td>Budi</td>
                                    <td>423</td>
                                    <td>Rp 1000</td>
                                    <td><span class="badge badge-warning">Pending</span></td>
                                </tr>
                                <tr>
                                    <td>Yanto</td>
                                    <td>423</td>
                                    <td>Rp 1000</td>
                                    <td><span class="badge badge-danger">Rejected</span></td>
                                </tr>
                                <!-- Additional rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection