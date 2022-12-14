<?php

namespace Tests\Unit\Rarity\Casino\Game;

use Framework\Rarity\Casino\Game\Rarity_5221;
use Framework\Support\Facades\Facade;
use Framework\Generator\Generator;
use Framework\Foundation\RandomTest;
use PHPUnit\Framework\TestCase;

class Rarity_5221_Test extends TestCase
{
    private $oRarity;
    private $aSetting = [
        'ChangeCards' => [
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9,
            10 => 10,
            11 => 1,
            12 => 2,
            13 => 3,
            14 => 4,
            15 => 9,
            16 => 1,
            17 => 2,
            18 => 3,
            19 => 4,
            20 => 9,
        ],

        'RateWeight' => [
            0 => 98540,
            9 => 10, 
            1 => 340, 
            2 => 337, 
            3 => 300, 
            4 => 473,
        ],

        // 一般滾輪
        'RollerContainer'       => [
            0 => [
                0 => [11,11,5,8,14,14,8,7,13,13,7,6,12,12,6,7,13,13,7,8,14,14,8,5,11,11,5,8,10,5,8,14,14,8,5,9,9,9,5,7,13,13,7,6,12,12,5,7,13,13,8,6,12,12,6,5,11,11,5,8,14,14,8,7,13,13,6,7,13,13,8,6,12,12,6,8,10,5,8,11,11,6,6,12,12,6,5,13,13,7,8,13,13,7,5],
                1 => [12,12,6,7,13,13,7,8,14,14,8,8,10,6,8,9,9,9,8,5,11,11,8,7,13,13,8,6,9,9,9,6,7,13,13,8,5,11,11,5,7,13,13,7,6,12,12,6,7,13,13,7,6,12,12,6,7,13,13,7,8,14,14,8,5,11,11,5,8,14,14,8,7,13,13,7,6,9,9,8,5,11,11,5,7,13,13,7,6],
                2 => [14,14,8,5,11,11,5,7,13,13,7,6,12,12,6,7,13,13,7,6,12,12,6,5,10,8,7,13,13,7,6,12,12,6,7,13,13,7,5,9,9,9,8,6,12,12,6,5,9,9,9,8,5,11,11,5,7,13,13,7,5,11,11,5,6,9,9,9,8,8,14,14,8,5,11,11,5,8,9,9,9,5,7,13,13,7,8],
                3 => [13,13,7,7,12,12,5,8,11,11,6,6,13,13,8,6,12,12,6,5,13,13,6,5,11,11,5,8,9,9,9,8,5,13,13,7,5,12,12,6,5,9,9,9,5,8,13,13,6,8,14,14,8,7,13,13,7,5,10,6,5,10,5,8,10,5,8,10,6,5,10,8,6,12,12,5,7,13,13,8,7,13,13,5,6,12,12,6,7],
                4 => [13,13,7,6,12,12,6,7,13,13,5,7,13,13,7,6,12,12,6,5,13,13,8,5,11,11,5,7,13,13,8,5,10,5,6,10,8,6,10,5,8,10,6,5,10,8,5,10,6,8,14,14,8,7,13,13,7,7,13,13,8,5,11,11,5,6,9,9,9,9,5,7,13,13,7,7]
            ],
        ],
        'SpecialRoller'       => [
            // MainGame
            0 => [
                1 => [2,2,2,3,3,3,4,4,4,9,9,9,2,2,2,4,4,4,3,3,3,2,2,2,4,4,4,3,3,3,4,4,4,2,2,2,3,3,3,4,4,4,2,2,2,3,3,3,4,4,4,2,2,2,3,3,3,4,4,4,2,2,2,3,3,3,4,4,4,3,3,3,4,4,4,2,2,2,3,3,3,4,4,4,3,3,3,4,4,4,2,2,2,3,3,3,4,4,4,1,1,4,4,3,3],
                2 => [1,1,1,3,3,3,4,4,4,3,3,3,4,4,4,9,9,9,3,3,3,1,1,1,4,4,4,3,3,3,4,4,4,1,1,1,4,4,4,3,3,3,4,4,4,1,1,1,4,4,4,3,3,3,1,1,1,3,3,3,4,4,4,3,3,3,2,2,3,3,4,4],
                3 => [1,1,1,2,2,2,3,3,3,4,4,4,9,9,9,1,1,1,2,2,2,4,4,4,2,2,1,1,9,9,4,4,4,2,2,2,4,4,4,2,2,3,3,1,1,2,2,3,3,4,4],
                4 => [1,1,1,2,2,2,4,4,4,3,3,3,1,1,1,9,9,9,3,3,3,4,4,2,2,9,9,3,3,4,4,3,3,4,4,2,2,4,4,1,1,9,9,3,3,4,4,3,3,4,4],
                9 => [4,4,4,9,9,9,4,4,4,2,2,3,3,4,4,2,2,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,2,2,3,3,1,1,2,2,3,3,1,1,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,2,2,3,3,1,1,2,2,4,4,2,2,3,3,4,4,2,2,4,4,3,3,2,2,3,3,4,4,2,2,3,3,4,4,2,2,3,3,4,4,2,2,3,3]
            ],
            //FreeGame
            1 => [
                1 => [1,1,1,2,2,2,3,3,3,4,4,4,9,9,9,4,4,4,3,3,3,4,4,4,2,2,2,10,10,10,4,4,4,2,2,2,3,3,3,4,4,4,2,2,2,3,3,3,1,1,2,2,10,10,4,4,4,9,9,3,3,1,1,2,2,10,10,4,4,1,1,3,3,9,9,2,2,1,1,4,4],
                2 => [1,1,1,2,2,2,3,3,3,4,4,4,9,9,9,4,4,4,1,1,1,3,3,3,2,2,1,1,9,9,4,4,4,1,1,1,10,10,10,4,4,4,3,3,3,2,2,4,4,2,2,1,1,1,10,10,10,4,4,4,9,9,1,1,2,2,3,3,2,2,1,1,10,10,10,4,4,2,2,3,3,9,9,1,1,2,2],
                3 => [1,1,1,3,3,3,2,2,2,10,10,10,4,4,4,9,9,9,4,4,4,1,1,1,3,3,4,4,3,3,2,2,2,3,3,4,4,9,9,1,1,1,10,10,10,4,4,3,3,1,1,9,9,4,4,2,2,10,10,10,4,4,3,3,2,2,9,9,1,1,3,3],
                4 => [1,1,1,2,2,2,3,3,3,1,1,1,9,9,9,3,3,3,2,2,2,10,10,10,4,4,1,1,9,9,2,2,2,1,1,1,10,10,10,4,4,1,1,9,9,2,2,2,10,10,10,4,4,3,3,2,2,10,10,10,4,4,1,1,9,9],
                9 => [3,3,4,4,3,3,4,4,9,9,4,4,3,3,4,4,3,3,10,10,10,4,4,2,2,3,3,10,10,4,4,3,3,10,10,4,4,3,3,10,10,4,4,3,3,4,4,3,3,10,10,4,4,2,2,3,3,4,4,2,2,4,4,3,3,10,10,4,4,2,2,3,3,4,4,2,2,4,4,3,3,10,10,4,4,3,3,1,1,4,4,2,2]
            ],
        ],
        
    ];

