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
    'Forum' => [
        "Boards" => [
            "icon"   => "fa fa-files-o",
            "submenu" => [
                "Add A New Board" => [
                    "action" => "Admin/Create/Board",
                ],
                "List Boards" => [
                    "action" => "Admin/Read/Board",
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
