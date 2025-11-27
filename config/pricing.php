<?php

return [
    'plans' => [
        'basic' => [
            'price' => 0,
            'display_name' => 'Basic',
            'features' => [
                'Limited downloads per day',
                'Core categories access',
                'Personal, non-commercial license',
                'Community support',
                'Cancel anytime',
                'No credit card required'
            ]
        ],
        'entrepreneur' => [
            'price' => 259000,
            'display_name' => 'Entrepreneur',
            'features' => [
                'All categories & tags',
                'Unlimited previews & collections',
                'Commercial license (individual)',
                'Priority support',
                'Early access to new packs',
                'Cancel anytime',
                'Monthly billing',
                'Taxes included where applicable',
                'Fair use policy'
            ]
        ],
        'professional' => [
            'price' => 399000,
            'display_name' => 'Professional',
            'features' => [
                'Everything in Entrepreneur',
                '3 team seats included',
                'Client project licensing',
                'Top-priority support & SLA',
                'Company billing & invoices',
                'Cancel anytime'
            ]
        ],
    ],
];