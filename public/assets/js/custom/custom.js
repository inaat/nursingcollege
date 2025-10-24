"use strict";
const $table = $("#table_list"); // "table" accordingly
$(function () {
    // $("#sortable-row").sortable({
    //     placeholder: "ui-state-highlight"
    // });
    function checkList(listName, newItem) {
        let duplicate = false;
        $("#" + listName + " > div").each(function () {
            if ($(this)[0] !== newItem[0]) {
                if ($(this).find("li").attr('id') == newItem.find("li").attr('id')) {
                    duplicate = true;
                }
            }
        });
        return duplicate;
    }

    $('#table_list_exam_questions').on('check.bs.table', function (e, row) {
        let questions = $(this).bootstrapTable('getSelections');
        let li = ''
        $.each(questions, function (index, value) {
            li = $('<div class="list-group"><input type="hidden" name="assign_questions[' + value.question_id + '][question_id]" value="' + value.question_id + '"><li id="q' + value.question_id + '" class="list-group-item justify-content-between align-items-center ui-state-default list-group-item-secondary m-2">' + value.question_id + ". " + value.question + ' <span class="text-right row mx-0"><input type="number" min="1" class="list-group-item col-md-3 col-sm-12 form-control-sm mr-2 mb-2" name="assign_questions[' + value.question_id + '][marks]" placeholder="' + trans['enter_marks'] + '"><a class="btn btn-danger btn-sm remove-row mb-2" data-id="' + value.question_id + '"><i class="fa fa-times" aria-hidden="true"></i></a></span></li></div>');
            let pasteItem = checkList("sortable-row", li, row.question_id);
            if (!pasteItem) {
                $("#sortable-row").append(li);
            }
        });
        createCkeditor();
    })
    $('#table_list_exam_questions').on('uncheck.bs.table', function (e, row) {
        $("#sortable-row > div").each(function () {
            $(this).find('#q' + row.question_id).remove();
        });
    })
    // $table.bootstrapTable('destroy').bootstrapTable({
    //     exportTypes: ['csv', 'excel', 'pdf', 'txt', 'json'],
    // });

    $("#toolbar")
        .find("select")
        .change(function () {
            $table.bootstrapTable("refreshOptions", {
                exportDataType: $(this).val()
            });
        });

    //File Upload Custom Component
    $('.file-upload-browse').on('click', function () {
        let file = $(this).parent().parent().parent().find('.file-upload-default');
        file.trigger('click');
    });
    $('.file-upload-default').on('change', function () {

        $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
    });

    let layout_direction = 'ltl';
    if(isRTL()) {
        layout_direction = 'rtl';
    } else {
        layout_direction = 'ltl';
    }

    if ($('#tinymce_message').length) {
        tinymce.init({
            directionality : layout_direction,
            height: "500",
            selector: '#tinymce_message',
            relative_urls: false,
            remove_script_host: false,
            menubar: 'file edit view formate tools',
            toolbar: [
                'styleselect fontselect fontsizeselect',
                'undo redo | cut copy paste | bold italic | alignleft aligncenter alignright alignjustify | table | image | fullscreen',
                'bullist numlist | outdent indent | blockquote autolink | lists | fontfamily | fontsize | code | preview'
            ],
            content_style: "@import url('https://fonts.googleapis.com/css2?family=Pinyon%20Script:wght@900&family=Pinyon%20Script&display=swap'); body { font-family: 'Lato', sans-serif; } h1,h2,h3,h4,h5,h6 { font-family: 'Pinyon%20Script', sans-serif; }",
            font_family_formats: "Arial Black=arial black,avant garde; Courier New=courier new,courier; Lato=lato; Pinyon Script=Pinyon Script;",
            plugins: 'autolink link image lists code table fullscreen preview',
            font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 28pt 36pt 48pt',
        });
    }

    $('.modal').on('hidden.bs.modal', function () {
        //Reset input file on modal close
        $('.file-upload-default').val('');
        $('.file-upload-info').val('');
    })
    /*simplemde editor*/
    if ($("#simpleMde").length) {
        new SimpleMDE({
            element: $("#simpleMde")[0],
            hideIcons: ["guide", "fullscreen", "image", "side-by-side"],
        });
    }

    if ($(".color-picker").length) {
        $('.color-picker').asColorPicker();
    }

    if ($(".theme_color").length) {
        $('.theme_color').asColorPicker();
    }
    if ($(".primary_color").length) {
        $('.primary_color').asColorPicker();
    }
    if ($(".secondary_color").length) {
        $('.secondary_color').asColorPicker();
    }

    //Color Picker Custom Component

    //Date Picker
    // if ($(".datepicker-popup-no-future").length) {
    //     var today = new Date();
    //     var maxDate = new Date();
    //     maxDate.setDate(today.getDate());
    //     $('.datepicker-popup-no-future').datepicker({
    //         enableOnReadonly: false,
    //         todayHighlight: true,
    //         format: "dd-mm-yyyy",
    //         endDate: maxDate,
    //     });
    // }
    //Added this for Dynamic Date Picker input Initialization
    $('body').on('focus', ".datepicker-popup-no-future", function () {
        let today = new Date();
        let maxDate = new Date();
        maxDate.setDate(today.getDate());
        $(this).datepicker({
            enableOnReadonly: false,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            endDate: maxDate,
            rtl: isRTL()
        });
    });

    $('body').on('focus', ".datepicker-popup-no-past", function () {
        let today = new Date();
        let minDate = new Date();
        minDate.setDate(today.getDate());
        $(this).datepicker({
            enableOnReadonly: false,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            startDate: minDate,
            rtl: isRTL()
        });
    });

    // //Date Picker
    // if ($(".datepicker-popup").length) {
    //     $('.datepicker-popup').datepicker({
    //         enableOnReadonly: false,
    //         todayHighlight: true,
    //         format: "dd-mm-yyyy",
    //     });
    // }
    //Added this for Dynamic Date Picker input Initialization
    $('body').on('focus', ".datepicker-popup", function () {
        $(this).datepicker({
            enableOnReadonly: false,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            rtl: isRTL()
        });
    });

    //Time Picker
    if ($("#timepicker-example").length) {
        $('#timepicker-example').datetimepicker({
            format: 'LT'
        });
    }
 

    $(document).on('click', '[data-toggle="lightbox"]', function (event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
});

// $('.edit-class-teacher-form').on('submit', function (e) {
//     e.preventDefault();
//     let formElement = $(this);
//     let submitButtonElement = $(this).find(':submit');
//     let data = new FormData(this);
//     let url = $(this).attr('action');
//
//     function successCallback(response) {
//         $('#table_list').bootstrapTable('refresh');
//
//         //Reset input file field
//         $('.file-upload-default').val('');
//         $('.file-upload-info').val('');
//         setTimeout(function () {
//             window.location.reload();
//         }, 1000)
//     }
//
//     formAjaxRequest('POST', url, data, formElement, submitButtonElement, successCallback);
// })


$(document).on('change', '.file_type', function () {
    let type = $(this).val();
    let parent = $(this).parent();
    if (type == "file_upload") {
        parent.siblings('#file_name_div').show();
        parent.siblings('#file_thumbnail_div').hide();
        parent.siblings('#file_div').show();
        parent.siblings('#file_link_div').hide();
    } else if (type == "video_upload") {
        parent.siblings('#file_name_div').show();
        parent.siblings('#file_thumbnail_div').show();
        parent.siblings('#file_div').show();
        parent.siblings('#file_link_div').hide();
    } else if (type == "youtube_link") {
        parent.siblings('#file_name_div').show();
        parent.siblings('#file_thumbnail_div').show();
        parent.siblings('#file_div').hide();
        parent.siblings('#file_link_div').show();
    } else if (type == "other_link") {
        parent.siblings('#file_name_div').show();
        parent.siblings('#file_thumbnail_div').show();
        parent.siblings('#file_div').hide();
        parent.siblings('#file_link_div').show();
    } else {
        parent.siblings('#file_name_div').hide();
        parent.siblings('#file_thumbnail_div').hide();
        parent.siblings('#file_div').hide();
        parent.siblings('#file_link_div').hide();
    }
})



$(document).on('click', '.remove-gallery-image', function (e) {
    e.preventDefault();
    var $this = $(this);
    var file_id = $(this).data('id');

    Swal.fire({
        title: window.trans['Are you sure'],
        text: window.trans['You wont be able to revert this'],
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: window.trans["Yes, Change it"],
        cancelButtonText: window.trans["Cancel"]
    }).then((result) => {
        if (result.isConfirmed) {
            let url = base_path+ '/gallery/file/delete/' + file_id;
            let data = null;

            function successCallback(response) {
                // $this.parent().remove();
                $this.parent().slideUp(500);
                $('#table_list').bootstrapTable('refresh');
                showSuccessToast(response.message);
            }

            function errorCallback(response) {
                showErrorToast(response.message);
            }

            ajaxRequest('DELETE', url, data, null, successCallback, errorCallback);
        }
    })
});

$('#topic_class_section_id').on('change', function () {
    let html = "<option value=''>--Select Lesson--</option>";
    $('#topic-lesson-id').html(html);
    $('#topic_subect_id').trigger('change');
})

$('#topic_subject_id').on('change', function () {
    let url = base_path+ '/lesson/search';
    let data = {
        'subject_id': $(this).val(),
        'class_section_id': $('#topic_class_section_id').val()
    };

    function successCallback(response) {
        let html = ""
        if (response.data.length > 0) {
            html += "<option>--Select Lesson--</option>"
            response.data.forEach(function (data) {
                html += "<option value='" + data.id + "'>" + data.name + "</option>";
            })
        } else {
            html = "<option value=''>No Data Found</option>";
        }
        $('#topic-lesson-id').html(html);
    }

    ajaxRequest('GET', url, data, null, successCallback, null, null, true);
})

$('#resubmission_allowed').on('change', function () {
    if ($(this).is(':checked')) {
        $(this).val(1);
        $('#extra_days_for_resubmission_div').show(500);
    } else {
        $(this).val(0);
        $('#extra_days_for_resubmission_div').hide(500);
    }
})

$('#edit_resubmission_allowed').on('change', function () {
    if ($(this).is(':checked')) {
        $(this).val(1);
        $('#edit_extra_days_for_resubmission_div').show(500);
    } else {
        $(this).val(0);
        $('#edit_extra_days_for_resubmission_div').hide(500);
    }
})

$('#edit_topic_class_section_id').on('change', function () {
    let html = "<option value=''>--Select Lesson--</option>";
    $('#topic-lesson-id').html(html);
    $('#topic_subect_id').trigger('change');
})

$('#edit_topic_subject_id').on('change', function () {
    let url = base_path+ '/lesson/search';
    let data = {
        'subject_id': $(this).val(),
        'class_section_id': $('#edit_topic_class_section_id').val()
    };

    function successCallback(response) {
        let html = ""
        if (response.data.length > 0) {
            response.data.forEach(function (data) {
                html += "<option value='" + data.id + "'>" + data.name + "</option>";
            })
        } else {
            html = "<option value=''>No Data Found</option>";
        }
        $('#edit_topic_lesson_id').html(html);
    }

    ajaxRequest('GET', url, data, null, successCallback, null, null, true);
})

$(document).on('click', '.remove-assignment-file', function (e) {
    e.preventDefault();
    let $this = $(this);
    let file_id = $(this).data('id');
    // TODO : Remove this and use deletepopup function
    Swal.fire({
        title: window.trans['Are you sure'],
        text: window.trans['You wont be able to revert this'],
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: window.trans['yes_delete'],
        cancelButtonText: window.trans["Cancel"]
    }).then((result) => {
        if (result.isConfirmed) {
            let url = base_path+ '/announcement/file/delete/' + file_id;
            let data = null;

            function successCallback(response) {
                $this.parent().remove();
                $('#table_list').bootstrapTable('refresh');
                showSuccessToast(response.message);
            }

            function errorCallback(response) {
                showErrorToast(response.message);
            }

            ajaxRequest('DELETE', url, data, null, successCallback, errorCallback);
        }
    })
});







// $('.general-setting').on('submit', function (e) {
//     e.preventDefault();
//     let formElement = $(this);
//     let submitButtonElement = $(this).find(':submit');
//     let url = $(this).attr('action');
//     let data = new FormData(this);

//     function successCallback() {
//         setTimeout(function () {
//             location.reload();
//         }, 1000)
//     }

//     formAjaxRequest('post', url, data, formElement, submitButtonElement, successCallback);
// });


$('#edit_class_section_id').on('change', function (e, subject_id) {
    // let class_id = $(this).find(':selected').data('class');
    let class_section_id = $(this).val();
    let url = base_path+ '/subject-by-class-section';
    let data = {class_section_id: class_section_id};

    function successCallback(response) {
        if (response.length > 0) {
            let html = '';
            $.each(response, function (key, value) {
                html += '<option value="' + value.subject_id + '">' + value.subject.name + ' - ' + value.subject.type + '</option>'
            });
            $('#edit_subject_id').html(html);
            if (subject_id) {
                $('#edit_subject_id').val(subject_id);
            }
        } else {
            $('#edit_subject_id').html("<option value=''>--No data Found--</option>>");
        }
    }

    ajaxRequest('GET', url, data, null, successCallback, null, null, true)
})

$('#system-update').on('submit', function (e) {
    e.preventDefault();
    let formElement = $(this);
    let submitButtonElement = $(this).find(':submit');
    let url = $(this).attr('action');
    let data = new FormData(this);

    function successCallback() {
        setTimeout(function () {
            window.location.reload();
        }, 1000)
    }

    formAjaxRequest('POST', url, data, formElement, submitButtonElement, successCallback);
})

// $("#create-form-bulk-data").submit(function (e) {
//     e.preventDefault();
//     let formElement = $(this);
//     let submitButtonElement = $(this).find(':submit');
//     let url = $(this).attr('action');
//     let data = new FormData(this);
//
//     function successCallback() {
//         formElement[0].reset();
//     }
//
//     function errorCallback(response) {
//     }
//
//     formAjaxRequest('POST', url, data, formElement, submitButtonElement, successCallback, errorCallback);
// });

$('#admin-profile-update').on('submit', function (e) {
    e.preventDefault();
    let formElement = $(this);
    let submitButtonElement = $(this).find(':submit');
    let url = $(this).attr('action');
    let data = new FormData(this);


    function successCallback() {
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }

    formAjaxRequest('POST', url, data, formElement, submitButtonElement, successCallback);
})
$('.edit-exam-result-marks-form').on('submit', function (e) {
    e.preventDefault();
    let formElement = $(this);
    let submitButtonElement = $(this).find(':submit');
    let url = $(this).attr('action');
    let data = new FormData(this);


    function successCallback() {
        $('#editModal').modal('hide');
        $('#table_list').bootstrapTable('refresh');
    }

    formAjaxRequest('POST', url, data, formElement, submitButtonElement, successCallback);
})

$('.edit-form-timetable').on('submit', function (e) {
    e.preventDefault();
    let formElement = $(this);
    let submitButtonElement = $(this).find(':submit');
    let url = $(this).attr('action');
    let data = new FormData(this);


    function successCallback() {
        $('#editModal').modal('hide');
        $('#table_list').bootstrapTable('refresh');
    }

    formAjaxRequest('POST', url, data, formElement, submitButtonElement, successCallback);
})
$('#verify_email').on('submit', function (e) {
    e.preventDefault();
    let formElement = $(this);
    let submitButtonElement = $(this).find(':submit');
    let url = $(this).attr('action');
    let data = new FormData(this);


    function successCallback() {
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }

    formAjaxRequest('POST', url, data, formElement, submitButtonElement, successCallback);
});

/* TODO : Used in Assign Subject Teacher. Remove this if not Required */
// $('.subject_id').on('change', function () {
//     // let class_id = $(this).find(':selected').data('class');
//     let class_section_id = $('.class_section_id').val();
//     let subject_id = $(this).val();
//     let url = base_path+ '/teacher-by-class-subject';
//     let data = {
//         class_section_id: class_section_id,
//         subject_id: subject_id
//     };
//
//
//     function successCallback(response) {
//         if (response.length > 0) {
//             let html = '';
//             $.each(response, function (key, value) {
//                 html += '<option value="' + value.id + '">' + value.user.first_name + ' ' + value.user.last_name + '</option>'
//             });
//             $('#teacher_id').html(html);
//         } else {
//             $('#teacher_id').html("<option value=''>--No data Found--</option>>");
//         }
//     }
//
//     ajaxRequest('GET', url, data, null, successCallback, null, null, true)
// })

// **************** TODO: MAHESH Route not defined ************
// $('#edit_subject_id').on('change', function () {

//     let edit_id = $('#id').val();
//     let class_section_id = $('#edit_class_section_id').val();
//     let subject_id = $(this).val();
//     let url = base_path+ '/teacher-by-class-subject';
//     let data = {
//         edit_id: edit_id,
//         class_section_id: class_section_id,
//         subject_id: subject_id
//     };

//     function successCallback(response) {
//         if (response.length > 0) {
//             let html = '';
//             $.each(response, function (key, value) {
//                 html += '<option value="' + value.id + '">' + value.user.first_name + ' ' + value.user.last_name + '</option>'
//             });
//             $('#edit_teacher_id').html(html);
//         } else {
//             $('#edit_teacher_id').html("<option value=''>--No data Found--</option>>");
//         }
//     }

//     ajaxRequest('GET', url, data, null, successCallback, null, null, true)
// })


// $(document).on('click', '.remove-fees-type', function (e) {
//     e.preventDefault();
//     // let $this = $(this);
//     // TODO : Remove this and use deletepopup function
//     if ($(this).data('id')) {
//         Swal.fire({
//             title: 'Are you sure?',
//             text: "You won't be able to revert this!",
//             icon: 'warning',
//             showCancelButton: true,
//             confirmButtonColor: '#3085d6',
//             cancelButtonColor: '#d33',
//             confirmButtonText: 'Yes, delete it!'
//         }).then((result) => {
//             if (result.isConfirmed) {
//                 let id = $(this).data('id');
//                 let url = base_path+ '/class/fees-type/' + id;
//
//                 function successCallback(response) {
//                     showSuccessToast(response['message']);
//                     setTimeout(function () {
//                         $('#editModal').modal('hide');
//                     }, 1000)
//                     $('#table_list').bootstrapTable('refresh');
//                     $(this).parent().parent().remove();
//                 }
//
//                 function errorCallback(response) {
//                     showErrorToast(response['message']);
//                 }
//
//                 ajaxRequest('DELETE', url, null, null, successCallback, errorCallback);
//             }
//         })
//     } else {
//         $(this).parent().parent().remove();
//     }
// });
$('.mode').on('change', function (e) {
    e.preventDefault();
    if ($(this).val() == 2) {
        $('.cheque-no-container').show(200);
    } else {
        $('.cheque-no-container').hide(200);
    }
});
$('.pay_student_fees_offline').on('submit', function (e) {
    e.preventDefault();
    let formElement = $(this);
    let submitButtonElement = $(this).find(':submit');
    let url = $(this).attr('action');
    let data = new FormData(this);


    function successCallback() {
        $('#editModal').modal('hide');
        $('.cheque-no-container').hide();
        formElement[0].reset();
        $('#table_list').bootstrapTable('refresh');
    }

    formAjaxRequest('POST', url, data, formElement, submitButtonElement, successCallback);
})
$('.edit_mode').on('change', function (e) {
    e.preventDefault();
    let mode_val = $(this).val();
    if (mode_val == 1) {
        $('.edit_cheque_no_container').show(200);
    } else {
        $('.edit_cheque_no_container').hide(200);
    }
});
$(document).on('click', '.remove-paid-optional-fees', function (e) {
    e.preventDefault();
    // TODO : Remove this and use deletepopup function
    Swal.fire({
        title: window.trans['Are you sure'],
        text: window.trans['You wont be able to revert this'],
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: window.trans['yes_delete'],
        cancelButtonText: window.trans["Cancel"]
    }).then((result) => {
        if (result.isConfirmed) {
            // let amount = $(this).data("amount");
            // let url = $(this).attr('href');
            let id = $(this).data("id");
            let url = base_path+ '/fees/paid/remove-optional-fee/' + id;
            let data = null;

            function successCallback(response) {
                $('#table_list').bootstrapTable('refresh');
                showSuccessToast(response.message);
                window.location.reload();
            }

            function errorCallback(response) {
                showErrorToast(response.message);
            }

            ajaxRequest('DELETE', url, data, null, successCallback, errorCallback);
        }
    })
})
$('#create-fees-config-form').on('submit', function (e) {
    e.preventDefault();
    let formElement = $(this);
    let submitButtonElement = $(this).find(':submit');
    let url = $(this).attr('action');
    let data = new FormData(this);

    function successCallback() {
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }

    formAjaxRequest('POST', url, data, formElement, submitButtonElement, successCallback);
})
$('#edit-fees-paid-form').on('submit', function (e) {
    e.preventDefault();
    let formElement = $(this);
    let submitButtonElement = $(this).find(':submit');
    let data = new FormData(this);
    data.append("_method", "PUT");
    let url = $(this).attr('action') + "/" + data.get('edit_id');

    function successCallback() {
        $('#table_list').bootstrapTable('refresh');
        setTimeout(function () {
            $('#editFeesPaidModal').modal('hide');
        }, 1000)
    }

    formAjaxRequest('POST', url, data, formElement, submitButtonElement, successCallback);
})

$('#razorpay_status').on('change', function (e) {
    e.preventDefault();
    if ($(this).val() == 1) {
        $('#stripe_status').val(0);
        $('#bank_transfer_status').val(0);
    } else {
        $('#stripe_status').val(1);
    }
});
$('#stripe_status').on('change', function (e) {
    e.preventDefault();
    if ($(this).val() == 1) {
        $('#razorpay_status').val(0);
        $('#bank_transfer_status').val(0);
    } else {
        $('#razorpay_status').val(1);
    }
});

$('#bank_transfer_status').on('change', function (e) {
    e.preventDefault();
    if ($(this).val() == 1) {
        $('#razorpay_status').val(0);
        $('#stripe_status').val(0);
    } else {
        $('#bank_transfer_status').val(1);
    }
});

$('#assign-roll-no-form').on('submit', function (e) {
    e.preventDefault();
    Swal.fire({
        title: window.trans["Are you sure"],
        text: window.trans["delete_warning"],
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: window.trans["Yes, Change it"],
        cancelButtonText: window.trans["Cancel"]
    }).then((result) => {
        if (result.isConfirmed) {
            let formElement = $(this);
            let submitButtonElement = $(this).find(':submit');
            let url = $(this).attr('action');
            let data = new FormData(this);

            function successCallback() {
                $('#table_list').bootstrapTable('refresh');
            }

            formAjaxRequest('POST', url, data, formElement, submitButtonElement, successCallback);
        }
    })
})

// $('#add-new-option').on('click', function (e) {
//     e.preventDefault();
//     let html = $('.option-container').find('.form-group:last').clone();
//     html.find('.add-question-option').val('');
//     html.find('.error').remove();
//     html.find('.has-danger').removeClass('has-danger');
//     $('.remove-option-content').css('display', 'none');
//     html.addClass('quation-option-extra');

//     // html.removeClass('col-md-6').addClass('col-md-5');
//     // This function will increment in the label option number
//     let inner_html = html.find('.option-number:last').html();
//     html.find('.option-number:last').each(function (key, element) {
//         inner_html = inner_html.replace(/(\d+)/, function (str, p1) {
//             return (parseInt(p1, 10) + 1);
//         });
//     })
//     html.find('.option-number:last').html(inner_html)

//     // This function will replace the last index value and increment in the multidimensional name attribute
//     html.find(':input').each(function () {
//         this.name = this.name.replace(/\[(\d+)\]/, function (str, p1) {
//             return '[' + (parseInt(p1, 10) + 1) + ']';
//         });
//     })
//     html.find('.remove-option-content').html('<button class="btn btn-inverse-danger remove-option btn-sm mt-1" type="button"><i class="fa fa-times"></i></button>')
//     $('.option-container').append(html)

//     let select_answer_option = '<option value=' + inner_html + ' class="answer_option extra_answers_options">' + window.trans["option"] + ' ' + inner_html + '</option>'
//     $('#answer_select').append(select_answer_option)
// });
// $(document).on('click', '.remove-option', function (e) {
//     e.preventDefault();
//     $(this).parent().parent().remove();
//     $('.option-container').find('.form-group:last').find('.remove-option-content').css('display', 'block');
//     $('#answer_select').find('.answer_option:last').remove();
// })
$('#create-online-exam-questions-form').on('submit', function (e) {
    e.preventDefault();
    for (let equation_editor in CKEDITOR.instances) {
        CKEDITOR.instances[equation_editor].updateElement();
    }
    let formElement = $(this);
    let submitButtonElement = $(this).find(':submit');
    let url = $(this).attr('action');
    let data = new FormData(this);

    function successCallback() {
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }

    formAjaxRequest('POST', url, data, formElement, submitButtonElement, successCallback);
})
// $('.question_type').on('change', function (e) {
//     $('.quation-option-extra').remove();
//     $('#answer_select').val(null).trigger("change");
//     if ($(this).val() == 1) {
//         $('#simple-question').hide();
//         $('#equation-question').show(500);
//     } else {
//         $('#simple-question').show(500);
//         $('#equation-question').hide();
//     }
// })
// $('#add-new-eqation-option').on('click', function (e) {
//     e.preventDefault();
//     let html = $('.equation-option-container').find('.quation-option-template:last').clone();
//     html.find('.error').remove();
//     html.find('.has-danger').removeClass('has-danger');
//     $('.remove-equation-option-content').css('display', 'none');

//     // html.removeClass('col-md-6').addClass('col-md-5');
//     // This function will increment in the label equation-option-number
//     let inner_html = html.find('.equation-option-number:last').html();
//     html.find('.equation-option-number:last').each(function (key, element) {
//         inner_html = inner_html.replace(/(\d+)/, function (str, p1) {
//             return (parseInt(p1, 10) + 1);
//         });
//     })

//     // This function will replace the last index value and increment in the multidimensional name attribute
//     let name;
//     html.find(':input').each(function (key, element) {
//         this.name = this.name.replace(/\[(\d+)\]/, function (str, p1) {
//             name = '[' + (parseInt(p1, 10) + 1) + ']';
//             return name;
//         });
//     })

//     let option_html = '<div class="form-group col-md-6 equation-editor-options-extra quation-option-template"><label>' + window.trans["option"] + ' <span class="equation-option-number">' + inner_html + '</span> <span class="text-danger">*</span></label><textarea class="editor_options" name="eoption' + name + '" placeholder="' + window.trans["Select Option"] + '"></textarea><div class="remove-equation-option-content"><button class="btn btn-inverse-danger remove-equation-option btn-sm mt-1" type="button"><i class="fa fa-times"></i></button></div></div>'
//     $('.equation-option-container').append(option_html).ready(function () {
//         createCkeditor();
//     });
//     let select_answer_option = '<option value=' + inner_html + ' class="answer_option extra_answers_options">' + window.trans["option"] + ' ' + inner_html + '</option>'
//     $('#answer_select').append(select_answer_option)
// });
$(document).on('click', '.remove-equation-option', function (e) {
    e.preventDefault();
    $(this).parent().parent().remove();
    $('.equation-option-container').find('.form-group:last').find('.remove-equation-option-content').css('display', 'block');
    $('#answer_select').find('.answer_option:last').remove();
})

$('.edit-question-type').on('change', function () {
    if ($(this).val() == 1) {
        $('#edit-simple-question-content').hide();
        $('#edit-equation-question-content').show(500);
    } else {
        $('#edit-simple-question-content').show(500);
        $('#edit-equation-question-content').hide();
    }
})
$(document).on('click', '.add-new-edit-option', function (e) {
    e.preventDefault();
    let html = $('.edit_option_container').find('.form-group:last').clone();
    html.find('.add-edit-question-option').val('');
    html.find('.error').remove();
    html.find('.has-danger').removeClass('has-danger');
    html.find('.edit_option_id').val('')
    let hide_button;
    hide_button = $('.remove-edit-option-content:last').find('.remove-edit-option')
    if (hide_button.data('id')) {
        $('.remove-edit-option-content:last').css('display', 'block');
    } else {
        $('.remove-edit-option-content:last').css('display', 'none');
    }

    // This function will increment in the label option number
    let inner_html = html.find('.edit-option-number:last').html();
    html.find('.edit-option-number:last').each(function () {
        inner_html = inner_html.replace(/(\d+)/, function (str, p1) {
            return (parseInt(p1, 10) + 1);
        });
    })
    html.find('.edit-option-number:last').html(inner_html)

    // This function will replace the last index value and increment in the multidimensional name attribute
    html.find(':input').each(function () {
        this.name = this.name.replace(/\[(\d+)\]/, function (str, p1) {
            return '[' + (parseInt(p1, 10) + 1) + ']';
        });
    })
    html.find('.remove-edit-option-content').html('<button class="btn btn-inverse-danger remove-edit-option btn-sm mt-1" type="button"><i class="fa fa-times"></i></button>')
    $('.edit_option_container').append(html)

    let select_answer_option = '<option value="new' + $.trim(inner_html) + '" class="edit_answer_option">' + window.trans["option"] + ' ' + inner_html + '</option>'
    $('.edit_answer_select').append(select_answer_option)
});

$('#add-new-question-online-exam').on('submit', function (e) {
    e.preventDefault();
    for (let equation_editor in CKEDITOR.instances) {
        CKEDITOR.instances[equation_editor].updateElement();
    }
    let formElement = $(this);
    let submitButtonElement = $(this).find(':submit');
    let url = $(this).attr('action');
    let data = new FormData(this);

    function successCallback(response) {
        // Get the CKEditor instance
        let editors = Object.values(CKEDITOR.instances);

        setTimeout(() => {
            location.reload();
        }, 1000);

        // Loop through each instance
        editors.filter(editor => editor.element.hasClass('editor_question')).forEach(editor => {
            editor.setData(''); // clear the text
            editor.resetDirty(); // reset the points to save the changes
        });

        editors.filter(editor => editor.element.hasClass('editor_options')).forEach(editor => {
            editor.setData(''); // clear the text
            editor.resetDirty(); // reset the points to save the changes
        });


        // remove the extra options of ckeditor
        $(document).find('.equation-editor-options-extra').remove();
        $(document).find('.extra_answers_options').remove();

        $('.add-new-question-container').hide(200)
        $('.add-new-question-button').show(300).ready(function () {
            $('.add-new-question-button').html(window.trans["add_new_question"]);
        })
        formElement[0].reset();

        $('#answer_select').val(null).trigger("change");
        $('.quation-option-extra').remove();
        $('#table_list_exam_questions').bootstrapTable('refresh');

        function checkList(listName, newItem) {
            let duplicate = false;
            $("#" + listName + " > div").each(function () {
                if ($(this)[0] !== newItem[0]) {
                    if ($(this).html() == newItem.html()) {
                        duplicate = true;
                    }
                }
            });
            return !duplicate;
        }

        let li;

        li = $('<div class="list-group"><input type="hidden" name="assign_questions[' + response.data.question_id + '][question_id]" value="' + response.data.question_id + '"><li id="q' + response.data.question_id + '" class="list-group-item justify-content-between align-items-center ui-state-default list-group-item-secondary m-2">' + response.data.question_id + ". " + response.data.question + ' <span class="text-right row mx-0"><input type="number" class="list-group-item col-md-3 col-sm-12 mr-2 mb-2" name="assign_questions[' + response.data.question_id + '][marks]" placeholder="' + trans['enter_marks'] + '"><a class="btn btn-danger btn-sm remove-row mb-2" data-id="' + response.data.question_id + '"><i class="fa fa-times" aria-hidden="true"></i></a></span></li></div>');

        let pasteItem = checkList("sortable-row", li);
        if (pasteItem) {
            $("#sortable-row").append(li);
        }
    }

    formAjaxRequest('POST', url, data, formElement, submitButtonElement, successCallback);
});
$('.add-new-question-button').on('click', function (e) {
    e.preventDefault();
    $('#answer_select').val(null).trigger("change");
    $('.add-new-question-container').show(300);
    createCkeditor();
    $(this).hide();
    $(this).html('');
})
$('.remove-add-new-question').on('click', function (e) {
    e.preventDefault();
    $('.add-new-question-container').hide(300);
    $('.add-new-question-button').show(300).ready(function () {
        $('.add-new-question-button').html(window.trans["add_new_question"]);
    });
})
$(document).on('click', '.remove-row', function () {
    let id = $(this).data('id');
    let edit_id = $(this).data('edit_id');
    let $this = $(this);
    if (edit_id) {
        Swal.fire({
            title: window.trans["Are you sure"],
            text: window.trans["delete_warning"],
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: window.trans["yes_delete"],
            cancelButtonText: window.trans["Cancel"]
        }).then((result) => {
            if (result.isConfirmed) {
                let url = base_path+ '/online-exam/remove-choiced-question/' + edit_id;

                function successCallback(response) {
                    showSuccessToast(response.message);
                    $this.parent().parent().parent().remove();
                    $('#table_list_exam_questions').bootstrapTable('refresh');
                }

                function errorCallback(response) {
                    showErrorToast(response.message);
                }

                ajaxRequest('DELETE', url, null, null, successCallback, errorCallback);
            }
        })
    } else {
        $(this).parent().parent().parent().remove();
        $('#table_list_exam_questions').bootstrapTable('uncheckBy', {field: 'question_id', values: [id]})
    }
})
$('#store-assign-questions-form').on('submit', function (e) {
    e.preventDefault();
    let formElement = $(this);
    let submitButtonElement = $(this).find(':submit');
    let url = $(this).attr('action');
    let data = new FormData(this);

    function successCallback() {
        window.location.reload();
    }

    formAjaxRequest('POST', url, data, formElement, submitButtonElement, successCallback);
})

$('#edit-online-exam-questions-form').on('submit', function (e) {
    e.preventDefault();
    for (let equation_editor in CKEDITOR.instances) {
        CKEDITOR.instances[equation_editor].updateElement();
    }
    let formElement = $(this);
    let submitButtonElement = $(this).find(':submit');
    let data = new FormData(this);
    data.append("_method", "PUT");
    let url = $(this).attr('action') + "/" + data.get('edit_id');

    function successCallback() {
        setTimeout(function () {
            window.location.reload();
        }, 1000)
    }

    formAjaxRequest('POST', url, data, formElement, submitButtonElement, successCallback);
})

// $(document).on('click', '.delete-question-form', function (e) {
//     e.preventDefault();
//     Swal.fire({
//         title: 'Are you sure?',
//         text: "You won't be able to revert this!",
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         confirmButtonText: 'Yes, delete it!'
//     }).then((result) => {
//         if (result.isConfirmed) {
//             let url = $(this).attr('href');
//             let data = null;

//             function successCallback(response) {
//                 $('#table_list_questions').bootstrapTable('refresh');
//                 showSuccessToast(response.message);
//             }

//             function errorCallback(response) {
//                 showErrorToast(response.message);
//             }

//             ajaxRequest('DELETE', url, data, null, successCallback, errorCallback);
//         }
//     })
// })
// $('#table_list_questions').on('load-success.bs.table', function () {
//     createCkeditor();
// });
$('#table_list_exam_questions').on('load-success.bs.table', function () {
    createCkeditor();
});

$('input[type="file"]').on('change', function () {
    $(this).closest('form').valid();
})

$(document).on('click', '.delete-class-section', function (e) {
    e.preventDefault();
    let $this = $(this);
    showDeletePopupModal($(this).attr('href'), {
        successCallBack: function () {
            $this.siblings('label').children('input').attr('checked', false).removeAttr('disabled');
            $this.siblings('a').remove();
            $this.remove();
        }
    })
})

// Function to make remove button accessible on the basis of Option Section Length
let toggleAccessOfDeleteButtons = () => {
    if ($('.option-section').length >= 3) {
        $('.remove-default-option').removeAttr('disabled');
    } else {
        $('.remove-default-option').attr('disabled', true);
    }
}

// Function to make remove button accessible on the basis of Option Section Length
let editToggleAccessOfDeleteButtons = () => {
    if ($('.edit-option-section').length >= 3) {
        $('.remove-edit-default-option').removeAttr('disabled');
    } else {
        $('.remove-edit-default-option').attr('disabled', true);
    }
}


$('.type-field').on('change', function (e) {
    e.preventDefault();

    const inputValue = $(this).val();
    const optionSection = $('.default-values-section');

    // Show/hide the "default-values-section" based on the selected value using a switch statement
    switch (inputValue) {
        case 'dropdown':
        case 'radio':
        case 'checkbox':
            optionSection.show(500).find('input').attr('required', true);
            break;
        default:
            optionSection.hide(500).find('input').removeAttr('required');
            break;
    }

});






// // To Add Second Option
// $(function () {
//     $('.add-new-option').click()
// });







// Student Reset Password Event
$(document).on('click', '.reset_password', function (e) {
    e.preventDefault();
    let studentID = $(this).data('id');
    let studentDOB = $(this).data('dob');
    let url = $(this).data('url');
    Swal.fire({
        title: window.trans["are_you_sure"],
        text: window.trans["reset_student_password"],
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: window.trans["Yes, Change it"],
        cancelButtonText: window.trans["Cancel"]
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    id: studentID,
                    dob: studentDOB
                },
                success: function (response) {
                    if (response.error == true) {
                        showErrorToast(response.message);
                    } else {
                        showSuccessToast(response.message);
                        $('#table_list').bootstrapTable('refresh');
                    }
                }
            })
        }
    })
})

