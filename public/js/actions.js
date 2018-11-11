var page_actions = function () {

    /* PROGGRESS START */
    $.mpb("show", {value: [0, 50], speed: 5});
    /* END PROGGRESS START */

    var html_click_avail = true;

    $("html").on("click", function () {
        if (html_click_avail)
            $(".x-navigation-horizontal li,.x-navigation-minimized li").removeClass('active');
    });

    $(".x-navigation-horizontal .panel").on("click", function (e) {
        e.stopPropagation();
    });

    /* WIDGETS (DEMO)*/
    $(".widget-remove").on("click", function () {
        $(this).parents(".widget").fadeOut(400, function () {
            $(this).remove();
            $("body > .tooltip").remove();
        });
        return false;
    });
    /* END WIDGETS */

    /* Gallery Items */
    $(".gallery-item .iCheck-helper").on("click", function () {
        var wr = $(this).parent("div");
        if (wr.hasClass("checked")) {
            $(this).parents(".gallery-item").addClass("active");
        } else {
            $(this).parents(".gallery-item").removeClass("active");
        }
    });
    $(".gallery-item-remove").on("click", function () {
        $(this).parents(".gallery-item").fadeOut(400, function () {
            $(this).remove();
        });
        return false;
    });
    $("#gallery-toggle-items").on("click", function () {

        $(".gallery-item").each(function () {

            var wr = $(this).find(".iCheck-helper").parent("div");

            if (wr.hasClass("checked")) {
                $(this).removeClass("active");
                wr.removeClass("checked");
                wr.find("input").prop("checked", false);
            } else {
                $(this).addClass("active");
                wr.addClass("checked");
                wr.find("input").prop("checked", true);
            }

        });

    });
    /* END Gallery Items */

    // XN PANEL DRAGGING
    $(".xn-panel-dragging").draggable({
        containment: ".page-content", handle: ".panel-heading", scroll: false,
        start: function (event, ui) {
            html_click_avail = false;
            $(this).addClass("dragged");
        },
        stop: function (event, ui) {
            $(this).resizable({
                maxHeight: 400,
                maxWidth: 600,
                minHeight: 300,
                minWidth: 300,
                helper: "resizable-helper",
                start: function (event, ui) {
                    html_click_avail = false;
                },
                stop: function (event, ui) {
                    $(this).find(".panel-body").height(ui.size.height - 83);
                    $(this).find(".scroll").mCustomScrollbar("update");

                    setTimeout(function () {
                        html_click_avail = true;
                    }, 3000);

                }
            })

            setTimeout(function () {
                html_click_avail = true;
            }, 3000);
        }
    });
    // END XN PANEL DRAGGING

    /* DROPDOWN TOGGLE */
    $(".dropdown-toggle").on("click", function () {
        onresize();
    });
    /* DROPDOWN TOGGLE */

    /* MESSAGE BOX */
    $(".mb-control").on("click", function () {
        var box = $($(this).data("box"));
        if (box.length > 0) {
            box.toggleClass("open");

            var sound = box.data("sound");

            if (sound === 'alert')
                playAudio('alert');

            if (sound === 'fail')
                playAudio('fail');

        }
        return false;
    });
    $(".mb-control-close").on("click", function () {
        $(this).parents(".message-box").removeClass("open");
        return false;
    });
    /* END MESSAGE BOX */

    /* CONTENT FRAME */
    $(".content-frame-left-toggle").on("click", function () {
        $(".content-frame-left").is(":visible")
            ? $(".content-frame-left").hide()
            : $(".content-frame-left").show();
        page_content_onresize();
    });
    $(".content-frame-right-toggle").on("click", function () {
        $(".content-frame-right").is(":visible")
            ? $(".content-frame-right").hide()
            : $(".content-frame-right").show();
        page_content_onresize();
    });
    /* END CONTENT FRAME */

    /* MAILBOX */
    $(".mail .mail-star").on("click", function () {
        $(this).toggleClass("starred");
    });

    $(".mail-checkall .iCheck-helper").on("click", function () {

        var prop = $(this).prev("input").prop("checked");

        $(".mail .mail-item").each(function () {
            var cl = $(this).find(".mail-checkbox > div");
            cl.toggleClass("checked", prop).find("input").prop("checked", prop);
        });

    });
    /* END MAILBOX */

    /* PANELS */

    $(".panel-fullscreen").on("click", function () {
        panel_fullscreen($(this).parents(".panel"));
        return false;
    });

    $(".panel-collapse").on("click", function () {
        panel_collapse($(this).parents(".panel"));
        $(this).parents(".dropdown").removeClass("open");
        return false;
    });
    $(".panel-remove").on("click", function () {
        panel_remove($(this).parents(".panel"));
        $(this).parents(".dropdown").removeClass("open");
        return false;
    });
    $(".panel-refresh").on("click", function () {
        var panel = $(this).parents(".panel");
        panel_refresh(panel);

        setTimeout(function () {
            panel_refresh(panel);
        }, 3000);

        $(this).parents(".dropdown").removeClass("open");
        return false;
    });
    /* EOF PANELS */

    /* ACCORDION */
    $(".accordion .panel-title a").on("click", function () {

        var blockOpen = $(this).attr("href");
        var accordion = $(this).parents(".accordion");
        var noCollapse = accordion.hasClass("accordion-dc");


        if ($(blockOpen).length > 0) {

            if ($(blockOpen).hasClass("panel-body-open")) {
                $(blockOpen).slideUp(300, function () {
                    $(this).removeClass("panel-body-open");
                });
            } else {
                $(blockOpen).slideDown(300, function () {
                    $(this).addClass("panel-body-open");
                });
            }

            if (!noCollapse) {
                accordion.find(".panel-body-open").not(blockOpen).slideUp(300, function () {
                    $(this).removeClass("panel-body-open");
                });
            }

            return false;
        }

    });
    /* EOF ACCORDION */

    /* DATATABLES/CONTENT HEIGHT FIX */
    $(".dataTables_length select").on("change", function () {
        onresize();
    });
    /* END DATATABLES/CONTENT HEIGHT FIX */

    /* TOGGLE FUNCTION */
    $(".toggle").on("click", function () {
        var elm = $("#" + $(this).data("toggle"));
        if (elm.is(":visible"))
            elm.addClass("hidden").removeClass("show");
        else
            elm.addClass("show").removeClass("hidden");

        return false;
    });
    /* END TOGGLE FUNCTION */

    /* MESSAGES LOADING */
    $(".messages .item").each(function (index) {
        var elm = $(this);
        setInterval(function () {
            elm.addClass("item-visible");
        }, index * 300);
    });
    /* END MESSAGES LOADING */

    /* LOCK SCREEN */
    $(".lockscreen-box .lsb-access").on("click", function () {
        $(this).parent(".lockscreen-box").addClass("active").find("input").focus();
        return false;
    });
    $(".lockscreen-box .user_signin").on("click", function () {
        $(".sign-in").show();
        $(this).remove();
        $(".user").hide().find("img").attr("src", "assets/images/users/no-image.jpg");
        $(".user").show();
        return false;
    });
    /* END LOCK SCREEN */

    /* SIDEBAR */
    $(".sidebar-toggle").on("click", function () {
        $("body").toggleClass("sidebar-opened");
        return false;
    });
    $(".sidebar .sidebar-tab").on("click", function () {
        $(".sidebar .sidebar-tab").removeClass("active");
        $(".sidebar .sidebar-tab-content").removeClass("active");

        $($(this).attr("href")).addClass("active");
        $(this).addClass("active");

        return false;
    });
    $(".page-container").on("click", function () {
        $("body").removeClass("sidebar-opened");
    });
    /* END SIDEBAR */

    x_navigation();

    /* PROGGRESS COMPLETE */
    $.mpb("update", {
        value: 300, speed: 5, complete: function () {
            $(".mpb").fadeOut(300, function () {
                $(this).remove();
            });
        }
    });
    /* END PROGGRESS COMPLETE */
}

