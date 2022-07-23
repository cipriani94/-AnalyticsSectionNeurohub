<?php
return [
    'entryNameValues' => [
        'Sito', //Singolare
        'Sito' //Plurale
    ],
    'sidebarName' => [
        'name' => 'Siti',
    ],
    'requestMessage' => [
        'domain' => 'Nome del dominio',
        'authorization' => 'Md5 hash'
    ],
    'listOperation' => [
        'label' => [
            'domain' => 'Dominio',
            'authorization' => 'Md5 hash',
            'createdAt' => 'Creato il',
            'updatedAt' => 'Modificato il'
        ]
    ],
    'createOperation' => [
        'label' => [
            'domain' => 'Dominio',
            'authorization' => 'Md5 hash',
        ],
        'placeholder' => [
            'domain' => 'Inserire il nome del dominio',
            'authorization' => 'Inserire il codice md5',
        ],
    ],
    'updateOperation' => [
        'label' => [
            'domain' => 'Dominio',
            'authorization' => 'Md5 hash',
        ],
        'placeholder' => [
            'domain' => 'Aggiorna il nome del dominio',
            'authorization' => 'Aggiorna il codice md5',
        ],
    ],
    'createMessage' => [
        'createSuccess' => 'Dominio memorizzato'
    ]
];
