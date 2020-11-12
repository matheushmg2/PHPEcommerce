<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Pedido N°<?php echo htmlspecialchars( $Pedidos["idperdidos"], ENT_COMPAT, 'UTF-8', FALSE ); ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/Adm"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="/Adm/orders">Pedidos</a></li>
            <li class="active"><a href="/Adm/orders/<?php echo htmlspecialchars( $Pedidos["idperdidos"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">Pedido N°<?php echo htmlspecialchars( $Pedidos["idperdidos"], ENT_COMPAT, 'UTF-8', FALSE ); ?></a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Editar Status do Pedido</h3>
                    </div>
                    <!-- /.box-header -->
                    <?php if( $MsgErro != '' ){ ?>
                    <div class="alert alert-danger alert-dismissible" style="margin:10px">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <p><?php echo htmlspecialchars( $MsgErro, ENT_COMPAT, 'UTF-8', FALSE ); ?></p>
                    </div>
                    <?php } ?> <?php if( $MsgSucesso != '' ){ ?>
                    <div class="alert alert-success alert-dismissible" style="margin:10px">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <p><?php echo htmlspecialchars( $MsgSucesso, ENT_COMPAT, 'UTF-8', FALSE ); ?></p>
                    </div>
                    <?php } ?>
                    <!-- form start -->
                    <form role="form" action="/Adm/orders/<?php echo htmlspecialchars( $Pedidos["idperdidos"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/status" method="post">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="desproduct">Status do Pedido</label>
                                <select class="form-control" name="idstatus">
                            <?php $counter1=-1;  if( isset($Status) && ( is_array($Status) || $Status instanceof Traversable ) && sizeof($Status) ) foreach( $Status as $key1 => $value1 ){ $counter1++; ?>
                            <option <?php if( $value1["idstatus"] === $Pedidos["tb_perdidosstatus_idstatus"] ){ ?>selected="selected"<?php } ?> value="<?php echo htmlspecialchars( $value1["idstatus"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><?php echo htmlspecialchars( $value1["desstatus"], ENT_COMPAT, 'UTF-8', FALSE ); ?></option>
                            <?php } ?>
                        </select>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->

    <div class="clearfix"></div>

</div>
<!-- /.content-wrapper -->