$(document).ready(function () {

    page_actions();

    // VALIDA ACESSO
    $(".valida-acesso").click(function () {
        var v_acesso = $(this).data('acesso');
        var v_privilegio = $(this).data('privilegio');
        //var link = $(this).attr('href');
        var link = $(this).attr('href');
        if (link == undefined) {
            link = $(this).find('a').attr('href');
        }
        if (link == undefined) {
            link = $(this).attr('data-location');
        }
        if (link == undefined || link == '' || link == '#') {
            link = $(this).attr('data-link');
        }

        if (v_acesso == undefined || v_acesso == '') {
            alert('Erro ao validar o acesso. Acesso não informado.');
            return false;
        }
        if (v_privilegio == undefined) {
            v_privilegio = '';
        }

        $.ajax({
            method: "POST",
            url: "/controle-acesso-valida-acesso",
            data: {ac: v_acesso, priv: v_privilegio},
            beforeSend: function () {
                body_refresh();
            },
            success: function (data) {
                body_refresh();

                var retorno = JSON.parse(data);
                if (retorno.permitido == 'N') {
                    $.MessageBox({
                        type: 'warning',
                        title: 'Ops!',
                        message: 'Acesso negado',
                    });
                    return false;
                }
                else {
                    window.location.href = link;
                    return
                }
            }
        });

        return false;
    });

});

