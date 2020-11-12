<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Produtos da Categoria <?php echo htmlspecialchars( $Categorias["descategorias"], ENT_COMPAT, 'UTF-8', FALSE ); ?>

        </h1>
        <ol class="breadcrumb">
            <li><a href="/Adm"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="/Adm/categories">Categorias</a></li>
            <li><a href="/Adm/categories/<?php echo htmlspecialchars( $Categorias["idcategorias"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">$Categorias.descategorias}</a></li>
            <li class="active"><a href="/Adm/categories/<?php echo htmlspecialchars( $Categorias["idcategorias"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/products">Produtos</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Todos os Produtos</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Nome do Produto</th>
                                    <th style="width: 240px">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter1=-1;  if( isset($ProdutosNaoRelacionado) && ( is_array($ProdutosNaoRelacionado) || $ProdutosNaoRelacionado instanceof Traversable ) && sizeof($ProdutosNaoRelacionado) ) foreach( $ProdutosNaoRelacionado as $key1 => $value1 ){ $counter1++; ?>

                                <tr>
                                    <td><?php echo htmlspecialchars( $value1["idprodutos"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                                    <td><?php echo htmlspecialchars( $value1["desprodutos"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                                    <td>
                                        <a href="/Adm/categories/<?php echo htmlspecialchars( $Categorias["idcategorias"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/products/<?php echo htmlspecialchars( $value1["idprodutos"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/add" class="btn btn-primary btn-xs pull-right"><i class="fa fa-arrow-right"></i> Adicionar</a>
                                    </td>
                                </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Produtos na Categoria <?php echo htmlspecialchars( $Categorias["descategorias"], ENT_COMPAT, 'UTF-8', FALSE ); ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Nome do Produto</th>
                                    <th style="width: 240px">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter1=-1;  if( isset($ProdutosRelacionado) && ( is_array($ProdutosRelacionado) || $ProdutosRelacionado instanceof Traversable ) && sizeof($ProdutosRelacionado) ) foreach( $ProdutosRelacionado as $key1 => $value1 ){ $counter1++; ?>

                                <tr>
                                    <td><?php echo htmlspecialchars( $value1["idprodutos"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                                    <td><?php echo htmlspecialchars( $value1["desprodutos"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                                    <td>
                                        <a href="/Adm/categories/<?php echo htmlspecialchars( $Categorias["idcategorias"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/products/<?php echo htmlspecialchars( $value1["idprodutos"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/remove" class="btn btn-primary btn-xs pull-right"><i class="fa fa-arrow-left"></i> Remover</a>
                                    </td>
                                </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->