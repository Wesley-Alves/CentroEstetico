<?php
    // Arquivo responsável por fazer a rota dos submits dos formulários para diferentes arquivos controladores.
    if (isset($_GET["tipo"])) {
        $tipo = $_GET["tipo"];
        $modo = isset($_GET["modo"]) ? $_GET["modo"] : "";
        
        if ($tipo == "fale_conosco") { // Rotas para o controlador de fale conosco.
            include_once("fale_conosco_controller.php");
            $faleConoscoController = new FaleConoscoController();
            if ($modo == "gravar") {
                $faleConoscoController->gravar();
            } else if ($modo == "visualizar") {
                $faleConoscoController->getModalVisualizar();
            } else if ($modo == "excluir") {
                $faleConoscoController->excluir();
            }
            
        } else if ($tipo == "usuario") { // Rotas para o controlador de usuários.
            include_once("usuario_controller.php");
            $usuarioController = new UsuarioController();
            if ($modo == "autenticar") {
                $usuarioController->autenticar();
            } else if ($modo == "adicionar") {
                $usuarioController->getModalAdicionar();
            } else if ($modo == "gravar") {
                $usuarioController->gravar();
            } else if ($modo == "editar") {
                $usuarioController->getModalEditar();
            } else if ($modo == "atualizar") {
                $usuarioController->atualizar();
            } else if ($modo == "excluir") {
                $usuarioController->excluir();
            } else if ($modo == "ativar") {
                $usuarioController->ativar();
            }
            
        } else if ($tipo == "nivel") { // Rotas para o controlador de níveis.
            include_once("nivel_controller.php");
            $nivelController = new NivelController();
            if ($modo == "adicionar") {
                $nivelController->getModalAdicionar();
            } else if ($modo == "gravar") {
                $nivelController->gravar();
            } else if ($modo == "editar") {
                $nivelController->getModalEditar();
            } else if ($modo == "atualizar") {
                $nivelController->atualizar();
            } else if ($modo == "excluir") {
                $nivelController->excluir();
            } else if ($modo == "ativar") {
                $nivelController->ativar();
            }
            
        } else if ($tipo == "destaque") { // Rotas para o controlador de destaques.
            include_once("destaque_controller.php");
            $destaqueController = new DestaqueController();
            if ($modo == "adicionar") {
                $destaqueController->getModalAdicionar();
            } else if ($modo == "gravar") {
                $destaqueController->gravar();
            } else if ($modo == "editar") {
                $destaqueController->getModalEditar();
            } else if ($modo == "atualizar") {
                $destaqueController->atualizar();
            } else if ($modo == "excluir") {
                $destaqueController->excluir();
            } else if ($modo == "ativar") {
                $destaqueController->ativar();
            }
            
        } else if ($tipo == "sobre_nos") { // Rotas para o controlador de sobre nós.
            include_once("sobre_nos_controller.php");
            $sobreNosCotroller = new SobreNosController();
            if ($modo == "adicionar") {
                $sobreNosCotroller->getModalAdicionar();
            } else if ($modo == "gravar") {
                $sobreNosCotroller->gravar();
            } else if ($modo == "editar") {
                $sobreNosCotroller->getModalEditar();
            } else if ($modo == "atualizar") {
                $sobreNosCotroller->atualizar();
            } else if ($modo == "excluir") {
                $sobreNosCotroller->excluir();
            } else if ($modo == "ativar") {
                $sobreNosCotroller->ativar();
            }
            
        } else if ($tipo == "loja") { // Rotas para o controlador de lojas.
            include_once("loja_controller.php");
            $lojaController = new LojaController();
            if ($modo == "adicionar") {
                $lojaController->getModalAdicionar();
            } else if ($modo == "cidades") {
                $lojaController->listarCidades();
            } else if ($modo == "gravar") {
                $lojaController->gravar();
            } else if ($modo == "editar") {
                $lojaController->getModalEditar();
            } else if ($modo == "atualizar") {
                $lojaController->atualizar();
            } else if ($modo == "excluir") {
                $lojaController->excluir();
            } else if ($modo == "ativar") {
                $lojaController->ativar();
            }
            
        } else if ($tipo == "slider") { // Rotas para o controlador de slider.
            include_once("slider_controller.php");
            $sliderController = new SliderController();
            if ($modo == "adicionar") {
                $sliderController->getModalAdicionar();
            } else if ($modo == "gravar") {
                $sliderController->gravar();
            } else if ($modo == "editar") {
                $sliderController->getModalEditar();
            } else if ($modo == "atualizar") {
                $sliderController->atualizar();
            } else if ($modo == "excluir") {
                $sliderController->excluir();
            } else if ($modo == "ativar") {
                $sliderController->ativar();
            }
            
        } else if ($tipo == "promocao") { // Rotas para o controlador de promoções.
            include_once("promocao_controller.php");
            $promocaoController = new PromocaoController();
            if ($modo == "adicionar") {
                $promocaoController->getModalAdicionar();
            } else if ($modo == "produto") {
                $promocaoController->getInfoProduto();
            } else if ($modo == "gravar") {
                $promocaoController->gravar();
            } else if ($modo == "editar") {
                $promocaoController->getModalEditar();
            } else if ($modo == "atualizar") {
                $promocaoController->atualizar();
            } else if ($modo == "excluir") {
                $promocaoController->excluir();
            } else if ($modo == "ativar") {
                $promocaoController->ativar();
            }
            
        } else if ($tipo == "produto_do_mes") { // Rotas para o controlador de produto do mês.
            include_once("produto_do_mes_controller.php");
            $produtoDoMesController = new ProdutoDoMesController();
            if ($modo == "ativar") {
                $produtoDoMesController->getModalAtivar();
            } else if ($modo == "salvar") {
                $produtoDoMesController->salvar();
            }
            
        } else if ($tipo == "categoria") { // Rotas para o controlador de categorias.
            include_once("categoria_controller.php");
            $categoriaController = new CategoriaController();
            if ($modo == "adicionar") {
                $categoriaController->montarFormAdicionar();
            } else if ($modo == "gravar") {
                $categoriaController->gravar();
            } else if ($modo == "editar") {
                $categoriaController->montarFormEditar();
            } else if ($modo == "atualizar") {
                $categoriaController->atualizar();
            } else if ($modo == "html") {
                $categoriaController->montarHtmlId();
            } else if ($modo == "ativar") {
                $categoriaController->ativar();
            } else if ($modo == "excluir") {
                $categoriaController->excluir();
            }
            
        } else if ($tipo == "subcategoria") { // Rotas para o controlador de subcategorias.
            include_once("subcategoria_controller.php");
            $subcategoriaController = new SubcategoriaController();
            if ($modo == "selecionar") {
                $subcategoriaController->selecionar();
            } else if ($modo == "adicionar") {
                $subcategoriaController->montarFormAdicionar();
            } else if ($modo == "gravar") {
                $subcategoriaController->gravar();
            } else if ($modo == "editar") {
                $subcategoriaController->montarFormEditar();
            } else if ($modo == "atualizar") {
                $subcategoriaController->atualizar();
            }  else if ($modo == "html") {
                $subcategoriaController->montarHtmlId();
            } else if ($modo == "ativar") {
                $subcategoriaController->ativar();
            } else if ($modo == "excluir") {
                $subcategoriaController->excluir();
            }
            
        } else if ($tipo == "produto") {
            include_once("produto_controller.php");
            $produtoController = new ProdutoController();
            if ($modo == "adicionar") {
                $produtoController->getModalAdicionar();
            } else if ($modo == "produto") {
                $produtoController->getInfoProduto();
            } else if ($modo == "gravar") {
                $produtoController->gravar();
            } else if ($modo == "editar") {
                $produtoController->getModalEditar();
            } else if ($modo == "atualizar") {
                $produtoController->atualizar();
            } else if ($modo == "excluir") {
                $produtoController->excluir();
            } else if ($modo == "ativar") {
                $produtoController->ativar();
            } else if ($modo == "listar") {
                $produtoController->listar();
            } else if ($modo == "detalhes") {
                $produtoController->mostrarDetalhes();
            }
        }
    }
?>