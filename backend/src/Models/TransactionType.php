<?php
declare(strict_types=1);

namespace App\Models;

enum TransactionType: string
{
    case INCOME = 'income';
    case EXPENSE = 'expense';
}