// For Announcement Create Form Class Section And Subject
// $('.show_class_section_id').hide();
$('#assign_to').on('change', function () {
    let data = $(this).val();
    if (data == 'class_section') {
        $('.show_class_section_id').show();
        $('.show_class_section_id').find('.class_section_id').attr('required', true);
    } else {
        $('.show_class_section_id').find('.class_section_id').removeAttr('required');
        $('.show_class_section_id').hide();
    }
});
// -------------------------------------------------------------------------------------------------------------------


// For Announcement Edit Form Class Section And Subject
$('.edit_show_class_section_id').hide();
$('#edit-assign-to').on('change', function () {
    let data = $(this).val();
    if (data == 'class_section') {
        $('.edit_show_class_section_id').show();
        $('.edit_show_class_section_id').find('#edit-class-section-id').attr('required', true);
    } else {
        $('.edit_show_class_section_id').find('#edit-class-section-id').removeAttr('required');
        $('.edit_show_class_section_id').hide();
    }
});
// -------------------------------------------------------------------------------------------------------------------


// Function to Check that Ending Range Should not be more than 100
function endingRangeEvent() {
    // Get Last Ending Range
    let endingRange = ($('.grade-content').find('.ending-range:last'));

    // Add Key Up Event to check that Value should not be more than 100
    endingRange.on('change keyup', function () {
        if (parseInt($(this).val()) >= 100) {
            $('.add-grade-content').prop('disabled', true); // Make Add New Button Disabled
        } else {
            $('.add-grade-content').prop('disabled', false); // Make Add New Button Clickable
        }
    });
}

