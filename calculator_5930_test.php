<?php

namespace Tests\Unit\Calculator\Casino\Game;

use Framework\Calculator\Calculator;
use Framework\Generator\Generator;
use Framework\Calculator\Casino\Game\Calculator_5930;
use Framework\Support\Facades\Facade;
use Framework\Foundation\RandomTest;
use PHPUnit\Framework\TestCase;

class Calculator_5930_Test extends TestCase
{
    private $oMod;
    private $aSetting = [
        # 各元件賠率
        'Rate' => [
            1 => [0, 0, 0, 0, 10, 20, 30, 45, 60, 100, 150, 250, 500, 5000],
            2 => [0, 0, 0, 0, 10, 20, 30, 45, 60, 100, 150, 250, 500, 5000],
            3 => [0, 0, 0, 0, 10, 20, 30, 45, 60, 100, 150, 250, 500, 5000],
            4 => [0, 0, 0, 0, 10, 20, 30, 45, 60, 100, 150, 250, 500, 5000],
            5 => [0, 0, 0, 0, 4, 8, 16, 24, 40, 60, 80, 150, 300, 3000],
            6 => [0, 0, 0, 0, 4, 8, 16, 24, 40, 60, 80, 150, 300, 3000],
            7 => [0, 0, 0, 0, 4, 8, 16, 24, 40, 60, 80, 150, 300, 3000],
            8 => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            9 => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            10 => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ],
        // 免費遊戲 連消次數 => 倍率
        'FreeGameDouble' => [
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 5,
            5 => 8,
            6 => 10,
        ],
    ];
    public function setUp()
    {
        #清除Facde以免影響後面TestCase
        Facade::clearResolvedInstances("Random");
        $aFacades['Random']     = new RandomTest();
        $aFacades['Calculator'] = new Calculator();
        Facade::setFacadeApplication($aFacades);
        $this->oMod = new Calculator_5930();
    }

