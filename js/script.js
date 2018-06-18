/* global $, document, window */
$(document).ready(function() {
    // Loadfix: Usado para resolver bug no internet explorer das animações serem ativadas no carregamento da página
    $("body").removeClass("loadfix");
    
    // Evento para abrir o menu no modo mobile
    $("#botao_menu").mousedown(function(event) {
        event.preventDefault();
        if (!$("#menu").hasClass("visivel")) {
            $("#menu").animate({height: "240px"}, 250, function() {
                $("#menu").addClass("visivel");
                $("#menu").removeAttr("style");
            });
            
        } else {
            $("#menu").animate({height: "0"}, 250, function() {
                $("#menu").removeClass("visivel");
                $("#menu").removeAttr("style");
            });
        }
    });
    
    // Detecta se a página atual possui o slider, e ativa o plugin nele
    if ($("#container_slider").length) {
        $("#slider").responsiveSlides({
            nav: true,
            prevText: "",
            nextText: "",
            speed: 1000
        });
    }
    
    // Evento para abrir o menu de categorias no modo mobile, calculando a altura de acordo com os itens nele
    $("#botao_categorias").mousedown(function(event) {
        event.preventDefault();
        var menu = $("#menu_categorias")
        $("#botao_categorias").toggleClass("ativo");
        
        if ($("#botao_categorias").hasClass("ativo")) {
            var height = menu.css("height", "auto").height();
            menu.height(0).animate({height: height}, 500, function() {
                menu.removeAttr("style");
                menu.addClass("visivel");
            });
            
        } else {
            menu.animate({height: 0}, 500, function() {
                menu.removeAttr("style");
                menu.removeClass("visivel");
            });
        }
    });
    
    // Código para galeria de cada item da página "Nossos Ambientes", onde ele adiciona automaticamente paginação e aplica o plugin de slider.
    $(".galeria").each(function(index, element) {
        var children = $(element).find(".imagens li").length;
        var controls = $(element).find(".controles");
        controls.addClass("control_" + index);
        controls.append("<ul class='pager pager_" + index + "'></ul>");
        for (var i = 0; i < children; i++) {
            controls.children(".pager").append("<li><a href='#'></a></li>")
        }

        $(element).children(".imagens").responsiveSlides({
            nav: true,
            speed: 500,
            manualControls: ".pager_" + index,
            navContainer: ".control_" + index,
            prevText: "<",
            nextText: ">"
        });
    });
    
    // Código para galeria de thumbnails da página "Produto do Mês".
    $(".galeria_thumbnail").each(function() {
        var image = $(this).find(".imagem");
        var thumbnail = $(this).find(".thumbnails a");
        var onTransition = false;
        
        thumbnail.click(function(event) {
            event.preventDefault();
            if (!onTransition) {
                onTransition = true;

                var src = $(this).find("img").attr("src");
                image.fadeOut(400, function() {
                    image.attr("src", src)
                }).fadeIn(400, function() {
                    onTransition = false;
                });

                thumbnail.removeClass("active");
                $(this).addClass("active");
            }
        });
    });
    
    // Código para aplicar máscara nos campos da página "Fale Conosco".
    if ($.fn.mask) {
        $("#txt_telefone").mask("(00) 0000-0000", {onInvalid: function(val, e, f) {
            $(f).addClass("invalid");
        }, onChange: function(val, e, f) {
            $(f).removeClass("invalid");
        }});
        
        $("#txt_celular").mask("(00) 00000-0000", {onInvalid: function(val, e, f) {
            $(f).addClass("invalid");
        }, onChange: function(val, e, f) {
            $(f).removeClass("invalid");
        }});
        
        $("#txt_nome").on("input", function(){
            var regexp = /[^a-zA-Z ]/g;
            if ($(this).val().match(regexp)) {
                $(this).val($(this).val().replace(regexp, ""));
                $(this).addClass("invalid");
            } else {
                $(this).removeClass("invalid");
            }
        });
    }
    
    // Evento de submit da página "Fale Conosco", utilizando o método Ajax para a página não recarregar ao submeter o formulário, mostrando o resultado em tempo real.
    $("#form_fale_conosco").submit(function(event) {
        event.preventDefault();
        if (!$(this).hasClass(".disabled")) {
            $(this).addClass("disabled");
            $("#form_fale_conosco *").blur();
            $.ajax({
                type: "POST",
                url: "controller/router.php?tipo=fale_conosco&modo=gravar",
                data: $(this).serialize(),
                success: function() { 
                    $("#form_fale_conosco")[0].reset();
                    $("#form_fale_conosco #aviso").html("<p>Seu formulário foi enviado com sucesso.</p>");
                },

                complete: function() {
                    $("#form_fale_conosco").removeClass("disabled");
                }
            });
        }
    });
    
    // Evento de submit do formulário de login.
    $("#form_login").submit(function(event) {
        event.preventDefault();
        if (!$(this).hasClass(".disabled")) {
            $(this).addClass("disabled");
            $("#form_login *").blur();
            $.ajax({
                type: "POST",
                url: "controller/router.php?tipo=usuario&modo=autenticar",
                data: $(this).serialize(),
                success: function(data) {
                    if (data == "") {
                        window.location = "./cms/";
                    } else {
                        getModal(data);
                    }
                },

                complete: function() {
                    $("#form_login").removeClass("disabled");
                }
            });
        }
    });
    
    // Evento para fechar o modal.
    $(document).on("click", ".modal .fechar", function(event) {
        event.preventDefault();
        $(".modal .body").animate({top: "-100%"}, {duration:300, complete:function() {
            $(".modal").remove();
        }});
    });
    
    // Evento para fazer uma requisição ajax quando uma subcategoria for selecionada.
    $(".submenu_categorias a").click(function(event) {
        event.preventDefault();
        $("#botao_categorias").removeClass("ativo");
        $("#menu_categorias").removeClass("visivel");
        $.ajax({
            type: "POST",
            url: "controller/router.php?tipo=produto&modo=listar",
            data: {idSubcategoria: $(this).attr("data-id")},
            success: function(data) {
                $("#area_produtos").html(data);
            }
        });
    });
    
    // Evento para fazer uma requisição ajax quando o formulário de pesquisa for submetido.
    $("#form_pesquisa").submit(function(event) {
        event.preventDefault();
        $("#form_pesquisa *").blur();
        $.ajax({
            type: "POST",
            url: "controller/router.php?tipo=produto&modo=listar",
            data: $(this).serialize(),
            success: function(data) {
                $("#area_produtos").html(data);
            }
        });
    });
    
    
    // Evento para mostrar uma modal com os detalhes de um produto.
    $(document).on("click", ".produto .detalhes", function(event) {
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "controller/router.php?tipo=produto&modo=detalhes",
            data: {id: $(this).attr("data-id")},
            success: function(data) {
                $(".modal").remove();
                $("body").append(data);
                $(".modal .body").animate({top: "50%"}, 300);
            }
        });
    });
});

// Evento para diminuir o tamanho do header ao dar scroll na página.
$(window).on("load scroll resize orientationchange", function() {
    var header = $("header");
    if (window.pageYOffset >= 50) {
        header.addClass("sticky");
    } else {
        header.removeClass("sticky");
    }
});

// Função usada para chamar o arquivo "modal.php" e exibir o resultado.
function getModal(texto) {
    $.ajax({
        url: "view/modal.php",
        data: {texto: texto},
        success: function(data) {
            $(".modal").remove();
            $("body").append(data);
            $(".modal .body").animate({top: "15%"}, 300);
        }
    });
}