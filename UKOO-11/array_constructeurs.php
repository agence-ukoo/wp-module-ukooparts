<?php

// $manufacturer = [];

// $manufacturer[] = 'Aprilia', 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/99/Aprilia-logo.svg/320px-Aprilia-logo.svg.png'
// $manufacturer[] = 'Yamaha', 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8b/Yamaha_Motor_Logo_%28full%29.svg/640px-Yamaha_Motor_Logo_%28full%29.svg.png'
// $manufacturer[] = 'Triumph', 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/60/Logo_Triumph.svg/320px-Logo_Triumph.svg.png'
// $manufacturer[] = 'Kawazaki', 'https://resources.kawasaki.eu/prd/assets/images/kawasaki-logo.svg'
// $manufacturer[] = 'Ducati';
// $manufacturer[] = 'Harley Davidson';
// $manufacturer[] = 'Suzuki';

//  echo json_encode($manufacturer);



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
        'logo' => 'https://resources.kawasaki.eu/prd/assets/images/kawasaki-logo.svg'
    ],
    [
        'brand' => 'Harley Davidson', 
        'logo' => 'https://upload.wikimedia.org/wikipedia/fr/thumb/b/b2/Harley-Davidson.svg/300px-Harley-Davidson.svg.png'
    ]
];

echo json_encode($manufacturers);

?>