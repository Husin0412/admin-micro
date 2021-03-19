$(document).ready(function() {
    /*animate loading*/ 
	window.addEventListener('load', function () {
		$("#loading").delay(800).fadeOut("slow");
	}); 
   	/*end animate loading*/ 
    // toast
    $('.toast').toast('show')

    // setTimeout(() => {
    //     $('.toast').css('opacity',0)
    // }, 6000);
 
    // $('.close').click(function() {
    //     $('.toast').css('opacity',0)
    // })
    // end
    // // modal
    $('.signout_navbar').click(function() {
        main = $('.signout_navbar'); url = main.attr('url'); title = main.attr('title'); message = main.attr('message'); type = main.attr('type')
        $('#exampleModalLabel').text(title); $('.modal-body').text(message)

        btn = $('.btn-confirm-modal');
        if(type === "danger") {
            btn.addClass('btn-danger');
            $('.aggre').remove()
            $('.spinner-container').remove()
            btn.append('<span class="aggre">Aggre</span>')
            $('.btn-cancel-modal').text('Close')
        }

        btn.click(function() {
            $('.aggre').remove()
            $('.spinner-container').remove()
            $('.btn-cancel-modal').text('Cancel')
            btn.append('<div class="spinner-container"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <span class="text-status ml-2"></span> </div>')
            var timeleft = 5;
            var time = 0;
            var downloadTimer = setInterval(function(){
            //   document.getElementById("progressBar").value =
                var waktu = timeleft - time ;
                time += 1;
                $('.text-status').text(waktu)
                if(waktu <= 0){
                    clearInterval(downloadTimer);
                    $('.spinner-border').css({'animation': 'spinner-border 1 linear infinite'});
                }
                if(waktu <= 1){
                    if(url === 'logout') {
                        window.location.href="/"+url+""
                    }
                }
            }, 1000);
            $('.btn-cancel-modal').click(function() {
                clearInterval(downloadTimer);
            })
        })
        
    })
    // // end

})

// function checkInputValue(e) {
//     id = e.id
//     $(".check_usergroup").prop('checked', false)
//     $("#"+id).prop('checked', true)
// }

$('.child-check').click(function(e) { id = e.target.id, $('.child-check').prop('checked', false), $('#'+id).prop('checked', true) })

function radioNetral() {$(".child-check").prop('checked', false);}

/**/
$('.parent-option').on('change', function(e) { 
    var selectedId = $(this).children("option:selected").attr('id');
    var selectedTex = $(this).children("option:selected").text();

    $('.parent-option').removeClass("text-primary")
    $('.select-chapter').prop('selected', true)
    $('#'+selectedId).prop('selected', true)
    $(this).addClass("text-primary")
    if(selectedTex === "select chapter")
    {
        $('.parent-option').removeClass("text-primary")
    }
}) 

/**/
$('.div-video').hover(function(){
    $(this).children('.div-video-img').attr("src", "assets/images/svg/icon.play.hover.svg")
    $(this).mouseleave(function(){
        $(this).children('.div-video-img').attr("src", "assets/images/svg/icon.play.svg")
    })
    $(this).click(function() {
        $('.overlay').remove()
        var video = $(this).children('span').data('video')
        var wrapper = '<div class="overlay"> <div class="video-convert"> <iframe id="ytplayer" type="text/html" width="90%" height="100%" src="http://www.youtube.com/embed/'+video+'" frameborder="0" allowfullscreen></iframe> </div> </div>'
        $('body').append(wrapper)
        $('.overlay').fadeIn('slow')
        $('.overlay').css('display','flex')
    })
    $('.overlay').click(function() {
        $(this).fadeOut("slow");
    })
}) 

