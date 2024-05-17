
$('._datatable').each(function(){
    $(this).footable({
        "paging": {
            "enabled": true,
            "size": 20,
            "limit": 5,
            "position": "center",
            "countFormat": "Page {CP} sur {TP}",
            "container": $(this).prev(".paging-ui-container"),
        },
        "filtering": {
			"enabled": true,
            "dropdownTitle": "Rechercher",
            "min": 2,
            "placeholder": "Rechercher"
		},
        calculateWidthOverride: function() {
        return { width: $(window).width() };
        }
    });
  }); 

/************ "search" item on all selects + select2 ******************
$('select').each(function() {
    let target = $(this).children(':first-child');
    if(target.val() == '') {
        $(this).children(':first-child').html('Sélectionner');
    } 
});
$('.select2').select2();

/********* TinyMCE ************/

var useDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;

tinymce.init({
    selector: 'textarea.tinymce',
    plugins: 'codesnippet searchreplace autolink code visualblocks visualchars fullscreen image link media table charmap anchor advlist lists wordcount help quickbars emoticons',
    menubar: 'file edit view insert tools table help',
    toolbar: 'undo redo | codesnippet | bold italic underline strikethrough | formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | removeformat | charmap emoticons | fullscreen | insertfile image media template link anchor',
    toolbar_sticky: true,
    autosave_ask_before_unload: true,
    autosave_interval: '30s',
    autosave_prefix: '{path}{query}-{id}-',
    autosave_restore_when_empty: false,
    autosave_retention: '2m',
    image_advtab: true,
    height: 600,
    image_caption: true,
    quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
    toolbar_mode: 'sliding',
    contextmenu: 'link image imagetools table',
    skin: useDarkMode ? 'oxide-dark' : 'oxide',
    content_css: useDarkMode ? 'dark' : 'default',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
    image_title: true,
    automatic_uploads: true,
    images_upload_url: '/upload_file',
    file_picker_types: 'image',
    file_picker_callback: (cb, value, meta) => {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');

        input.addEventListener('change', (e) => {
        const file = e.target.files[0];

        const reader = new FileReader();
        reader.addEventListener('load', () => {
            const id = 'blobid' + (new Date()).getTime();
            const blobCache =  tinymce.activeEditor.editorUpload.blobCache;
            const base64 = reader.result.split(',')[1];
            const blobInfo = blobCache.create(id, file, base64);
            blobCache.add(blobInfo);
            cb(blobInfo.blobUri(), { title: file.name });
        });
        reader.readAsDataURL(file);
        });
        input.click();
    },
    codesnippet_languages: [
        { text: 'HTML/XML', value: 'markup' },
        { text: 'JavaScript', value: 'javascript' },
        { text: 'CSS', value: 'css' },
        { text: 'PHP', value: 'php' },
        { text: 'Shell', value: 'shell' },
        { text: 'Swift', value: 'swift' },
        { text: 'Ruby', value: 'ruby' },
        { text: 'Python', value: 'python' },
        { text: 'Java', value: 'java' },
        { text: 'C', value: 'c' },
        { text: 'C#', value: 'csharp' },
        { text: 'C++', value: 'cpp' }
      ],
 });

/********* Admin collapse menu *********/
!function(l) {
    "use strict";
    l("#sidebarToggle, #sidebarToggleTop").on("click", function(e) {
        l("body").toggleClass("sidebar-toggled"),
        l(".sidebar").toggleClass("toggled"),
        l(".sidebar").hasClass("toggled") && l(".sidebar .collapse").collapse("hide")
    }),
    l(window).resize(function() {
        l(window).width() < 768 && l(".sidebar .collapse").collapse("hide"),
        l(window).width() < 480 && !l(".sidebar").hasClass("toggled") && (l("body").addClass("sidebar-toggled"),
        l(".sidebar").addClass("toggled"),
        l(".sidebar .collapse").collapse("hide"))
    }),
    l("body.fixed-nav .sidebar").on("mousewheel DOMMouseScroll wheel", function(e) {
        var o;
        768 < l(window).width() && (o = (o = e.originalEvent).wheelDelta || -o.detail,
        this.scrollTop += 30 * (o < 0 ? 1 : -1),
        e.preventDefault())
    }),
    l(document).on("scroll", function() {
        100 < l(this).scrollTop() ? l(".scroll-to-top").fadeIn() : l(".scroll-to-top").fadeOut()
    }),
    l(document).on("click", "a.scroll-to-top", function(e) {
        var o = l(this);
        l("html, body").stop().animate({
            scrollTop: l(o.attr("href")).offset().top
        }, 1e3, "easeInOutExpo"),
        e.preventDefault()
    })
}(jQuery);

/****** sidebar dropdown *********/
$(document).on('click', '.has-icon', function(e){
    e.preventDefault;
    $('.submenu').each(function(){
        $(this).slideUp();
    });
    $('.has-icon').each(function(){
        $(this).children('.caretIcon').removeClass('caretIconOpen').addClass('caretIconClose');
    });
    $(this).next().slideDown();
    $(this).children('.caretIcon').removeClass('caretIconClose').addClass('caretIconOpen');
});

/********* Delete alert ************/
$(document).on('click', '._deleteBtn', function(e) {
    e.preventDefault();
    let parent = $(this).parent('form');
    $.confirm({
        theme: 'supervan',
        icon: 'fa-solid fa-triangle-exclamation fa-2xl text-red',
        title: '',
        content: 'Supprimer cette donnée ?<br />Attention cette action est irréversible',
        buttons: {
            confirm: {
                text: "Oui",
                action: function() {
                    parent.submit();
                }
            },
            cancel: {
                text: "Non"
            }
        }
    });
});

