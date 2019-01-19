@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="row">
            <h3 class="text-center">Lookup Address Now</h3>
            <div class="input-group mb-3"><input type="text" id="home-search" placeholder="1L1YwaHKfNGxGx6PGYp6SC6uA14tP9FbXt" aria-describedby="button-addon" class="form-control">
                <div class="input-group-append">
                    <button type="button" id="button-addon" onclick="window.goToAddress($('#home-search').val())" class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i>
                        Search
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
            <h3 class="text-center">Submit Address</h3>
            <div class="input-group mb-3"><input type="text" id="home-search" placeholder="1L1YwaHKfNGxGx6PGYp6SC6uA14tP9FbXt" aria-describedby="button-addon" class="form-control">
                <div class="input-group-append">
                    <button type="button" id="button-addon" onclick="window.goToAddress($('#home-search').val())" class="btn btn-outline-secondary">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection