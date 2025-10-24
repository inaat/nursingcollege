<div id="school_tags">
  <h3>School Information</h3>
   @foreach($school_tags as $key => $value)
    
  
    <a data-value="{{ $value }}" class="btn btn-gradient-light btn_tag mt-2">{{ $value }} </a>    
    @endforeach

  
    {{-- @foreach ($formFields as $formField)
        <a data-value="{{ '{'.$formField->name.'}' }}" class="btn btn-gradient-light btn_tag mt-2">{ {{ __($formField->name) }} }</a>
    @endforeach --}}
</div>
<div id="student_tags">
  <h3>Student Information</h3>
   @foreach($student_tags as $key => $value)
    
  
    <a data-value="{{ $value }}" class="btn btn-gradient-light btn_tag mt-2">{{ $value }} </a>    
    @endforeach

  
    {{-- @foreach ($formFields as $formField)
        <a data-value="{{ '{'.$formField->name.'}' }}" class="btn btn-gradient-light btn_tag mt-2">{ {{ __($formField->name) }} }</a>
    @endforeach --}}
</div>
<div id="staff_tags">
  <h3>Employee Information</h3>

   @foreach($employee_tags as $key => $value)
 <a data-value="{{ $value }}" class="btn btn-gradient-light btn_tag mt-2">{{ $value }} </a>    
    @endforeach
    
</div>