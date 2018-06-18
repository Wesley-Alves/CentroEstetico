/* global $, document, FileReader, FormData */
$(document).ready(function() {
    // Evento para fechar o modal de formulário.
    $(document).on("click", ".modal_form .fechar", function(event) {
        event.preventDefault();
        $(".modal_form .body").animate({top: "-100%"}, {duration:300, complete:function() {
            $(".modal_form").remove();
        }});
    });
    
    // Evento para fechar o modal.
    $(document).on("click", ".modal .fechar", function(event) {
        event.preventDefault();
        $(".modal .body").animate({top: "-100%"}, {duration:300, complete:function() {
            $(".modal").remove();
        }});
    });
    
    // Evento para os botões de adcionar e editar.
    $(document).on("click", "#adicionar, #tabela .editar, #tabela .visualizar, #tabela .ativar_produto_do_mes", function(event) {
        event.preventDefault();
        var currentButton = this;
        if (!$(this).hasClass("disabled")) {
            $(this).addClass("disabled");
            $.ajax({
                url: $(this).attr("href"),
                success: function(data) {
                    $(".modal_form").remove();
                    $("body").append(data);
                    $(".modal_form .body").animate({top: "50%"}, 300);
                    if ($.fn.mask) {
                        if ($("#txt_telefone").length > 0) {
                            if ($("#txt_telefone").val().length == 15) {
                                $("#txt_telefone").mask("(00) 00000-0009");
                            } else {
                                $("#txt_telefone").mask("(00) 0000-00009");
                            }
                            
                            $("#txt_telefone").blur(function() {
                                if ($(this).val().length == 15) {
                                    $(this).mask("(00) 00000-0009");
                                } else {
                                    $(this).mask("(00) 0000-00009");
                                }
                            });
                        }
                        
                        $("#txt_cep").mask("00000-000");
                        $("#txt_preco, #txt_novo_preco").mask("0.000,00", {reverse: true});
                    }
                },
                
                complete: function() {
                    $(currentButton).removeClass("disabled");
                }
            });
        }
    });
    
    // Evento para o botão de selecionar imagem.
    $(document).on("change", ".upload_imagem input", function() {
        if (this.files && this.files[0]) {
            var file = this.files[0];
            var reader = new FileReader();
            var parent = $(this).parent();
            reader.onload = function(event) {
                parent.children("img").attr("src", event.target.result);
                parent.children("img").attr("title", file.name);
            }
            
            reader.readAsDataURL(file);
          }
    });
    
    // Evento para o botão de selecionar mais de uma imagem.
    $(document).on("change", ".upload_imagem_multiplo>input", function() {
        if (this.files && this.files[0]) {
            var file = this.files[0];
            var reader = new FileReader();
            var parent = $(this).parent();
            var count = parent.children("ul").children("li").length;
            var li = $("<li></li>").appendTo(parent.children("ul"));
            $("<a href='#' class='remover'><img src='imagens/icones/excluir.png' alt='Remover' title='Remover'></a>").appendTo(li);
            $("<a href='#' class='editar'><img src='imagens/icones/editar.png' alt='Editar' title='Editar'></a>").appendTo(li);
            reader.onload = function(event) {
                $("<img />").attr("src", event.target.result).attr("title", file.name).appendTo(li);
                $("<span>" + file.name + "</span>").appendTo(li);
            }

            reader.readAsDataURL(file);
            $(this).clone().removeAttr("id").attr("name", "img_foto_" + count).appendTo(li);
            $(this).val("");
        }
    });
    
    // Evento para o clique do botão de editar imagem na seleção de multiplas imagens.
    $(document).on("click", ".upload_imagem_multiplo .editar", function(event) {
        event.preventDefault();
        $(this).parent().find("input").trigger("click");
    });
    
    // Evento para seleção de arquivo na edição de multiplas imagens
    $(document).on("change", ".upload_imagem_multiplo li input", function() {
        if (this.files && this.files[0]) {
            var file = this.files[0];
            var reader = new FileReader();
            var parent = $(this).parent();
            reader.onload = function(event) {
                parent.children("img").attr("src", event.target.result).attr("title", file.name);
                parent.find("span").html(file.name);
            }

            reader.readAsDataURL(file); 
        }
    });
    
    // Evento para o clique do botão de remover imagem na seleção de multiplas imagens.
    $(document).on("click", ".upload_imagem_multiplo .remover", function(event) {
        event.preventDefault();
        var ul = $(this).parent().parent();
        $(this).parent().remove();
        var count = ul.children("li").length;
        for (var i = 0; i < count; i++) {
            ul.children("li").eq(i).find("input[type='file']").attr("name", "img_foto_" + i);
            ul.children("li").eq(i).find("input[type='hidden']").attr("name", "imagem_" + i);
        }
    });
    
    // Evento para o clique do botão de submeter formulário.
    $(document).on("click", ".form_submit", function(event) {
        event.preventDefault();
        var form = $(this).attr("data-form");
        if ($(form).find(".upload_imagem_multiplo").length > 0) {
            if ($(form).find(".upload_imagem_multiplo").find("ul").children("li").length == 0) {
                $(form).find(".upload_imagem_multiplo").find("#img_foto")[0].setCustomValidity("Selecione pelo menos uma imagem.");
            } else {
                $(form).find(".upload_imagem_multiplo").find("#img_foto")[0].setCustomValidity("");
            }
        }
        
        $(form).find("[type='submit']").trigger("click");
    });
    
    // Evento para submeter os formulários.
    $(document).on("submit", ".modal_form form", function(event) {
        event.preventDefault();
        var form = this;
        var parent = $(this).parent();
        var id;
        if (!$(parent).hasClass("disabled")) {
            $(parent).addClass("disabled");
            $(this).find("*").blur();
            $.ajax({
                type: "POST",
                url: $(form).attr("action"),
                data: new FormData(form),
                contentType: false,
                processData: false,
                cache: false,
                success: function(data) {
                    if (data.startsWith("ERRO:")) {
                        $(form).parent().parent().find(".erro").text(data.substr(5));
                    } else {
                        $(".modal_form .body").animate({top: "-100%"}, {duration:300, complete:function() {
                            $(".modal_form").remove();
                        }});

                        if ($(form).attr("id") == "form_adicionar") {
                            $("body, html").animate({scrollTop: $("#tabela_body").offset().top + $("#tabela_body")[0].scrollHeight - 100}, 500).promise().then(function() {
                                $(data).css("height", 0).appendTo("#tabela_body").animate({height: "140px"}, "normal");
                            });
                            
                        } else if ($(form).attr("id") == "form_ativar") {
                            id = $(form).find("input[name='id']").attr("value");
                            $("#tabela_body").find(".linha").each(function() {
                                var currentId = $(this).attr("data-id");
                                if (currentId == id) {
                                    $(this).find("a").replaceWith("<a href'#' class='desativar'><img src='imagens/icones/habilitado.png' alt='Habilitado' title='Habilitado'></a>")
                                } else {
                                    $(this).find("a").replaceWith("<a href='../controller/router.php?tipo=produto_do_mes&modo=ativar&id=" + currentId + "' class='ativar_produto_do_mes'><img src='imagens/icones/desabilitado.png' alt='Desabilitado' title='Desabilitado'></a>")
                                }
                            });
                            
                        } else {
                            id = $(form).find("input[name='id']").attr("value");
                            var element = $("#tabela_body").find(".linha[data-id='" + id + "']");
                            element.fadeOut("slow", function() {
                                element.replaceWith(function() {
                                    return $(data).hide().fadeIn();
                                });
                            });
                        }
                    }
                },
                
                complete: function() {
                    $(parent).removeClass("disabled");
                }
            });
        }
    });
    
    // Evento para excluir item.
    $(document).on("click", "#tabela .excluir", function(event) {
        event.preventDefault();
        var titulo = $(this).is("[data-titulo]") ? ("<strong>" + $(this).attr("data-titulo") + "</strong>") : "este item";
        getModal("Deseja realmente excluir " + titulo + " ?", "Excluir", "confirm", $(this).attr("href"));
    });
    
    // Evento para confirmação de exclusão.
    $(document).on("click", ".modal .confirmar", function(event) {
        event.preventDefault();
        var currentButton = this;
        if (!$(this).hasClass("disabled")) {
            $(this).addClass("disabled");
            $.ajax({
                url: $(this).attr("href"),
                dataType: "json",
                success: function(data) {
                    if (data.error) {
                        getModal(data.error, "Erro");
                    } else {
                        var element = $(data.type == "categoria" ? "#categorias" : data.type == "subcategoria" ? "#subcategorias" : "#tabela_body").find(".linha[data-id='" + data.id + "']");
                        element.fadeOut("slow", function() {
                            element.remove();
                        });

                        $(".modal .body").animate({top: "-100%"}, {duration:300, complete:function() {
                            $(".modal").remove();
                        }});
                        
                        if (data.type == "categoria" && $("#subcategorias .header").attr("data-id") == data.id) {
                            resetSubcategory();
                        }
                    }
                },
                
                complete: function() {
                    $(currentButton).removeClass("disabled");
                }
            });
        }
    });
    
    // Evento para ativar e desativar item.
    $(document).on("click", "#tabela .ativar", function(event) {
        event.preventDefault();
        var currentButton = this;
        if (!$(this).hasClass("disabled")) {
            $(this).addClass("disabled");
            var enabled = $(this).children("img").attr("data-ativo");
            $.ajax({
                url: $(this).attr("href"),
                data: {ativo: enabled},
                success: function(data) {
                    if (data.startsWith("ERRO:")) {
                        getModal(data.substr(5));
                    } else {
                        getModal(data);
                        if (enabled == "1") {
                            $(currentButton).html("<img src='imagens/icones/desabilitado.png' alt='Desabilitado' title='Desabilitado' data-ativo='0'>")
                        } else {
                            $(currentButton).html("<img src='imagens/icones/habilitado.png' alt='Habilitado' title='Habilitado' data-ativo='1'>")
                        }
                    }
                },
                
                complete: function() {
                    $(currentButton).removeClass("disabled");
                }
            });
        }
    });
    
    // Evento para desativar produto do mês.
    $(document).on("click", "#tabela .desativar", function(event) {
        event.preventDefault();
        getModal("Para desativar um produto do mês, basta ativar algum outro produto.")
    });
    
    // Evento para atualizar lista de cidades ao selecionar um estado.
    $(document).on("change", "#slt_estado", function() {
        var id = $(this).find(":selected").attr("value");
        $("#slt_cidade").attr("disabled", "disabled");
        $.ajax({
            url: "../controller/router.php",
            data: {tipo: "loja", modo: "cidades", id: id},
            success: function(data) {
                $("#slt_cidade").html(data);
                $("#slt_cidade").removeAttr("disabled");
            }
        });
    });
    
    // Evento para atualizar as informações da promoção ao selecionar um produto.
    $(document).on("change", "#slt_produto", function() {
        var id = $(this).find(":selected").attr("value");
        $.ajax({
            url: "../controller/router.php",
            data: {tipo: "promocao", modo: "produto", id: id},
            dataType: "json",
            success: function(data) {
                $(".upload_imagem img").attr("src", "../imagens/produtos/" + data.imagem);
                $("#txt_preco").val(data.preco).trigger("input");
                $("#txt_descricao").html(data.descricao);
            }
        });
    });
    
    // Evento para os botões de adicionar categoria e subcategoria.
    $(document).on("click", "#adicionar_categoria, #adicionar_subcategoria", function(event) {
        event.preventDefault();
        var parent = $(this.id == "adicionar_categoria" ? "#categorias .body" : "#subcategorias .body");
        if (!$(this).hasClass("disabled_no_load")) {
            $.ajax({
                url: $(this).attr("href"),
                success: function(data) {
                    $(data).css("height", 0).appendTo(parent).animate({height: "50px"}, 300);
                },

                error: function() {
                    console.log("Erro interno.");
                }
            });
        }
    });
    
    // Evento para os botões de cancelar a criação / edição de uma categoria e subcategoria;
    $(document).on("click", "#categorias .cancelar, #subcategorias .cancelar", function(event) {
        event.preventDefault();
        var parent = $(this).parents(".linha");
        if (parent.hasClass("atualizar")) {
            $.ajax({
                url: $(this).attr("href"),
                success: function(data) {
                    parent.fadeOut("default", function() {
                        parent.replaceWith(function() {
                            return $(data).hide().fadeIn();
                        });
                    });
                },

                error: function() {
                    console.log("Erro interno.");
                }
            });
            
        } else {
            parent.fadeOut("default", function() {
                parent.remove();
            });
        }
    });
    
    // Evento para salvar uma categoria e uma subcategoria.
    $(document).on("click", "#categorias .salvar, #subcategorias .salvar", function(event) {
        event.preventDefault();
        $(this).parents(".linha").find("input[type=\"submit\"]").trigger("click");
    });
    
    // Evento para submissão dos formulários de salvar uma categoria e uma subcategoria.
    $(document).on("submit", "#categorias form, #subcategorias form", function(event) {
        event.preventDefault();
        var form = this;
        $(form).find("*").blur();
        $.ajax({
            type: "POST",
            url: $(form).attr("action"),
            data: $(form).serialize(),
            success: function(data) {
                var element = $(form).parents(".linha");
                element.fadeOut("default", function() {
                    element.replaceWith(function() {
                        return $(data).hide().fadeIn();
                    });
                });
            },

            error: function() {
                console.log("Erro interno.");
            }
        });
    });
    
    // Evento para editar uma categoria e uma subcategoria.
    $(document).on("click", "#categorias .editar, #subcategorias .editar", function(event) {
        event.preventDefault();
        var parent = $(this).parents(".linha");
        $.ajax({
            url: $(this).attr("href"),
            success: function(data) {
                parent.fadeOut("default", function() {
                    parent.replaceWith(function() {
                        return $(data).hide().fadeIn();
                    });
                });
            },

            error: function() {
                console.log("Erro interno.");
            }
        });
    });
    
    // Evento para ativar e desativar uma categoria e uma subcategoria.
    $(document).on("click", "#categorias .ativar, #subcategorias .ativar", function(event) {
        event.preventDefault();
        var parent = $(this).parents(".linha");
        var ativo = parent.find(".ativar img").attr("data-ativo");
        if (parent.hasClass("atualizar") || parent.hasClass("adicionar")) {
            if (ativo == "1") {
                parent.find(".ativar").html("<img src=\"imagens/icones/desabilitado.png\" alt=\"Desabilitado\" title=\"Desabilitado\" data-ativo=\"0\">")
                parent.find("input[name=\"ativo\"]")[0].value = "0";
            } else {
                parent.find(".ativar").html("<img src=\"imagens/icones/habilitado.png\" alt=\"Habilitado\" title=\"Habilitado\" data-ativo=\"1\">")
                parent.find("input[name=\"ativo\"]")[0].value = "1";
            }
            
        } else {
            $.ajax({
                url: $(this).attr("href"),
                data: {ativo: ativo},
                success: function(data) {
                    getModal(data);
                    if (ativo == "1") {
                        parent.find(".ativar").html("<img src=\"imagens/icones/desabilitado.png\" alt=\"Desabilitado\" title=\"Desabilitado\" data-ativo=\"0\">")
                    } else {
                        parent.find(".ativar").html("<img src=\"imagens/icones/habilitado.png\" alt=\"Habilitado\" title=\"Habilitado\" data-ativo=\"1\">")
                    }
                },

                error: function() {
                    console.log("Erro interno.");
                }
            });
        }
    });
    
    // Evento para excluir uma categoria.
    $(document).on("click", "#categorias .excluir", function(event) {
        event.preventDefault();
        getModal("Deseja realmente excluir " + "<strong>" + $(this).attr("data-titulo") + "</strong>" + " e todas suas subcategorias ?", "Excluir", "confirm", $(this).attr("href"));
    });
    
    // Evento para excluir uma subcategoria.
    $(document).on("click", "#subcategorias .excluir", function(event) {
        event.preventDefault();
        getModal("Deseja realmente excluir " + "<strong>" + $(this).attr("data-titulo") + "</strong>" + " ?", "Excluir", "confirm", $(this).attr("href"));
    });
    
    // Evento para selecionar as subcategorias de uma categoria.
    $(document).on("click", "#categorias .selecionar", function(event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr("href"),
            success: function(data) {
                $("#subcategorias").fadeOut("fast", function() {
                    $("#subcategorias").html(data).fadeIn("fast");
                });
            },

            error: function() {
                console.log("Erro interno.");
            }
        });
    });
    
    // Evento para formato de exibição do gráfico de estatistícas.
    $("#tabs a").click(function(event) {
        event.preventDefault();
        var id = $(this).attr("data-id");
        if (!$("#tab" + id).hasClass("active")) {
            $("#tabs #barra").css("left", $(this).width() * (id - 1));
            $("#tab_content .tab.active").fadeOut(175, function() {
                $("#tab_content .tab").removeClass("active");
                $("#tab" + id).fadeIn(175, function() {
                    $("#tab" + id).addClass("active");
                });
            });
        }
    });
});

// Função usada para chamar o arquivo "modal.php" e exibir o resultado.
function getModal(texto, titulo, tipo, acao) {
    $.ajax({
        url: "../view/modal.php",
        data: {texto: texto, titulo: titulo, tipo: tipo, acao: acao},
        success: function(data) {
            $(".modal").remove();
            $("body").append(data);
            $(".modal .body").animate({top: "15%"}, 300);
        }
    });
}

// Função para resetar o html das subcategorias de uma categoria ao apagar uma categoria.
function resetSubcategory() {
    $.ajax({
        url: "../controller/router.php?tipo=subcategoria&modo=selecionar",
        success: function(data) {
            $("#subcategorias").fadeOut("fast", function() {
                $("#subcategorias").html(data).fadeIn("fast");
            });
        },

        error: function() {
            console.log("Erro interno.");
        }
    });
}