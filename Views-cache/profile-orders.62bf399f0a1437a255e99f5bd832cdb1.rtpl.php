<?php if(!class_exists('Rain\Tpl')){exit;}?>

<div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h2>Minha Conta</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">                
            <div class="col-md-3">
                <?php require $this->checkTemplate("profile-menu");?>

            </div>
            <div class="col-md-9">
                
                <div class="cart-collaterals">
                    <h2>Meus Pedidos</h2>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Valor Total</th>
                            <th>Status</th>
                            <th>Endereço</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $counter1=-1;  if( isset($UsuarioPedidos) && ( is_array($UsuarioPedidos) || $UsuarioPedidos instanceof Traversable ) && sizeof($UsuarioPedidos) ) foreach( $UsuarioPedidos as $key1 => $value1 ){ $counter1++; ?>

                        <tr>
                            <th scope="row"><?php echo htmlspecialchars( $value1["idperdidos"], ENT_COMPAT, 'UTF-8', FALSE ); ?></th>
                            <td>R$<?php echo htmlspecialchars( $value1["vltotal"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                            <td><?php echo htmlspecialchars( $value1["desstatus"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                            <td><?php echo htmlspecialchars( $value1["desenderecos"], ENT_COMPAT, 'UTF-8', FALSE ); ?>, <?php echo htmlspecialchars( $value1["desdistritos"], ENT_COMPAT, 'UTF-8', FALSE ); ?>, <?php echo htmlspecialchars( $value1["descidades"], ENT_COMPAT, 'UTF-8', FALSE ); ?> - , <?php echo htmlspecialchars( $value1["desestados"], ENT_COMPAT, 'UTF-8', FALSE ); ?> CEP: <?php echo htmlspecialchars( $value1["descodigopostal"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                            <td style="width:222px;">
                                <a class="btn btn-success" href="/payment/<?php echo htmlspecialchars( $value1["idperdidos"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" role="button">Imprimir Boleto</a>
                                <a class="btn btn-default" href="/profile/orders/<?php echo htmlspecialchars( $value1["idperdidos"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" role="button">Detalhes</a>
                            </td>
                        </tr>
                        <?php }else{ ?>

                        <div class="alert alert-info">
                            Nenhum pedido foi encontrado.
                        </div>
                        <?php } ?>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>