// action
$('.actions > .btn-add').click(function() {
    var $this=$(this), link=$this.data('link')
    window.location.href = link;
})
$('.actions > .btn-save').click(function() {
    var $this=$(this),form=$('#form-table');
    form.submit();
})
$('.actions > .btn-edit').click(function() {
    var $this=$(this),form=$('#form-table');
    if($('.child-check:checked').length == 0){
		var notice = new PNotify({
			title: 'Caution !',
			text: 'Please select at least one data for process..',
			type: 'warning',
			addclass: 'click-2-close',
			hide: false,
			buttons: {
				closer: false,
				sticker: false
			}
		});

		notice.get().click(function() {
			notice.remove();
		});
        return
    } 
    form.attr('action', $this.data('link')).submit();
})
$('.actions > .btn-cancel').click(function() {
    var $this=$(this),link=$this.data('link');
    window.location.href = link;
})
$('.actions > .btn-delete').click(function() {
    var $this=$(this),link=$this.data('link'),form=$('#form-table');

    if($('.child-check:checked').length == 0){
		var notice = new PNotify({
			title: 'Caution !',
			text: 'Please select at least one data for process..',
			type: 'warning',
			addclass: 'click-2-close',
			hide: false,
			buttons: {
				closer: false,
				sticker: false
			}
		});

		notice.get().click(function() {
			notice.remove();
		});
        return
    }
    // 
    var notice = new PNotify({
        title: 'Are you sure?',
        text: 'want to delete this file.',
        icon: 'mdi mdi-18px mdi-comment-alert-outline',
        styling: 'bootstrap3',
        width: '330',
        hide: false,
        confirm: {
            confirm: true
        },
        buttons: {
            closer: false,
            sticker: false
        },
        history: {
            history: false
        },
        addclass: 'stack-modal',
        stack: {'dir1': 'down', 'dir2': 'right', 'modal': true}
    })

    $('.alert-warning').addClass('alert-primary-custome')
    $('.btn-default').addClass('text-white-custome')

    notice.get().on('pnotify.confirm', function(){
        form.attr('action', $this.data('link')).submit();
    })
    notice.get().on('pnotify.cancel', function(){
        notice.remove();
    })
    
})

$('.actions > .btn-details').click(function() {
    var $this=$(this),link=$this.data('link'),form=$('#form-table');

    if($('.child-check:checked').length == 0)
    {
		var notice = new PNotify({
			title: 'Caution !',
			text: 'Please select at least one data for process..',
			type: 'warning',
			addclass: 'click-2-close',
			hide: false,
			buttons: {
				closer: false,
				sticker: false
			}
		});

		notice.get().click(function() {
			notice.remove();
		});
        return
    }
    form.attr('action', $this.data('link')).submit();
})

/*view or not password*/ 
$('.eye-mdi').click(function(e) {
    e.preventDefault();
    var name = $(this).data('name'); child = $(this).find('i');
    var input = $("input[name='"+name+"']")
    if(child.attr('class') === 'mdi mdi-eye-off text-secondary'){
        child.removeClass('mdi mdi-eye-off text-secondary').addClass('mdi mdi-eye text-dark')
        input.attr('type', 'text')
    } else {
        child.removeClass('mdi mdi-eye text-dark').addClass('mdi mdi-eye-off text-secondary')
        input.attr('type', 'password')
    }
})

$('.input-group-append').click(function(e) {
    e.preventDefault()
    var name = $(this).data('name'); child = $(this).find('i');
    var input = $("input[name='"+name+"']")
    if(child.attr('class') === 'mdi mdi-18px mdi-email-outline') { return }
    if(child.attr('class') === 'mdi mdi-18px mdi-eye-off text-secondary'){
        child.removeClass('mdi mdi-18px mdi-eye-off text-secondary').addClass('mdi mdi-18px mdi-eye text-dark')
        input.attr('placeholder','password')
        input.attr('type', 'text')
    } else {
        child.removeClass('mdi mdi-18px mdi-eye text-dark').addClass('mdi mdi-18px mdi-eye-off text-secondary')
        input.attr('placeholder','********')
        input.attr('type', 'password')
    }
})

/* pormat number */
$('.price-input-Rp').priceFormat({
    prefix: 'Rp ',
    centsSeparator: '.',
    thousandsSeparator: ',',
    centsLimit:2
}); 