// Check the Change max value of Starting Range
function ChangeMaxValueOfStartingRange() {
    $('.ending-range').on('change keyup', function () {
        $(this).parent().siblings().find(".starting-range").attr('max', ($(this).val() - 1));
    })
}


$(document).ready(function () {
    endingRangeEvent(); // Call Ending Range Function on the DOM LOAD
    ChangeMaxValueOfStartingRange();
});

// --------------------------------------------------------------------------------------------------------------------------





/*Create Timetable Page*/

function isRTL() {
    var dir = $('html').attr('dir');
    if (dir === 'rtl') {
        return true;
    } else {
        return false;
    }
    return false;
}
$(document).on('change', '.timetable_start_time', function () {
    let $this = $(this);
    let end_time = $(this).parent().siblings().children('.timetable_end_time');
    $(end_time).rules("add", {
        timeGreaterThan: $this,
    });
})

let days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
let calendarEl = document.getElementById('calendar');
let containerEl = document.getElementById('external-events');
if (containerEl !== null) {
    new FullCalendar.Draggable(containerEl, {
        itemSelector: '.fc-event',
        eventData: function (eventEl) {
            return {
                title: eventEl.innerText,
                color: $(eventEl).data('color'),
                duration: $(eventEl).data('duration'),
                textColor: getContrastColor($(eventEl).data('color')),
                // "data-id": $(eventEl).data('id'),
            };
        }
    });

}
let layout_direction = 'ltl';
if (isRTL()) {
    layout_direction = 'rtl'
} else {
    layout_direction = 'ltl'
}






