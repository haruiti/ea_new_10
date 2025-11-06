<?php

return [

    /*
     * Caminho completo para o arquivo JSON da conta de serviço do Google.
     */
    'service_account_credentials_json' => env('GOOGLE_APPLICATION_CREDENTIALS'),

    /*
     * ID do calendário Google (geralmente o e-mail da conta).
     */
    'calendar_id' => env('GOOGLE_CALENDAR_ID'),
];