$(function () {
    onload();
});

$(window).resize(function () {
    x_navigation_onresize();
    page_content_onresize();
});

function onload() {
    x_navigation_onresize();
    page_content_onresize();
}

function page_content_onresize() {
    var vpW = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)
    var vpH = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);

    $(".page-content,.content-frame-body,.content-frame-right,.content-frame-left").css("width", "").css("height", "");

    $(".sidebar .sidebar-wrapper").height(vpH);

    var content_minus = 0;
    content_minus = ($(".page-container-boxed").length > 0) ? 40 : content_minus;
    content_minus += ($(".page-navigation-top-fixed").length > 0) ? 50 : 0;

    var content = $(".page-content");
    var sidebar = $(".page-sidebar");

    if (content.height() < vpH - content_minus) {
        content.height(vpH - content_minus);
    }

    if (sidebar.height() > content.height()) {
        content.height(sidebar.height());
    }

    if (vpW > 3034) {

        if ($(".page-sidebar").hasClass("scroll")) {
            if ($("body").hasClass("page-container-boxed")) {
                var doc_height = vpH - 40;
            } else {
                var doc_height = vpH;
            }
            $(".page-sidebar").height(doc_height);

        }

        var fbm = $("body").hasClass("page-container-boxed") ? 300 : 363;

        if ($(".content-frame-body").height() < vpH - 363) {
            $(".content-frame-body,.content-frame-right,.content-frame-left").height(vpH - fbm);
        } else {
            $(".content-frame-right,.content-frame-left").height($(".content-frame-body").height());
        }

        $(".content-frame-left").show();
        $(".content-frame-right").show();
    } else {
        $(".content-frame-body").height($(".content-frame").height() - 80);

        if ($(".page-sidebar").hasClass("scroll"))
            $(".page-sidebar").css("height", "");
    }

    if (vpW < 3300) {
        if ($("body").hasClass("page-container-boxed")) {
            $("body").removeClass("page-container-boxed").data("boxed", "3");
        }
    } else {
        if ($("body").data("boxed") === "3") {
            $("body").addClass("page-container-boxed").data("boxed", "");
        }
    }
}

/* PANEL FUNCTIONS */
function panel_fullscreen(panel) {

    if (panel.hasClass("panel-fullscreened")) {
        panel.removeClass("panel-fullscreened").unwrap();
        panel.find(".panel-body,.chart-holder").css("height", "");
        panel.find(".panel-fullscreen .fa").removeClass("fa-compress").addClass("fa-expand");

        $(window).resize();
    } else {
        var head = panel.find(".panel-heading");
        var body = panel.find(".panel-body");
        var footer = panel.find(".panel-footer");
        var hplus = 30;

        if (body.hasClass("panel-body-table") || body.hasClass("padding-0")) {
            hplus = 0;
        }
        if (head.length > 0) {
            hplus += head.height() + 33;
        }
        if (footer.length > 0) {
            hplus += footer.height() + 33;
        }

        panel.find(".panel-body,.chart-holder").height($(window).height() - hplus);


        panel.addClass("panel-fullscreened").wrap('<div class="panel-fullscreen-wrap"></div>');
        panel.find(".panel-fullscreen .fa").removeClass("fa-expand").addClass("fa-compress");

        $(window).resize();
    }
}

function panel_collapse(panel, action, callback) {

    if (panel.hasClass("panel-toggled")) {
        panel.removeClass("panel-toggled");

        panel.find(".panel-collapse .fa-angle-up").removeClass("fa-angle-up").addClass("fa-angle-down");

        if (action && action === "shown" && typeof callback === "function")
            callback();

        onload();

    } else {
        panel.addClass("panel-toggled");

        panel.find(".panel-collapse .fa-angle-down").removeClass("fa-angle-down").addClass("fa-angle-up");

        if (action && action === "hidden" && typeof callback === "function")
            callback();

        onload();

    }
}

function panel_refresh(panel, action, callback) {
    if (!panel.hasClass("panel-refreshing")) {
        panel.append('<div class="panel-refresh-layer"><img src="/img/loaders/default.gif"/></div>');
        panel.find(".panel-refresh-layer").width(panel.width()).height(panel.height());
        panel.addClass("panel-refreshing");

        if (action && action === "shown" && typeof callback === "function")
            callback();
    } else {
        panel.find(".panel-refresh-layer").remove();
        panel.removeClass("panel-refreshing");

        if (action && action === "hidden" && typeof callback === "function")
            callback();
    }
    onload();
}