$(document).ready(function () {
    $('#class-section-id').trigger('change')
    $('#exam_id').trigger('change')
    $('#exam-class-section-id').trigger('change');
    $('#exam-id').trigger('change');
    $('#transfer_class_section').trigger('change');
    $('#filter-class-section-id').trigger('change');
    $('#filter_session_year_id').trigger('change');
});

$('#class-section-id').on('change', function () {
    getSubjectOptionsList('#subject-id', $(this))
});

$('#class-section-id').on('change', function () {
    var selectedOption = $(this).find(':selected');
    var dataId = selectedOption.data('class-id');
    getClassSubjectOptionsList('#class-subject-id', dataId)
});

$('#filter-class-section-id').on('change', function () {
    var selectedOption = $(this).find(':selected');
    var dataId = selectedOption.data('class-id');
    getClassSubjectOptionsList('#filter-class-subject-id', dataId)
});

$('#edit-class-section-id').on('change', function () {
    getSubjectOptionsList('#edit-subject-id', $(this))
});

$('#filter-class-section-id').on('change', function () {
    getFilterSubjectOptionsList('#filter-subject-id', $(this))
});

$('#exam-id').on('change', function () {
    getExamSubjectOptionsList('#class_subject_id', $(this), $('#exam-class-section-id').val())
});

