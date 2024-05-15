
const burger = document.querySelector(".burger");
const menu = document.querySelector(".menu");
const mediaQuery = window.matchMedia("(min-width: 990px)");

/*nav fixe au scroll + changement de background color*/
/*media queries*/


const navScroll = () => {
	let linkItems = document.querySelectorAll(".link-item");
	const navbar = document.querySelector('#navbar');
	window.onscroll = () => {
	  if (document.documentElement.scrollTop >= 100 && mediaQuery.matches) {
		navbar.style.backgroundColor = '#ffff';
		navbar.style.padding = '0';
		for (let linkItem of linkItems) {
			linkItem.style.color = "#121b22";
			}
	  } else {
		navbar.style.backgroundColor = 'transparent';
		navbar.style.padding = '1rem';
		for (let linkItem of linkItems) {
			linkItem.style.color = "#ffff";
			}
	  }
	}
  }
  navScroll();

/*const animBurger = () => {
	burger.addEventListener("click", () => {
		/* toggle Nav*/
		/*menu.classList.toggle("nav-active");
		/* animation burger*/
		/*burger.classList.toggle("toggle");
		
	});
};*/
//animBurger();

/************ contact mail enable button ************/
$(document).on('click', '#user_agreeTerms', function(){
	if($(this).prop('checked')) {
		$('#sendContactMail').attr('disabled', false);
	} else {
		$('#sendContactMail').attr('disabled', true);
	}
});

/************ contact mail errors ************/
$('.required').each(function(){
	var cible = $(this).next();
	cible.on('focusout', function(){
		if($(this).val() == '') {
			$(this).addClass('is-invalid');
		} else {
			$(this).removeClass('is-invalid');
		}
	});
});

/************ send contact mail ************/
$(document).on('click', '#sendContactMail', function(e){
    e.preventDefault();
	if($('#potdemiel').val() != '') return false;
	var errors1 = 0;
	var errors2 = 0;
    var name = $('#contactPrenom').val() + ' ' + $('#contactNom').val();
    var email = $('#contactMail').val();
    var tel = $('#contactTel').val();
    var subject = $('#contactSubject').val();
    var message = $('#contactMessage').val();
	$('#errorMess').hide();
	$('#successMess').hide();
	$('#emptyMess').hide();
	$('#emailMess').hide();
	$('.required').each(function(){
		if($(this).next().val() == '') {
			errors1 = 1;
			$(this).next().addClass('is-invalid');
		}
	});
	var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
  	if(email != '' && re.test(email) == false) {
		$('#contactMail').addClass('is-invalid');
		errors2 = 1;
	}
	if(errors1 == 1) $('#emptyMess').show();
	if(errors2 == 1) $('#emailMess').show();
	if(errors1 == 1 || errors2 == 1) return false;
	if($('#captcha').val() != 7) return false;
    $.ajax({
        url: '/contact',
        type: 'POST',
        data: 'name=' + name + '&email=' + email + '&tel=' + tel + '&subject=' + subject + '&message=' + message,
        dataType: 'json',
        cache: false
    })
    .done(function(res) {
        if (res.result == 'success') {
			$().msgpopup({
				text: '<h3>Votre message nous à bien été délivré,<br />nous allons vous répondre dans les plus brefs délais</h3>',
				type: 'success', // or success, error, alert and normal
				success: true,
				time: 10000, // or false
				x: true, // or false
			});
			$('#sendContactMail').attr('disabled', true);
			$('#contactPrenom').val('');
			$('#contactNom').val('');
    		$('#contactMail').val('');
    		$('#contactTel').val('');
    		$('#contactSubject').val('');
    		$('#contactMessage').val('');
			$(this).prop('checked', false)
        } else {
            $('#errorMess').show();
        }
    });
});