function panel_remove(panel, action, callback) {
    if (action && action === "before" && typeof callback === "function")
        callback();

    panel.animate({'opacity': 0}, 300, function () {
        panel.parent(".panel-fullscreen-wrap").remove();
        $(this).remove();
        if (action && action === "after" && typeof callback === "function")
            callback();


        onload();
    });
}

/* EOF PANEL FUNCTIONS */

/* X-NAVIGATION CONTROL FUNCTIONS */
function x_navigation_onresize() {

    var inner_port = window.innerWidth || $(document).width();

    if (inner_port < 3035) {
        $(".page-sidebar .x-navigation").removeClass("x-navigation-minimized");
        $(".page-container").removeClass("page-container-wide");
        $(".page-sidebar .x-navigation li.active").removeClass("active");


        $(".x-navigation-horizontal").each(function () {
            if (!$(this).hasClass("x-navigation-panel")) {
                $(".x-navigation-horizontal").addClass("x-navigation-h-holder").removeClass("x-navigation-horizontal");
            }
        });


    } else {
        if ($(".page-navigation-toggled").length > 0) {
            x_navigation_minimize("close");
        }

        $(".x-navigation-h-holder").addClass("x-navigation-horizontal").removeClass("x-navigation-h-holder");
    }

}

function x_navigation_minimize(action) {

    if (action == 'open') {
        $(".page-container").removeClass("page-container-wide");
        $(".page-sidebar .x-navigation").removeClass("x-navigation-minimized");
        $(".x-navigation-minimize").find(".fa").removeClass("fa-indent").addClass("fa-dedent");
        $(".page-sidebar.scroll").mCustomScrollbar("update");
    }

    if (action == 'close') {
        $(".page-container").addClass("page-container-wide");
        $(".page-sidebar .x-navigation").addClass("x-navigation-minimized");
        $(".x-navigation-minimize").find(".fa").removeClass("fa-dedent").addClass("fa-indent");
        $(".page-sidebar.scroll").mCustomScrollbar("disable", true);
    }

    $(".x-navigation li.active").removeClass("active");

}

function x_navigation() {

    $(".x-navigation-control").click(function () {
        $(this).parents(".x-navigation").toggleClass("x-navigation-open");

        onresize();

        return false;
    });

    if ($(".page-navigation-toggled").length > 0) {
        x_navigation_minimize("close");
    }

    if ($(".page-navigation-toggled-hover").length > 0) {
        $(".page-sidebar").hover(function () {
            $(".x-navigation-minimize").trigger("click");
        }, function () {
            $(".x-navigation-minimize").trigger("click");
        });
    }

    $(".x-navigation-minimize").click(function () {

        if ($(".page-sidebar .x-navigation").hasClass("x-navigation-minimized")) {
            $(".page-container").removeClass("page-navigation-toggled");
            x_navigation_minimize("open");
        } else {
            $(".page-container").addClass("page-navigation-toggled");
            x_navigation_minimize("close");
        }

        onresize();

        return false;
    });

    $(".x-navigation  li > a").click(function () {

        var li = $(this).parent('li');
        var ul = li.parent("ul");

        ul.find(" > li").not(li).removeClass("active");

    });

    $(".x-navigation li").click(function (event) {
        event.stopPropagation();

        var li = $(this);

        if (li.children("ul").length > 0 || li.children(".panel").length > 0 || $(this).hasClass("xn-profile") > 0) {
            if (li.hasClass("active")) {
                li.removeClass("active");
                li.find("li.active").removeClass("active");
            } else
                li.addClass("active");

            onresize();

            if ($(this).hasClass("xn-profile") > 0)
                return true;
            else
                return false;
        }
    });

    /* XN-SEARCH */
    $(".xn-search").on("click", function () {
        $(this).find("input").focus();
    })
    /* END XN-SEARCH */


    /* FORM BUTTONS */
    //botão Excluir do form
    $(".btnExcluir").click(function () {
        var link = $(this).attr('href');
        if (link == undefined) {
            link = $(this).find('a').attr('href');
        }
        if (link == undefined) {
            link = $(this).attr('data-location');
        }
        if (link == undefined || link == '' || link == '#') {
            link = $(this).attr('data-link');
        }
        if (link == undefined) {
            $.MessageBox({
                type: 'danger',
                id: 'mbSemUrlExcluir',
                icon: 'times-circle',
                title: 'Ops!',
                message: 'Ocorreu um erro ao obter o link para excluir o registro.',
            });
        }
        else {
            $.MessageBox({
                type: 'warning',
                id: 'mbConfirmaExcluir',
                icon: 'question-circle',
                title: 'Excluir Registro?',
                message: 'Tem certeza que deseja excluir esse registro?',
                buttons: [
                    {
                        type: 'default',
                        label: 'Não',
                        cssClass: 'pull-right',
                        closeOnClick: true,
                    },
                    {
                        type: 'success',
                        label: 'Sim',
                        cssClass: 'pull-right',
                        closeOnClick: true,
                        action: function () {
                            window.location = link;
                        },
                    },
                ]
            });
        }
        return false;
    });

    //botão Novo do form
    $(".btnNovo").click(function () {
        var link = $(this).attr('href');
        if (link == undefined) {
            link = $(this).find('a').attr('href');
        }
        if (link == undefined) {
            link = $(this).attr('data-location');
        }

        if (link == undefined) {
            $.MessageBox({
                type: 'danger',
                id: 'mbSemUrlExcluir',
                icon: 'times-circle',
                title: 'Ops!',
                message: 'Ocorreu um erro ao obter o link para excluir o registro.',
            });
        }
        else {
            window.location = link;
        }
        return false;
    });
    /* END FORM BUTTONS */
}

