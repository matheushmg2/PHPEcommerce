<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Lista de Categorias
        </h1>
        <ol class="breadcrumb">
            <li><a href="/Adm"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><a href="/Adm/categories">Categorias</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    <div class="box-header">
                        <a href="/Adm/categories/create" class="btn btn-success">Cadastrar Categoria</a>
                        <div class="box-tools">
                            <form action="/Adm/categories">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="search" class="form-control pull-right" placeholder="Search" value="<?php echo htmlspecialchars( $search, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="box-body no-padding">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Nome da Categoria</th>
                                    <th style="width: 240px">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter1=-1;  if( isset($Categorias) && ( is_array($Categorias) || $Categorias instanceof Traversable ) && sizeof($Categorias) ) foreach( $Categorias as $key1 => $value1 ){ $counter1++; ?>

                                <tr>
                                    <td><?php echo htmlspecialchars( $value1["idcategorias"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                                    <td><?php echo htmlspecialchars( $value1["descategorias"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                                    <td>
                                        <a href="/Adm/categories/<?php echo htmlspecialchars( $value1["idcategorias"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/products" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Produtos</a>
                                        <a href="/Adm/categories/<?php echo htmlspecialchars( $value1["idcategorias"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Editar</a>
                                        <a href="/Adm/categories/<?php echo htmlspecialchars( $value1["idcategorias"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/delete" onclick="return confirm('Deseja realmente excluir este registro?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Excluir</a>
                                    </td>
                                </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                        <ul class="pagination pagination-sm no-margin pull-right">
                            <?php $counter1=-1;  if( isset($Paginas) && ( is_array($Paginas) || $Paginas instanceof Traversable ) && sizeof($Paginas) ) foreach( $Paginas as $key1 => $value1 ){ $counter1++; ?>

                            <li><a href="<?php echo htmlspecialchars( $value1["href"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><?php echo htmlspecialchars( $value1["text"], ENT_COMPAT, 'UTF-8', FALSE ); ?></a></li>
                            <?php } ?>

                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->