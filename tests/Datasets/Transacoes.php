<?php

dataset('formasPagamento',
    [
        ['P'], ['D'], ['C']
    ]);

dataset('formasPagamentoComTaxa',
    [
        ['P', 0], ['D', 0.03], ['C', 0.05] 
    ]);

dataset('formasPagamentoValoresInvalidos',
    [
        ['P', 0], ['D', 0], ['C', 0],
        ['P', -1], ['D', -1], ['C', -1],
        ['P', null], ['D', null], ['C', null],
        ['P', ''], ['D', ''], ['C', ''] 
    ]);

dataset('formasPagamentoInvalidos',
    [
        ['A'], [0.01], [1], [null], ['']
    ]);