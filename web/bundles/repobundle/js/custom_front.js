
$('.dropdown-toggle').dropdown();

/****BORRAR NOTIFICACIONES CLIENTE****/

$('.delNotif').click(function(){
    var table = $(this).closest('table').children('tbody').children('tr').length;
    var url = $(this).data('path');

    $(this).parent().parent().fadeOut('slow', function(){
        $(this).remove();
        if(table-1 == 0)
        {
            $('#notifCont').append('<tr class="error"><td style="text-align:center"><h4>No existen notificaciones</h4></td></tr>');
        }
        $.post(url);
    });
});

$('.showS').click(function(){
    $(this).fadeOut('slow', function(){
        $('.formContainer').show('slow');
    });

});

$('.hideS').click(function(){
    event.preventDefault();
    $('.formContainer').hide('slow', function(){
        $('.showS').show('slow');
    });
});

$(document).ready(function(){

    $('.servBut').click(function(){
        event.preventDefault();
        var servSelected = $(this).data('id');
        var buttonClicked = $(this);


        $('.checklist input:checkbox').each(function(){
            if($(this).val() == servSelected)
            {
                if(!$(this).is(':checked'))
                {
                    $(this).prop('checked', true);
                    buttonClicked.addClass('selected');
                }else
                {
                    $(this).prop('checked', false);
                    buttonClicked.removeClass('selected');
                }
            }
        });

        var activatedButton = $('.checklist input:checked').length;

        if(activatedButton > 0)
        {
            $('.butBack').fadeOut('slow');
            $('.field_custom_s').fadeIn('slow', function(){
                $('#doContra').fadeIn('slow');
                $('.butBack2').fadeIn('slow');
            });
        }else
        {
            $('#doContra').fadeOut('slow');
            $('.butBack2').fadeOut('slow');
            $('.field_custom_s').fadeOut('slow', function(){
                $('.butBack').fadeIn('slow');
            });

        }

    });

    /**SELECCION DE SERVICIOS PARA AMPLIACIÓN CONTRATACIÓN**/

    $('.selectServBut').click(function(){
        var selectID = $(this).data('info');
        var toHighligth = $(this).parent().parent();

        $('.offeredservices input:checkbox').each(function(){
            if($(this).val() == selectID)
            {
                if(!$(this).is(':checked'))
                {
                    toHighligth.addClass('serv_selected');
                    $(this).prop('checked', true);

                }else
                {
                    toHighligth.removeClass('serv_selected');
                    $(this).prop('checked', false);

                }
            }
        });
    });

});