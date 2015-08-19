jQuery(document).ready(function ($){

  function updateMsgNotifications()
    {
        var url = $('#amsg').data('path');
        $.post(url,
            function(response){
                if(response.responseCode == 200 && response.respuesta == 'OK' && response.cantidadDeConversaciones > 0)
                {
                    $('.msg').html(response.cantidadDeConversaciones).fadeIn('slow', function(){
                        $(this).data('toggle', 'changed');
                    });

                }
            });
    }

    function updateGestNotifications()
    {
        var url = $('#agest').data('path');
        $.post(url,
            function(response){
                if(response.responseCode == 200 && response.respuesta == 'OK' && response.cantidadDeGestiones > 0)
                {
                    $('.gest').html(response.cantidadDeGestiones).fadeIn('slow', function(){
                        $(this).data('toggle', 'changed');
                    });
                }
            });
    }

    function updateChangeNotifications()
    {
        var url = $('#cenot').data('path');

        $.post(url, function(response){
            if(response.responseCode == 200 && response.respuesta == 'OK' && response.numeroNotificaciones > 0)
            {
                $('.noback').fadeIn('slow', function(){
                    $('.gestchange').html(response.numeroNotificaciones).fadeIn('slow')
                });

            }
        });
    }

    function updateContNotifications()
    {
        var url = $('#acont').data('path');
        $.post(url,
            function(response){
                if(response.responseCode == 200 && response.respuesta == 'OK' && response.nuevasContrataciones > 0)
                {
                    $('.cont').html(response.nuevasContrataciones).fadeIn('slow', function(){
                        $(this).data('toggle', 'changed');
                    });

                }
            });
    }

    function updateContClientNotifications()
    {
        var url = $('#clie_cont').data('path');
        $.post(url,
            function(response){
                if(response.responseCode == 200 && response.respuesta == 'OK' && response.nuevasContratacionesCliente > 0)
                {
                    $('.ccont').html(response.nuevasContratacionesCliente).fadeIn('slow', function(){
                        $(this).data('toggle', 'changed');
                    });
                }
            });
    }

    function updateContTotalNotifications()
    {
        var url = $('#tot_cont').data('path');
        $.post(url,
            function(response){
                if(response.responseCode == 200 && response.respuesta == 'OK' && response.totalNuevasContrataciones > 0)
                {
                    $('.gcont').html(response.totalNuevasContrataciones).fadeIn('slow', function(){
                        $(this).data('toggle', 'changed');
                    });

                }
            });
    }

    function updateMsgContactNotifications()
    {
        var url = $('#amsgCont').data('path');
        $.post(url,
            function(response){
                if(response.responseCode == 200 && response.respuesta == 'OK' && response.cantidadDeContactos > 0)
                {
                    $('.msgCont').html(response.cantidadDeContactos).fadeIn('slow', function(){
                        $(this).data('toggle', 'changed');
                    });

                }
            });
    }

    function updateNewUsers()
    {
        var url = $('#newus').data('path');

        $.post(url, function(response){
            if(response.responseCode == 200 && response.respuesta == 'OK' && response.nuevosClientes > 0)
            {
                $('.noback').fadeIn('slow', function(){
                    $('.newUser').html(response.nuevosClientes).fadeIn('slow')
                });

            }
        });
    }

    var notifContCheck = setInterval(function(){ updateContNotifications()}, 15000);
    var notifMsgCheck = setInterval(function() { updateMsgNotifications() }, 15000);
    var notifMsgContactCheck = setInterval(function() { updateMsgContactNotifications() }, 15000);
    var notifGestCheck = setInterval(function() { updateGestNotifications() }, 15000);
    var notifChangeCheck = setInterval(function(){ updateChangeNotifications()}, 15000);
    var notifTotalNuevasContra = setInterval(function(){updateContTotalNotifications()}, 15000);
    var notifClientNewContratacion = setInterval(function(){updateContClientNotifications()}, 15000);
    var notifupdateNewUsers = setInterval(function(){updateNewUsers()}, 15000);
});


