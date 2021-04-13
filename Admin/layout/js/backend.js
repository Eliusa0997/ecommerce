







$(function () {

    'use strict';

    // Dashbord

        $('.child-link').hover( function () {
            $(this).find('.show-delete').show(400);

        }, function () {
            $(this).find('.show-delete').hide(400);
        
    });

    $('.toggle-info').click(function () {

        $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(50);

        if ($(this).hasClASS('selected')) {

            $(this).html('<i class="fa fa-minus fa-lg"></i>');

        }else{

            $(this).html('<i class="fa fa-plus fa-lg"></i>');
        }
    });

    // Trigger SelectBox It

    $("select").selectBoxIt({
        autoWidth:false
    });

        // hide placeholser on form fuacs

        $('[placeholder]').focus(function (){

            $(this).attr('data-text', $(this).attr('placeholder'));
                $(this).attr('placeholder', '');
    
            }).blur(function (){
                $(this).attr('placeholder', $(this).attr('data-text'));
            });
    
            // Add Astreisk On Field
    
         $('input').each(function () {
    
            if($(this).attr('required') === 'required') {
                $(this).after('<span class="asterisk">*</span>');
            
            }
    
         });
    
         // Convert Password Field To Text ON Hover
    
                var passfield = $('.password');
    
                $('.show-pass').hover(function () {
    
                    passfield.attr('type','text');
    
                },function () {
                    passfield.attr('type','password');
    
                });
                 
                // Confirm Massage On Button
    
                $('.confirm').click(function() {
    
                        return confirm('Are You Sure?');
    
                });
    
                // Categories View Option
    
               // $('.cat h3').click(function () {
    
                  //  $(this).next('.full-view').fedeToggle(200);
    
              //  });
    
              //  $('.Option span').click(function () {
                    $(this).addClass('active').siblings('span').removeClass('active');
    
                 //   if ($(this).data('view') === 'full') {
                        
                        $('.cat .full-view').fedeIn(200);
    
                   // }eles{
    
                        $(' .cat .full-view').fedeOut(200);
                   // }
    
            //    });         


    
});