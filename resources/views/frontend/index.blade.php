@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('css')
<style>
    .table-hide {
        display: none;
    }
</style>
@endsection

@section('js')
<script>
    var userId = null;
    $(function () {
        userId = $('#userId').val();
    });

    $('#detect_address').keyup(function (e) {
        if (e.keyCode == 13) {
            $('#button_detect').trigger("click");
        }
    });

    $('#button_report').click(function () {
        var address = $('#report_address').val().trim();
        if (address.length < 2 || address.length > 100) {
            $('#error_message').html('Address is invalid.');
            $('#modal_submit_error').modal('show');
            return false;
        }

        $.ajax({
            url: "/api/report/submit",
            type: "POST",
            data: JSON.stringify({
                'address': address,
                'report': $('#report_comment').val(),
                'userId': userId
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
        var address = $('#detect_address').val().trim();
        if (address.length < 2 || address.length > 100) {
            $('#error_message').html('Address is invalid.');
            $('#modal_submit_error').modal('show');
            return false;
        }

        $.ajax({
            url: "/api/getLook",
            type: "POST",
            data: JSON.stringify({
                'address': address,
                'userId': userId
            }),
            dataType: 'json',
            contentType: 'application/json',
            success: function (response) {
                console.log(response);
                var html = '';
                for (var i = 0; i < response.data.length; i++) {
                    var item = response.data[i];
                    html += `
                        <tr>
                            <th scope="row">${i + 1}</th>
                            <td>${item.user.full_name}</td>
                            <td>${item.address}</td>
                        </tr>
                        `;
                }
                if (html.length > 0) {
                    $('#table_list_data').removeClass('table-hide');
                } else {
                    $('#table_list_data').addClass('table-hide');
                }
                $('#table_list_data tbody').html(html);

                $('#lookup_address').text(response.address);
                $('#report_count').text(response.data.length);
                $('#score').text(response.score);
                //$('#score').text(Math.ceil(Math.min(Math.log(response.score + 1) * 100 / 2.5, 100)));
                $('#updated_date').text(response.updated_date);
                $('#platform_fees').text(response.platform_fees);
                $('#total_fees').text(response.total_fees);
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
            @guest
            <div class="input-group mb-3">
                <div class="p-1 bg-warning text-dark">Please <a href="{{route('frontend.auth.login')}}">login</a> to use this service.</div>
            </div>
            @else
            <input type="hidden" id="userId" name="userId" value="{{ Auth::user()->id }}">
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
                                <td style="min-width: 400px;"><i id="lookup_address"></i></td>
                            </tr>
                            <tr>
                                <th>Report Count</th>
                                <td id="report_count" style="min-width: 400px;"></td>
                            </tr>
                            <tr>
                                <th>Score</th>
                                <td id="score" style="min-width: 400px;"></td>
                            </tr>
                            <tr>
                                <th>Latest Report</th>
                                <td><i id="updated_date" style="min-width: 400px;"></i></td>
                            </tr>
                            <tr>
                                <th>Num Reports Submitted</th>
                                <td id="address_score" style="min-width: 400px;"></td>
                            </tr>
                            <tr>
                                <th>Platform fees</th>
                                <td id="platform_fees" style="min-width: 400px;"></td>
                            </tr>
                            <tr>
                                <th>Total fees</th>
                                <td id="total_fees" style="min-width: 400px;"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="input-group mt-2 mb-3">
                <table class="table table-dark table-hide" id="table_list_data">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Address</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            @endguest
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

<div class="modal" tabindex="-1" role="dialog" id="modal_submit_error">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="error_message"></p>
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
