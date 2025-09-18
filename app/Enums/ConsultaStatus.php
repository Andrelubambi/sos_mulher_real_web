<?php

namespace App\Enums;

enum ConsultaStatus: string
{
    case PENDENTE = 'pendente';
    case CONFIRMADA = 'confirmada';
    case CANCELADA = 'cancelada';
    case REALIZADA = 'realizada';
}