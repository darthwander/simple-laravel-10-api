<?php

dataset('saldos',
    [
        [0], [10], [100], [1000]
    ]
);

dataset('numerosContasInvalidas',
    [
        [null], [0], [-1], [-10], [-100]
    ]
);

dataset('numerosContasInexistentes',
    [
        [null], [0], [-1], [-10], [-100], [30000000]
    ]
);