$('#filter_session_year_id').on('change', function () {
    // TODO : this code needs to be improved. Instead of this Ajax should be here
    getExamOptionsList('#filter_exam_id', $(this))
});
$('#session_year_id').on('change', function () {
    // TODO : this code needs to be improved. Instead of this Ajax should be here
    getExamOptionsList('#exam_id', $(this))
});

$('#exam_result_session_year_id').on('change', function () {
    getDashboardExamOptionsList('#exam_reuslt_exam_name', $(this))
});

$('#filter-class-id').on('change', function () {
    getExamOptionsListByClass('#filter-exam-id', $(this))
});

$('#filter_exam_id').on('change', function () {
    // TODO : this code needs to be improved. Instead of this Ajax should be here
    getExamClassOptionsList('#filter_class_section_id')
});







$('.fees-installment-toggle').on('change', function () {
    if ($(this).val() == 1) {
        $('#add-installment').trigger('click');
        $('.fees-installment-repeater').delay(50).show(600)
    } else {
        $('.fees-installment-repeater').hide(200);
        $('.fees-installment-repeater').find('[data-repeater-item]').slice(0).empty();
    }
})

$(document).on('click', '.pay-in-installment', function () {
    if ($(this).is(':checked')) {
        $('#installment-mode').val(1)
        $('.installment_rows').show(200);
        $('#total_amount_text').html(Number(0).toFixed(2));
        $('.without_installment_enter_amount').addClass('d-none');

        $('.installment-checkbox').each(function () {
            if ($(this).hasClass('default-checked-installment')) {
                $(this).prop('checked', true).trigger('change');
                // $(this).bind("click", function () {
                //     return false;
                // });
            }
        })
    } else {
        // 
        $('.without_installment_enter_amount').removeClass('d-none');
        $('.default-checked-installment').prop('checked', false).trigger('change');
        $('.installment_rows').hide(200);
        $('#installment-mode').val(0);
        $('#total_amount_text').html($('#total_compulsory_fees').val());
    }
})

