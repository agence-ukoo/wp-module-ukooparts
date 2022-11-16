<?php 

$manufacturers = [
    [
        'brand' => 'Aprilia',
        'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/99/Aprilia-logo.svg/320px-Aprilia-logo.svg.png'
    ],
    [
        'brand' => 'Honda',
        'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Honda.svg/275px-Honda.svg.png'
    ],
    [
        'brand' => 'Yamaha',
        'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8b/Yamaha_Motor_Logo_%28full%29.svg/640px-Yamaha_Motor_Logo_%28full%29.svg.png'
    ],
    [
        'brand' => 'Triumph',
        'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/60/Logo_Triumph.svg/320px-Logo_Triumph.svg.png'
    ],
    [
        'brand' => 'Kawazaki',
        'logo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSUlEpUc2hF60gGanZjRrPoYTjvqTL0xWUCmA&usqp=CAU'
    ],
    [
        'brand' => 'Harley Davidson',
        'logo' => 'https://upload.wikimedia.org/wikipedia/fr/thumb/b/b2/Harley-Davidson.svg/300px-Harley-Davidson.svg.png'
    ]
];
 
echo json_encode($manufacturers);

?>