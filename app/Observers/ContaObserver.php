<?php

namespace App\Observers;

use App\Models\Conta;
use Illuminate\Support\Facades\Log;

class ContaObserver
{
    public function updated(Conta $conta): void
    {
        Log::info("Conta atualizada: $conta->numero_conta");
    }
}