$('.installment-checkbox').on('change', function () {
    let installmentAmount = parseFloat($(this).siblings('.installment-amount').val());
    let dueChargesAmount = parseFloat($(this).siblings('.due-charges-amount').val());
    let installmentWithDueCharges = installmentAmount + dueChargesAmount;
    let totalInstallmentAmount = parseFloat($('#total_installment_amount').val());
    let remainingAmount = parseFloat($('#remaining_amount').val());

    $('.enter_amount').val(0);
    $('#advance').trigger('change');


    if ($(this).is(':checked')) {
        // $('#total_amount_text').html((totalAmount + totalInstallmentAmount).toFixed(2));
        $('#total_installment_amount').val((totalInstallmentAmount + installmentWithDueCharges).toFixed(2)).trigger('change');
        $('#remaining_amount').val((remainingAmount - installmentAmount).toFixed(2)).trigger('change');
        $(this).siblings().prop('disabled', false);
    } else {
        // $('#total_amount_text').html((totalAmount - totalInstallmentAmount).toFixed(2));
        $('#total_installment_amount').val((totalInstallmentAmount - installmentWithDueCharges).toFixed(2)).trigger('change');

        $('#remaining_amount').val((remainingAmount + installmentAmount).toFixed(2)).trigger('change');
        $(this).siblings().prop('disabled', true);
    }
});

$('#remaining_amount').on('change', function () {
    $('#advance').prop('max', parseFloat($(this).val()).toFixed(2));
})

$('#total_installment_amount').on('change', function () {
    let totalInstallmentAmount = parseFloat($(this).val());
    let advance = parseFloat($('#advance').val());
    $('#total_amount_text').html((totalInstallmentAmount + advance).toFixed(2));
})

$('#advance').on('change', function () {
    let totalAmount = parseFloat($('#total_installment_amount').val());
    let advance = parseFloat($(this).val());
    $('#total_amount_text').text((totalAmount + advance).toFixed(2));
})

$('#exam-class-section-id').on('change', function () {
    // Get Class ID form the Data Attribute of Class Selected
    let classId = $(this).find('option[value="' + $(this).val() + '"]').data('classid');

    // Add Exams Options According to Class ID
    $('#exam-id').val("").removeAttr('disabled').show();
    $('#exam-id').find('option').hide();
    if ($('#exam-id').find('option[data-classId="' + classId + '"]').length) {
        $('#exam-id').find('option[data-classId="' + classId + '"]').show();
    } else {
        $('#exam-id').val("data-not-found").attr('disabled', true).show();
    }
})

// Timetable set text color depend in subject div color
$(document).ready(function () {
    // Sidebar #Subject
    setTimeout(() => {
        // fc-div-color
        $(".fc-div-color").each(function () {
            // Access each element using $(this)
            let div_color = $(this).css("background-color");
            // Convert color rgb to hex

            let textColor = getContrastColor(div_color);

            $(this).find('.fc-event-main').css('color', textColor);
        });

        // fc-event-time
    }, 1000);

    // Calendar data
    setTimeout(() => {
        $('.fc-event-start').each(function () {
            // element == this
            let div_color = $(this).css('background-color');
            var textColor = getContrastColor(div_color);
            $(this).find("*").css('color', textColor);

        });
    }, 1000);

});


// End timetable color

$('#subject-id').on('change', function () {
    let classSectionId = $("#class-section-id").val()
    $("#topic-lesson-id").val("").removeAttr('disabled').show();
    $("#topic-lesson-id").find('option').hide();
    if ($("#topic-lesson-id").find('option[data-class-section="' + classSectionId + '"][data-subject="' + $(this).val() + '"]').length) {
        $("#topic-lesson-id").find('option[data-class-section="' + classSectionId + '"][data-subject="' + $(this).val() + '"]').show();
    } else {
        $("#topic-lesson-id").val("data-not-found").attr('disabled', true).show();
    }
})





$('.include_semesters').on('change', function () {
    if ($(this).is(':checked')) {
        $(this).val(1)
    } else {
        $(this).val(0)
    }
})

$(document).on('change', '.semesters', function () {
    let semester = $(this);
    let subjects = $(this).parents('.semester-div').next('div').find('.subject');

    subjects.each(function (index, subject) {
        $(subject).attr('data-group', $(semester).val());
    })
})




// $('#stream_id').on('change', function (e) {
//     if ($('#stream_id').val().length == 0) {
//         $('#default-section-div').show();
//         $('#stream-wise-section-div').hide();
//     }
// })

