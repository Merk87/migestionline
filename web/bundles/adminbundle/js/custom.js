/*
* JS FILE FOR ADMIN PANEL
* AUTHOR: MERKURY
* DATE: JUNE 2013
* */

$('.dropdown-toggle').dropdown();


jQuery.noConflict()(function($)
{
    var userToDel = "";
    $('.delUser').click(function(){
        $('#delModal').modal('show');
        $('.okDel').attr('href', '../users/block/'+$(this).attr('data-user'));
    });

    $('.delUserFilter').click(function(){
        $('#delModal').modal('show');
        $('.okDelFilter').attr('href', '../../../block/'+$(this).attr('data-user'));
    });

    $('.desEmpresa').click(function(){
        $('#empModal').modal('show');
        $('.okDes').attr('href', '../empresa/disable/'+$(this).attr('data-emp'));
    });

    $('.newMsg').click(function(){
        $('#selectEmpModal').modal('show');

    });

    $('.newGest').click(function(){
        $('#selectClientModal').modal('show');
    });

    $('.newDest').click(function(){
        $('#chatIdCont').val($(this).data('idchat'));
        $('#changeDest').modal('show');
    });

    $('.blockRepo').click(function(){
        $('#repoModal').modal('show');
        $('.okRepoBlock').attr('href', '../repositorio/disable/'+$(this).attr('data-repo'));
    });

    $('.blockRepoFilter').click(function(){
        $('#repoModal').modal('show');
        $('.okRepoBlockFilter').attr('href', '../../../../repositorio/disable/'+$(this).attr('data-repo'));
    });

    $('.blockServ').click(function(){
        $('#servModal').modal('show');
        $('.okServBlock').attr('href', '../servicio/disable/'+$(this).attr('data-serv'));
    });

    $('.blockServFilter').click(function(){
        $('#servModal').modal('show');

        $('.okServBlockFilter').attr('href', '../../../../servicio/disable/'+$(this).attr('data-serv'));
    });

    $('.blockCat').click(function(){
        $('#catModal').modal('show');
        $('.okCatBlock').attr('href', '../categoria/disable/'+$(this).attr('data-cat'));
    });

    $('.blockCatFilter').click(function(){
        $('#catModal').modal('show');
        $('.okCatBlockFilter').attr('href', '../../../../categoria/disable/'+$(this).attr('data-cat'));
    });

    $('.delContacto').click(function(){
        $('#delContacto').modal('show');
        $('.okDel').attr('href', $(this).data('conv'));
    });

    $('.reContacto').click(function(){
        $('#reContacto').modal('show');
        $('.okRe').attr('href', $(this).data('conv'));
    });

    $('.showcc').click(function(){
        var cc = $(this).data('info');

        var first = cc.slice(0, 4);
        var second = cc.slice(4, 8);
        var third = cc.slice(8, 10);
        var fourth = cc.slice(10);

        var ccf = first + ' - ' + second + ' - ' + third + ' - ' + fourth;

        $('.cccn').html(ccf);
        $('#ccModal').modal('show');
    });

    $('.closeMod').click(function(){
       $(this).parent().parent().modal('hide');
    });


//CONTROLADOR PARA LA SELECCION DE EMPRESA PRO EL USUARIO MULTEMPRESA - USER
    $('#sel_empresa').change(function(){
        var idEmp = $(this).val();

        var path = window.location.pathname;
        var check_path = path.split('/admin/');

        if(idEmp != 'none' && check_path[1].indexOf('filter') == -1)
        {
            if(check_path[1].indexOf('view') != -1)
            {
                window.location = '../../../filter/'+idEmp+'/list/1';
            }else
            {
                window.location = '../users/filter/'+idEmp+'/list/1';
            }
        }else if(idEmp != 'none' && check_path[1].indexOf('filter') != -1)
        {

            window.location = '../../../filter/'+idEmp+'/list/1';
        }

    });

    //CONTROLADOR PARA LA SELECCION DE USURIO ROL
    $('#sel_role').change(function(){
        var idRol = $(this).val();
        var empId = $(this).attr('data-emp');

        var path = window.location.pathname;
        var check_path = path.split('/admin/');


        if(idRol != 'none' && empId != 'none' && check_path[1].indexOf('filter') == -1)
        {
            window.location = '../../'+idRol+'/'+empId+'/1';
        }else if(empId != 'none' && check_path[1].indexOf('filter') != -1)
        {
            window.location = '../../../view/'+idRol+'/'+empId+'/1';
        }

    });

    //CONTROLADOR PARA LA SELECCION DE EMPRESA POR EL USUARIO MULTEMPRESA - REPO
    $('#sel_empresaRepo').change(function(){
        var idEmp = $(this).val();

        var path = window.location.pathname;
        var check_path = path.split('/admin/');

        if(idEmp != 'none' && check_path[1].indexOf('filter') == -1)
        {
            window.location = '../repositorios/filter/'+idEmp+'/list/1';
        }else if(idEmp != 'none' && check_path[1].indexOf('filter') != -1)
        {
            window.location = '../../../filter/'+idEmp+'/list/1';
        }

    });

    //CONTROLADOR PARA LA SELECCION DE EMPRESA POR EL USUARIO MULTEMPRESA - USER
    $('#sel_empresaServ').change(function(){
        var idEmp = $(this).val();

        var path = window.location.pathname;
        var check_path = path.split('/admin/');

        if(idEmp != 'none' && check_path[1].indexOf('filter') == -1)
        {
            window.location = '../servicios/filter/'+idEmp+'/list/1';
        }else if(idEmp != 'none' && check_path[1].indexOf('filter') != -1)
        {

            window.location = '../../../filter/'+idEmp+'/list/1';
        }

    });

    //CONTROLADOR PARA LA SELECCION DE EMPRESA POR EL USUARIO MULTEMPRESA - USER
    $('#sel_empresaCat').change(function(){
        var idEmp = $(this).val();

        var path = window.location.pathname;
        var check_path = path.split('/admin/');

        if(idEmp != 'none' && check_path[1].indexOf('filter') == -1)
        {
            window.location = '../categorias/filter/'+idEmp+'/list/1';
        }else if(idEmp != 'none' && check_path[1].indexOf('filter') != -1)
        {

            window.location = '../../../filter/'+idEmp+'/list/1';
        }

    });

    //CONTROLADOR PARA LA SELECCION DE EMPRESA PORA FILTRO GESTIONES
    $('#sel_empresaGestion').change(function(){
        var idEmp = $(this).val();

        var path = window.location.pathname;
        var check_path = path.split('/admin/');

        if(idEmp != 'none' && check_path[1].indexOf('filter') == -1)
        {

            window.location = '../filter/'+idEmp+'/list/1';
        }else if(idEmp != 'none' && check_path[1].indexOf('filter') != -1 && check_path[1].indexOf('by') == -1 )
        {

            window.location = '../../../filter/'+idEmp+'/list/1';
        }else if(idEmp != 'none' && check_path[1].indexOf('by') != -1)
        {
            check_path_2 = path.split('/by/');

            check_path_3 = check_path_2[0].split('/filter/');

           window.location = check_path_3[0] + '/filter/'+ idEmp + '/list/1';
        }

    });


    $('#sel_estadoGest').change(function(){
        var idEst = $(this).val();

        //gestiones/filter/{empId}/list/{page}/by/{estId}/{ord}

        var path = window.location.pathname;
        var check_path = path.split('/admin/');

        if(idEst != 'none' && check_path[1].search('filter') != -1 && check_path[1].search('by') == -1 )
        {
            window.location = '../list/1/by/'+idEst+'/0';
        }
        if(idEst != 'none' && check_path[1].search('filter') != -1 && check_path[1].search('by') != -1)
        {
            check_path_2 = path.split('/by/');
            window.location = check_path_2[0] + '/by/'+idEst+'/0'
        }

    });

    $('#sel_estadoGestion').change(function(){
        var idEst = $(this).val();
        var idGest = $('#estForm').attr('data-foo');
        var est = $('#estForm').attr('data-check');

       if(est != idEst)
       {
           if(idEst != 'none' && idGest > 0)
           {
               window.location = '../gestion/update/'+idGest+'/to/'+idEst;
           }
       }

    });

    /***NUEVO MENSAJE ADMIN***/
    $('.newMsgAdmin').click(function(){
        var empId = $('.empName:selected').val();

        if(empId != 'none' && empId > 0)
        {
            window.location = '../messages/new/admin/conv/'+empId;
        }
    });

    /**** NUEVO DESTINATARIO ***/
    $('.okNewDest').click(function(){
        var userId = $('.userId:selected').val();
        var chatId = $('#chatIdCont').val();

        if(userId != 'none' && userId > 0 && chatId > 0)
        {
            window.location = '../messages/change/reciever/'+chatId+'/to/'+ userId;
        }

    });

    /****BORRAR NOTIFICACIONES ADMIN****/

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

    /*****NUEVA GESTIÓN PANEL ADMINISTRACIÓN*********/
    $('.newGestAdmin').click(function(){
        var clientId = $('.cliName:selected').val();
        var path = $('.cliName:selected').data('url');

        if(clientId != 'none' && clientId > 0)
        {
            window.location = path;
        }
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

    $('.toolTip').popover(function(){
        event.preventDefault();
    });

    $('.refreshForm').click(function(){
        var path = $(this).data('path');
        window.location = path;
    });


    /**SELECCION DE SERVICIOS PARA AMPLIACIÓN CONTRATACIÓN**/

    $('.selectServBut').click(function(){
        var selectID = $(this).data('info');
        var toHighligth = $(this).parent().parent();

        $('.checkservice input:checkbox').each(function(){
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

    $('.checkservice input:checkbox').each(function(){
       if($(this).is(':checked'))
       {
            var value = $(this).val();
            $('.thumbnail').each(function(){
                if($(this).data('val') == value)
                {
                    $(this).addClass('serv_selected');
                }
            })
       }
    });

});
