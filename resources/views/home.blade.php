@extends('layouts.main')
@section('content')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- Dashboard Analytics Start -->
            <section id="dashboard-analytics">

                <!-- List DataTable -->
                <!-- Assume this is in your Blade view file (.blade.php) -->
                <div style="display: none;" id="api-url" data-api-url="{{ url('/api/v1/history-absen') }}"></div>
                <div style="display: none;" id="api-download" data-api-download="{{ url('/api/v1/absen/rekap') }}"></div>
                <div class="row">
                    <div class="col-12">
                        <div class="card invoice-list-wrapper">
                            <div class="card-datatable table-responsive">
                                <table class="invoice-list-table table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Username</th>
                                            <th>Type</th>
                                            <th>Absen In</th>
                                            <th>Desc In</th>
                                            <th>Absen Out</th>
                                            <th>Desc Out</th>
                                            <th>Status</th>
                                            <th>Lama Bekerja</th>
                                            <th class="cell-fit">Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ List DataTable -->
            </section>
            <!-- Dashboard Analytics end -->

        </div>
    </div>
</div>
@endsection
    