$('#roll-number-order').on('change', function () {
    let value = $(this).val().split(',');
    $('#roll-number-sort-column').val(value[0]);
    $('#roll-number-sort-order').val(value[1]);
})

$(document).ready(function () {
    let sortColumn = $('#roll-number-sort-column').val();
    let sortOrder = $('#roll-number-sort-order').val();
    if (sortColumn && sortOrder) {
        let selectValue = sortColumn + ',' + sortOrder;
        $('#roll-number-order').val(selectValue).trigger('change');
    }
});

$('#change-roll-ckh-settings').on('click', function () {
    Swal.fire({
        title: window.trans["Are you sure"],
        text: window.trans["Change Roll Number for All Classes"],
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: window.trans["Yes"],
        cancelButtonText: window.trans["Cancel"]
    }).then((result) => {
        if (result.isConfirmed) {
            $(this).prop("checked", true);
        } else {
            $(this).prop("checked", false);
        }
    })
})
$('.delete-related-data').on('click', function (e) {
    e.preventDefault();
    let url = base_path+ "/related-data/delete/" + $(this).data('table') + "/" + $(this).data('id');

    showDeletePopupModal(url, {
        text: "After deleting this, It won't be possible to recover this data",
    });
})


$("#select-all").click(function () {
    let dropdown = $(this).parent().parent().siblings('select');
    if ($(this).is(':checked')) {
        $(dropdown).find("option").prop("selected", "selected");
        $(dropdown).trigger("change");
    } else {
        $(dropdown).find("option").removeAttr("selected");
        $(dropdown).val('').trigger("change");
    }
});


$('#to_date,#from_date').change(function (e) {
    e.preventDefault();
    let from_date = $('#from_date').val().split("-").reverse().join("-");
    let to_date = $('#to_date').val().split("-").reverse().join("-");
    let div = '.leave_dates';
    let to_date_null = '#to_date';
    let disabled = '';
    let holiday_days = $('.holiday_days').val();
    // public_holiday
    let public_holiday = $('.public_holiday').val();
    if (holiday_days) {
        holiday_days = holiday_days.split(',');
    } else {
        holiday_days = [];
    }
    let html = date_list(from_date, to_date, div, to_date_null, disabled, holiday_days, public_holiday);

    $('.leave_dates').html(html);
});

$('#edit_to_date,#edit_from_date').change(function (e) {
    e.preventDefault();
    let from_date = $('#edit_from_date').val().split("-").reverse().join("-");
    let to_date = $('#edit_to_date').val().split("-").reverse().join("-");
    let div = '.edit_leave_dates';
    let to_date_null = '#edit_to_date';
    let disabled = 'disabled';
    let holiday_days = $('.holiday_days').val();
    let public_holiday = $('.public_holiday').val();
    if (holiday_days) {
        holiday_days = holiday_days.split(',');
    } else {
        holiday_days = [];
    }
    let html = date_list(from_date, to_date, div, to_date_null, disabled, holiday_days, public_holiday);

    $('.edit_leave_dates').html(html);
});

function date_list(from_date, to_date, div, to_date_null, disabled, holiday_days, public_holiday) {
    if (from_date && to_date) {
        from_date = new Date(from_date);
        to_date = new Date(to_date);
        var days = [window.trans["Sunday"], window.trans["Monday"], window.trans["Tuesday"], window.trans["Wednesday"], window.trans["Thursday"], window.trans["Friday"], window.trans["Saturday"]];
        if (from_date > to_date) {
            $(to_date_null).val('');
        }

        if (public_holiday) {
            public_holiday = public_holiday.split(',');
        }

        let html = '';
        $(div).slideDown(500);
        while (from_date <= to_date) {
            let date = moment(from_date, 'YYYY-MM-DD').format('DD-MM-YYYY');
            let day = days[from_date.getDay()];
            if (!holiday_days.includes(day) && !public_holiday.includes(date)) {
                html += '<div class="form-group col-sm-12 col-md-12">';
                html += '<label class="mr-2">' + date + '</label>-';
                html += '<label class="ml-2">' + day + '</label>';
                html += '<div class="form-group row col-sm-12 col-md-12"> <div class="form-check mr-3"> <label class="form-check-label"> <input type="radio" class="form-check-input" name="type[' + date + '][]" id="optionsRadios1" checked="" ' + disabled + ' value="Full"> ' + window.trans['full'] + ' <i class="input-helper"></i></label> </div> <div class="form-check mr-3"> <label class="form-check-label"> <input type="radio" class="form-check-input" name="type[' + date + '][]" id="optionsRadios2" ' + disabled + ' value="First Half"> ' + window.trans['first_half'] + ' <i class="input-helper"></i></label> </div> <div class="form-check mr-3"> <label class="form-check-label"> <input type="radio" class="form-check-input" name="type[' + date + '][]" id="optionsRadios3" ' + disabled + ' value="Second Half">' + window.trans['second_half'] + ' <i class="input-helper"></i></label> </div> </div>';
                html += '</div>';
            }
            from_date.setDate(from_date.getDate() + 1);
        }
        return html;
    }
}


$('#send_verification_email').on('submit', function (e) {
    e.preventDefault();
    let formElement = $(this);
    let submitButtonElement = $(this).find(':submit');
    let url = $(this).attr('action');
    let data = new FormData(this);
    formAjaxRequest('POST', url, data, formElement, submitButtonElement, function () {
        $('#error-div').hide();
    }, function (response) {
        $('#error-div').show();
        $('#error').text(response.data.error);
        $('#stacktrace').text(response.data.stacktrace);
    });

})

$(document).on('input', '.amount', function () {
    $('#due_charges_percentage').trigger('input');
})

$('#due_charges_percentage').on('input', function () {
    let compulsoryFeesAmounts = $('.compulsory-fees-types').find('.amount');

    let totalCompulsoryFee = 0;

    compulsoryFeesAmounts.each(function (value, element) {
        totalCompulsoryFee += parseFloat($(element).val());
    })

    let dueAmount = totalCompulsoryFee * $("#due_charges_percentage").val() / 100;
    $('#due_charges_amount').val(dueAmount);
})

$('#due_charges_amount').on('input', function () {
    let compulsoryFeesAmounts = $('.compulsory-fees-types').find('.amount');

    let totalCompulsoryFee = 0;

    compulsoryFeesAmounts.each(function (value, element) {
        totalCompulsoryFee += parseFloat($(element).val());
    })

    let duePercentage = ($("#due_charges_amount").val() * 100) / totalCompulsoryFee;
    $('#due_charges_percentage').val(duePercentage);
})



$('.filter_birthday').change(function (e) { 
    e.preventDefault();
    let type = $(this).val();
    $.ajax({
        type: "get",
        url: base_path+ '/users/birthday/'+ type,
        success: function (response) {
            let html = '';
            if (response.data.length) {
                $.each(response.data, function (index, value) { 
                    html += '<tr> <td> <img src="'+value.image+'" onerror="onErrorImage(event)" class="me-2" alt="image"> </td> <td>'+value.full_name+' </td> <td class="text-right">'+value.dob_date+'</td> </tr>';
                });
            } else {
                html += '<tr> <td colspan="2" class="text-center"> '+window.trans['no_data_found']+' </td> </tr>';
            }
            setTimeout(() => {
                $('.birthday-list').html(html);
            }, 500);
        }
    });
});

$('.filter_leaves').change(function (e) {
    e.preventDefault();
    let filter_leave = $(this).val();
    let url = base_path+ '/leave/filter';
    let data = {
        'filter_leave': filter_leave,
    };

    function successCallback(response) {
        let html = ""
        if (response.data.length > 0) {
            $.each(response.data, function (index, value) {
                if (value.type == "Full") {
                    html += '<tr> <td>'+value.leave.user.full_name+'<span class="m-2 text-white text-small leave-type leave-full">'+value.type+' Day</span> </td> <td class="text-right">'+value.leave_date+'</td> </tr>';
                }
                if (value.type == "First Half") {
                    html += '<tr> <td>'+value.leave.user.full_name+'<span class="m-2 text-white text-small leave-type leave-half">'+value.type+'</span> </td> <td class="text-right">'+value.leave_date+'</td> </tr>';
                }
                if (value.type == "Second Half") {
                    html += '<tr> <td>'+value.leave.user.full_name+'<span class="m-2 text-white text-small leave-type leave-half">'+value.type+'</span> </td> <td class="text-right">'+value.leave_date+'</td> </tr>';
                }
                
            });
        } else {
            // 
            html += '<tr> <td colspan="2" class="text-center"> '+window.trans['All are presents']+' </td> </tr>';
        }
        $('.leave-list').html(html);
    }

    ajaxRequest('GET', url, data, null, successCallback, null, null, true);
});