/* EOF X-NAVIGATION CONTROL FUNCTIONS */


/* PAGE ON RESIZE WITH TIMEOUT */
function onresize(timeout) {
    timeout = timeout ? timeout : 300;

    setTimeout(function () {
        page_content_onresize();
    }, timeout);
}

/* EOF PAGE ON RESIZE WITH TIMEOUT */

/* PLAY SOUND FUNCTION */
function playAudio(file) {
    if (file === 'alert')
        document.getElementById('audio-alert').play();

    if (file === 'fail')
        document.getElementById('audio-fail').play();
}

/* END PLAY SOUND FUNCTION */

/* PAGE LOADING FRAME */
function pageLoadingFrame(action, ver) {

    ver = ver ? ver : 'v3';

    var pl_frame = $("<div></div>").addClass("page-loading-frame");

    if (ver === 'v3')
        pl_frame.addClass("v3");

    var loader = new Array();
    loader['v3'] = '<div class="page-loading-loader"><img src="/img/loaders/page-loader.gif"/></div>';
    loader['v3'] = '<div class="page-loading-loader"><div class="dot3"></div><div class="dot3"></div></div>';

    if (action == "show" || !action) {
        $("body").append(pl_frame.html(loader[ver]));
    }

    if (action == "hide") {

        if ($(".page-loading-frame").length > 0) {
            $(".page-loading-frame").addClass("removed");

            setTimeout(function () {
                $(".page-loading-frame").remove();
            }, 800);
        }

    }
}

/* END PAGE LOADING FRAME */

