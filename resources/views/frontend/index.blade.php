@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('js')
<script>
    // $('#modal_submit_address').modal('show');

    $('#button_report').click(function () {
        $.ajax({
            url: "/api/report/submit",
            type: "POST",
            data: JSON.stringify({
                'address': $('#report_address').val(),
                'report': $('#report_comment').val()
            }),
            dataType: 'json',
            contentType: 'application/json',
            success: function (data) {
                $('#modal_submit_address').modal('show');
            },
            error: function (request, status, error) {
                debugger
            }
        });
    });

    $('#button_detect').click(function () {
        $.ajax({
            url: "/api/getLook",
            type: "POST",
            data: JSON.stringify({
                'address': $('#detect_address').val()
            }),
            dataType: 'json',
            contentType: 'application/json',
            success: function (data) {
                console.log(JSON.stringify(data));
                $('#lookup_address').text(data.address);
                $('#address_score').text(data.score);
                $('#updated_date').text(data.updated_date);
            },
            error: function (request, status, error) {
                debugger
            }
        });
    });
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
                    <button type="button" id="button_detect" class="btn btn-outline-secondary">
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
                                <td><i id="lookup_address">38JZBto35r1upCZ23anoJQMibpdL7EBwaC</i></td>
                            </tr>
                            <tr>
                                <th>Report Count</th>
                                <td id="address_score">0</td>
                            </tr>
                            <tr>
                                <th>Latest Report</th>
                                <td><i id="updated_date"></i>—<br></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="input-group mt-2 mb-3">
                <button type="button" class="btn btn-info">View Info</button>
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
                    <button type="button" id="button_report" class="btn btn-outline-secondary">
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
                <h5 class="modal-title">Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Report Successfully!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_view_data" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Larry</td>
                            <td>the Bird</td>
                            <td>@twitter</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection