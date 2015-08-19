jQuery(document).ready(function ($){
    function updateNotifications()
    {
        var url = $('#amsg').data('path');
        $.post(url,
            function(response){
                if(response.responseCode == 200 && response.respuesta == 'OK' && response.cantidadDeConversaciones > 0)
                {
                    $('#amsg').css('display', 'inline-block').fadeIn('slow', function(){
                        $('.msg').html(response.cantidadDeConversaciones).fadeIn('slow', function(){
                            $(this).data('toggle', 'changed');
                        });
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

    function updateContratacionChangeNotifications()
    {
        var url = $('#acontmsg').data('path');

        $.post(url, function(response){
            if(response.responseCode == 200 && response.respuesta == 'OK' && response.ContratacionesARevisar > 0)
            {
                $('.noback_cont').fadeIn('slow', function(){
                    $('.contchange').html(response.ContratacionesARevisar).fadeIn('slow')
                });
            }
        });
    }

    var notifCheck = setInterval(function() { updateNotifications() }, 15000);
    var estadoChangeCheck = setInterval(function() { updateChangeNotifications() }, 15000);
    var contratacionPendiente = setInterval(function() { updateContratacionChangeNotifications() }, 15000);
});


