     <div class="card">
         <div class="card-body">
             <h5 class="card-title text-primary">@lang('english.general_settings')
                 <small class="text-info font-13">@lang('english.setting_help_text')</small>
             </h5>
             <hr>

             <div class="row">
               
               
                                  <div class="clearfix"></div>


                 <div class="clearfix"></div>

                 
                     <div class="col-sm-8">
                         <h5 class="card-title ">@lang('english.admission_banner')</h5>
                         <img src="{{'uploads/front_image/'.$front_settings->admission_banner}}" class="logo_admission_banner card-img-top" alt="...">
                         {!! Form::label('logo_image', __('english.admission_banner') . ':') !!}
                         {!! Form::file('admission_banner', ['accept' => 'image/*','class' => 'form-control upload_admission_banner']) !!}
                         <p class="card-text fs-6 help-block"></p>
                     </div>
                  <hr>
                 <div class="clearfix"></div>


                 <div class="row p-3">

               

                     <div class="col-sm-4">
                         <h5 class="card-title ">@lang('english.page_banner')</h5>
                         <img src="{{'uploads/front_image/'.$front_settings->page_banner }}" class="page_banner card-img-top" alt="...">
                         {!! Form::label('page_banner', __('english.page_banner') . ':') !!}
                         {!! Form::file('page_banner', ['accept' => 'image/*','class' => 'form-control upload_page_banner']) !!}
                         <p class="card-text fs-6 help-block"></p>
                     </div>


                 </div>



             </div>
         </div>


     </div>
