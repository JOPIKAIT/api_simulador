<?php

namespace App\Models\GuiaPagamento;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuiaPagamento extends Model
{
    use HasFactory;

    protected $table = 'guia_pagamento';
    protected $fillable = ['entidade', 'rupe', 'valor', 'situacao', 'gpt', 'data_vencimento'];
}
