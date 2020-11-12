<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="product-big-title-area">
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
                <?php if( $MsgPerfil != '' ){ ?>

                <div class="alert alert-success">
                    <?php echo htmlspecialchars( $MsgPerfil, ENT_COMPAT, 'UTF-8', FALSE ); ?>

                </div>
                <?php } ?> <?php if( $MsgErrorPerfil != '' ){ ?>

                <div class="alert alert-danger">
                    <?php echo htmlspecialchars( $MsgErrorPerfil, ENT_COMPAT, 'UTF-8', FALSE ); ?>

                </div>
                <?php } ?>

                <form method="post" action="/profile">
                    <div class="form-group">
                        <label for="despessoas">Nome completo</label>
                        <input type="text" class="form-control" id="despessoas" name="despessoas" placeholder="Digite o nome aqui" value="<?php echo htmlspecialchars( $Usuario["despessoas"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                    </div>
                    <div class="form-group">
                        <label for="desemail">E-mail</label>
                        <input type="email" class="form-control" id="desemail" name="desemail" placeholder="Digite o e-mail aqui" value="<?php echo htmlspecialchars( $Usuario["desemail"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                    </div>
                    <div class="form-group">
                        <label for="nrphone">Telefone</label>
                        <input type="tel" class="form-control" id="nrphone" name="nrphone" placeholder="Digite o telefone aqui" value="<?php echo htmlspecialchars( $Usuario["nrphone"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>