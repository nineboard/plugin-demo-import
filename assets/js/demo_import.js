$(function () {
    $('.import_btn').click(function (e) {
        var form;
        var themeIds;

        form = $(this).closest('form');

        themeIds = $('.theme_id').serializeArray();

        console.log(themeIds);

        if (themeIds.length == 0) {
            alert('데이터를 가져올 테마를 선택하세요.');
            return;
        }

        XE.ajax({
            url : form.attr('action'),
            type: 'post',
            dataType: 'json',
            data: {'themeIds': $('.theme_id').serializeArray()},
            success: function (data) {
                initResultRaw();

                if (data.type == 'success') {
                    $('.success_row').css('display', 'block');
                } else {
                    $('.fail_row').css('display', 'block');
                }
            },
        })
    });

    $('.import_checkbox').click(function (e) {
        $(this).siblings('input[type=hidden]').prop('disabled', !$(this).prop('checked'));
    });
});

function initResultRaw() {
    $('.success_row').css('display', 'none');
    $('.fail_row').css('display', 'none');
}