    /**
     * 連線一條
     * @test
     * @group Calculator
     */
    public function calculateOneLines()
    {
        //Arrange
        $aSetting  = $this->aSetting;
        $aCardInfo = [
            'Cards' => [
                [
                    1, 1, 1, 1, 1, 2, 1, 1,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 1, 1, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                ],
                [
                    2, 6, 2, 6, 2, 2, 1, 1,
                    6, 2, 2, 4, 5, 5, 5, 5,
                    2, 7, 3, 3, 2, 2, 2, 2,
                    2, 1, 1, 4, 5, 5, 5, 5,
                    2, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    2, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5
                ]
            ],
            'Lines' => [
                [
                    [
                        'ElementID' => 1,
                        'Grids' => [0,1,2,3,4],
                        'GridNum' => 5,
                    ],
                ],
            ],
            'Special' => [],
        ];
        $aBetInfo  = [
            'event'          => true,
            'LineBet'        => 50,
            'BetBalanceRate' => '1',
            'BetCreditRate'  => '1',
            'BetCredit'      => 50,
            'BetLevel'       => 1,
        ];
        $aAccumulationInfo = [];
        $aInput    = [
            'Cards' => [
                [
                    0 => '1-1-1-1-1-2-1-1',
                    1 => '2-2-2-4-5-5-5-5',
                    2 => '6-7-3-3-2-2-2-2',
                    3 => '2-1-1-4-5-5-5-5',
                    4 => '6-7-3-3-2-2-2-2',
                    5 => '2-2-2-4-5-5-5-5',
                    6 => '6-7-3-3-2-2-2-2',
                    7 => '2-2-2-4-5-5-5-5',
                ],
                [
                    0 => '2-6-2-6-2-2-1-1',
                    1 => '6-2-2-4-5-5-5-5',
                    2 => '2-7-3-3-2-2-2-2',
                    3 => '2-1-1-4-5-5-5-5',
                    4 => '2-7-3-3-2-2-2-2',
                    5 => '2-2-2-4-5-5-5-5',
                    6 => '2-7-3-3-2-2-2-2',
                    7 => '2-2-2-4-5-5-5-5',
                ]
            ],
            'Lines' => [
                [
                    [
                        'ElementID' => 1,
                        'Grids' => [0,1,2,3,4],
                        'GridNum' => 5,
                        'Payoff' => 10,
                    ],
                ],
            ],
            'PayTotal' => 10,
            'BetTotal' => 50,
            'Special'  => [],
            'FreeGame' => [],
            'FreeGameSpin' => [],
            'AccumulationInfo' => [
                'FreeGame' => [],
            ],
        ];


        //Act
        $aOutput = $this->oMod->calculate($aSetting, $aCardInfo, $aBetInfo, $aAccumulationInfo);
        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 無連線
     * @test
     * @group Calculator
     */
    public function calculateNoLines()
    {
        //Arrange
        $aSetting  = $this->aSetting;
        $aCardInfo = [
            'Cards' => [
                [
                    6, 2, 6, 2, 6, 2, 1, 1,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 1, 1, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                ],
            ],
            'Lines' => [[]],
            'Special' => [],
        ];
        $aBetInfo  = [
            'event'          => true,
            'LineBet'        => 50,
            'BetBalanceRate' => '1',
            'BetCreditRate'  => '1',
            'BetCredit'      => 50,
            'BetLevel'       => 1,
        ];
        $aAccumulationInfo = [];
        $aInput    = [
            'Cards' => [
                [
                    0 => '6-2-6-2-6-2-1-1',
                    1 => '2-2-2-4-5-5-5-5',
                    2 => '6-7-3-3-2-2-2-2',
                    3 => '2-1-1-4-5-5-5-5',
                    4 => '6-7-3-3-2-2-2-2',
                    5 => '2-2-2-4-5-5-5-5',
                    6 => '6-7-3-3-2-2-2-2',
                    7 => '2-2-2-4-5-5-5-5',
                ],
            ],
            'Lines' => [[]],
            'PayTotal' => 0,
            'BetTotal' => 50,
            'Special'  => [],
            'FreeGame' => [],
            'FreeGameSpin' => [],
            'AccumulationInfo' => [
                'FreeGame' => [],
            ],
        ];

        //Act
        $aOutput = $this->oMod->calculate($aSetting, $aCardInfo, $aBetInfo, $aAccumulationInfo);
        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 連線三條
     * @test
     * @group Calculator
     */
    public function calculateThreeLines()
    {
        //Arrange
        $aSetting  = $this->aSetting;
        $aCardInfo = [
            'Cards' => [
                [
                    1, 1, 1, 1, 1, 2, 1, 3,
                    2, 2, 2, 4, 5, 5, 5, 3,
                    6, 7, 3, 3, 2, 2, 2, 3,
                    2, 1, 1, 4, 5, 5, 5, 3,
                    6, 7, 3, 3, 2, 2, 2, 3,
                    2, 2, 2, 4, 5, 5, 2, 2,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5
                ],
                [
                    4, 4, 4, 4, 4, 4, 4, 4,
                    4, 2, 2, 7, 2, 7, 1, 4,
                    4, 4, 3, 1, 3, 3, 7, 4,
                    4, 5, 2, 3, 4, 2, 2, 4,
                    4, 5, 3, 4, 5, 2, 2, 4,
                    4, 5, 3, 5, 5, 5, 4, 2,
                    4, 5, 5, 2, 5, 5, 5, 6,
                    4, 1, 2, 2, 6, 2, 6, 2
                ]
            ],
            'Lines' => [
                [
                    [
                        'ElementID' => 1,
                        'Grids' => [0,1,2,3,4],
                        'GridNum' => 5,
                    ],
                    [
                        'ElementID' => 3,
                        'Grids' => [7,15,23,31,39],
                        'GridNum' => 5,
                    ],
                    [
                        'ElementID' => 2,
                        'Grids' => [36,37,38,46,47,52,53,54,55],
                        'GridNum' => 9,
                    ],
                ],
                [],
            ],
            'Special' => [],
        ];
        $aBetInfo  = [
            'event'          => true,
            'LineBet'        => 50,
            'BetBalanceRate' => '1',
            'BetCreditRate'  => '1',
            'BetCredit'      => 50,
            'BetLevel'       => 1,
        ];
        $aAccumulationInfo = [];
        $aInput    = [
            'Cards' => [
                [
                    0 => '1-1-1-1-1-2-1-3',
                    1 => '2-2-2-4-5-5-5-3',
                    2 => '6-7-3-3-2-2-2-3',
                    3 => '2-1-1-4-5-5-5-3',
                    4 => '6-7-3-3-2-2-2-3',
                    5 => '2-2-2-4-5-5-2-2',
                    6 => '6-7-3-3-2-2-2-2',
                    7 => '2-2-2-4-5-5-5-5',
                ],
                [
                    0 => '4-4-4-4-4-4-4-4',
                    1 => '4-2-2-7-2-7-1-4',
                    2 => '4-4-3-1-3-3-7-4',
                    3 => '4-5-2-3-4-2-2-4',
                    4 => '4-5-3-4-5-2-2-4',
                    5 => '4-5-3-5-5-5-4-2',
                    6 => '4-5-5-2-5-5-5-6',
                    7 => '4-1-2-2-6-2-6-2',
                ]
            ],
            'Lines' => [
                [
                    [
                        'ElementID' => 1,
                        'Grids' => [0,1,2,3,4],
                        'GridNum' => 5,
                        'Payoff' => 10,
                    ],
                    [
                        'ElementID' => 3,
                        'Grids' => [7,15,23,31,39],
                        'GridNum' => 5,
                        'Payoff' => 10,
                    ],
                    [
                        'ElementID' => 2,
                        'Grids' => [36,37,38,46,47,52,53,54,55],
                        'GridNum' => 9,
                        'Payoff' => 60,
                    ],
                ],
                [],
            ],
            'PayTotal' => 80,
            'BetTotal' => 50,
            'Special'  => [],
            'FreeGame' => [],
            'FreeGameSpin' => [],
            'AccumulationInfo' => [
                'FreeGame' => [],
            ],
        ];

        //Act
        $aOutput = $this->oMod->calculate($aSetting, $aCardInfo, $aBetInfo, $aAccumulationInfo);
        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 中免費遊戲
     * @test
     * @group Calculator
     */
    public function calculateHitFreeGame()
    {
        //Arrange
        $aSetting  = $this->aSetting;
        $aCardInfo = [
            'Cards' => [
                [
                    8, 1, 4, 3, 3, 5, 4, 5,
                    6, 5, 6, 3, 5, 7, 1, 3,
                    5, 4, 7, 1, 5, 6, 5, 4,
                    6, 1, 1, 5, 4, 4, 6, 7,
                    5, 3, 3, 7, 6, 4, 5, 7,
                    6, 1, 6, 4, 3, 1, 6, 2,
                    7, 5, 6, 1, 6, 4, 7, 5,
                    4, 7, 4, 1, 5, 2, 5, 5,
                ],
            ],
            'Lines' => [[]],
            'Special' => [
                'Done' => false,
                'SpecialType' => 8,
                'Grid' => 0,
            ],
        ];
        $aBetInfo  = [
            'event'          => true,
            'LineBet'        => 50,
            'BetBalanceRate' => '1',
            'BetCreditRate'  => '1',
            'BetCredit'      => 50,
            'BetLevel'       => 1,
        ];
        $aAccumulationInfo = [];
        $aInput    = [
            'Cards' => [
                [
                    0 => '8-1-4-3-3-5-4-5',
                    1 => '6-5-6-3-5-7-1-3',
                    2 => '5-4-7-1-5-6-5-4',
                    3 => '6-1-1-5-4-4-6-7',
                    4 => '5-3-3-7-6-4-5-7',
                    5 => '6-1-6-4-3-1-6-2',
                    6 => '7-5-6-1-6-4-7-5',
                    7 => '4-7-4-1-5-2-5-5',
                ],
            ],
            'Lines' => [[]],
            'PayTotal' => 0,
            'BetTotal' => 50,
            'Special'  => [
                'Done' => true,
                'SpecialType' => 8,
                'Grid' => 0,
            ],
            'FreeGame' => [
                'ID' => 8,
                'Grid' => 0,
                'FreeGameTime' => 10,
                "FreeGamePayoffTotal" => 0,
            ],
            'AccumulationInfo' => [
                'FreeGame' => [
                    'FreeGameTime' => 10,
                    "FreeGamePayoffTotal" => 0,
                ],
            ],
            'FreeGameSpin' => [],
        ];


        //Act
        $aOutput = $this->oMod->calculate($aSetting, $aCardInfo, $aBetInfo, $aAccumulationInfo);
        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 免費遊戲中 無連線
     * @test
     * @group Calculator
     */
    public function calculateFreeGameNoLine()
    {
        //Arrange
        $aSetting  = $this->aSetting;
        $aCardInfo = [
            'Cards' => [
                [
                    2, 1, 4, 3, 3, 5, 4, 5,
                    6, 5, 6, 3, 5, 7, 1, 3,
                    5, 4, 7, 1, 5, 6, 5, 4,
                    6, 1, 1, 5, 4, 4, 6, 7,
                    5, 3, 3, 7, 6, 4, 5, 7,
                    6, 1, 6, 4, 3, 1, 6, 2,
                    7, 5, 6, 1, 6, 4, 7, 5,
                    4, 7, 4, 1, 5, 2, 5, 5,
                ],
            ],
            'Lines' => [[]],
            'Special' => [],
        ];
        $aBetInfo  = [
            'event'          => true,
            'LineBet'        => 50,
            'BetBalanceRate' => '1',
            'BetCreditRate'  => '1',
            'BetCredit'      => 0,
            'BetLevel'       => 1,
        ];
        $aAccumulationInfo = [
            'FreeGame' => [
                'FreeGameTime' => 10,
                "FreeGamePayoffTotal" => 0,
            ],
        ];
        $aInput    = [
            'Cards' => [
                [
                    0 => '2-1-4-3-3-5-4-5',
                    1 => '6-5-6-3-5-7-1-3',
                    2 => '5-4-7-1-5-6-5-4',
                    3 => '6-1-1-5-4-4-6-7',
                    4 => '5-3-3-7-6-4-5-7',
                    5 => '6-1-6-4-3-1-6-2',
                    6 => '7-5-6-1-6-4-7-5',
                    7 => '4-7-4-1-5-2-5-5',
                ],
            ],
            'Lines' => [[]],
            'PayTotal' => 0,
            'BetTotal' => 0,
            'Special'  => [],
            'FreeGame' => [],
            'AccumulationInfo' => [
                'FreeGame' => [
                    'FreeGameTime' => 9,
                    "FreeGamePayoffTotal" => 0,
                ],
            ],
            'FreeGameSpin' => [
                'FreeGameTime' => 9,
                "FreeGamePayoffTotal" => 0,
            ],
        ];

        //Act
        $aOutput = $this->oMod->calculate($aSetting, $aCardInfo, $aBetInfo, $aAccumulationInfo);
        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 免費遊戲中 1條連線
     * @test
     * @group Calculator
     */
    public function calculateFreeGameOneLine()
    {
        //Arrange
        $aSetting  = $this->aSetting;
        $aCardInfo = [
            'Cards' => [
                [
                    1, 1, 1, 1, 1, 2, 1, 1,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 1, 1, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                ],
                [
                    2, 6, 2, 6, 2, 2, 1, 1,
                    6, 2, 2, 4, 5, 5, 5, 5,
                    2, 7, 3, 3, 2, 2, 2, 2,
                    2, 1, 1, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5
                ]
            ],
            'Lines' => [
                [
                    [
                        'ElementID' => 1,
                        'Grids' => [0,1,2,3,4],
                        'GridNum' => 5,
                    ],
                ],
            ],
            'Special' => [],
        ];
        $aBetInfo  = [
            'event'          => true,
            'LineBet'        => 50,
            'BetBalanceRate' => '1',
            'BetCreditRate'  => '1',
            'BetCredit'      => 0,
            'BetLevel'       => 1,
        ];
        $aAccumulationInfo = [
            'FreeGame' => [
                'FreeGameTime' => 9,
                "FreeGamePayoffTotal" => 0,
            ],
        ];
        $aInput    = [
            'Cards' => [
                [
                    0 => '1-1-1-1-1-2-1-1',
                    1 => '2-2-2-4-5-5-5-5',
                    2 => '6-7-3-3-2-2-2-2',
                    3 => '2-1-1-4-5-5-5-5',
                    4 => '6-7-3-3-2-2-2-2',
                    5 => '2-2-2-4-5-5-5-5',
                    6 => '6-7-3-3-2-2-2-2',
                    7 => '2-2-2-4-5-5-5-5',
                ],
                [
                    0 => '2-6-2-6-2-2-1-1',
                    1 => '6-2-2-4-5-5-5-5',
                    2 => '2-7-3-3-2-2-2-2',
                    3 => '2-1-1-4-5-5-5-5',
                    4 => '6-7-3-3-2-2-2-2',
                    5 => '2-2-2-4-5-5-5-5',
                    6 => '6-7-3-3-2-2-2-2',
                    7 => '2-2-2-4-5-5-5-5',
                ]
            ],
            'Lines' => [
                [
                    [
                        'ElementID' => 1,
                        'Grids' => [0,1,2,3,4],
                        'GridNum' => 5,
                        'Payoff' => 10,
                        'DoubleTime' => 1,
                    ],
                ],
            ],
            'PayTotal' => 10,
            'BetTotal' => 0,
            'Special'  => [],
            'FreeGame' => [],
            'AccumulationInfo' => [
                'FreeGame' => [
                    'FreeGameTime' => 8,
                    "FreeGamePayoffTotal" => 10,
                ],
            ],
            'FreeGameSpin' => [
                'FreeGameTime' => 8,
                "FreeGamePayoffTotal" => 10,
            ],
        ];

        //Act
        $aOutput = $this->oMod->calculate($aSetting, $aCardInfo, $aBetInfo, $aAccumulationInfo);
        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 免費遊戲中 3條連線
     * @test
     * @group Calculator
     */
    public function calculateFreeGameThreeLine()
    {
        //Arrange
        $aSetting  = $this->aSetting;
        $aCardInfo = [
            'Cards' => [
                [
                    1, 1, 1, 1, 1, 2, 1, 3,
                    2, 2, 2, 4, 5, 5, 5, 3,
                    6, 7, 3, 3, 2, 2, 2, 3,
                    2, 1, 1, 4, 5, 5, 5, 3,
                    6, 7, 3, 3, 2, 2, 2, 3,
                    2, 2, 2, 4, 5, 5, 2, 2,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5
                ],
                [
                    4, 4, 4, 1, 2, 5, 4, 4,
                    3, 2, 2, 7, 2, 7, 1, 5,
                    4, 4, 3, 1, 3, 3, 7, 4,
                    2, 5, 2, 3, 4, 2, 2, 3,
                    4, 5, 3, 4, 5, 2, 2, 4,
                    1, 5, 3, 5, 5, 5, 4, 2,
                    4, 5, 5, 2, 5, 5, 5, 6,
                    4, 1, 2, 2, 6, 2, 6, 2
                ]
            ],
            'Lines' => [
                [
                    [
                        'ElementID' => 1,
                        'Grids' => [0,1,2,3,4],
                        'GridNum' => 5,
                    ],
                    [
                        'ElementID' => 3,
                        'Grids' => [7,15,23,31,39],
                        'GridNum' => 5,
                    ],
                    [
                        'ElementID' => 2,
                        'Grids' => [36,37,38,46,47,52,53,54,55],
                        'GridNum' => 9,
                    ],
                ],
            ],
            'Special' => [],
        ];
        $aBetInfo  = [
            'event'          => true,
            'LineBet'        => 50,
            'BetBalanceRate' => '1',
            'BetCreditRate'  => '1',
            'BetCredit'      => 0,
            'BetLevel'       => 1,
        ];
        $aAccumulationInfo = [
            'FreeGame' => [
                'FreeGameTime' => 8,
                "FreeGamePayoffTotal" => 10,
            ],
        ];
        $aInput    = [
            'Cards' => [
                [
                    0 => '1-1-1-1-1-2-1-3',
                    1 => '2-2-2-4-5-5-5-3',
                    2 => '6-7-3-3-2-2-2-3',
                    3 => '2-1-1-4-5-5-5-3',
                    4 => '6-7-3-3-2-2-2-3',
                    5 => '2-2-2-4-5-5-2-2',
                    6 => '6-7-3-3-2-2-2-2',
                    7 => '2-2-2-4-5-5-5-5',
                ],
                [
                    0 => '4-4-4-1-2-5-4-4',
                    1 => '3-2-2-7-2-7-1-5',
                    2 => '4-4-3-1-3-3-7-4',
                    3 => '2-5-2-3-4-2-2-3',
                    4 => '4-5-3-4-5-2-2-4',
                    5 => '1-5-3-5-5-5-4-2',
                    6 => '4-5-5-2-5-5-5-6',
                    7 => '4-1-2-2-6-2-6-2',
                ]
            ],
            'Lines' => [
                [
                    [
                        'ElementID' => 1,
                        'Grids' => [0,1,2,3,4],
                        'GridNum' => 5,
                        'Payoff' => 10,
                        'DoubleTime' => 1,
                    ],
                    [
                        'ElementID' => 3,
                        'Grids' => [7,15,23,31,39],
                        'GridNum' => 5,
                        'Payoff' => 10,
                        'DoubleTime' => 1,
                    ],
                    [
                        'ElementID' => 2,
                        'Grids' => [36,37,38,46,47,52,53,54,55],
                        'GridNum' => 9,
                        'Payoff' => 60,
                        'DoubleTime' => 1,
                    ],
                ],
            ],
            'PayTotal' => 80,
            'BetTotal' => 0,
            'Special'  => [],
            'FreeGame' => [],
            'AccumulationInfo' => [
                'FreeGame' => [
                    'FreeGameTime' => 7,
                    "FreeGamePayoffTotal" => 90,
                ],
            ],
            'FreeGameSpin' => [
                'FreeGameTime' => 7,
                "FreeGamePayoffTotal" => 90,
            ],
        ];

        //Act
        $aOutput = $this->oMod->calculate($aSetting, $aCardInfo, $aBetInfo, $aAccumulationInfo);
        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

   /**
     * 免費遊戲中 兩倍
     * @test
     * @group Calculator
     */
    public function calculateFreeGameTwoDouble()
    {
        //Arrange
        $aSetting  = $this->aSetting;
        $aCardInfo = [
            'Cards' => [
                [
                    1, 1, 1, 1, 1, 2, 1, 3,
                    2, 2, 2, 4, 5, 5, 5, 3,
                    6, 7, 3, 3, 2, 2, 2, 3,
                    2, 1, 1, 4, 5, 5, 5, 3,
                    6, 7, 3, 3, 2, 2, 2, 3,
                    2, 2, 2, 4, 5, 5, 2, 2,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5
                ],
                [
                    4, 4, 4, 5, 4, 5, 3, 4,
                    3, 3, 4, 5, 7, 1, 5, 7,
                    3, 6, 4, 1, 5, 6, 6, 7,
                    4, 5, 7, 5, 4, 4, 5, 2,
                    1, 4, 1, 7, 6, 4, 6, 5,
                    1, 1, 3, 4, 3, 1, 7, 5,
                    6, 3, 6, 6, 1, 6, 4, 5,
                    5, 1, 5, 7, 4, 1, 5, 2,
                ]
            ],
            'Lines' => [
                [
                    [
                        'ElementID' => 1,
                        'Grids' => [0,1,2,3,4],
                        'GridNum' => 5,
                    ],
                    [
                        'ElementID' => 3,
                        'Grids' => [7,15,23,31,39],
                        'GridNum' => 5,
                    ],
                    [
                        'ElementID' => 2,
                        'Grids' => [36,37,38,46,47,52,53,54,55],
                        'GridNum' => 9,
                    ],
                ],
                [
                    [
                        'ElementID' => 4,
                        'Grids' => [0,1,2,10,18],
                        'GridNum' => 5,
                    ],
                ],
            ],
            'Special' => [],
        ];
        $aBetInfo  = [
            'event'          => true,
            'LineBet'        => 50,
            'BetBalanceRate' => '1',
            'BetCreditRate'  => '1',
            'BetCredit'      => 0,
            'BetLevel'       => 1,
        ];
        $aAccumulationInfo = [
            'FreeGame' => [
                'FreeGameTime' => 8,
                "FreeGamePayoffTotal" => 10,
            ],
        ];
        $aInput    = [
            'Cards' => [
                [
                    0 => '1-1-1-1-1-2-1-3',
                    1 => '2-2-2-4-5-5-5-3',
                    2 => '6-7-3-3-2-2-2-3',
                    3 => '2-1-1-4-5-5-5-3',
                    4 => '6-7-3-3-2-2-2-3',
                    5 => '2-2-2-4-5-5-2-2',
                    6 => '6-7-3-3-2-2-2-2',
                    7 => '2-2-2-4-5-5-5-5',
                ],
                [
                    0 => '4-4-4-5-4-5-3-4',
                    1 => '3-3-4-5-7-1-5-7',
                    2 => '3-6-4-1-5-6-6-7',
                    3 => '4-5-7-5-4-4-5-2',
                    4 => '1-4-1-7-6-4-6-5',
                    5 => '1-1-3-4-3-1-7-5',
                    6 => '6-3-6-6-1-6-4-5',
                    7 => '5-1-5-7-4-1-5-2',
                ]
            ],
            'Lines' => [
                [
                    [
                        'ElementID' => 1,
                        'Grids' => [0,1,2,3,4],
                        'GridNum' => 5,
                        'Payoff' => 10,
                        'DoubleTime' => 1,
                    ],
                    [
                        'ElementID' => 3,
                        'Grids' => [7,15,23,31,39],
                        'GridNum' => 5,
                        'Payoff' => 10,
                        'DoubleTime' => 1,
                    ],
                    [
                        'ElementID' => 2,
                        'Grids' => [36,37,38,46,47,52,53,54,55],
                        'GridNum' => 9,
                        'Payoff' => 60,
                        'DoubleTime' => 1,
                    ],
                ],
                [
                    [
                        'ElementID' => 4,
                        'Grids' => [0,1,2,10,18],
                        'GridNum' => 5,
                        'Payoff' => 20,
                        'DoubleTime' => 2,
                    ],
                ],
            ],
            'PayTotal' => 100,
            'BetTotal' => 0,
            'Special'  => [],
            'FreeGame' => [],
            'AccumulationInfo' => [
                'FreeGame' => [
                    'FreeGameTime' => 7,
                    "FreeGamePayoffTotal" => 110,
                ],
            ],
            'FreeGameSpin' => [
                'FreeGameTime' => 7,
                "FreeGamePayoffTotal" => 110,
            ],
        ];

        //Act
        $aOutput = $this->oMod->calculate($aSetting, $aCardInfo, $aBetInfo, $aAccumulationInfo);
        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

   /**
     * 免費遊戲中 四倍
     * @test
     * @group Calculator
     */
    public function calculateFreeGameFourDouble()
    {
        //Arrange
        $aSetting  = $this->aSetting;
        $aCardInfo = [
            'Cards' => [
                [
                    4, 4, 4, 5, 4, 5, 3, 4,
                    3, 3, 4, 5, 7, 1, 5, 7,
                    3, 6, 4, 1, 5, 6, 6, 7,
                    4, 5, 7, 5, 4, 4, 5, 2,
                    1, 4, 1, 7, 6, 4, 6, 5,
                    1, 1, 3, 4, 3, 1, 7, 5,
                    6, 3, 6, 6, 1, 6, 4, 5,
                    5, 1, 5, 7, 4, 1, 5, 2,
                ],
                [
                    4, 4, 4, 5, 4, 5, 3, 4,
                    3, 3, 4, 5, 7, 1, 5, 7,
                    3, 6, 4, 1, 5, 6, 6, 7,
                    4, 5, 7, 5, 4, 4, 5, 2,
                    1, 4, 1, 7, 6, 4, 6, 5,
                    1, 1, 3, 4, 3, 1, 7, 5,
                    6, 3, 6, 6, 1, 6, 4, 5,
                    5, 1, 5, 7, 4, 1, 5, 2,
                ],
                [
                    4, 4, 4, 5, 4, 5, 3, 4,
                    3, 3, 4, 5, 7, 1, 5, 7,
                    3, 6, 4, 1, 5, 6, 6, 7,
                    4, 5, 7, 5, 4, 4, 5, 2,
                    1, 4, 1, 7, 6, 4, 6, 5,
                    1, 1, 3, 4, 3, 1, 7, 5,
                    6, 3, 6, 6, 1, 6, 4, 5,
                    5, 1, 5, 7, 4, 1, 5, 2,
                ],
                [
                    4, 4, 4, 5, 4, 5, 3, 4,
                    3, 3, 4, 5, 7, 1, 5, 7,
                    3, 6, 4, 1, 5, 6, 6, 7,
                    4, 5, 7, 5, 4, 4, 5, 2,
                    1, 4, 1, 7, 6, 4, 6, 5,
                    1, 1, 3, 4, 3, 1, 7, 5,
                    6, 3, 6, 6, 1, 6, 4, 5,
                    5, 1, 5, 7, 4, 1, 5, 2,
                ]
            ],
            'Lines' => [
                [
                    [
                        'ElementID' => 4,
                        'Grids' => [0,1,2,10,18],
                        'GridNum' => 5,
                    ],
                ],
                [
                    [
                        'ElementID' => 4,
                        'Grids' => [0,1,2,10,18],
                        'GridNum' => 5,
                    ],
                ],
                [
                    [
                        'ElementID' => 4,
                        'Grids' => [0,1,2,10,18],
                        'GridNum' => 5,
                    ],
                ],
                [
                    [
                        'ElementID' => 4,
                        'Grids' => [0,1,2,10,18],
                        'GridNum' => 5,
                    ],
                ],
            ],
            'Special' => [],
        ];
        $aBetInfo  = [
            'event'          => true,
            'LineBet'        => 50,
            'BetBalanceRate' => '1',
            'BetCreditRate'  => '1',
            'BetCredit'      => 0,
            'BetLevel'       => 1,
        ];
        $aAccumulationInfo = [
            'FreeGame' => [
                'FreeGameTime' => 8,
                "FreeGamePayoffTotal" => 10,
            ],
        ];
        $aInput    = [
            'Cards' => [
                [
                    0 => '4-4-4-5-4-5-3-4',
                    1 => '3-3-4-5-7-1-5-7',
                    2 => '3-6-4-1-5-6-6-7',
                    3 => '4-5-7-5-4-4-5-2',
                    4 => '1-4-1-7-6-4-6-5',
                    5 => '1-1-3-4-3-1-7-5',
                    6 => '6-3-6-6-1-6-4-5',
                    7 => '5-1-5-7-4-1-5-2',
                ],
                [
                    0 => '4-4-4-5-4-5-3-4',
                    1 => '3-3-4-5-7-1-5-7',
                    2 => '3-6-4-1-5-6-6-7',
                    3 => '4-5-7-5-4-4-5-2',
                    4 => '1-4-1-7-6-4-6-5',
                    5 => '1-1-3-4-3-1-7-5',
                    6 => '6-3-6-6-1-6-4-5',
                    7 => '5-1-5-7-4-1-5-2',
                ],
                [
                    0 => '4-4-4-5-4-5-3-4',
                    1 => '3-3-4-5-7-1-5-7',
                    2 => '3-6-4-1-5-6-6-7',
                    3 => '4-5-7-5-4-4-5-2',
                    4 => '1-4-1-7-6-4-6-5',
                    5 => '1-1-3-4-3-1-7-5',
                    6 => '6-3-6-6-1-6-4-5',
                    7 => '5-1-5-7-4-1-5-2',
                ],
                [
                    0 => '4-4-4-5-4-5-3-4',
                    1 => '3-3-4-5-7-1-5-7',
                    2 => '3-6-4-1-5-6-6-7',
                    3 => '4-5-7-5-4-4-5-2',
                    4 => '1-4-1-7-6-4-6-5',
                    5 => '1-1-3-4-3-1-7-5',
                    6 => '6-3-6-6-1-6-4-5',
                    7 => '5-1-5-7-4-1-5-2',
                ],
            ],
            'Lines' => [
                [
                    [
                        'ElementID' => 4,
                        'Grids' => [0,1,2,10,18],
                        'GridNum' => 5,
                        'Payoff' => 10,
                        'DoubleTime' => 1,
                    ],
                ],
                [
                    [
                        'ElementID' => 4,
                        'Grids' => [0,1,2,10,18],
                        'GridNum' => 5,
                        'Payoff' => 20,
                        'DoubleTime' => 2,
                    ],
                ],
                [
                    [
                        'ElementID' => 4,
                        'Grids' => [0,1,2,10,18],
                        'GridNum' => 5,
                        'Payoff' => 30,
                        'DoubleTime' => 3,
                    ],
                ],
                [
                    [
                        'ElementID' => 4,
                        'Grids' => [0,1,2,10,18],
                        'GridNum' => 5,
                        'Payoff' => 50,
                        'DoubleTime' => 5,
                    ],
                ],
            ],
            'PayTotal' => 110,
            'BetTotal' => 0,
            'Special'  => [],
            'FreeGame' => [],
            'AccumulationInfo' => [
                'FreeGame' => [
                    'FreeGameTime' => 7,
                    "FreeGamePayoffTotal" => 120,
                ],
            ],
            'FreeGameSpin' => [
                'FreeGameTime' => 7,
                "FreeGamePayoffTotal" => 120,
            ],
        ];

        //Act
        $aOutput = $this->oMod->calculate($aSetting, $aCardInfo, $aBetInfo, $aAccumulationInfo);
        //Assert
        $this->assertEquals($aInput, $aOutput);
    }
}