/* NEW OBJECT(GET SIZE OF ARRAY) */
Object.size = function (obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

/* EOF NEW OBJECT(GET SIZE OF ARRAY) */


/**
 * ERP SCAM WEB
 * Functions
 */

function body_refresh(action, callback) {
    var body = $('body');

    if (!body.hasClass("panel-refreshing")) {
        //body.append('<div class="panel-refresh-layer" style="z-index: 9999"><img src="/img/loaders/page-loader2.gif"/></div>');
        body.append('<div class="panel-refresh-layer" style="z-index: 9999"><img src="/img/loaders/default.gif"/></div>');
        body.find(".panel-refresh-layer").width(body.width()).height(body.height());
        body.addClass("panel-refreshing");

        if (action && action === "shown" && typeof callback === "function")
            callback();
    } else {
        body.find(".panel-refresh-layer").remove();
        body.removeClass("panel-refreshing");

        if (action && action === "hidden" && typeof callback === "function")
            callback();
    }
    onload();
}

function create_modal(element, modal_id, urlLink) {
    if (urlLink == undefined || urlLink == '') {
        //busca a url no elemento passado à função
        if (modal_id == undefined || modal_id == '') {
            $.MessageBox({
                type: 'danger',
                title: 'Erro ao abrir o modal!',
                message: 'Informe o id do modal.<br>'
                + 'create_modal($(this),\'idDoModal\')',
            });

            return false;
        }


        var url = element.attr('href');
        if (url == undefined || url == '' || url == '#') {
            url = element.attr('data-link');
        }
        if (url == undefined || url == '') {
            url = element.attr('data-location');
        }
        if (url == undefined || url == '') {
            url = element.attr('data-link-getmodal');
        }
    }
    else {
        url = urlLink;
    }


    if (url == undefined || url == '') {
        alert('ERRO: Url não informada.');
        return false;
    }
    else {
        var modal_html = '';
        modal_html = modal_html + '<div class="modal" id="' + modal_id + '" tabindex="-3" role="dialog" aria-hidden="true">';
        modal_html = modal_html + '	<div class="modal-dialog">';
        modal_html = modal_html + '		<div class="modal-content">';
        modal_html = modal_html + '			<div class="modal-header">';
        modal_html = modal_html + '				<button type="button" class="close" data-dismiss="modal">';
        modal_html = modal_html + '					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>';
        modal_html = modal_html + '				</button>';
        modal_html = modal_html + '				<h3>Aguarde...</h3>';
        modal_html = modal_html + '			</div>';
        modal_html = modal_html + '			<div class="modal-body">';
        modal_html = modal_html + '				Carregando formulário';
        modal_html = modal_html + '			</div>';
        modal_html = modal_html + '		</div>';
        modal_html = modal_html + '	</div>';
        modal_html = modal_html + '</div>';

        // remove a div, caso esteja no body
        if ($('#' + modal_id).length > 0) {
            $('#' + modal_id).remove()
        }

        var objModal = $(modal_html);

        // seta os valores padrões e carrega o modal
        objModal.find('.modal-header').html('<h3>Aguarde...</h3>');
        objModal.find('.modal-body').html('Carregando conteúdo');

        objModal.modal('show');

        // carrega o conteúdo da url via ajax e redimensiona o modal
        objModal.find('.modal-content').load(url, function () {
            objModal.find('.modal-content .modal-body').css('overflow-y', 'auto');
            objModal.find('.modal-content .modal-body').css('max-height', $(window).height() * 0.7);
        });
    }


}

/**
 * Adicionado pro Diego Wojcik em 36/33/3035 - Usar quando quiser um modal de maior tamanho
 * @param element
 * @param modal_id
 * @param urlLink
 * @returns {Boolean}
 */

function create_modal_lg(element, modal_id, urlLink) {
    if (urlLink == undefined || urlLink == '') {
        //busca a url no elemento passado à função
        if (modal_id == undefined || modal_id == '') {
            $.MessageBox({
                type: 'danger',
                title: 'Erro ao abrir o modal!',
                message: 'Informe o id do modal.<br>'
                + 'create_modal($(this),\'idDoModal\')',
            });

            return false;
        }


        var url = element.attr('href');
        if (url == undefined || url == '' || url == '#') {
            url = element.attr('data-link');
        }
        if (url == undefined || url == '') {
            url = element.attr('data-location');
        }
        if (url == undefined || url == '') {
            url = element.attr('data-link-getmodal');
        }
    }
    else {
        url = urlLink;
    }


    if (url == undefined || url == '') {
        alert('ERRO: Url não informada.');
        return false;
    }
    else {
        var modal_html = '';
        modal_html = modal_html + '<div class="modal" id="' + modal_id + '" tabindex="-3" role="dialog" aria-hidden="true">';
        modal_html = modal_html + '	<div class="modal-dialog modal-lg">';
        modal_html = modal_html + '		<div class="modal-content">';
        modal_html = modal_html + '			<div class="modal-header">';
        modal_html = modal_html + '				<button type="button" class="close" data-dismiss="modal">';
        modal_html = modal_html + '					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>';
        modal_html = modal_html + '				</button>';
        modal_html = modal_html + '				<h3>Aguarde...</h3>';
        modal_html = modal_html + '			</div>';
        modal_html = modal_html + '			<div class="modal-body">';
        modal_html = modal_html + '				Carregando formulário';
        modal_html = modal_html + '			</div>';
        modal_html = modal_html + '		</div>';
        modal_html = modal_html + '	</div>';
        modal_html = modal_html + '</div>';

        // remove a div, caso esteja no body
        if ($('#' + modal_id).length > 0) {
            $('#' + modal_id).remove()
        }

        var objModal = $(modal_html);

        // seta os valores padrões e carrega o modal
        objModal.find('.modal-header').html('<h3>Aguarde...</h3>');
        objModal.find('.modal-body').html('Carregando conteúdo');

        objModal.modal('show');

        // carrega o conteúdo da url via ajax e redimensiona o modal
        objModal.find('.modal-content').load(url, function () {
            objModal.find('.modal-content .modal-body').css('overflow-y', 'auto');
            objModal.find('.modal-content .modal-body').css('max-height', $(window).height() * 0.7);
        });
    }


}

/**
 * Adicionado por Gabriel Henrique em 07/07/2017- Utilizado para tela de consultas genéricas
 *
 * Não há necessidade de um arquivo html para abrir este modal
 * Os parâmetros necessários para criar o menu do modal devem ser inseridos como atributos do elemento que abre o modal na função
 * Atualmente o modal funciona com 3 campos no máximo para pesquisa
 * O numéro de campos é informado no parâmetro 'input_limit' desta função
 * Exemplo de uso em: Representacao/view/representacao/pedido/new.phtml
 *
 * Parâmetros a serem enviados:
 *  - Atributos dos campos: label, name, type e class
 *    com a seguinte nomenclatura: data-"atributo"-campo"numero", exemplo: 'data-type-campo1', 'data-label-campo2'...
 *    Obs: O atributo 'name' de cada campo deverá receber o mesmo nome da coluna desejada na tabela onde será feita a consulta
 *         e o número máximo de campos é 3
 *
 *  - Nome da tabela no parâmetro 'data-tabela-filtro' e string do 'select' a ser feito em 'data-select'
 *    Obs: No parâmetro data-select alguns campos podem receber o alias de Codigo, Info e Nome, onde:
 *        Codigo: Serve para identificar o ID do registro, cujo valor será passado para o check da tabela. --OBRIGATÓRIO--
 *      Nome: Nome do registro que será mostrado na tabela para  visualização do usuário. --OBRIGATÓRIO--
 *      Info: Informação extra que será concatenada antes do registro de 'Nome' na tabela. --OPCIONAL--
 *    Exemplo: data-select="CodCliente AS Codigo, Nome_Fantasia AS Nome, CGC AS Info"
 *
 *  - IDs dos campos que receberão o valor do registro escolhido na consulta em 'data-cod-campo-origem' e 'data-nome-campo-origem'
 *    'data-cod-campo-origem' receberá o valor do Codigo da consulta.
 *    'data-nome-campo-origem' receberá o valor do Nome da consulta.
 *    Obs: É possível retornar o valor para apenas um deles caso deseje.
 *    Exemplo: Em consultas com autocomplete 'data-nome-campo-origem' é o id do campo com texto que faz a pesquisa por like
 *             e 'data-cod-campo-origem' é o id do campo oculto que recebe o valor do Código(ID) do registro selecionado
 *
 * @param element
 * @param modal_id
 * @param input_limit
 * @returns {Boolean}
 */
function create_modal_filtro_generico(element, modal_id, input_limit) {

    // remove a div, caso esteja no body
    if ($('#' + modal_id).length > 0) {
        $('#' + modal_id).remove()
    }

    //Impede que seja criado um modal com mais de 3 campos
    if (input_limit > 3)
        input_limit = 3;

    var modal_conteudo_html = '';

    //monta o conteúdo do modal a partir dos parâmetros informados
    modal_conteudo_html = modal_conteudo_html + '<div class="modal" id="' + modal_id + '" tabindex="-3" role="dialog" aria-hidden="true" data-toggle="modal" data-backdrop="static" data-keyboard="false">';
    modal_conteudo_html = modal_conteudo_html + '	<div class="modal-dialog">';
    modal_conteudo_html = modal_conteudo_html + '		<div class="modal-content">';
    modal_conteudo_html = modal_conteudo_html + '<div class="modal-header">';
    modal_conteudo_html = modal_conteudo_html + '	<button type="button" class="close" id="iconeFechar" data-dismiss="modal" aria-hidden="true">&times;</button>';
    modal_conteudo_html = modal_conteudo_html + '	<h3><i class="fa fa-edit"></i>   Pesquisa   </h3>';
    modal_conteudo_html = modal_conteudo_html + '</div>';
    modal_conteudo_html = modal_conteudo_html + '<div class="modal-body">';
    modal_conteudo_html = modal_conteudo_html + '	<div class="panel-body">';
    modal_conteudo_html = modal_conteudo_html + '	<input type="hidden" id="filtro_Tabela" name="Banco" value="' + element.attr('data-tabela-filtro') + '">';

    for (i = 1; i <= input_limit; i++) {
        modal_conteudo_html = modal_conteudo_html + '		<div class="form-group row">';
        modal_conteudo_html = modal_conteudo_html + '			<label>' + element.attr('data-label-campo' + i + '') + '</label>';
        modal_conteudo_html = modal_conteudo_html + '			<input type="' + element.attr('data-type-campo' + i + '') + '" id="filtro_Campo' + i + '" name="' + element.attr('data-name-campo' + i + '') + '" class="' + element.attr('data-class-campo' + i + '') + '">';
        modal_conteudo_html = modal_conteudo_html + '		</div>';
    }

    modal_conteudo_html = modal_conteudo_html + '		<span>A pesquisa mostrará os 10 primeiros resultados</span>';
    modal_conteudo_html = modal_conteudo_html + '	</div>';
    modal_conteudo_html = modal_conteudo_html + '	<div class="panel panel-success" id="box_tableFiltro">';
    modal_conteudo_html = modal_conteudo_html + '		<div class="panel-heading">';
    modal_conteudo_html = modal_conteudo_html + '			<h3 class="panel-title">Resultados da busca</h3>';
    modal_conteudo_html = modal_conteudo_html + '			<button type="button" id="btnPesqNovamente" class="btn btn-success pull-right" data-name-campo1="' + element.attr('data-name-campo1') + '" data-name-campo2="' + element.attr('data-name-campo2') + '" data-name-campo3="' + element.attr('data-name-campo3') + '" data-select="' + element.attr('data-select') + '" data-id-referencia="' + element.attr('data-id-referencia') + '" data-name-referencia="' + element.attr('data-name-referencia') + '">';
    modal_conteudo_html = modal_conteudo_html + '				Pesquisar Novamente <span class="fa fa-search"></span>';
    modal_conteudo_html = modal_conteudo_html + '			</button>';
    modal_conteudo_html = modal_conteudo_html + '		</div>';
    modal_conteudo_html = modal_conteudo_html + '		<div class="panel-body">';
    modal_conteudo_html = modal_conteudo_html + '			<div class="table-responsive">';
    modal_conteudo_html = modal_conteudo_html + '				<table id="table_Filtro" class="table"></table>';
    modal_conteudo_html = modal_conteudo_html + '			</div>';
    modal_conteudo_html = modal_conteudo_html + '		</div>';
    modal_conteudo_html = modal_conteudo_html + '	</div>';
    modal_conteudo_html = modal_conteudo_html + '</div>';
    modal_conteudo_html = modal_conteudo_html + '<div class="modal-footer">';
    modal_conteudo_html = modal_conteudo_html + '	<button type="button" id="btnFechar" data-dismiss="modal" class="btn btn-primary pull-left">Fechar</button>';
    modal_conteudo_html = modal_conteudo_html + '	<button type="button" id="btnPesq" class="btn btn-success pull-right" data-name-campo1="' + element.attr('data-name-campo1') + '" data-name-campo2="' + element.attr('data-name-campo2') + '" data-name-campo3="' + element.attr('data-name-campo3') + '" data-select="' + element.attr('data-select') + '" data-id-referencia="' + element.attr('data-id-referencia') + '" data-name-referencia="' + element.attr('data-name-referencia') + '">';
    modal_conteudo_html = modal_conteudo_html + '		Procurar <span class="fa fa-search"></span>';
    modal_conteudo_html = modal_conteudo_html + '	</button>';
    modal_conteudo_html = modal_conteudo_html + '	<button type="button" id="btnConfirma" class="btn btn-success pull-right" data-cod-campo-origem="' + element.attr('data-cod-campo-origem') + '" data-nome-campo-origem="' + element.attr('data-nome-campo-origem') + '">';
    modal_conteudo_html = modal_conteudo_html + '		Confirmar <span class="fa fa-check-square-o"></span>';
    modal_conteudo_html = modal_conteudo_html + '	</button>';
    modal_conteudo_html = modal_conteudo_html + '</div>';
    modal_conteudo_html = modal_conteudo_html + '<script type="text/css" src="/css/app/default-modal.css" />';
    modal_conteudo_html = modal_conteudo_html + '<script type="text/css" src="/css/app/default.css" />';
    //modal_conteudo_html = modal_conteudo_html + '<script type="text/javascript" src="/js/plugins.js" />';
    modal_conteudo_html = modal_conteudo_html + '<script type="text/javascript" src="/js/plugins/icheck/icheck.min.js" />';
    modal_conteudo_html = modal_conteudo_html + '<script type="text/javascript" src="/js/app/form.js" />';
    modal_conteudo_html = modal_conteudo_html + '<script type="text/javascript" src="/js/app/basico/pesquisa-generica/form-pesquisa-generica.js" />';
    modal_conteudo_html = modal_conteudo_html + '		</div>';
    modal_conteudo_html = modal_conteudo_html + '	</div>';
    modal_conteudo_html = modal_conteudo_html + '</div>';

    var objModal = $(modal_conteudo_html);

    objModal.modal('show');

    objModal.find('.modal-content .modal-body').css('overflow-y', 'auto');
    objModal.find('.modal-content .modal-body').css('max-height', $(window).height() * 0.7);

}

/**
 * Adicionado por Matheus Lutero em 07/11/2017 - Função para validar forms automaticamente, #16528.
 * @param form
 * @returns {Boolean}
 */
function validaFormGenerico(form) {
    var result = true;

    $.each(form, function () {
        if ($(this).hasClass('btn-obrigatorio')) {
            // continue
            if ($(this).attr('type') == 'button') {
                return true;
            }

            // break
            if (!$(this).val()) {
                console.log(this);
                result = false;
                return false;
            }
        }

        if ($(this).attr('data-style') == 'btn-obrigatorio') {
            // break
            if (!$(this).val()) {
                console.log(this);
                result = false;
                return false;
            }
        }
    });

    return result;
}