var FormAction = {
    init: function () {
        this.multiselect();
        this.addImage();
        this.addField();
        this.mask();
        this.tooltip();
        this.selectContentControl();
        this.selectList();
    },
    tooltip: function () {
        $('.init_tooltip').tooltip();
    },
    mask: function () {
        $('input[id$=phone]').mask("+7-999-999-99-99", {placeholder: "_"});
        $('input[id$=phone]').bind('blur', function () {
            if ($(this).val() == '+7-')
                $(this).val(null);
        })
    },
    selectList: function () {
        $('select[id$=catalog_parent]').chosen({
            display_selected_options: true,
            search_contains: true,
            disable_search_threshold: 5,
            no_results_text: "Не найдено ни одного варианта",
            width: '100%',
            placeholder_text_single: 'Выберите родительский элемент'
        });
        $('select[id$=catalog_content]').chosen({
            display_selected_options: true,
            allow_single_deselect: true,
            search_contains: true,
            disable_search_threshold: 5,
            no_results_text: "Не найдено ни одного варианта",
            width: '100%',
            placeholder_text_single: 'Выберите контент'
        });
        $('select[id$=category_parent]').chosen({
            display_selected_options: true,
            allow_single_deselect: true,
            search_contains: true,
            disable_search_threshold: 5,
            no_results_text: "Не найдено ни одного варианта",
            width: '100%',
            placeholder_text_single: 'Выберите категорию'
        });
        $('select[id$=catalog_category]').chosen({
            display_selected_options: true,
            allow_single_deselect: true,
            search_contains: true,
            disable_search_threshold: 5,
            no_results_text: "Не найдено ни одного варианта",
            width: '100%',
            placeholder_text_single: 'Выберите категорию'
        });
    },
    multiselect: function () {
        $(".multiselect").width($('.tab-content').width() - 90);
        $.localise('ui-multiselect', {language: 'ru', path: '/js/'});
        $(".multiselect").multiselect({
            sortable: false
        });
    },
    addImage: function () {
        var $collectionHolder;

        var $addTagLink = $('#add_image');

        $collectionHolder = $('div[id$=imagecategory_images], div[id$=videocategory_videos]');

        $collectionHolder.data('index', $collectionHolder.find('input[id$=image]').length);

        $addTagLink.on('click', function (e) {
            e.preventDefault();
            FormAction.addImageForm($collectionHolder);
        });

    },
    addImageForm: function ($collectionHolder) {
        var prototype = $collectionHolder.data('prototype');

        var index = $collectionHolder.data('index');

        var newForm = prototype.replace(/__name__/g, index);

        $collectionHolder.data('index', index + 1);

        $('#box_image_form').append(newForm).find('input[id$=' + index + '_is_active]').attr('checked', 'checked');
    },
    deleteItem: function (id) {
        if (confirm('Вы уверены что хотите удалить изображение?')) {
            $('#' + id).remove();
            if ($('#' + id + '_thumb').length > 0)
                $('#' + id + '_thumb').remove();
        }
    },
    addField: function () {
        var $collectionHolder;

        var $addTagLink = $('#add_field');

        $collectionHolder = $('div[id$=project_fields]');

        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        $addTagLink.on('click', function (e) {
            e.preventDefault();
            FormAction.addFieldForm($collectionHolder);
        });

    },
    addFieldForm: function ($collectionHolder) {
        var prototype = $collectionHolder.data('prototype');

        var index = $collectionHolder.data('index');

        var newForm = prototype.replace(/__name__/g, index);

        $collectionHolder.data('index', index + 1);

        $('#box_field_form').append(newForm).find('input[id$=' + index + '_is_active]').attr('checked', 'checked');
    },
    deleteField: function (id) {
        if (confirm('Вы уверены что хотите удалить поле?')) {
            $('tr[id^=' + id + ']').remove();
        }
    },
    useVisualEditor: function (check, field) {
        $('input[id$=' + check + ']').change(function () {
            var id = $('textarea[id$=' + field + ']').attr('id');
            var instance = CKEDITOR.instances[id];
            if ($(this).is(':checked')) {
                if (instance) {
                    instance.destroy(true);
                }
                CKEDITOR.replace(id);
            }
            else {
                if (instance) {
                    instance.destroy(true);
                }
            }
        });
    },
    editItem: function (id) {
        $('#' + id + '_thumb').hide();
        $('#' + id).show();
    },
    editField: function (id) {
        $('#' + id + '_item').hide();
        $('#' + id + '_form').show();
    },
    dublicatTitleForMeta: function () {
        $('input[id$=catalog_title]').bind('textchange', function () {
            $('input[id$=catalog_meta_meta_title]').val($(this).val());
        });
        $('input[id$=content_title]').bind('textchange', function () {
            $('input[id$=content_meta_meta_title]').val($(this).val());
        });
    },
    dublicateTitleForImage: function () {
        $('input[id$=content_title]').bind('textchange', function () {
            $('input[id$=content_title_image], input[id$=content_alt_image], input[id$=content_title_big_image], input[id$=content_alt_big_image]').val($(this).val());
        });
    },
    dublicateTitleForImageGallery: function (id) {
        $('#' + id).bind('textchange', function () {
            var i = id.split('_')[3];
            $('input[id$=imagecategory_images_' + i + '_alt]').val($(this).val());
        });
    },
    refreshContentChoice: function (url) {
        $.ajax({
            url: url,
            dataType: 'html',
            timeout: 30000,
            success: function (msg) {
                $('select[id$=catalog_content]').html(msg);
                $('select[id$=catalog_content]').trigger("chosen:updated");
            }
        });
    },
    selectContentControl: function () {
        $('select[id$=catalog_content]').change(function () {
            var id = $(this).val();
            if (id) {
                $('#main_button_refresh').attr("onclick", "FormAction.refreshContentChoice('/admin/catalog/refresh_content/" + id + "')");
                $('#link_content_edit a').show().attr("href", "/admin/content/" + id + "/edit");
            }
            else {
                $('#main_button_refresh').attr("onclick", "FormAction.refreshContentChoice('/admin/catalog/refresh_content/')");
                $('#link_content_edit').hide();
            }
        });
    }
};

$().ready(function () {
    FormAction.init();
})


