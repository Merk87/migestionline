jQuery.noConflict()(function($)
{

    $('#sel_empresa').change(function(){
        var path = $(this).val();

        if(path != 'none' )
        {
            window.location = path;
        }

    });

    $('#sel_role').change(function(){
        var rpath = $(this).val();

        if(rpath != 'none' )
        {
            window.location = rpath;
        }

    });


    $('#sel_empresaRepo').change(function(){
        var rpath = $(this).val();

        if(rpath != 'none' )
        {
            window.location = rpath;
        }

    });

    $('#sel_empresaServ').change(function(){
        var rpath = $(this).val();

        if(rpath != 'none' )
        {
            window.location = rpath;
        }

    });

    $('#sel_empresaCat').change(function(){
        var rpath = $(this).val();

        if(rpath != 'none' )
        {
            window.location = rpath;
        }

    });

    $('#sel_empresaGestion').change(function(){
        var rpath = $(this).val();

        if(rpath != 'none' )
        {
            window.location = rpath;
        }

    });

    $('#sel_estadoGest').change(function(){
        var rpath = $(this).val();

        if(rpath != 'none' )
        {
            window.location = rpath;
        }

    });

    $('.newDest').click(function(){
        $('#chatIdCont').val($(this).data('idchat'));
        $('#changeDest').modal('show');
    });

    $('.closeMod').click(function(){
        $(this).parent().parent().modal('hide');
    });

    /*****MOSTRAR FORM DE BUSQUEDA******/

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

    $('.disButton').click(function(){
        event.preventDefault();
        alert('No activo en la versión de demostración.')
    });

    $('.toolTip').popover(function(){
        event.preventDefault();
    });

    $('#show_pp_b').click(function(){
        var navInfo = window.navigator.appName.toLowerCase();

        if(navInfo == 'microsoft internet explorer')
        {
            var url = $(this).data('path');
            window.location.href = url;
        }else
        {
            $('#show_pp').modal('show');
        }
    });

    $('#show_cc_b').click(function(){
        var navInfo = window.navigator.appName.toLowerCase();

        if(navInfo == 'microsoft internet explorer')
        {
            var url = $(this).data('path');
            window.location.href = url;
        }else
        {
            $('#show_cc').modal('show');
        }
    });

    $('#show_av_b').click(function(){
        var navInfo = window.navigator.appName.toLowerCase();

        if(navInfo == 'microsoft internet explorer')
        {
            var url = $(this).data('path');
            window.location.href = url;
        }else
        {
            $('#show_av').modal('show')
        }
    });

    $('.clientlogin').hover(function(){
        $('.getInClMsg').fadeIn('slow');
    }, function(){
        $('.getInClMsg').fadeOut('slow')
    });

    $('.adminlogin').hover(function(){
        $('.getInAdMsg').fadeIn('slow');
    }, function(){
        $('.getInAdMsg').fadeOut('slow')
    });

    $(document).ready(function(){
        if($.cookie('accepted_cookies') == null)
        {
            $('.topamsg').fadeIn('slow');
        }

        $('#closemsg').click(function(){
            $.cookie("accepted_cookies", "accepted",{ expires: 365, path: '/' } );
        });
    });

});