$('#filter_expense_session_year_id').change(function(e) {
    e.preventDefault();
    let session_year_id = $(this).val();
    $.ajax({
        type: "get",
        url: base_path+ '/expense/filter/' + session_year_id,
        success: function(response) {
            if (response.data) {
                setTimeout(() => {
                    expense_graph(response.data.expense_months, response.data.expense_amount);
                }, 1000);
            }
        }
    });
});

$('#exam_result_session_year_id,#exam_reuslt_exam_name').on('change', function (e) {
    e.preventDefault();
    let exam_name = $('#exam_reuslt_exam_name').val();
    let session_year_id = $('#exam_result_session_year_id').val();
    if (exam_name && session_year_id) {
        $.ajax({
            type: "get",
            url: base_path+ '/exams/result-report/'+session_year_id+'/'+exam_name,
            success: function (response) {                
                let html = '';
                if (response.data.length) {
                    let bg_colors = ['bg-success','bg-info','bg-primary','bg-warning','bg-danger'];
                    $.each(response.data, function (index, value) { 
                         let total_students = parseInt(value.total_students);
                         let total_pass = parseInt(value.pass_students);
                         let per = (total_pass*100) / total_students;
                         per = per.toFixed(2);
                         html += '<div class="d-flex justify-content-between mt-3"> <small class="font-weight-bold">'+window.trans['Class']+': '+value.class_name+'</small> <small class="font-weight-bold">'+per+'%</small> </div> <div class="progress progress-lg mt-2"> <div class="progress-bar '+bg_colors[index]+'" role="progressbar" style="width: '+per+'%" aria-valuenow="'+per+'" aria-valuemin="0" aria-valuemax="100"></div> </div>';

                    });
                }
                $('#class-progress-report').html(html);
            }
        });    
    } else {
        $('#class-progress-report').html('');
    }
})

$('.class-section-attendance').change(function (e) { 
    e.preventDefault();
    let class_id = $(this).val();

    $.ajax({
        type: "get",
        url: base_path+ '/class/attendance/' + class_id,
        success: function(response) {
            if (response.data) {
                setTimeout(() => {
                    class_attendance(response.data.section, response.data.data);
                }, 1000);
            } else {
                setTimeout(() => {
                    class_attendance(['A','B','C','D','E'], []);
                }, 1000);
            }
        }
    });
});


$('.year-filter').change(function (e) { 
    e.preventDefault();
    let year = $(this).val();

    $.ajax({
        type: "get",
        url: base_path+ '/subscriptions/transaction/' + year,
        success: function(response) {

            if (response.data) {
                setTimeout(() => {
                    subscription_transaction(Object.keys(response.data), Object.values(response.data));
                }, 1000);    
            } else {
                setTimeout(() => {
                    subscription_transaction(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], []);
                }, 1000); 
            }
        }
    });
});

$('.page_layout').change(function (e) { 
    e.preventDefault();
    let layout = $(this).val();
    if (layout == 'A4 Landscape') {
        $('.height').val(210);
        $('.width').val(297);
    } else if(layout == 'A4 Portrait') {
        $('.height').val(297);
        $('.width').val(210);

    } else {
        // $('.height').val('');
        // $('.width').val('');
    }
});

$('.certificate_type').change(function (e) { 
    e.preventDefault();
    var type = $('input[name="type"]:checked').val();
    if (type == 'Student') {
        $('#staff_tags').hide(500);
        $('#student_tags').show(500);
    } else {
        $('#staff_tags').show(500);
        $('#student_tags').hide(500);
    }
});


$('.btn_tag').click(function (e) { 
    e.preventDefault();
    var value = $(this).data('value');
    if (tinymce.activeEditor) { // Check if editor is active
        tinymce.activeEditor.insertContent(value);
    } else {
        alert('TinyMCE editor not active');
    }
});

$('#razorpay_status').on('change', function (e) {
    e.preventDefault();
    if ($(this).val() == 1) {
        $('#stripe_status').val(0);
    }
});
$('#stripe_status').on('change', function (e) {
    e.preventDefault();
    if ($(this).val() == 1) {
        $('#razorpay_status').val(0);
    }
});

$('.fees-over-due-class').change(function (e) { 
    e.preventDefault();
    let class_section_id = $(this).val();
   
    $.ajax({
        type: "get",
        url: base_path+ '/fees/fees-over-due/' + class_section_id,
        success: function(response) {
            let html = '';
            if (response.data.length) {
                $.each(response.data, function (index, value) { 
                    html += '<tr> <td> <img src="'+ value.user.image +'"/></td><td>' + value.full_name + '</td> <td> <input type="checkbox" name="studentids[]" data-id="' + value.user.id + '"> </td> </tr>';
                });
                $('.fees-overdue-btn').removeClass('d-none');
            } else {
                html += '<tr> <td colspan="2" class="text-center"> '+ window.trans['no_data_found'] +' </td> </tr>';
                $('.fees-overdue-btn').addClass('d-none');
            }
            setTimeout(() => {
                $('.fees-over-due-list').html(html);
            }, 500);
        }
    });
});

$('#fees-overdue-form').on('submit', function(e) {
    // Collect checked checkbox IDs
    var checkedIds = [];
    $('input[type="checkbox"]:checked').each(function() {
        checkedIds.push($(this).data('id'));
    });

    // Add the checked IDs to a hidden input field
    $('<input>').attr({
        type: 'hidden',
        name: 'checked_ids',
        value: checkedIds.join(',')
    }).appendTo('#fees-overdue-form');

    e.preventDefault();
    let formElement = $(this);
    let submitButtonElement = formElement.find(':submit');
    let url = formElement.attr('action');
    let data = new FormData(this);


    function successCallback() {
        setTimeout(function () {
            window.location.reload();
        }, 2000);
    }

    formAjaxRequest('POST', url, data, formElement, submitButtonElement, successCallback);
            
});

$(document).ready(function() {
    $('.domain-pattern').on('input', function() {
        // Replace spaces with dashes
        var inputVal = $(this).val().replace(/ /g, '-');
        // Allow only letters, numbers, and dashes
        inputVal = inputVal.replace(/[^a-zA-Z0-9-.]/g, '');
        $(this).val(inputVal);
    });
});


$('#edit_student_class_id').on('change', function () {

    let class_id = $(this).val();
    let url = base_path+ '/students/get-class-section-by-class/' + class_id;
  
    $('#edit_student_class_section_id option').hide();

    function successCallback(response) {
        let html = ''
        html = '<option value="">Select Class Section</option>';
        if (response.data) {
            // html = '<option value="">Select Exam</option>';
            $.each(response.data, function (key, data) {
                html += '<option value=' + data.id + '>' + data.full_name +  '</option>';
            });
        } else {
            html = '<option>No Class Section Found</option>';
        }
        $('#edit_student_class_section_id').html(html);
    }

    ajaxRequest('GET', url, null, null, successCallback, null);
});

$('#filter_class_id').on('change', function () {

    let class_id = $(this).val();
    let url = base_path+ '/students/get-class-section-by-class/' + class_id;
  
    $('#filter_class_section_id option').hide();

    function successCallback(response) {
        let html = ''
        html = '<option value="">Select Class Section</option>';
        if (response.data) {
            // html = '<option value="">Select Exam</option>';
            $.each(response.data, function (key, data) {
                html += '<option value=' + data.id + '>' + data.full_name +  '</option>';
            });
        } else {
            html = '<option>No Class Section Found</option>';
        }
        $('#filter_class_section_id').html(html);
    }

    ajaxRequest('GET', url, null, null, successCallback, null);
});