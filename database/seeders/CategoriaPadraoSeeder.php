<?php

namespace Database\Seeders;

use Api\Models\Categoria\Categoria;
use Illuminate\Database\Seeder;

/**
 * Categorias padrão do sistema (id_usuario = null).
 * Pode rodar várias vezes sem duplicar.
 */
class CategoriaPadraoSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            // Despesas
            ['Alimentação', 'despesa', 'utensils',      '#E67E22'],
            ['Moradia',     'despesa', 'house',         '#8E44AD'],
            ['Transporte',  'despesa', 'car',           '#2980B9'],
            ['Saúde',       'despesa', 'heart-pulse',   '#C0392B'],
            ['Educação',    'despesa', 'graduation-cap','#16A085'],
            ['Lazer',       'despesa', 'gamepad',       '#F1C40F'],
            ['Compras',     'despesa', 'bag-shopping',  '#D35400'],
            ['Contas',      'despesa', 'file-invoice',  '#7F8C8D'],
            ['Outros',      'despesa', 'ellipsis',      '#95A5A6'],
            // Receitas
            ['Salário',      'receita', 'money-bill',   '#27AE60'],
            ['Freelance',    'receita', 'laptop',       '#2ECC71'],
            ['Investimentos','receita', 'chart-line',   '#1ABC9C'],
            ['Outros',       'receita', 'ellipsis',     '#95A5A6'],
        ];

        foreach ($categorias as [$nome, $tipo, $icone, $cor]) {
            Categoria::updateOrCreate(
                ['id_usuario' => null, 'nome_categoria' => $nome, 'tipo_categoria' => $tipo],
                ['icone_categoria' => $icone, 'cor_categoria' => $cor],
            );
        }
    }
}
