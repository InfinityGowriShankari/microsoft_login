<?php

return [
    'client_id' => env('MICROSOFT_CLIENT_ID', 'c3a5eccc-7de8-49d7-b3e5-bb6f96a310ef'),
    'client_secret' => env('MICROSOFT_CLIENT_SECRET', '1rV8Q~xaFn5bC_svpsACfVNGfAsJqXsbbDLmoaz-'),
    'tenant_id' => env('MICROSOFT_TENANT_ID', '05469f5d-5114-4146-bc17-9d9640b1a2f7'),
    'redirect_uri' => env('MICROSOFT_SSO_REDIRECT_URI', 'http://localhost:8000/microsoft/sso/callback'),

];

?>