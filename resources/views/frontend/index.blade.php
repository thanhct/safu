@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('js')
<script>
    $('#modal_submit_address').modal('show');
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="row">
            <h3 class="text-center">Detect Address</h3>
            <div class="input-group mb-3">
                <input type="text" id="detect_address" placeholder="1L1YwaHKfNGxGx6PGYp6SC6uA14tP9FbXt" aria-describedby="button-addon" class="form-control">
                <div class="input-group-append">
                    <button type="button" id="button_detect" onclick="window.goToAddress($('#home-search').val())" class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i>
                        Detect
                    </button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Address <b>not</b> found in database:</h5>
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr>
                                <th>Address</th>
                                <td><i>38JZBto35r1upCZ23anoJQMibpdL7EBwaC</i></td>
                            </tr>
                            <tr>
                                <th>Report Count</th>
                                <td>0</td>
                            </tr>
                            <tr>
                                <th>Latest Report</th>
                                <td>—<br></td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="mb-0"><i>This address has not been reported</i>. <a href="/reports/create?address=38JZBto35r1upCZ23anoJQMibpdL7EBwaC">File Report</a></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="row offset-lg-1">
            <h3 class="text-center">Report Address</h3>
            @guest
            <div class="input-group mb-3">
                <div class="p-1 bg-warning text-dark">Please <a href="{{route('frontend.auth.login')}}">login</a> to use this service.</div>
            </div>
            @else
            <div class="input-group mb-3">
                <input type="text" id="report_address" placeholder="1L1YwaHKfNGxGx6PGYp6SC6uA14tP9FbXt" aria-describedby="button-addon" class="form-control">
                <div class="input-group-append">
                    <button type="button" id="button_report" onclick="window.goToAddress($('#home-search').val())" class="btn btn-outline-secondary">
                        Report
                    </button>
                </div>
            </div>
            <div class="input-group mb-3">
                <textarea class="form-control" rows="5" id="report_comment" placeholder="Enter your report reason"></textarea>
            </div>
            @endguest
        </div>
    </div>
</div>
@endsection

@section('modal')
<div class="modal" tabindex="-1" role="dialog" id="modal_submit_address">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection