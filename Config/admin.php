<?php
$admin = [
    'General' => [
        "Users" => [
            "icon"   => "fa fa-user",
            "submenu" => [
                "Add A New User" => [
                    "action" => "Admin/Create/User",
                ],
                "List Users" => [
                    "action" => "Admin/Read/User",
                ]
            ]
        ],

    ],
    'Account' => [
        "Logout" => [
            "action" => "Login/Logout",
            "icon"   => "fa fa-sign-out"
        ]
    ]
];
