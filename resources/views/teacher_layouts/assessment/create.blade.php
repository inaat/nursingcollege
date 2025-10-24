@extends("admin_layouts.app")
@section('title', __('english.assessment'))
@section("style")
<style>
/* 
Max width before this PARTICULAR table gets nasty
This query will take effect for any screen smaller than 760px
and also iPads specifically.
*/
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {

	/* Force table to not be like tables anymore */
	table, thead, tbody, th, td, tr { 
		display: block; 
	}
	
	/* Hide table headers (but not display: none;, for accessibility) */
	thead tr { 
		position: absolute;
		top: -9999px;
		left: -9999px;
	}
	
	tr { border: 1px solid #ccc; }
	
	td { 
		/* Behave  like a "row" */
		border: none;
		border-bottom: 1px solid #eee; 
		position: relative;
		padding-left: 50%; 
	}
	
	td:before { 
		/* Now like a table header */
		position: absolute;
		/* Top/left values mimic padding */
		top: 6px;
		left: 6px;
		width: 45%; 
		padding-right: 10px; 
		white-space: nowrap;
	}
}
	</style>

@endsection
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.assessment')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.assessment')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        {!! Form::open(['url' => action('TeacherLayout\AssessmentController@postAssessment'), 'method' => 'post', 'class'=>'','id' =>'search_student_fee' ]) !!}

        <div class="card">

            <div class="card-body">
                <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                <hr>
                <div class="row ">
                 
                
                  <div class="table-responsive">
                             <table class="table mb-0" width="100%" id="students_table" style="width: 100%;  border-collapse: collapse;">
                                 <thead class="table-light" width="100%">
                                     <tr>
                                         <th>Title</th>
                                         <th>Assessment</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                 <input type="hidden" name="student_id" value={{$student_id}}>
                                   @foreach ($assessments as $key => $items)
                                     <tr>
                                    <td colspan="2" class="text-center" ><h4>{{$key}}</h4></td>
                                    <tr>
                                    @foreach ($items as  $item)
                                     <tr>
                                    <td><h5>{{ $item['subTitle'] }}</h5></td>
                                     <td>      
                                            <input type="hidden" name="assessment[{{$item['id']}}][id]" value={{$item['id']}}>                                                                                      
                                             <input type="radio" class="form-check-input" name="assessment[{{$item['id']}}][status]"  @if($item['isAverage']) checked  @endif value="isAverage">
                                           <label class="form-check-label" for="">Is Average </label>
                                             <input type="radio" class="form-check-input" name="assessment[{{$item['id']}}][status]"  @if($item['isGood']) checked  @endif value="isGood">
                                           <label class="form-check-label" for="">Is Good</label>
                                             <input type="radio" class="form-check-input" name="assessment[{{$item['id']}}][status]"  @if($item['isPoor']) checked  @endif value="isPoor">
                                           <label class="form-check-label" for="">Is Poor</label>
                                    </td>
                                     <tr>
                                     @endforeach
                                    @endforeach
                                    </tbody>
                                 </table>
                         </div>
                   
                </div>
                <div class="d-lg-flex align-items-center mt-4 gap-3">
                    <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                            <i class="fas fa-filter"></i>@lang('english.submit')</button></div>
                </div>
            </div>
        </div>


        {{ Form::close() }}


       

    </div>
</div>
@endsection

@section('javascript')

<script type="text/javascript">
    $(document).ready(function() {
     

    });

</script>
@endsection

