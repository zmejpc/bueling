$(function() {
    if($('.locale-currency-select').length) {
        $('.locale-currency-select').select2().on('change', function(e) {
            $.post('/admin/currency/ajax/set-locale-currency', {
               locale_currency_id : $(e.target).attr('data-locale-id'),
               currency_id: $(e.target).val()
            })
        });
    }

    if($('.kt-menu__item--open.kt-menu__item--here').length)
        setTimeout(function() {
            $('.kt-menu__item--open.kt-menu__item--here').last()[0].scrollIntoView({block: "center"});
        });

    $("[data-counter='counterup']").counterUp({
        delay: 10,
        time: 1000
    });

    $('body').on('change', '.ajax-checkbox', function() {
        var self = this;
        if($(self).hasClass('reload-datatable')) {
            KTApp.blockPage()
        }

        $.post('/admin/ajax/checkbox', {
            id: $(self).attr('data-id'),
            entity: $(self).attr('data-entity'),
            field: $(self).attr('data-field'),
            checked: self.checked
        }, function() {
            if($(self).hasClass('reload-datatable')) {
                $('.kt-datatable').data().KTDatatable.load();
                setTimeout(KTApp.unblockPage, 1000)
            }
        })
    })

    $('body').on('keyup', '.list-input', function() {
        $(this).siblings('.list-input-submit').addClass('visible')
    })

    $('body').on('click', '.list-sort-input-submit', function() {
        var self = this, $el = $(self).siblings('.list-sort-input');
        $.post('/admin/ajax/sort-position', {
            id: $el.attr('data-id'),
            entity: $el.attr('data-entity'),
            position: $el.val()
        }, function() { $(self).removeClass('visible') })
    })
   
   $('body').on('click', '.copy-command-btn', function() {
        var input = document.getElementById($(this).attr('data-target'));
        
        input.select();
        document.execCommand('copy');
   });

   $('body').on('mousedown', '.btn-outline-danger', function() {
        if($(this).closest('.delete-attr-block').length) {
            if(!confirm('Удалить?'))
                return;
            
            if(attr_id = $(this).closest('.delete-attr-block').find('select').val()) {
                if($(this).hasClass('clear-group-attr-value')) {
                    $(this).closest('.row').find('input').val('');
                    $.get('/admin/product/clear-attr-value/'+attr_id)
                } else {
                    $.get('/admin/product/delete-attr/'+attr_id)
                }
            }
        }
   });

   $('body').on('click', '.list-input-default-submit', function() {
        var self = this, $input = $(self).prev();
        $(self).removeClass('visible');
        $.post($input.attr('data-action'), {id: $input.attr('data-id'), [$input.attr('name')]: $input.val()}, function(response) {
            if(response.status)
                toastr.success(response.text);
            else
                toastr.error(response.text);
        }, 'json');
   });

   initNicescroll();
});

function initNicescroll() {
    $('.nicescroll').customScrollbar({
        skin: 'modern-skin',
        hScroll: false,
        updateOnWindowResize: true
    });
    // $('.nicescroll').niceScroll({
    //     cursorcolor: '#1e1e2d'
    // });
}