<?php

dataset('formasPagamento',
    [
        ['P'], ['D'], ['C']
    ]
);

dataset('formasPagamentoComTaxa',
    [
        ['P', 0], ['D', 0.03], ['C', 0.05] 
    ]
);

dataset('valoresInvalidos',
    [
        [0], [-1], [null], ['']
    ]    
);

dataset('formasPagamentoInvalidos',
    [
        ['A'], [0.01], [1], [null], ['']
    ]
);

dataset('contasInvalidas',
    [
        [999998], [999997], [0], [-1], ['A'], [0.01], [null], ['']
    ]
);