<?php

namespace Api\Models\Enums;

enum TipoConta: string
{
    case Corrente     = 'corrente';
    case Poupanca     = 'poupanca';
    case Carteira     = 'carteira';
    case Investimento = 'investimento';
}
