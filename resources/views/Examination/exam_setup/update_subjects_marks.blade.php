@extends("admin_layouts.app")
@section('title', __('english.update_subjects_marks'))
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.update_subjects_marks')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.update_subjects_marks')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            {!! Form::open(['url' => action('Examination\ExamSetupController@updateSubjectsMarkPost'), 'method' => 'post', 'id' => 'store_student_fee' ]) !!}
            <div class="row">
                {!! Form::hidden("exam_create_id", $exam_create->id) !!}
                
                <!-- Global Inputs -->
                <div class="col-lg-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">@lang('english.global_theory_mark')</label>
                                    <input type="number" id="global-theory-mark" class="form-control" placeholder="Enter theory mark for all subjects">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">@lang('english.global_practical_mark')</label>
                                    <input type="number" id="global-practical-mark" class="form-control" placeholder="Enter practical mark for all subjects">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach ($grouped_by_class as $classes)
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title text-primary">{{ $classes[0]->current_class->title }}</h6>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('english.subject_name')</th>
                                            <th>@lang('english.theory_mark')</th>
                                            <th>@lang('english.practical_mark')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($classes as $class)
                                        <tr>
                                            <td>
                                                {{ $class->subject_name->name }}
                                            </td>
                                            <td>
                                                <input type="hidden" value="{{ $class->current_class->id }}" required name="subjects[{{$class->subject_name->id}}][class_id]" class="form-control">
                                                <input type="hidden" value="{{ $class->subject_name->id }}" required name="subjects[{{$class->subject_name->id}}][subject_id]" class="form-control">
                                                <input type="number" value="{{ $class->theory_mark }}" required name="subjects[{{$class->subject_name->id}}][theory_mark]" class="form-control theory-mark">
                                            </td>
                                            <td>
                                                <input type="number" value="{{ $class->parc_mark}}" required name="subjects[{{$class->subject_name->id}}][parc_mark]" class="form-control practical-mark">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-lg-flex align-items-center mt-4 gap-3">
                                <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0 tabkey" type="submit">
                                    @lang('english.submit')</button></div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="instructions"><strong>@lang('english.instruction'): </strong>@lang('english.mark_entry_warning')</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>

    <!-- JavaScript for global inputs -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const globalTheoryInput = document.getElementById('global-theory-mark');
            const globalPracticalInput = document.getElementById('global-practical-mark');
            const theoryInputs = document.querySelectorAll('.theory-mark');
            const practicalInputs = document.querySelectorAll('.practical-mark');

            // Update all theory marks when global theory input changes
            globalTheoryInput.addEventListener('input', function() {
                const value = this.value;
                theoryInputs.forEach(input => {
                    input.value = value;
                });
            });

            // Update all practical marks when global practical input changes
            globalPracticalInput.addEventListener('input', function() {
                const value = this.value;
                practicalInputs.forEach(input => {
                    input.value = value;
                });
            });
        });
    </script>
@endsection