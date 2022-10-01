<?php

namespace Tests\Unit\Calculator\Casino\Game;

use Framework\Calculator\Calculator;
use Framework\Calculator\Casino\Game\Calculator_5221;
use Framework\Foundation\RandomTest;
use Framework\Generator\Generator;
use Framework\Support\Facades\Facade;
use PHPUnit\Framework\TestCase;

class Calculator_5221_Test extends TestCase
{
    private $oCalculator5221;
    private $aBetInfo = [
        'BetLevel' => 10,
        'LineNum' => 20,
        'LineBet' => 20,
        'BetBalanceRate' => '1',
        'BetCreditRate' => '1',
        'BetCredit' => 200,
    ];
    private $aSetting = [
        'CalculateByLevel' => true,
        # 一般遊戲最大列數
        'MaxRows' => 3,
        # 一般遊戲最大軸數
        'MaxCols' => 5,
        # 可變標記元件ID
        'WildCard' => [9],
        # 可變標記不可替換元件ID
        'NotWild' => [10],
        # 可變標記元件賠率
        'WildRate' => [],
        // 一般滾輪
        'RollerContainer' => [
            0 => [
                0 => [11, 11, 5, 8, 14, 14, 8, 7, 13, 13, 7, 6, 12, 12, 6, 7, 13, 13, 7, 8, 14, 14, 8, 5, 11, 11, 5, 8, 10, 5, 8, 14, 14, 8, 5, 9, 9, 9, 5, 7, 13, 13, 7, 6, 12, 12, 5, 7, 13, 13, 8, 6, 12, 12, 6, 5, 11, 11, 5, 8, 14, 14, 8, 7, 13, 13, 6, 7, 13, 13, 8, 6, 12, 12, 6, 8, 10, 5, 8, 11, 11, 6, 6, 12, 12, 6, 5, 13, 13, 7, 8, 13, 13, 7, 5],
                // 0 => [10,10,10,10],
                1 => [12, 12, 6, 7, 13, 13, 7, 8, 14, 14, 8, 8, 10, 6, 8, 9, 9, 9, 8, 5, 11, 11, 8, 7, 13, 13, 8, 6, 9, 9, 9, 6, 7, 13, 13, 8, 5, 11, 11, 5, 7, 13, 13, 7, 6, 12, 12, 6, 7, 13, 13, 7, 6, 12, 12, 6, 7, 13, 13, 7, 8, 14, 14, 8, 5, 11, 11, 5, 8, 14, 14, 8, 7, 13, 13, 7, 6, 9, 9, 8, 5, 11, 11, 5, 7, 13, 13, 7, 6],
                2 => [14, 14, 8, 5, 11, 11, 5, 7, 13, 13, 7, 6, 12, 12, 6, 7, 13, 13, 7, 6, 12, 12, 6, 5, 10, 8, 7, 13, 13, 7, 6, 12, 12, 6, 7, 13, 13, 7, 5, 9, 9, 9, 8, 6, 12, 12, 6, 5, 9, 9, 9, 8, 5, 11, 11, 5, 7, 13, 13, 7, 5, 11, 11, 5, 6, 9, 9, 9, 8, 8, 14, 14, 8, 5, 11, 11, 5, 8, 9, 9, 9, 5, 7, 13, 13, 7, 8],
                3 => [13, 13, 7, 7, 12, 12, 5, 8, 11, 11, 6, 6, 13, 13, 8, 6, 12, 12, 6, 5, 13, 13, 6, 5, 11, 11, 5, 8, 9, 9, 9, 8, 5, 13, 13, 7, 5, 12, 12, 6, 5, 9, 9, 9, 5, 8, 13, 13, 6, 8, 14, 14, 8, 7, 13, 13, 7, 5, 10, 6, 5, 10, 5, 8, 10, 5, 8, 10, 6, 5, 10, 8, 6, 12, 12, 5, 7, 13, 13, 8, 7, 13, 13, 5, 6, 12, 12, 6, 7],
                4 => [13, 13, 7, 6, 12, 12, 6, 7, 13, 13, 5, 7, 13, 13, 7, 6, 12, 12, 6, 5, 13, 13, 8, 5, 11, 11, 5, 7, 13, 13, 8, 5, 10, 5, 6, 10, 8, 6, 10, 5, 8, 10, 6, 5, 10, 8, 5, 10, 6, 8, 14, 14, 8, 7, 13, 13, 7, 7, 13, 13, 8, 5, 11, 11, 5, 6, 9, 9, 9, 9, 5, 7, 13, 13, 7, 7],
            ],
        ],
        'SpecialRoller' => [
            // MainGame
            0 => [
                1 => [2, 2, 2, 3, 3, 3, 4, 4, 4, 9, 9, 9, 2, 2, 2, 4, 4, 4, 3, 3, 3, 2, 2, 2, 4, 4, 4, 3, 3, 3, 4, 4, 4, 2, 2, 2, 3, 3, 3, 4, 4, 4, 2, 2, 2, 3, 3, 3, 4, 4, 4, 2, 2, 2, 3, 3, 3, 4, 4, 4, 2, 2, 2, 3, 3, 3, 4, 4, 4, 3, 3, 3, 4, 4, 4, 2, 2, 2, 3, 3, 3, 4, 4, 4, 3, 3, 3, 4, 4, 4, 2, 2, 2, 3, 3, 3, 4, 4, 4, 1, 1, 4, 4, 3, 3],
                2 => [1, 1, 1, 3, 3, 3, 4, 4, 4, 3, 3, 3, 4, 4, 4, 9, 9, 9, 3, 3, 3, 1, 1, 1, 4, 4, 4, 3, 3, 3, 4, 4, 4, 1, 1, 1, 4, 4, 4, 3, 3, 3, 4, 4, 4, 1, 1, 1, 4, 4, 4, 3, 3, 3, 1, 1, 1, 3, 3, 3, 4, 4, 4, 3, 3, 3, 2, 2, 3, 3, 4, 4],
                3 => [1, 1, 1, 2, 2, 2, 3, 3, 3, 4, 4, 4, 9, 9, 9, 1, 1, 1, 2, 2, 2, 4, 4, 4, 2, 2, 1, 1, 9, 9, 4, 4, 4, 2, 2, 2, 4, 4, 4, 2, 2, 3, 3, 1, 1, 2, 2, 3, 3, 4, 4],
                4 => [1, 1, 1, 2, 2, 2, 4, 4, 4, 3, 3, 3, 1, 1, 1, 9, 9, 9, 3, 3, 3, 4, 4, 2, 2, 9, 9, 3, 3, 4, 4, 3, 3, 4, 4, 2, 2, 4, 4, 1, 1, 9, 9, 3, 3, 4, 4, 3, 3, 4, 4],
                9 => [4, 4, 4, 9, 9, 9, 4, 4, 4, 2, 2, 3, 3, 4, 4, 2, 2, 3, 3, 4, 4, 3, 3, 4, 4, 3, 3, 4, 4, 3, 3, 4, 4, 3, 3, 4, 4, 3, 3, 4, 4, 3, 3, 4, 4, 3, 3, 4, 4, 3, 3, 4, 4, 3, 3, 4, 4, 3, 3, 4, 4, 3, 3, 2, 2, 3, 3, 1, 1, 2, 2, 3, 3, 1, 1, 4, 4, 3, 3, 4, 4, 3, 3, 4, 4, 3, 3, 4, 4, 3, 3, 2, 2, 3, 3, 1, 1, 2, 2, 4, 4, 2, 2, 3, 3, 4, 4, 2, 2, 4, 4, 3, 3, 2, 2, 3, 3, 4, 4, 2, 2, 3, 3, 4, 4, 2, 2, 3, 3, 4, 4, 2, 2, 3, 3],
            ],
            //FreeGame
            1 => [
                1 => [1, 1, 1, 2, 2, 2, 3, 3, 3, 4, 4, 4, 9, 9, 9, 4, 4, 4, 3, 3, 3, 4, 4, 4, 2, 2, 2, 10, 10, 10, 4, 4, 4, 2, 2, 2, 3, 3, 3, 4, 4, 4, 2, 2, 2, 3, 3, 3, 1, 1, 2, 2, 10, 10, 4, 4, 4, 9, 9, 3, 3, 1, 1, 2, 2, 10, 10, 4, 4, 1, 1, 3, 3, 9, 9, 2, 2, 1, 1, 4, 4],
                2 => [1, 1, 1, 2, 2, 2, 3, 3, 3, 4, 4, 4, 9, 9, 9, 4, 4, 4, 1, 1, 1, 3, 3, 3, 2, 2, 1, 1, 9, 9, 4, 4, 4, 1, 1, 1, 10, 10, 10, 4, 4, 4, 3, 3, 3, 2, 2, 4, 4, 2, 2, 1, 1, 1, 10, 10, 10, 4, 4, 4, 9, 9, 1, 1, 2, 2, 3, 3, 2, 2, 1, 1, 10, 10, 10, 4, 4, 2, 2, 3, 3, 9, 9, 1, 1, 2, 2],
                3 => [1, 1, 1, 3, 3, 3, 2, 2, 2, 10, 10, 10, 4, 4, 4, 9, 9, 9, 4, 4, 4, 1, 1, 1, 3, 3, 4, 4, 3, 3, 2, 2, 2, 3, 3, 4, 4, 9, 9, 1, 1, 1, 10, 10, 10, 4, 4, 3, 3, 1, 1, 9, 9, 4, 4, 2, 2, 10, 10, 10, 4, 4, 3, 3, 2, 2, 9, 9, 1, 1, 3, 3],
                4 => [1, 1, 1, 2, 2, 2, 3, 3, 3, 1, 1, 1, 9, 9, 9, 3, 3, 3, 2, 2, 2, 10, 10, 10, 4, 4, 1, 1, 9, 9, 2, 2, 2, 1, 1, 1, 10, 10, 10, 4, 4, 1, 1, 9, 9, 2, 2, 2, 10, 10, 10, 4, 4, 3, 3, 2, 2, 10, 10, 10, 4, 4, 1, 1, 9, 9],
                9 => [3, 3, 4, 4, 3, 3, 4, 4, 9, 9, 4, 4, 3, 3, 4, 4, 3, 3, 10, 10, 10, 4, 4, 2, 2, 3, 3, 10, 10, 4, 4, 3, 3, 10, 10, 4, 4, 3, 3, 10, 10, 4, 4, 3, 3, 4, 4, 3, 3, 10, 10, 4, 4, 2, 2, 3, 3, 4, 4, 2, 2, 4, 4, 3, 3, 10, 10, 4, 4, 2, 2, 3, 3, 4, 4, 2, 2, 4, 4, 3, 3, 10, 10, 4, 4, 3, 3, 1, 1, 4, 4, 2, 2],
            ],
        ],

        'Line' => [
            1 => [2, 5, 8, 11, 14],
            2 => [1, 4, 7, 10, 13],
            3 => [3, 6, 9, 12, 15],
            4 => [1, 5, 9, 11, 13],
            5 => [3, 5, 7, 11, 15],
            6 => [2, 4, 7, 10, 14],
            7 => [2, 6, 9, 12, 14],
            8 => [1, 4, 8, 12, 15],
            9 => [3, 6, 8, 10, 13],
            10 => [2, 6, 8, 10, 14],
            11 => [2, 4, 8, 12, 14],
            12 => [1, 5, 8, 11, 13],
            13 => [3, 5, 8, 11, 15],
            14 => [1, 5, 7, 11, 13],
            15 => [3, 5, 9, 11, 15],
            16 => [2, 5, 7, 11, 14],
            17 => [2, 5, 9, 11, 14],
            18 => [1, 4, 9, 10, 13],
            19 => [3, 6, 7, 12, 15],
            20 => [1, 6, 9, 12, 13],
        ],

        'Rate' => [
            1 => [0, 0, 0, 20, 50, 100],
            2 => [0, 0, 0, 15, 45, 70],
            3 => [0, 0, 0, 10, 25, 40],
            4 => [0, 0, 0, 5, 20, 30],
            5 => [0, 0, 0, 3, 5, 12],
            6 => [0, 0, 0, 3, 5, 10],
            7 => [0, 0, 0, 2, 3, 5],
            8 => [0, 0, 0, 2, 3, 5],
            9 => [0, 0, 0, 25, 100, 300],
            10 => [0, 0, 0, 0, 0, 0],
        ],
        // 分散標記元件賠率
        'ScatterRate' => [
            10 => [0, 0, 0, 2, 10, 50],
        ],

        'FreeGameTime' => [
            3 => 5,
            4 => 7,
            5 => 10,
        ],

        /* ----------遊戲暫存預設值 初始化用---------- */
        'AccumulationInfo' => [
            'FreeGame' => [
                'HitFree' => false,
                "ID" => 0,
                "FirstFreeGameTime" => 0,
                "FreeGameTime" => 0,
                "FreeGamePayoffTotal" => 0,
                "AddFreeGame" => 0,
                "Lead" => 0,
            ],
            'RollerNumber' => 0,
        ],
    ];