    public function setUp()
    {
        //清除Facde以免影響後面TestCase
        Facade::clearResolvedInstances();
        $aFacades['Random'] = new RandomTest();
        $aFacades['Generator'] = new Generator();
        Facade::setFacadeApplication($aFacades);
        $this->oRarity = new Rarity_5221();
    }

    /**
     * 執行MainGame的輪軸
     * @test
     */
    public function handleMainGameRollerNumber()
    {
        // Arrange
        $aAccumulationInfo = [
            'FreeGame' => [
                'FreeGameTime' => 0,
            ],
            'RollerNumber' => 0,
        ];
        $aExpected = [
            'FreeGame' => [
                'FreeGameTime' => 0,
            ],
            'RollerNumber' => 0,
        ];

        // Act
        $aActual = $this->oRarity->handleRollerNumber($aAccumulationInfo);

        // Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * 執行FreeGame的輪軸
     * @test
     */
    public function handleFreeGameRollerNumber()
    {
        // Arrange
        $aAccumulationInfo = [
            'FreeGame' => [
                'FreeGameTime' => 1,
            ],
            'RollerNumber' => 0,
        ];
        $aExpected = [
            'FreeGame' => [
                'FreeGameTime' => 1,
            ],
            'RollerNumber' => 1,
        ];

        // Act
        $aActual = $this->oRarity->handleRollerNumber($aAccumulationInfo);

        // Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * 換10以上的牌
     * @test
     */
    public function changeCards()
    {
        // Arrange
        $aCardInfo = [
            'Program' => '7-8-13-9-13-14-12-5-15-6-11-8-6-7-4',
        ];
        $aExpected = '7-8-3-9-3-4-2-5-9-6-1-8-6-7-4';

        // Act
        $aActual = $this->oRarity->changeCards($aCardInfo, $this->aSetting['ChangeCards']);

        // Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * 換成指定輸出格式
     * @test
     */
    public function formatCards()
    {
        // Arrange
        $aCardInfo = [
            'Flash' => '7-8-3,9-3-4,2-5-9,6-1-8,6-7-4',
        ];
        $aExpected = [
            '7-8-3',
            '9-3-4',
            '2-5-9',
            '6-1-8',
            '6-7-4',
        ];

        // Act
        $aActual = $this->oRarity->formatCards($aCardInfo);

        // Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * 中權重換輪軸
     * @test
     */
    public function changeRollerByRateWeight()
    {
        // Arrange
        $aAccumulationInfo = [
            'RollerNumber' => 0,
        ];

        $aExpected = [
            'AccumulationInfo' => [
                'ExpandRoller' => 1,
            ],
            'ChangeCards' => [
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5,
                6 => 6,
                7 => 7,
                8 => 8,
                9 => 9,
                10 => 10,
                11 => 1,
                12 => 2,
                13 => 3,
                14 => 4,
                15 => 9,
                16 => 1,
                17 => 2,
                18 => 3,
                19 => 4,
                20 => 9,
            ],
    
            'RateWeight' => [
                0 => 98540,
                9 => 10, 
                1 => 340, 
                2 => 337, 
                3 => 300, 
                4 => 473,
            ],
    
            // 一般滾輪
            'RollerContainer'       => [
                0 => [
                    0 => [4,4,4],
                    1 => [4,4,4],
                    2 => [1,1,1,2,2,2,4,4,4,3,3,3,1,1,1,9,9,9,3,3,3,4,4,2,2,9,9,3,3,4,4,3,3,4,4,2,2,4,4,1,1,9,9,3,3,4,4,3,3,4,4],
                    3 => [1,1,1,2,2,2,4,4,4,3,3,3,1,1,1,9,9,9,3,3,3,4,4,2,2,9,9,3,3,4,4,3,3,4,4,2,2,4,4,1,1,9,9,3,3,4,4,3,3,4,4],
                    4 => [1,1,1,2,2,2,4,4,4,3,3,3,1,1,1,9,9,9,3,3,3,4,4,2,2,9,9,3,3,4,4,3,3,4,4,2,2,4,4,1,1,9,9,3,3,4,4,3,3,4,4],
                ],
            ],
            'SpecialRoller'       => [
                // MainGame
                0 => [
                    1 => [2,2,2,3,3,3,4,4,4,9,9,9,2,2,2,4,4,4,3,3,3,2,2,2,4,4,4,3,3,3,4,4,4,2,2,2,3,3,3,4,4,4,2,2,2,3,3,3,4,4,4,2,2,2,3,3,3,4,4,4,2,2,2,3,3,3,4,4,4,3,3,3,4,4,4,2,2,2,3,3,3,4,4,4,3,3,3,4,4,4,2,2,2,3,3,3,4,4,4,1,1,4,4,3,3],
                    2 => [1,1,1,3,3,3,4,4,4,3,3,3,4,4,4,9,9,9,3,3,3,1,1,1,4,4,4,3,3,3,4,4,4,1,1,1,4,4,4,3,3,3,4,4,4,1,1,1,4,4,4,3,3,3,1,1,1,3,3,3,4,4,4,3,3,3,2,2,3,3,4,4],
                    3 => [1,1,1,2,2,2,3,3,3,4,4,4,9,9,9,1,1,1,2,2,2,4,4,4,2,2,1,1,9,9,4,4,4,2,2,2,4,4,4,2,2,3,3,1,1,2,2,3,3,4,4],
                    4 => [1,1,1,2,2,2,4,4,4,3,3,3,1,1,1,9,9,9,3,3,3,4,4,2,2,9,9,3,3,4,4,3,3,4,4,2,2,4,4,1,1,9,9,3,3,4,4,3,3,4,4],
                    9 => [4,4,4,9,9,9,4,4,4,2,2,3,3,4,4,2,2,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,2,2,3,3,1,1,2,2,3,3,1,1,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,2,2,3,3,1,1,2,2,4,4,2,2,3,3,4,4,2,2,4,4,3,3,2,2,3,3,4,4,2,2,3,3,4,4,2,2,3,3,4,4,2,2,3,3]
                ],
                //FreeGame
                1 => [
                    1 => [1,1,1,2,2,2,3,3,3,4,4,4,9,9,9,4,4,4,3,3,3,4,4,4,2,2,2,10,10,10,4,4,4,2,2,2,3,3,3,4,4,4,2,2,2,3,3,3,1,1,2,2,10,10,4,4,4,9,9,3,3,1,1,2,2,10,10,4,4,1,1,3,3,9,9,2,2,1,1,4,4],
                    2 => [1,1,1,2,2,2,3,3,3,4,4,4,9,9,9,4,4,4,1,1,1,3,3,3,2,2,1,1,9,9,4,4,4,1,1,1,10,10,10,4,4,4,3,3,3,2,2,4,4,2,2,1,1,1,10,10,10,4,4,4,9,9,1,1,2,2,3,3,2,2,1,1,10,10,10,4,4,2,2,3,3,9,9,1,1,2,2],
                    3 => [1,1,1,3,3,3,2,2,2,10,10,10,4,4,4,9,9,9,4,4,4,1,1,1,3,3,4,4,3,3,2,2,2,3,3,4,4,9,9,1,1,1,10,10,10,4,4,3,3,1,1,9,9,4,4,2,2,10,10,10,4,4,3,3,2,2,9,9,1,1,3,3],
                    4 => [1,1,1,2,2,2,3,3,3,1,1,1,9,9,9,3,3,3,2,2,2,10,10,10,4,4,1,1,9,9,2,2,2,1,1,1,10,10,10,4,4,1,1,9,9,2,2,2,10,10,10,4,4,3,3,2,2,10,10,10,4,4,1,1,9,9],
                    9 => [3,3,4,4,3,3,4,4,9,9,4,4,3,3,4,4,3,3,10,10,10,4,4,2,2,3,3,10,10,4,4,3,3,10,10,4,4,3,3,10,10,4,4,3,3,4,4,3,3,10,10,4,4,2,2,3,3,4,4,2,2,4,4,3,3,10,10,4,4,2,2,3,3,4,4,2,2,4,4,3,3,10,10,4,4,3,3,1,1,4,4,2,2]
                ],
            ],

        ];

        // Act
        $oMock = $this->getMockBuilder('Framework\Foundation\RandomTest')->setMethods(['randByWeight'])->getMock();
        $oMock->method('randByWeight')->will($this->onConsecutiveCalls(4));
        RandomTest::$oMock = $oMock;

        $aActual = $this->oRarity->changeRoller($aAccumulationInfo, $this->aSetting);


        // Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * 免費遊戲換輪軸
     * @test
     */
    public function changeRollerByFreeGame()
    {
        // Arrange
        $aAccumulationInfo = [
            'RollerNumber' => 1,
            'FreeGame' =>[
                'Lead' => 4,
            ]
        ];

        $aExpected = [
            'AccumulationInfo' => [
                'ExpandRoller' => 1,
            ],
            'ChangeCards' => [
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5,
                6 => 6,
                7 => 7,
                8 => 8,
                9 => 9,
                10 => 10,
                11 => 1,
                12 => 2,
                13 => 3,
                14 => 4,
                15 => 9,
                16 => 1,
                17 => 2,
                18 => 3,
                19 => 4,
                20 => 9,
            ],
    
            'RateWeight' => [
                0 => 98540,
                9 => 10, 
                1 => 340, 
                2 => 337, 
                3 => 300, 
                4 => 473,
            ],
    
            // 一般滾輪
            'RollerContainer'       => [
                0 => [
                    0 => [4,4,4],
                    1 => [4,4,4],
                    2 => [1,1,1,2,2,2,3,3,3,1,1,1,9,9,9,3,3,3,2,2,2,10,10,10,4,4,1,1,9,9,2,2,2,1,1,1,10,10,10,4,4,1,1,9,9,2,2,2,10,10,10,4,4,3,3,2,2,10,10,10,4,4,1,1,9,9],
                    3 => [1,1,1,2,2,2,3,3,3,1,1,1,9,9,9,3,3,3,2,2,2,10,10,10,4,4,1,1,9,9,2,2,2,1,1,1,10,10,10,4,4,1,1,9,9,2,2,2,10,10,10,4,4,3,3,2,2,10,10,10,4,4,1,1,9,9],
                    4 => [1,1,1,2,2,2,3,3,3,1,1,1,9,9,9,3,3,3,2,2,2,10,10,10,4,4,1,1,9,9,2,2,2,1,1,1,10,10,10,4,4,1,1,9,9,2,2,2,10,10,10,4,4,3,3,2,2,10,10,10,4,4,1,1,9,9],
                ],
            ],
            'SpecialRoller'       => [
                // MainGame
                0 => [
                    1 => [2,2,2,3,3,3,4,4,4,9,9,9,2,2,2,4,4,4,3,3,3,2,2,2,4,4,4,3,3,3,4,4,4,2,2,2,3,3,3,4,4,4,2,2,2,3,3,3,4,4,4,2,2,2,3,3,3,4,4,4,2,2,2,3,3,3,4,4,4,3,3,3,4,4,4,2,2,2,3,3,3,4,4,4,3,3,3,4,4,4,2,2,2,3,3,3,4,4,4,1,1,4,4,3,3],
                    2 => [1,1,1,3,3,3,4,4,4,3,3,3,4,4,4,9,9,9,3,3,3,1,1,1,4,4,4,3,3,3,4,4,4,1,1,1,4,4,4,3,3,3,4,4,4,1,1,1,4,4,4,3,3,3,1,1,1,3,3,3,4,4,4,3,3,3,2,2,3,3,4,4],
                    3 => [1,1,1,2,2,2,3,3,3,4,4,4,9,9,9,1,1,1,2,2,2,4,4,4,2,2,1,1,9,9,4,4,4,2,2,2,4,4,4,2,2,3,3,1,1,2,2,3,3,4,4],
                    4 => [1,1,1,2,2,2,4,4,4,3,3,3,1,1,1,9,9,9,3,3,3,4,4,2,2,9,9,3,3,4,4,3,3,4,4,2,2,4,4,1,1,9,9,3,3,4,4,3,3,4,4],
                    9 => [4,4,4,9,9,9,4,4,4,2,2,3,3,4,4,2,2,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,2,2,3,3,1,1,2,2,3,3,1,1,4,4,3,3,4,4,3,3,4,4,3,3,4,4,3,3,2,2,3,3,1,1,2,2,4,4,2,2,3,3,4,4,2,2,4,4,3,3,2,2,3,3,4,4,2,2,3,3,4,4,2,2,3,3,4,4,2,2,3,3]
                ],
                //FreeGame
                1 => [
                    1 => [1,1,1,2,2,2,3,3,3,4,4,4,9,9,9,4,4,4,3,3,3,4,4,4,2,2,2,10,10,10,4,4,4,2,2,2,3,3,3,4,4,4,2,2,2,3,3,3,1,1,2,2,10,10,4,4,4,9,9,3,3,1,1,2,2,10,10,4,4,1,1,3,3,9,9,2,2,1,1,4,4],
                    2 => [1,1,1,2,2,2,3,3,3,4,4,4,9,9,9,4,4,4,1,1,1,3,3,3,2,2,1,1,9,9,4,4,4,1,1,1,10,10,10,4,4,4,3,3,3,2,2,4,4,2,2,1,1,1,10,10,10,4,4,4,9,9,1,1,2,2,3,3,2,2,1,1,10,10,10,4,4,2,2,3,3,9,9,1,1,2,2],
                    3 => [1,1,1,3,3,3,2,2,2,10,10,10,4,4,4,9,9,9,4,4,4,1,1,1,3,3,4,4,3,3,2,2,2,3,3,4,4,9,9,1,1,1,10,10,10,4,4,3,3,1,1,9,9,4,4,2,2,10,10,10,4,4,3,3,2,2,9,9,1,1,3,3],
                    4 => [1,1,1,2,2,2,3,3,3,1,1,1,9,9,9,3,3,3,2,2,2,10,10,10,4,4,1,1,9,9,2,2,2,1,1,1,10,10,10,4,4,1,1,9,9,2,2,2,10,10,10,4,4,3,3,2,2,10,10,10,4,4,1,1,9,9],
                    9 => [3,3,4,4,3,3,4,4,9,9,4,4,3,3,4,4,3,3,10,10,10,4,4,2,2,3,3,10,10,4,4,3,3,10,10,4,4,3,3,10,10,4,4,3,3,4,4,3,3,10,10,4,4,2,2,3,3,4,4,2,2,4,4,3,3,10,10,4,4,2,2,3,3,4,4,2,2,4,4,3,3,10,10,4,4,3,3,1,1,4,4,2,2]
                ],
            ],

        ];

        // Act
        $aActual = $this->oRarity->changeRoller($aAccumulationInfo, $this->aSetting);


        // Assert
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * 擴展4,5軸
     * @test
     */
    public function expand4and5Roller()
    {
        // Arrange
        $aCardInfo = [
            'Flash' => '4-4-4,4-4-4,3-1-1,2-3-3,1-3-3',
        ];
        $aSetting['AccumulationInfo']['ExpandRoller'] = 1;
        $aSetting['MaxRows'] = 3;
        $aSetting['MaxCols'] = 5;

        $aExpected = [
            'Flash' => '4-4-4,4-4-4,3-1-1,3-1-1,3-1-1',
            'Program' => '4-4-4-4-4-4-3-1-1-3-1-1-3-1-1',
            'CardsCount' => [
                'GridNum' => [
                    '4' => 6,
                    '3' => 3,
                    '1' => 6,
                ],
                'Grid' => [
                    '4' => '1,2,3,4,5,6',
                    '3' => '7,10,13',
                    '1' => '8,9,11,12,14,15',
                ],
            ],
        ];

        // Act
        $aActual = $this->oRarity->expand4and5Roller($aCardInfo, $aSetting);

        // Assert
        $this->assertEquals($aExpected, $aActual);
    }

}