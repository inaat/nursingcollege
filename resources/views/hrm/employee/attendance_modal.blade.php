<div class="modal fade" id="attendance_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            {!! Form::open(['url' => action('Hrm\HrmAttendanceController@updateAttendance'), 'method' => 'post', 'id' => 'attendance_form' ]) !!}


            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="exampleModalLabel">@lang('english.update_status')
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <div class="modal-body">
                <div class="row">

                    <div class="col-md-6 ">
                        {!! Form::checkbox('check_all', 1, null ,[ 'class' => 'form-check-input big-checkbox'] ) !!}
                        {{ Form::label('check_all', __('Mark This Month Attendence') , ['class' => 'control-label mt-2 ','id'=>'check_all']) }}
                    </div>
                    <div class="col-md-6 ">
                        {!! Form::checkbox('check_single', 1, null ,[ 'class' => 'form-check-input big-checkbox'] ) !!}
                        {{ Form::label('check_single', __('Mark This Month Attendence For Single Employee') , ['class' => 'control-label mt-2 ','id'=>'check_single']) }}
                    </div>
                    <div class="col-md-12 ">
                        {!! Form::label('status', __('english.status') . ':*') !!}
                        {!! Form::select('status', __('english.attendance_status'), null, ['class' => 'form-control', 'placeholder' => __('english.please_select'), 'required']) !!}
                    </div>
                    <input type="hidden" name="attendance_date" id="attendance_date" />
                    <input type="hidden" name="attendance_employee_id" id="attendance_employee_id" />
					   <div class="col-md-12 ">
                    {!! Form::label('description', __('english.description')) !!}
                    {!! Form::text('description',null, ['class' => 'form-control', 'style' => 'width:100%', 'placeholder' => __('english.description')]) !!}
                   </div>
                    <div class="col-md-12 ">
                        {!! Form::label('over_time_hour', __( 'english.over_time_hours' ) . ':*') !!}
                        {!! Form::text('over_time_hour', 0, ['class' => 'form-control input_number', 'id'=>'over_time_hour','required', 'placeholder' => __( 'english.over_time_hours' ) ]) !!}

                    </div>
                </div> 
            </div>

            <div class="modal-footer">

                <button type="submit" class="btn btn-primary">@lang( 'english.update' )</button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang( 'english.close' )</button>

            </div>

            {!! Form::close() !!}

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
