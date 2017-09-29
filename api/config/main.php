<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),    
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'api\modules\v1\Module'
        ]
    ],
    'components' => [        
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => ['v1/user'], 
                    'patterns' => [
                      'POST login' => 'login',
                      'POST set-fcm' => 'set-fcm',
                      'POST signup' => 'signup',
                      'POST verify-otp' => 'verify-otp',
                      'POST isregister' => 'isregister',
                      'POST device-update' => 'device-update',
                      'POST resend-otp' => 'resend-otp',
                      'POST profile' => 'profile',
                      'POST reset-password' => 'reset-password',
					  'POST user-info' => 'user-info',
					  'POST state-by-country' => 'state-by-country',
					  'POST city-by-state' => 'city-by-state',
					  'POST change-password' => 'change-password',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]
                    
                ], 
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => ['v1/subscription'], 
                    'patterns' => [
                      'POST list' => 'list',
					  'POST edit-list' => 'edit-list',
                      'POST add' => 'add',
                      'POST subscription-update' => 'subscription-update',
                      'POST pause-subscription' => 'pause-subscription',
                      'POST single-tap-pause' => 'single-tap-pause',
                      'POST single-tap-unpause' => 'single-tap-unpause',
					  'POST product-for-pause' => 'product-for-pause',
					  'POST unsubscribe' => 'unsubscribe',
					  'POST change-quantity' => 'change-quantity',
					  'POST short-sms-response' => 'short-sms-response',
					  
					  
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]
                    
                ],
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => ['v1/product'], 
                    'patterns' => [
                      'POST product-list' => 'product-list',
                      'POST detail' => 'detail',
					  'POST search-product' => 'search-product',
					  'POST get-product-list' => 'get-product-list',
					  'POST get-product-sort' => 'get-product-sort',
					  'POST get-sort-list' => 'get-sort-list',
					  
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]
                    
                ],
				[
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => ['v1/category'], 
                    'patterns' => [
                      'POST category-list' => 'category-list',
					  'POST category-name' => 'category-name',
					  
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]
                    
                ],
				[
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => ['v1/faq'], 
                    'patterns' => [
                      'POST faq-list' => 'faq-list',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]
                    
                ],
				[
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => ['v1/contactus'], 
                    'patterns' => [
                      'POST add-contact' => 'add-contact',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]
                    
                ], 
				[
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => ['v1/delivery'], 
                    'patterns' => [
                       'POST customer-list' => 'customer-list',
					   'POST delivery-product-details' => 'delivery-product-details',
					   'POST delivery-status' => 'delivery-status',
					   'POST add-coordinate-map' => 'add-coordinate-map',
					   'POST delivery-update' => 'delivery-update',
					   'POST delivery-boy-interval' => 'delivery-boy-interval',
					   'POST customer-interval' => 'customer-interval',
					   'POST delivery-boy-location' => 'delivery-boy-location',
					   'POST customer-location' => 'customer-location',
					   'POST pending-customer-list' => 'pending-customer-list',
					  
					  
					  
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]
                    
                ],
				[
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => ['v1/account'], 
                    'patterns' => [
                      'POST account-list' => 'account-list',
					  'POST account-view' => 'account-view',
					  'POST order-history' => 'order-history',
					  'POST account-update' => 'account-update',
					  'POST customer-bill' => 'customer-bill',
					  'POST generate-checksum' => 'generate-checksum',
					  'POST verify-checksum' => 'verify-checksum',
					  'POST verify-transaction' => 'verify-transaction',					  
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]
                    
                ]
            ],        
        ]
    ],
    'params' => $params,
];



