<?php

namespace Hcode\Models;
use Hcode\DB\Sql;
use Hcode\Models;

class PedidosStatus extends Models {

    const EM_ABERTO = 1;
    const AGUARDANDO_PAGAMENTO = 2;
    const PAGO = 3;
    const ENTREGUE = 4;

    public static function listaPedidosStatdosAll()
    {
        $sql = new Sql();
        return $sql->select("SELECT * FROM tb_perdidosstatus ORDER BY desstatus");
    }

}