/********* Close alerts ************/
$(document).ready(function () {
    $( '.alert-close' ).click(function() {
        $( this ).parent().parent().fadeOut();
    });
});

/*********** suppression logos *************/
$(document).on('click', '._deleteLogo', function(){
    let id = $(this).data('id');
    let entity = $(this).data('entity');
    let universe = $(this).data('universe');
    let current = $(this).parent('div');
    $.confirm({
        theme: 'supervan',
        icon: 'fa-solid fa-triangle-exclamation fa-2xl text-red',
        title: '',
        content: 'Supprimer cette donnée ?<br />Attention cette action est irréversible',
        buttons: {
            confirm: {
                text: "Oui",
                action: function() {
                    $.ajax({
                        url: '/' + universe + '/' + entity + '/deleteLogo',
                        type: 'POST',
                        data: 'id=' + id,
                        dataType: false,
                        cache: false
                    })
                    .done(function(r) {
                        res = $.parseJSON(r);
                        if (res.result == 'success') {
                            current.slideUp();
                        } else {
                            $('#errorIcon').show();
                            $('#alertMessage').html('Une erreur s\'est produite');
                            $('#jsAlertBox').addClass('error');
                            $('#jsAlertBox').show();
                        }
                    });
                }
            },
            cancel: {
                text: "Non"
            }
        }
    });
})

/*********** transactional variables validation ************/
$(document).on('click', '#validVars', function(){
    let tab = new Array;
    $('input[type=checkbox]').each(function(){
        if($(this).prop('checked') == true) {
            if($(this).attr('id') != null) {
                let subTab = new Array;
                subTab.push($(this).attr('id'));
                let text = $(this).parent('td').next('td').find('input[type=text]');
                subTab.push(text.val());
                tab.push(subTab);
            }
        }
    })
    $.ajax({
        url: '/admin/transactional/manageVars',
        type: 'POST',
        data: 'tab=' + JSON.stringify(tab),
        dataType: 'json',
        cache: false
    })
    .done(function(res) {
        if (res.result == 'success') {
            $('#successIcon').show();
            $('#alertMessage').html('Mise à jour effectuée');
            $('#jsAlertBox').addClass('success');
            $('#jsAlertBox').show();
        } else {
            $('#errorIcon').show();
            $('#alertMessage').html('Une erreur s\'est produite');
            $('#jsAlertBox').addClass('error');
            $('#jsAlertBox').show();
        }
    });
});

$('body').on('click', '._activeButton', function (e) {
    let id = $(this).data('id');
    let entity = $(this).data('entity');
    let status = $(this).data('status');
    let cible = $(this).children('i');
    $.confirm({
        theme: 'supervan',
        icon: 'fa-solid fa-triangle-exclamation fa-2xl text-red',
        title: '',
        content: status == 1 ? 'Mettre en ligne ?' : 'Désactiver ?',
        buttons: {
            confirm: {
                text: "Oui",
                action: function() {
                    $.ajax({
                        url: '/admin/' + entity + '/activate',
                        type: 'POST',
                        data: 'id=' + id + '&status=' + status,
                        dataType: false,
                        cache: false
                    })
                    .done(function(s) {
                        $('#jsAlertBox').removeClass('success');
                        $('#jsAlertBox').removeClass('error');
                        $('#jsAlertBox').removeClass('notice');
                        $('#jsAlertBox').removeClass('wait');
                        var res = jQuery.parseJSON(s);
                        if (res.result == 'success') {
                            $('#successIcon').show();
                            $('#alertMessage').html('Activation effectuée');
                            $('#jsAlertBox').addClass('success');
                            cible.toggleClass('fa-circle-check fa-circle-xmark').toggleClass('text-green text-red');
                        } else {
                            $('#errorIcon').show();
                            $('#alertMessage').html('Une erreur s\'est produite');
                            $('#jsAlertBox').addClass('error');
                        }
                        $('#jsAlertBox').show();
                    });
                }
            },
            cancel: {
                text: "Non"
            }
        }
    });
});

$(document).on('click', '.alert-close', function(){
	$(this).parent().parent('div').fadeOut();
});

$(document).on('click', '._persona', function(e){
	e.preventDefault;
	let id = $(this).data('id');
	$('.selected').each(function() {
		$(this).removeClass('selected');
	});
	$('#users_persona option[value="' + id + '"]').prop('selected', true);
	$(this).parent('div').addClass('selected');
});

$(document).on('click', '._deleteAvatar', function(e){
	e.preventDefault;
	$.ajax({
        url: '/account/users/delete_avatar',
        type: 'POST',
        data: null,
        cache: false
    })
    .done(function(r) {
		let res = $.parseJSON(r);
        if (res.result == 'success') {
			$('#avatarBloc').fadeOut();
        } 
    });
});

$('#posts_fk_tags').addClass('d-flex flex-row justify-content-around');

$(document).on('click', '#deleteAccount', function(e){
    e.preventDefault;
    $.confirm({
        theme: 'supervan',
        icon: 'fa-solid fa-triangle-exclamation fa-2xl text-red',
        title: '',
        content: 'Voulez vous vraiment supprimer votre compte ?<br />Cete action est irréversible ...<br />Tous vos articles et vos données personnelles seront effacés',
        buttons: {
            confirm: {
                text: "Oui",
                action: function() {
                    $('#AccountDeleteBtn').trigger('click');
                }
            },
            cancel: {
                text: "Non"
            }
        }
    });
})