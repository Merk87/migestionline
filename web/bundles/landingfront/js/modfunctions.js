jQuery.noConflict()(function($){
    $('#mod_cc').click(function(){
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

    $('#mod_cc2').click(function(){
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

    $('#mod_pp').click(function(){
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

    $('#mod_pp2').click(function(){
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

    $('#mod_av').click(function(){
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
    
    $('.closeMod').click(function(){
        $(this).parent().parent().modal('hide');
    });


    if($.cookie('accepted_cookies') == null)
    {
        $('.topamsg').fadeIn('slow');
    }

    $('#closemsg').click(function(){
        $.cookie("accepted_cookies", "accepted",{ expires: 365, path: '/' } );
    })

});