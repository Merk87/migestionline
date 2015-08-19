jQuery.noConflict()(function($){
    $(document).ready(function(){
        var sourceSwap = function () {
            var $this = $(this).find('img');
            var newSource = $this.data('swap');
            $this.data('swap', $this.attr('src'));
            $this.attr('src', newSource);
        }

        $(function () {
            $('.window').hover(sourceSwap, sourceSwap);
        });
    });

    $('.window').click(function(){
        var link = ($(this).data('path'));
        if(typeof link != 'undefined')
        {
            window.location.href = link;
        }
    })

});