    public function setUp()
    {
        # 清除Facde以免影響後面TestCase
        Facade::clearResolvedInstances();
        $aFacades['Random'] = new RandomTest();
        $aFacades['Calculator'] = new Calculator();
        $aFacades['Generator'] = new Generator();
        Facade::setFacadeApplication($aFacades);
        $this->oCalculator5221 = new Calculator_5221();
    }

    /**
     * 沒有連線
     * @test
     * @group Calculator
     */
    public function calculateNoLine()
    {

        $aCardInfo = [
            'Flash' => '7-3-8,6-5-10,10-8-2,6-2-8,8-9-3',
            'Program' => '7-3-8-6-5-10-10-8-2-6-2-8-8-9-3',
            'CardsCount' => [
                'GridNum' => [
                    '7' => 1,
                    '3' => 2,
                    '8' => 4,
                    '6' => 2,
                    '5' => 1,
                    '10' => 2,
                    '2' => 2,
                    '9' => 1,
                ],
                'Grid' => [
                    '7' => '1',
                    '3' => '2,15',
                    '8' => '3,8,12,13',
                    '6' => '4,10',
                    '5' => '5',
                    '10' => '6,7',
                    '2' => '9,11',
                    '9' => '14',
                ],
            ],
            'AxisLocation' => '50-241-123-7-99',

        ];

        $aAccumulationInfo = [
            'RollerNumber' => 0,
            'FreeGame' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 0,
            ],
        ];
        $aExpected = [
            'GamePayTotal' => [
                'Lines' => 0,
            ],
            'PayTotal' => 0,
            'AllPayTotal' => 0,
            'Lines' => [
            ],
            'Scatter' => [
                'ID' => 0,
                'GridNum' => 0,
                'Grids' => '',
                'Payoff' => 0,
            ],
            'AccumulationInfo' => [
                'FreeGame' => [
                    'FreeGameTime' => 0,
                    'FreeGamePayoffTotal' => 0,
                    'AddFreeGame' => 0,
                    'Lead' => 0,
                ],
                'RollerNumber' => 0,
            ],
            'FreeGameSpin' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
            ],
            'FreeGame' => [
                'HitFree' => false,
                'ID' => 0,
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 0,
            ],
        ];

        //Act
        $aActual = $this->oCalculator5221->calculate($this->aSetting, $aCardInfo, $this->aBetInfo, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * 一般遊戲有連線(1條)
     * @test
     * @group Calculator
     */
    public function calculateOneLine()
    {
        $aCardInfo = [
            'Flash' => '7-8-6,9-2-6,2-5-6,6-11-8,6-7-4',
            'Program' => '7-8-6-9-2-6-2-5-6-6-11-8-6-7-4',
            'CardsCount' => [
                'GridNum' => [
                    '7' => 2,
                    '8' => 2,
                    '6' => 5,
                    '2' => 2,
                    '9' => 1,
                    '5' => 1,
                    '11' => 1,
                    '4' => 1,
                ],
                'Grid' => [
                    '7' => '1,14',
                    '8' => '2,12',
                    '6' => '3,6,9,10,13',
                    '2' => '5,7',
                    '9' => '4',
                    '5' => '8',
                    '11' => '11',
                    '4' => '15',
                ],
            ],
            'AxisLocation' => '114-274-204-51-56',
        ];

        $aAccumulationInfo = [
            'RollerNumber' => 0,
            'FreeGame' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 0,
            ],
        ];

        $aExpected = [
            'GamePayTotal' => [
                'Lines' => 30,
            ],
            'PayTotal' => 30,
            'AllPayTotal' => 30,
            'Lines' => [
                '0' => [
                    'LineID' => 3,
                    'GridNum' => 3,
                    'Grids' => '3,6,9',
                    'Payoff' => 30,
                    'Element' => [
                        '0' => 6,
                        '1' => 6,
                        '2' => 6,
                        '3' => 8,
                        '4' => 4,
                    ],
                    'ElementID' => '6',
                ],
            ],
            'Scatter' => [
                'ID' => 0,
                'GridNum' => 0,
                'Grids' => '',
                'Payoff' => 0,
            ],
            'AccumulationInfo' => [
                'FreeGame' => [
                    'FreeGameTime' => 0,
                    'FreeGamePayoffTotal' => 0,
                    'AddFreeGame' => 0,
                    'Lead' => 0,
                ],
                'RollerNumber' => 0,
            ],
            'FreeGameSpin' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
            ],
            'FreeGame' => [
                'HitFree' => false,
                'ID' => 0,
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 0,
            ],
        ];

        //Act
        $aActual = $this->oCalculator5221->calculate($this->aSetting, $aCardInfo, $this->aBetInfo, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * 一般遊戲有連線(1條 5元件)
     * @test
     * @group Calculator
     */
    public function calculateOneLineWithFiveElements()
    {
        $aCardInfo = [
            'Flash' => '7-8-6,9-2-6,2-5-6,8-11-6,4-7-6',
            'Program' => '7-8-6-9-2-6-2-5-6-8-11-6-4-7-6',
            'CardsCount' => [
                'GridNum' => [
                    '7' => 2,
                    '8' => 2,
                    '6' => 5,
                    '2' => 2,
                    '9' => 1,
                    '5' => 1,
                    '11' => 1,
                    '4' => 1,
                ],
                'Grid' => [
                    '7' => '1,14',
                    '8' => '2,10',
                    '6' => '3,6,9,12,15',
                    '2' => '5,7',
                    '9' => '4',
                    '5' => '8',
                    '11' => '11',
                    '4' => '13',
                ],
            ],
            'AxisLocation' => '114-274-204-51-56',
        ];

        $aAccumulationInfo = [
            'RollerNumber' => 0,
            'FreeGame' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 0,
            ],
        ];

        $aExpected = [
            'GamePayTotal' => [
                'Lines' => 100,
            ],
            'PayTotal' => 100,
            'AllPayTotal' => 100,
            'Lines' => [
                '0' => [
                    'LineID' => 3,
                    'GridNum' => 5,
                    'Grids' => '3,6,9,12,15',
                    'Payoff' => 100,
                    'Element' => [
                        '0' => 6,
                        '1' => 6,
                        '2' => 6,
                        '3' => 6,
                        '4' => 6,
                    ],
                    'ElementID' => '6',
                ],
            ],
            'Scatter' => [
                'ID' => 0,
                'GridNum' => 0,
                'Grids' => '',
                'Payoff' => 0,
            ],
            'AccumulationInfo' => [
                'FreeGame' => [
                    'FreeGameTime' => 0,
                    'FreeGamePayoffTotal' => 0,
                    'AddFreeGame' => 0,
                    'Lead' => 0,
                ],
                'RollerNumber' => 0,
            ],
            'FreeGameSpin' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
            ],
            'FreeGame' => [
                'HitFree' => false,
                'ID' => 0,
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 0,
            ],
        ];

        //Act
        $aActual = $this->oCalculator5221->calculate($this->aSetting, $aCardInfo, $this->aBetInfo, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * 一般遊戲有連線(2條)
     * @test
     * @group Calculator
     */
    public function calculateTwoLine()
    {
        $aCardInfo = [
            'Flash' => '7-8-6,4-8-6,2-8-6,8-11-6,4-7-6',
            'Program' => '7-8-6-4-8-6-2-8-6-8-11-6-4-7-6',
            'CardsCount' => [
                'GridNum' => [
                    '7' => 2,
                    '8' => 4,
                    '6' => 5,
                    '2' => 1,
                    '11' => 1,
                    '4' => 2,
                ],
                'Grid' => [
                    '7' => '1,14',
                    '8' => '2,5,8,10',
                    '6' => '3,6,9,12,15',
                    '2' => '7',
                    '11' => '11',
                    '4' => '4,13',
                ],
            ],
            'AxisLocation' => '114-274-204-51-56',
        ];

        $aAccumulationInfo = [
            'RollerNumber' => 0,
            'FreeGame' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 0,
            ],
        ];

        $aExpected = [
            'GamePayTotal' => [
                'Lines' => 120,
            ],
            'PayTotal' => 120,
            'AllPayTotal' => 120,
            'Lines' => [
                '0' => [
                    'LineID' => 1,
                    'GridNum' => 3,
                    'Grids' => '2,5,8',
                    'Payoff' => 20,
                    'Element' => [
                        '0' => 8,
                        '1' => 8,
                        '2' => 8,
                        '3' => 11,
                        '4' => 7,
                    ],
                    'ElementID' => '8',
                ],
                '1' => [
                    'LineID' => 3,
                    'GridNum' => 5,
                    'Grids' => '3,6,9,12,15',
                    'Payoff' => 100,
                    'Element' => [
                        '0' => 6,
                        '1' => 6,
                        '2' => 6,
                        '3' => 6,
                        '4' => 6,
                    ],
                    'ElementID' => '6',
                ],
            ],
            'Scatter' => [
                'ID' => 0,
                'GridNum' => 0,
                'Grids' => '',
                'Payoff' => 0,
            ],
            'AccumulationInfo' => [
                'FreeGame' => [
                    'FreeGameTime' => 0,
                    'FreeGamePayoffTotal' => 0,
                    'AddFreeGame' => 0,
                    'Lead' => 0,
                ],
                'RollerNumber' => 0,
            ],
            'FreeGameSpin' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
            ],
            'FreeGame' => [
                'HitFree' => false,
                'ID' => 0,
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 0,
            ],
        ];

        //Act
        $aActual = $this->oCalculator5221->calculate($this->aSetting, $aCardInfo, $this->aBetInfo, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * 一般遊戲無連線(2個Scatter)
     * @test
     * @group Calculator
     */
    public function calculateTwoScatter()
    {
        $aCardInfo = [
            'Flash' => '7-8-5,9-2-10,2-5-10,8-11-6,4-7-6',
            'Program' => '7-8-5-9-2-10-2-5-10-8-11-6-4-7-6',
            'CardsCount' => [
                'GridNum' => [
                    '10' => 2,
                    '7' => 2,
                    '8' => 2,
                    '6' => 2,
                    '2' => 2,
                    '9' => 1,
                    '5' => 2,
                    '11' => 1,
                    '4' => 1,
                ],
                'Grid' => [
                    '7' => '1,14',
                    '8' => '2,10',
                    '5' => '3,8',
                    '9' => '4',
                    '2' => '5,7',
                    '10' => '6,9',
                    '11' => '11',
                    '6' => '12,15',
                    '4' => '13',
                ],
            ],
            'AxisLocation' => '114-274-204-51-56',
        ];

        $aAccumulationInfo = [
            'RollerNumber' => 0,
            'FreeGame' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 0,
            ],
        ];

        $aExpected = [
            'GamePayTotal' => [
                'Lines' => 0,
            ],
            'PayTotal' => 0,
            'AllPayTotal' => 0,
            'Lines' => [
            ],
            'Scatter' => [
                'ID' => 0,
                'GridNum' => 0,
                'Grids' => '',
                'Payoff' => 0,
            ],
            'AccumulationInfo' => [
                'FreeGame' => [
                    'FreeGameTime' => 0,
                    'FreeGamePayoffTotal' => 0,
                    'AddFreeGame' => 0,
                    'Lead' => 0,
                ],
                'RollerNumber' => 0,
            ],
            'FreeGameSpin' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
            ],
            'FreeGame' => [
                'HitFree' => false,
                'ID' => 0,
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 0,
            ],
        ];

        //Act
        $aActual = $this->oCalculator5221->calculate($this->aSetting, $aCardInfo, $this->aBetInfo, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * 一般遊戲無連線(3個Scatter)
     * @test
     * @group Calculator
     */
    public function calculateThreeScatter()
    {
        $aCardInfo = [
            'Flash' => '7-8-10,9-2-10,2-5-10,8-11-6,4-7-6',
            'Program' => '7-8-10-9-2-10-2-5-10-8-11-6-4-7-6',
            'CardsCount' => [
                'GridNum' => [
                    '10' => 3,
                    '7' => 2,
                    '8' => 2,
                    '6' => 2,
                    '2' => 2,
                    '9' => 1,
                    '5' => 1,
                    '11' => 1,
                    '4' => 1,
                ],
                'Grid' => [
                    '7' => '1,14',
                    '8' => '2,10',
                    '10' => '3,6,9',
                    '9' => '4',
                    '2' => '5,7',
                    '5' => '8',
                    '11' => '11',
                    '6' => '12,15',
                    '4' => '13',
                ],
            ],
            'AxisLocation' => '114-274-204-51-56',
        ];

        $aAccumulationInfo = [
            'RollerNumber' => 0,
            'FreeGame' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 0,
            ],
        ];

        $aExpected = [
            'GamePayTotal' => [
                'Lines' => 400,
            ],
            'PayTotal' => 400,
            'AllPayTotal' => 400,
            'Lines' => [
            ],
            'Scatter' => [
                'ID' => 10,
                'GridNum' => 3,
                'Grids' => '3,6,9',
                'Payoff' => 400,
            ],
            'AccumulationInfo' => [
                'FreeGame' => [
                    'FreeGameTime' => 5,
                    'FreeGamePayoffTotal' => 0,
                    'AddFreeGame' => 0,
                    'Lead' => 4,
                ],
                'RollerNumber' => 0,
            ],
            'FreeGameSpin' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
            ],
            'FreeGame' => [
                'HitFree' => true,
                'ID' => 10,
                'FreeGameTime' => 5,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 4,
            ],
        ];

        //Act
        $aActual = $this->oCalculator5221->calculate($this->aSetting, $aCardInfo, $this->aBetInfo, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * 一般遊戲有連線(3個Scatter 1條連線)
     * @test
     * @group Calculator
     */
    public function calculateThreeScatterAndOneLine()
    {
        $aCardInfo = [
            'Flash' => '7-2-10,4-2-10,2-5-10,8-11-6,4-7-6',
            'Program' => '7-2-10-4-2-10-2-5-10-8-11-6-4-7-6',
            'CardsCount' => [
                'GridNum' => [
                    '10' => 3,
                    '2' => 3,
                    '7' => 2,
                    '8' => 1,
                    '6' => 2,
                    '5' => 1,
                    '11' => 1,
                    '4' => 2,
                ],
                'Grid' => [
                    '7' => '1,14',
                    '8' => '10',
                    '10' => '3,6,9',
                    '2' => '2,5,7',
                    '5' => '8',
                    '11' => '11',
                    '6' => '12,15',
                    '4' => '4,13',
                ],
            ],
            'AxisLocation' => '114-274-204-51-56',
        ];

        $aAccumulationInfo = [
            'RollerNumber' => 0,
            'FreeGame' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 0,
            ],
        ];

        $aExpected = [
            'GamePayTotal' => [
                'Lines' => 550,
            ],
            'PayTotal' => 550,
            'AllPayTotal' => 550,
            'Lines' => [
                0 => [
                    'LineID' => 16,
                    'GridNum' => 3,
                    'Grids' => '2,5,7',
                    'Payoff' => 150,
                    'Element' => [
                        '0' => '2',
                        '1' => '2',
                        '2' => '2',
                        '3' => '11',
                        '4' => '7',
                    ],
                    'ElementID' => '2',
                ],
            ],
            'Scatter' => [
                'ID' => 10,
                'GridNum' => 3,
                'Grids' => '3,6,9',
                'Payoff' => 400,
            ],
            'AccumulationInfo' => [
                'FreeGame' => [
                    'FreeGameTime' => 5,
                    'FreeGamePayoffTotal' => 0,
                    'AddFreeGame' => 0,
                    'Lead' => 4,
                ],
                'RollerNumber' => 0,
            ],
            'FreeGameSpin' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
            ],
            'FreeGame' => [
                'HitFree' => true,
                'ID' => 10,
                'FreeGameTime' => 5,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 4,
            ],
        ];

        //Act
        $aActual = $this->oCalculator5221->calculate($this->aSetting, $aCardInfo, $this->aBetInfo, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * 一般遊戲無連線(5個Scatter)
     * @test
     * @group Calculator
     */
    public function calculateFiveScatter()
    {
        $aCardInfo = [
            'Flash' => '7-8-10,9-2-10,2-5-10,8-11-10,4-7-10',
            'Program' => '7-8-10-9-2-10-2-5-10-8-11-10-4-7-10',
            'CardsCount' => [
                'GridNum' => [
                    '10' => 5,
                    '7' => 2,
                    '8' => 2,
                    '2' => 2,
                    '9' => 1,
                    '5' => 1,
                    '11' => 1,
                    '4' => 1,
                ],
                'Grid' => [
                    '7' => '1,14',
                    '8' => '2,10',
                    '10' => '3,6,9,12,15',
                    '9' => '4',
                    '2' => '5,7',
                    '5' => '8',
                    '11' => '11',
                    '4' => '13',
                ],
            ],
            'AxisLocation' => '114-274-204-51-56',
        ];

        $aAccumulationInfo = [
            'RollerNumber' => 0,
            'FreeGame' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 0,
            ],
        ];

        $aExpected = [
            'GamePayTotal' => [
                'Lines' => 10000,
            ],
            'PayTotal' => 10000,
            'AllPayTotal' => 10000,
            'Lines' => [
            ],
            'Scatter' => [
                'ID' => 10,
                'GridNum' => 5,
                'Grids' => '3,6,9,12,15',
                'Payoff' => 10000,
            ],
            'AccumulationInfo' => [
                'FreeGame' => [
                    'FreeGameTime' => 10,
                    'FreeGamePayoffTotal' => 0,
                    'AddFreeGame' => 0,
                    'Lead' => 4,
                ],
                'RollerNumber' => 0,
            ],
            'FreeGameSpin' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
            ],
            'FreeGame' => [
                'HitFree' => true,
                'ID' => 10,
                'FreeGameTime' => 10,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 4,
            ],
        ];

        //Act
        $aActual = $this->oCalculator5221->calculate($this->aSetting, $aCardInfo, $this->aBetInfo, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * 一般遊戲hitFree(4個Scatter)
     * @test
     * @group Calculator
     */
    public function calculateHitFree()
    {
        $aCardInfo = [
            'Flash' => '7-8-10,9-2-10,2-5-4,8-11-10,4-7-10',
            'Program' => '7-8-10-9-2-10-2-5-4-8-11-10-4-7-10',
            'CardsCount' => [
                'GridNum' => [
                    '10' => 4,
                    '7' => 2,
                    '8' => 2,
                    '2' => 2,
                    '9' => 1,
                    '5' => 1,
                    '11' => 1,
                    '4' => 2,
                ],
                'Grid' => [
                    '7' => '1,14',
                    '8' => '2,10',
                    '10' => '3,6,12,15',
                    '9' => '4',
                    '2' => '5,7',
                    '5' => '8',
                    '11' => '11',
                    '4' => '9,13',
                ],
            ],
            'AxisLocation' => '114-274-204-51-56',
        ];

        $aAccumulationInfo = [
            'RollerNumber' => 0,
            'FreeGame' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 0,
            ],
        ];

        $aExpected = [
            'GamePayTotal' => [
                'Lines' => 2000,
            ],
            'PayTotal' => 2000,
            'AllPayTotal' => 2000,
            'Lines' => [
            ],
            'Scatter' => [
                'ID' => 10,
                'GridNum' => 4,
                'Grids' => '3,6,12,15',
                'Payoff' => 2000,
            ],
            'AccumulationInfo' => [
                'FreeGame' => [
                    'FreeGameTime' => 7,
                    'FreeGamePayoffTotal' => 0,
                    'AddFreeGame' => 0,
                    'Lead' => 4,
                ],
                'RollerNumber' => 0,
            ],
            'FreeGameSpin' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
            ],
            'FreeGame' => [
                'HitFree' => true,
                'ID' => 10,
                'FreeGameTime' => 7,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 4,
            ],
        ];

        //Act
        $aActual = $this->oCalculator5221->calculate($this->aSetting, $aCardInfo, $this->aBetInfo, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * 計算派彩
     * @test 有一個Wild
     */
    public function haveOneWild()
    {

        $aCardInfo = [
            'Flash' => '7-8-5,9-2-10,7-5-10,8-11-6,4-7-6',
            'Program' => '7-8-5-9-2-10-7-5-10-8-11-6-4-7-6',
            'CardsCount' => [
                'GridNum' => [
                    '10' => 2,
                    '7' => 3,
                    '8' => 2,
                    '6' => 2,
                    '2' => 1,
                    '9' => 1,
                    '5' => 2,
                    '11' => 1,
                    '4' => 1,
                ],
                'Grid' => [
                    '7' => '1,7,14',
                    '8' => '2,10',
                    '5' => '3,8',
                    '9' => '4',
                    '2' => '5',
                    '10' => '6,9',
                    '11' => '11',
                    '6' => '12,15',
                    '4' => '13',
                ],
            ],
            'AxisLocation' => '114-274-204-51-56',
        ];

        $aAccumulationInfo = [
            'RollerNumber' => 0,
            'FreeGame' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 0,
            ],
        ];

        $aExpected = [
            'GamePayTotal' => [
                'Lines' => 20,
            ],
            'PayTotal' => 20,
            'AllPayTotal' => 20,
            'Lines' => [
                '0' => [
                    'LineID' => 2,
                    'GridNum' => 3,
                    'Grids' => '1,4,7',
                    'Payoff' => 20,
                    'Element' => [
                        '0' => 7,
                        '1' => 9,
                        '2' => 7,
                        '3' => 8,
                        '4' => 4,
                    ],
                    'ElementID' => '7',
                ],
            ],
            'Scatter' => [
                'ID' => 0,
                'GridNum' => 0,
                'Grids' => '',
                'Payoff' => 0,
            ],
            'AccumulationInfo' => [
                'FreeGame' => [
                    'FreeGameTime' => 0,
                    'FreeGamePayoffTotal' => 0,
                    'AddFreeGame' => 0,
                    'Lead' => 0,
                ],
                'RollerNumber' => 0,
            ],
            'FreeGameSpin' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
            ],
            'FreeGame' => [
                'HitFree' => false,
                'ID' => 0,
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 0,
            ],
        ];

        //Act
        $aActual = $this->oCalculator5221->calculate($this->aSetting, $aCardInfo, $this->aBetInfo, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * 計算派彩
     * @test 有兩個Wild
     */
    public function haveTwoWild()
    {

        $aCardInfo = [
            'Flash' => '7-8-5,9-2-10,7-5-10,9-11-6,4-7-6',
            'Program' => '7-8-5-9-2-10-7-5-10-9-11-6-4-7-6',
            'CardsCount' => [
                'GridNum' => [
                    '10' => 2,
                    '7' => 3,
                    '8' => 1,
                    '6' => 2,
                    '2' => 1,
                    '9' => 2,
                    '5' => 2,
                    '11' => 1,
                    '4' => 1,
                ],
                'Grid' => [
                    '7' => '1,7,14',
                    '8' => '2',
                    '5' => '3,8',
                    '9' => '4,10',
                    '2' => '5',
                    '10' => '6,9',
                    '11' => '11',
                    '6' => '12,15',
                    '4' => '13',
                ],
            ],
            'AxisLocation' => '114-274-204-51-56',
        ];

        $aAccumulationInfo = [
            'RollerNumber' => 0,
            'FreeGame' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 0,
            ],
        ];

        $aExpected = [
            'GamePayTotal' => [
                'Lines' => 30,
            ],
            'PayTotal' => 30,
            'AllPayTotal' => 30,
            'Lines' => [
                '0' => [
                    'LineID' => 2,
                    'GridNum' => 4,
                    'Grids' => '1,4,7,10',
                    'Payoff' => 30,
                    'Element' => [
                        '0' => 7,
                        '1' => 9,
                        '2' => 7,
                        '3' => 9,
                        '4' => 4,
                    ],
                    'ElementID' => '7',
                ],
            ],
            'Scatter' => [
                'ID' => 0,
                'GridNum' => 0,
                'Grids' => '',
                'Payoff' => 0,
            ],
            'AccumulationInfo' => [
                'FreeGame' => [
                    'FreeGameTime' => 0,
                    'FreeGamePayoffTotal' => 0,
                    'AddFreeGame' => 0,
                    'Lead' => 0,
                ],
                'RollerNumber' => 0,
            ],
            'FreeGameSpin' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
            ],
            'FreeGame' => [
                'HitFree' => false,
                'ID' => 0,
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 0,
            ],
        ];

        //Act
        $aActual = $this->oCalculator5221->calculate($this->aSetting, $aCardInfo, $this->aBetInfo, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * FreeGame中無連線
     * @test
     * @group Calculator
     */
    public function calculateInFG()
    {
        $aCardInfo = [
            'Flash' => '4-4-4,4-4-4,3-3-1,3-3-1,3-3-1',
            'Program' => '4-4-4-4-4-4-3-3-1-3-3-1-3-3-1',
            'CardsCount' => [
                'GridNum' => [
                    '4' => 6,
                    '3' => 6,
                    '1' => 3,
                ],
                'Grid' => [
                    '4' => '1,2,3,4,5,6',
                    '3' => '7,8,10,11,13,14',
                    '1' => '9,12,15',
                ],
            ],
            'AxisLocation' => '50-241-123-7-99',

        ];

        $aAccumulationInfo = [
            'RollerNumber' => 1,
            'FreeGame' => [
                'FreeGameTime' => 5,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 4,
            ],
        ];

        $aExpected = [
            'GamePayTotal' => [
              'Lines' => 0,
            ],
            'PayTotal' => 0,
            'AllPayTotal' => 0,
            'Lines' => [],
            'Scatter' => [
              'ID' => 0,
              'GridNum' => 0,
              'Grids' => '',
              'Payoff' => 0,
            ],
            'AccumulationInfo' => [
              'FreeGame' => [
                'FreeGameTime' => 4,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 4,
              ],
              'RollerNumber' => 1,
            ],
            'FreeGameSpin' => [
              'FreeGameTime' => 4,
              'FreeGamePayoffTotal' => 0,
            ],
            'FreeGame' => [
              'HitFree' => false,
              'ID' => 0,
              'FreeGameTime' => 0,
              'FreeGamePayoffTotal' => 0,
              'AddFreeGame' => 0,
              'Lead' => 0,
            ],
        ];

        //Act
        $aActual = $this->oCalculator5221->calculate($this->aSetting, $aCardInfo, $this->aBetInfo, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aExpected, $aActual);
    }


    /**
     * FreeGame中有六條連線
     * @test
     * @group Calculator
     */
    public function calculateSixLineInFG()
    {
        $aCardInfo = [
            'Flash' => '4-4-4,4-4-4,4-1-1,4-1-1,4-1-1',
            'Program' => '4-4-4-4-4-4-4-1-1-4-1-1-4-1-1',
            'CardsCount' => [
                'GridNum' => [
                    '4' => 9,
                    '1' => 6,
                ],
                'Grid' => [
                    '4' => '1,2,3,4,5,6,7,10,13',
                    '1' => '8,9,11,12,14,15',
                ],
            ],
            'AxisLocation' => '50-241-123-7-99',

        ];

        $aAccumulationInfo = [
            'RollerNumber' => 1,
            'FreeGame' => [
                'FreeGameTime' => 4,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 4,
            ],
        ];

        $aExpected = [
            'GamePayTotal' => [
              'Lines' => 700,
            ],
            'PayTotal' => 700,
            'AllPayTotal' => 700,
            'Lines' => [
                '0' => [
                    'LineID' => 2,
                    'GridNum' => 5,
                    'Grids' => '1,4,7,10,13',
                    'Payoff' => 300,
                    'Element' => [
                        '0' => 4,
                        '1' => 4,
                        '2' => 4,
                        '3' => 4,
                        '4' => 4,
                    ],
                    'ElementID' => '4',
                ],
                '1' => [
                    'LineID' => 5,
                    'GridNum' => 3,
                    'Grids' => '3,5,7',
                    'Payoff' => 50,
                    'Element' => [
                        '0' => 4,
                        '1' => 4,
                        '2' => 4,
                        '3' => 1,
                        '4' => 1,
                    ],
                    'ElementID' => '4',
                ],
                '2' => [
                    'LineID' => 6,
                    'GridNum' => 4,
                    'Grids' => '2,4,7,10',
                    'Payoff' => 200,
                    'Element' => [
                        '0' => 4,
                        '1' => 4,
                        '2' => 4,
                        '3' => 4,
                        '4' => 1,
                    ],
                    'ElementID' => '4',
                ],
                '3' => [
                    'LineID' => 14,
                    'GridNum' => 3,
                    'Grids' => '1,5,7',
                    'Payoff' => 50,
                    'Element' => [
                        '0' => 4,
                        '1' => 4,
                        '2' => 4,
                        '3' => 1,
                        '4' => 4,
                    ],
                    'ElementID' => '4',
                ],
                '4' => [
                    'LineID' => 16,
                    'GridNum' => 3,
                    'Grids' => '2,5,7',
                    'Payoff' => 50,
                    'Element' => [
                        '0' => 4,
                        '1' => 4,
                        '2' => 4,
                        '3' => 1,
                        '4' => 1,
                    ],
                    'ElementID' => '4',
                ],
                '5' => [
                    'LineID' => 19,
                    'GridNum' => 3,
                    'Grids' => '3,6,7',
                    'Payoff' => 50,
                    'Element' => [
                        '0' => 4,
                        '1' => 4,
                        '2' => 4,
                        '3' => 1,
                        '4' => 1,
                    ],
                    'ElementID' => '4',
                ],
            ],
            'Scatter' => [
              'ID' => 0,
              'GridNum' => 0,
              'Grids' => '',
              'Payoff' => 0,
            ],
            'AccumulationInfo' => [
              'FreeGame' => [
                'FreeGameTime' => 3,
                'FreeGamePayoffTotal' => 700,
                'AddFreeGame' => 0,
                'Lead' => 4,
              ],
              'RollerNumber' => 1,
            ],
            'FreeGameSpin' => [
              'FreeGameTime' => 3,
              'FreeGamePayoffTotal' => 700,
            ],
            'FreeGame' => [
              'HitFree' => false,
              'ID' => 0,
              'FreeGameTime' => 0,
              'FreeGamePayoffTotal' => 0,
              'AddFreeGame' => 0,
              'Lead' => 0,
            ],
        ];

        //Act
        $aActual = $this->oCalculator5221->calculate($this->aSetting, $aCardInfo, $this->aBetInfo, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * FreeGame中FreeGame
     * @test
     * @group Calculator
     */
    public function calculateNineScatterInFG()
    {
        $aCardInfo = [
            'Flash' => '4-4-4,4-4-4,10-10-10,10-10-10,10-10-10',
            'Program' => '4-4-4-4-4-4-10-10-10-10-10-10-10-10-10',
            'CardsCount' => [
                'GridNum' => [
                    '10' => 9,
                    '4' => 6,
                ],
                'Grid' => [
                    '4' => '1, 2, 3, 4, 5, 6',
                    '10' => '7, 8, 9, 10, 11, 12, 13, 14, 15',
                ],
            ],
            'AxisLocation' => '50-241-123-7-99',

        ];

        $aAccumulationInfo = [
            'RollerNumber' => 1,
            'FreeGame' => [
                'FirstFreeGameTime' => 5,
                'FreeGameTime' => 3,
                'FreeGamePayoffTotal' => 700,
                'AddFreeGame' => 0,
                'Lead' => 4,
            ],
        ];

        $aExpected = [
            'GamePayTotal' => [
              'Lines' => 0,
            ],
            'PayTotal' => 0,
            'AllPayTotal' => 0,
            'Lines' => [],
            'Scatter' => [
              'ID' => 10,
              'GridNum' => 9,
              'Grids' => '7, 8, 9, 10, 11, 12, 13, 14, 15',
              'Payoff' => 0,
            ],
            'AccumulationInfo' => [
              'FreeGame' => [
                'FirstFreeGameTime' => 5,
                'FreeGameTime' => 7,
                'FreeGamePayoffTotal' => 700,
                'AddFreeGame' => 1,
                'Lead' => 3,
              ],
              'RollerNumber' => 1,
            ],
            'FreeGameSpin' => [
              'FreeGameTime' => 7,
              'FreeGamePayoffTotal' => 700,
            ],
            'FreeGame' => [
              'HitFree' => true,
              'ID' => 10,
              'FreeGameTime' => 5,
              'FreeGamePayoffTotal' => 0,
              'AddFreeGame' => 1,
              'Lead' => 3,
            ],
        ];

        //Act
        $aActual = $this->oCalculator5221->calculate($this->aSetting, $aCardInfo, $this->aBetInfo, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * FreeGame中最後一局暫存變初始值
     * @test
     * @group Calculator
     */
    public function calculateFreeGameSpinEnd()
    {
        $aCardInfo = [
            'Flash' => '4-4-4,4-4-4,3-3-1,3-3-1,3-3-1',
            'Program' => '4-4-4-4-4-4-3-3-1-3-3-1-3-3-1',
            'CardsCount' => [
                'GridNum' => [
                    '4' => 6,
                    '3' => 6,
                    '1' => 3,
                ],
                'Grid' => [
                    '4' => '1,2,3,4,5,6',
                    '3' => '7,8,10,11,13,14',
                    '1' => '9,12,15',
                ],
            ],
            'AxisLocation' => '50-241-123-7-99',

        ];

        $aAccumulationInfo = [
            'RollerNumber' => 1,
            'FreeGame' => [
                'FreeGameTime' => 1,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 4,
            ],
        ];

        $aExpected = [
            'GamePayTotal' => [
              'Lines' => 0,
            ],
            'PayTotal' => 0,
            'AllPayTotal' => 0,
            'Lines' => [],
            'Scatter' => [
              'ID' => 0,
              'GridNum' => 0,
              'Grids' => '',
              'Payoff' => 0,
            ],
            'AccumulationInfo' => [
              'FreeGame' => [
                'FreeGameTime' => 0,
                'FreeGamePayoffTotal' => 0,
                'AddFreeGame' => 0,
                'Lead' => 0,
              ],
              'RollerNumber' => 1,
            ],
            'FreeGameSpin' => [
              'FreeGameTime' => 0,
              'FreeGamePayoffTotal' => 0,
            ],
            'FreeGame' => [
              'HitFree' => false,
              'ID' => 0,
              'FreeGameTime' => 0,
              'FreeGamePayoffTotal' => 0,
              'AddFreeGame' => 0,
              'Lead' => 0,
            ],
        ];

        //Act
        $aActual = $this->oCalculator5221->calculate($this->aSetting, $aCardInfo, $this->aBetInfo, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aExpected, $aActual);
    }


    /**
     * FreeGame中計算總派彩
     * @test
     * @group Calculator
     */
    public function calculatePayOffInFG()
    {
        $aCardInfo = [
            'Flash' => '4-4-4,4-4-4,4-1-1,4-1-1,4-1-1',
            'Program' => '4-4-4-4-4-4-4-1-1-4-1-1-4-1-1',
            'CardsCount' => [
                'GridNum' => [
                    '4' => 9,
                    '1' => 6,
                ],
                'Grid' => [
                    '4' => '1,2,3,4,5,6,7,10,13',
                    '1' => '8,9,11,12,14,15',
                ],
            ],
            'AxisLocation' => '50-241-123-7-99',

        ];

        $aAccumulationInfo = [
            'RollerNumber' => 1,
            'FreeGame' => [
                'FreeGameTime' => 2,
                'FreeGamePayoffTotal' => 1000,
                'AddFreeGame' => 0,
                'Lead' => 4,
            ],
        ];

        $aExpected = [
            'GamePayTotal' => [
              'Lines' => 700,
            ],
            'PayTotal' => 700,
            'AllPayTotal' => 700,
            'Lines' => [
                '0' => [
                    'LineID' => 2,
                    'GridNum' => 5,
                    'Grids' => '1,4,7,10,13',
                    'Payoff' => 300,
                    'Element' => [
                        '0' => 4,
                        '1' => 4,
                        '2' => 4,
                        '3' => 4,
                        '4' => 4,
                    ],
                    'ElementID' => '4',
                ],
                '1' => [
                    'LineID' => 5,
                    'GridNum' => 3,
                    'Grids' => '3,5,7',
                    'Payoff' => 50,
                    'Element' => [
                        '0' => 4,
                        '1' => 4,
                        '2' => 4,
                        '3' => 1,
                        '4' => 1,
                    ],
                    'ElementID' => '4',
                ],
                '2' => [
                    'LineID' => 6,
                    'GridNum' => 4,
                    'Grids' => '2,4,7,10',
                    'Payoff' => 200,
                    'Element' => [
                        '0' => 4,
                        '1' => 4,
                        '2' => 4,
                        '3' => 4,
                        '4' => 1,
                    ],
                    'ElementID' => '4',
                ],
                '3' => [
                    'LineID' => 14,
                    'GridNum' => 3,
                    'Grids' => '1,5,7',
                    'Payoff' => 50,
                    'Element' => [
                        '0' => 4,
                        '1' => 4,
                        '2' => 4,
                        '3' => 1,
                        '4' => 4,
                    ],
                    'ElementID' => '4',
                ],
                '4' => [
                    'LineID' => 16,
                    'GridNum' => 3,
                    'Grids' => '2,5,7',
                    'Payoff' => 50,
                    'Element' => [
                        '0' => 4,
                        '1' => 4,
                        '2' => 4,
                        '3' => 1,
                        '4' => 1,
                    ],
                    'ElementID' => '4',
                ],
                '5' => [
                    'LineID' => 19,
                    'GridNum' => 3,
                    'Grids' => '3,6,7',
                    'Payoff' => 50,
                    'Element' => [
                        '0' => 4,
                        '1' => 4,
                        '2' => 4,
                        '3' => 1,
                        '4' => 1,
                    ],
                    'ElementID' => '4',
                ],
            ],
            'Scatter' => [
              'ID' => 0,
              'GridNum' => 0,
              'Grids' => '',
              'Payoff' => 0,
            ],
            'AccumulationInfo' => [
              'FreeGame' => [
                'FreeGameTime' => 1,
                'FreeGamePayoffTotal' => 1700,
                'AddFreeGame' => 0,
                'Lead' => 4,
              ],
              'RollerNumber' => 1,
            ],
            'FreeGameSpin' => [
              'FreeGameTime' => 1,
              'FreeGamePayoffTotal' => 1700,
            ],
            'FreeGame' => [
              'HitFree' => false,
              'ID' => 0,
              'FreeGameTime' => 0,
              'FreeGamePayoffTotal' => 0,
              'AddFreeGame' => 0,
              'Lead' => 0,
            ],
        ];

        //Act
        $aActual = $this->oCalculator5221->calculate($this->aSetting, $aCardInfo, $this->aBetInfo, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * FreeGame中FreeGame 已達AddFreeGame上限
     * @test
     * @group Calculator
     */
    public function calculateAddFreeGameLimitInFG()
    {
        $aCardInfo = [
            'Flash' => '4-4-4,4-4-4,10-10-10,10-10-10,10-10-10',
            'Program' => '4-4-4-4-4-4-10-10-10-10-10-10-10-10-10',
            'CardsCount' => [
                'GridNum' => [
                    '10' => 9,
                    '4' => 6,
                ],
                'Grid' => [
                    '4' => '1, 2, 3, 4, 5, 6',
                    '10' => '7, 8, 9, 10, 11, 12, 13, 14, 15',
                ],
            ],
            'AxisLocation' => '50-241-123-7-99',

        ];

        $aAccumulationInfo = [
            'RollerNumber' => 1,
            'FreeGame' => [
                'FirstFreeGameTime' => 5,
                'FreeGameTime' => 18,
                'FreeGamePayoffTotal' => 700,
                'AddFreeGame' => 10,
                'Lead' => 2,
            ],
        ];

        $aExpected = [
            'GamePayTotal' => [
              'Lines' => 0,
            ],
            'PayTotal' => 0,
            'AllPayTotal' => 0,
            'Lines' => [],
            'Scatter' => [
              'ID' => 10,
              'GridNum' => 9,
              'Grids' => '7, 8, 9, 10, 11, 12, 13, 14, 15',
              'Payoff' => 0,
            ],
            'AccumulationInfo' => [
              'FreeGame' => [
                'FirstFreeGameTime' => 5,
                'FreeGameTime' => 17,
                'FreeGamePayoffTotal' => 700,
                'AddFreeGame' => 10,
                'Lead' => 2,
              ],
              'RollerNumber' => 1,
            ],
            'FreeGameSpin' => [
              'FreeGameTime' => 17,
              'FreeGamePayoffTotal' => 700,
            ],
            'FreeGame' => [
              'HitFree' => false,
              'ID' => 0,
              'FreeGameTime' => 0,
              'FreeGamePayoffTotal' => 0,
              'AddFreeGame' => 0,
              'Lead' => 0,
            ],
        ];

        //Act
        $aActual = $this->oCalculator5221->calculate($this->aSetting, $aCardInfo, $this->aBetInfo, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * FreeGame中FreeGame 已達Lead上限
     * @test
     * @group Calculator
     */
    public function calculateLeadLimitInFG()
    {
        $aCardInfo = [
            'Flash' => '4-4-4,4-4-4,10-10-10,10-10-10,10-10-10',
            'Program' => '4-4-4-4-4-4-10-10-10-10-10-10-10-10-10',
            'CardsCount' => [
                'GridNum' => [
                    '10' => 9,
                    '4' => 6,
                ],
                'Grid' => [
                    '4' => '1, 2, 3, 4, 5, 6',
                    '10' => '7, 8, 9, 10, 11, 12, 13, 14, 15',
                ],
            ],
            'AxisLocation' => '50-241-123-7-99',

        ];

        $aAccumulationInfo = [
            'RollerNumber' => 1,
            'FreeGame' => [
                'FirstFreeGameTime' => 5,
                'FreeGameTime' => 12,
                'FreeGamePayoffTotal' => 700,
                'AddFreeGame' => 4,
                'Lead' => 9,
            ],
        ];

        $aExpected = [
            'GamePayTotal' => [
              'Lines' => 0,
            ],
            'PayTotal' => 0,
            'AllPayTotal' => 0,
            'Lines' => [],
            'Scatter' => [
              'ID' => 10,
              'GridNum' => 9,
              'Grids' => '7, 8, 9, 10, 11, 12, 13, 14, 15',
              'Payoff' => 0,
            ],
            'AccumulationInfo' => [
              'FreeGame' => [
                'FirstFreeGameTime' => 5,
                'FreeGameTime' => 16,
                'FreeGamePayoffTotal' => 700,
                'AddFreeGame' => 5,
                'Lead' => 9,
              ],
              'RollerNumber' => 1,
            ],
            'FreeGameSpin' => [
              'FreeGameTime' => 16,
              'FreeGamePayoffTotal' => 700,
            ],
            'FreeGame' => [
              'HitFree' => true,
              'ID' => 10,
              'FreeGameTime' => 5,
              'FreeGamePayoffTotal' => 0,
              'AddFreeGame' => 5,
              'Lead' => 9,
            ],
        ];

        //Act
        $aActual = $this->oCalculator5221->calculate($this->aSetting, $aCardInfo, $this->aBetInfo, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aExpected, $aActual);
    }

}