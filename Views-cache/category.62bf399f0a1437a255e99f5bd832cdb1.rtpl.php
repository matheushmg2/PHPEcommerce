<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h2><?php echo htmlspecialchars( $Categorias["descategorias"], ENT_COMPAT, 'UTF-8', FALSE ); ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <?php $counter1=-1;  if( isset($Produtos) && ( is_array($Produtos) || $Produtos instanceof Traversable ) && sizeof($Produtos) ) foreach( $Produtos as $key1 => $value1 ){ $counter1++; ?>

            <div class="col-md-3 col-sm-6">
                <div class="single-shop-product">
                    <div class="product-upper">
                        <img src="<?php echo htmlspecialchars( $value1["desphoto"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" alt="">
                    </div>
                    <h2><a href="/products/<?php echo htmlspecialchars( $value1["desurl"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><?php echo htmlspecialchars( $value1["desprodutos"], ENT_COMPAT, 'UTF-8', FALSE ); ?></a></h2>
                    <div class="product-carousel-price">
                        <ins>R$<?php echo formatarValores($value1["vlpreco"]); ?></ins>
                    </div>

                    <div class="product-option-shop">
                        <a class="add_to_cart_button" data-quantity="1" data-product_sku="" data-product_id="70" rel="nofollow" href="/cart/<?php echo htmlspecialchars( $value1["idprodutos"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/add">Comprar</a>
                    </div>
                </div>
            </div>
            <?php } ?>


        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="product-pagination text-center">
                    <nav>
                        <ul class="pagination">
                            <?php $counter1=-1;  if( isset($Paginacoes) && ( is_array($Paginacoes) || $Paginacoes instanceof Traversable ) && sizeof($Paginacoes) ) foreach( $Paginacoes as $key1 => $value1 ){ $counter1++; ?>

                            <li><a href="<?php echo htmlspecialchars( $value1["links"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><?php echo htmlspecialchars( $value1["pg"], ENT_COMPAT, 'UTF-8', FALSE ); ?></a></li>
                            <?php } ?>

                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>