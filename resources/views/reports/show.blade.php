@extends("admin_layouts.app")
@section('title', __('english.student_details'))

@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.students_flitters')</h5>

                {!! Form::open(['url' => route('reports.generate', $report), 'method' => 'GET', 'id' => 'report_generate_form']) !!}

                <div class="row">
                    <div class="col-md-4 p-1">
                        {!! Form::label('campus_id', __('english.campuses') . ':*') !!}
                        {!! Form::select('campus_id', $campuses, null, [
                            'class' => 'form-select select2 global-campuses',
                            'id' => 'students_list_filter_campus_id',
                            'style' => 'width:100%',
                            'required',
                            'placeholder' => __('english.all')
                        ]) !!}
                    </div>

                    <div class="col-md-4 p-1">
                        {!! Form::label('class_id', __('english.classes')) !!}
                        {!! Form::select('class_id', [], null, [
                            'class' => 'form-select select2 global-classes',
                            'id' => 'students_list_filter_class_id',
                            'style' => 'width:100%',
                           
                            'placeholder' => __('english.all')
                        ]) !!}
                    </div>
  <div class="col-md-4 p-1">
                        {!! Form::label('batch_id', __('english.batches')) !!}
                        {!! Form::select('batch_id',$batches, null, [
                            'class' => 'form-select select2',
                            'id' => 'students_list_filter_batach',
                            'style' => 'width:100%',
                           
                            'placeholder' => __('english.all')
                        ]) !!}
                    </div>
                      <div class="col-md-4 p-1">
                        {!! Form::label('semester_id', __('english.semesters')) !!}
                        {!! Form::select('semester_id',$semesters, null, [
                            'class' => 'form-select select2 ',
                            'id' => 'students_list_filter_semester_id',
                            'style' => 'width:100%',
                           
                            'placeholder' => __('english.all')
                        ]) !!}
                    </div>
                    <div class="col-md-4 p-1">
                        {!! Form::label('class_section_id', __('english.sections')) !!}
                        {!! Form::select('class_section_id', [], null, [
                            'class' => 'form-select select2 global-class_sections',
                            'id' => 'students_list_filter_class_section_id',
                            'style' => 'width:100%',

                            'placeholder' => __('english.all')
                        ]) !!}
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-3 p-1">
                        {!! Form::label('status', __('english.student_status') . ':*') !!}
                        {!! Form::select('status', __('english.std_status'), 'active', [
                            'class' => 'form-control select2',
                            'id' => 'students_list_filter_status',
                            'placeholder' => __('english.please_select'),
                            'required'
                        ]) !!}
                    </div>

                    <div class="col-md-3 p-1">
                        {!! Form::label('vehicle_id', __('english.vehicles')) !!}
                        {!! Form::select('vehicle_id', $vehicles, null, [
                            'class' => 'form-control select2',
                            'id' => 'students_list_filter_vehicle_id',
                            'placeholder' => __('english.please_select'),
                            
                        ]) !!}
                    </div>

                    <div class="col-md-3 p-1">
                        {!! Form::label('gender', __('english.gender')) !!}
                        {!! Form::select("gender", [
                            'male' => __('english.male'),
                            'female' => __('english.female'),
                            'others' => __('english.others')
                        ], null, [
                            'class' => 'form-select',
                            'id' => 'students_list_filter_gender',
                            'style' => 'width:100%',
                           
                            'placeholder' => __('english.please_select')
                        ]) !!}
                    </div>

                    <div class="col-md-3">
                        <div class="form-check mt-3">
                            {!! Form::checkbox('only_transport', 1, null, [
                                'class' => 'form-check-input big-checkbox',
                                'id' => 'only_transport'
                            ]) !!}
                            {!! Form::label('only_transport', __('english.only_transport')) !!}
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-md-12 text-end mt-3">
                        {!! Form::submit(__('english.generate_report'), ['class' => 'btn btn-primary']) !!}
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('/js/student.js?v=' . $asset_v) }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        // JS logic if needed
    });
</script>
@endsection
