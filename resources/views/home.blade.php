@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Calendar</h3>
        </div>
        <div class="card-body left-0">
            <div class="container-fluid p-0 m-0">
                <div class="row">
                    <div class="col-4">
                        <form id="formSched">
                            <div class="container p-0 m-0">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="event">Event</label>
                                        <input type="text" class="form-control" id="event" name="event" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="form-group col-md-6">
                                        <label for="from_date">From</label>
                                        <input type="text" class="form-control datepicker" id="from_date" name="from_date" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="to_date">To</label>
                                        <input type="text" class="form-control datepicker" id="to_date" name="to_date" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="chk_mon" name="day[]" value="Mon">
                                            <label class="form-check-label" for="chk_mon">Mon</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="chk_tue" name="day[]" value="Tue">
                                            <label class="form-check-label" for="chk_tue">Tue</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="chk_wed" name="day[]" value="Wed">
                                            <label class="form-check-label" for="chk_wed">Wed</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="chk_thu" name="day[]" value="Thu">
                                            <label class="form-check-label" for="chk_thu">Thu</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="chk_fri" name="day[]" value="Fri">
                                            <label class="form-check-label" for="chk_fri">Fri</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="chk_sat" name="day[]" value="Sat">
                                            <label class="form-check-label" for="chk_sat">Sat</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="chk_sun" name="day[]" value="Sun">
                                            <label class="form-check-label" for="chk_sun">Sun</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 mt-3">
                                    <div class="col">
                                        <button type="submit" id="btnSave" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="schedule" class="col-lg">
                        {!! $schedule !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $("#btnSave").on("click",function (e) {
            e.preventDefault();

            $.ajax({
                url: "/saveSched",
                type: "post",
                data: $("#formSched").serialize() + "&_token=" + $('meta[name="csrf-token"]').attr('content'),
                success: function (data) {
                    $("#schedule").empty().append(data);
                    toastr.success('Event successfully saved');
                }
            });
        });
    </script>
@endsection
