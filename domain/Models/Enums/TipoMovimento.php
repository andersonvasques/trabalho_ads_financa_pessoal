<?php

namespace Api\Models\Enums;

/**
 * Tipo usado por categorias e lançamentos.
 */
enum TipoMovimento: string
{
    case Receita = 'receita';
    case Despesa = 'despesa';
}
