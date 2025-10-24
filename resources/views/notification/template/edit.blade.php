@extends("admin_layouts.app")
@section('title', __('english.general_sms'))
@section("wrapper")
@section('style')
<link rel="stylesheet" href="{{ asset('/assets/css/jquery.tagsinput.min.css') }}">
 <link rel="stylesheet" href="{{ asset('/css/remixicon.css') }}">

<style>
    .page-section1111 {
        border: 1px solid lightgray;
        padding: 1rem;
    }

    .file-upload-default {
        visibility: hidden;
        position: absolute;
    }

    .file-upload-info {
        background: white;
    }

   .btn-gradient-light {
        background: -webkit-gradient(linear, left top, right top, from(#da8cff), to(#9a55ff));
        background: linear-gradient(to right, #da8cff, #9a55ff);
        border: 0;
        color: #ffffff;
        padding: 6px 14px;
        font-size: .8125rem;
        font-family: inherit;
        line-height: 1;
    }

 
.text-editor {
  padding-inline: 20px;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  gap: 30px;
}
.text-editor .editor-actions {
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  gap: 8px;
  position: relative;
}
.text-editor .editor-actions .action-item {
  line-height: 1.1;
}
.text-editor .editor-actions .action-item > button {
  background-color: transparent;
  font-size: 20px;
  line-height: 1.1;
  position: relative;
  color: #6B7280;
}
.text-editor .editor-actions .action-item > button:hover {
  color: #7f56d9;
}
.text-editor .editor-actions .action-item > button .tooltiptext {
  opacity: 0;
  visibility: hidden;
  width: -webkit-max-content;
  width: -moz-max-content;
  width: max-content;
  background-color: #667085;
  color: #fff;
  text-align: center;
  border-radius: 5%;
  padding: 3px 6px;
  position: absolute;
  line-height: 1.1;
  z-index: 2;
  isolation: isolate;
  top: 10px;
  font-size: 10px;
  inset-block-start: -110%;
  inset-inline-start: 50%;
  -webkit-transform: translateX(-50%);
  -ms-transform: translateX(-50%);
  transform: translateX(-50%);
}

.text-editor .editor-actions .action-item > button .tooltiptext::after {
  content: "";
  position: absolute;
  inset-block-start: 100%;
  inset-inline: 50%;
  -webkit-transform: translateX(-50%);
  -ms-transform: translateX(-50%);
  transform: translateX(-50%);
  border-width: 4px;
  border-style: solid;
  border-color: gray transparent transparent transparent;
}
[data-bs-theme=dark] .text-editor .editor-actions .action-item > button .tooltiptext::after {
  border-color: #494949 transparent transparent transparent;
}
.text-editor .editor-actions .action-item > button:hover .tooltiptext {
  opacity: 1;
  visibility: visible;
}
.text-editor .editor-actions::after {
  content: "";
  position: absolute;
  width: 1px;
  height: 100%;
  background: #6B7280;
  inset-inline-end: -15px;
  inset-block: 0;
}
.text-editor .editor-actions:last-child::after {
  all: unset;
}
</style>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pr-3">@lang('english.general_sms')</div>
            <div class="pl-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.general_sms')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        {!! Form::open(['url' =>action('NotificationTemplateController@update', [$template->id]), 'method' => 'PUT' ]) !!}

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- Message Body Section -->
                    <div class="col-md-12 mt-3">
                        <div class="form-group" style="border: 1px solid #dee2e6; padding: 15px; border-radius: 5px;">
                            <h5 class="mb-3">{{ $template_name }}</h5>
                            <textarea class="form-control" name="sms_body" id="message" rows="8" placeholder="@lang('english.message')">{{ $template->sms_body }}</textarea>
                            <div class="text-editor mt-2">
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <button class="btn btn-outline-secondary btn-sm style-link" data-style="bold" type="button"> <i class="ri-bold"></i> </button>
                                    </li>
                                    <li class="list-inline-item">
                                        <button class="btn btn-outline-secondary btn-sm style-link" data-style="italic" type="button"> <i class="ri-italic"></i> </button>
                                    </li>
                                    <li class="list-inline-item">
                                        <button class="btn btn-outline-secondary btn-sm style-link" data-style="mono" type="button"> <i class="ri-space"></i> </button>
                                    </li>
                                    <li class="list-inline-item">
                                        <button class="btn btn-outline-secondary btn-sm style-link" data-style="strike" type="button"> <i class="ri-format-clear"></i> </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-4 ">
                             <input class="form-check-input big-checkbox" id="auto_send_sms" name="auto_send_sms" @if($template->auto_send_sms)value="1" checked @endif type="checkbox" >
                        <label for="auto_send_sms" class="control-label mt-2 ">Auto Send Sms</label>

                     </div>
                                        <div class="clearfix"></div>

                    <!-- Placeholder for Future Enhancements -->
                    <div class="col-md-12 mt-3">
                        <div class="form-group col-sm-12 col-md-12">
                                     @include('notification.template.tags') 
                                </div>
                    </div>
                </div>
            </div>
             <!-- Submit Button -->
        <div class="d-flex justify-content-center mt-4">
            <button class="btn btn-primary" type="submit">@lang('english.update')</button>
        </div>
        </div>
       
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('javascript')

<script type="text/javascript">
    $(document).ready(function() {
 $('.btn_tag').click(function (e) {
    e.preventDefault();
    var value = $(this).data('value');
    var textarea = $('#message')[0];

    if (textarea) {
        var cursorPos = textarea.selectionStart;
        var text = textarea.value;
        var before = text.substring(0, cursorPos);
        var after = text.substring(cursorPos);
        textarea.value = before + value + after;
        textarea.setSelectionRange(cursorPos + value.length, cursorPos + value.length);
        textarea.focus();
    }
});

       $('.style-link').on('click', function (e) {

    e.preventDefault();

    var style        = $(this).data('style');
    var textarea     = $('#message')[0];
    var selectedText = textarea.value.substring(textarea.selectionStart, textarea.selectionEnd);

    if (selectedText.trim() === '') {
        return;
    }

    var startChar = '';
    var endChar   = '';

    switch (style) {

        case 'bold' :

            startChar = '*';
            endChar   = '*';
            break;
        case 'italic' :

            startChar = '_';
            endChar   = '_';
            break;
        case 'mono' :

            startChar = '```';
            endChar   = '```';
            break;
        case 'strike' :

            startChar = '~';
            endChar   = '~';
            break;
    }

    var startOffset  = textarea.selectionStart;
    var endOffset    = textarea.selectionEnd;
    var modifiedText = startChar + selectedText + endChar;
    textarea.setRangeText(modifiedText, startOffset, endOffset, 'end');
    textarea.setSelectionRange(startOffset + startChar.length, startOffset + startChar.length + selectedText.length + endChar.length);
});
});
</script>
@endsection
