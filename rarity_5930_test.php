<?php

namespace Tests\Unit\Rarity\Casino\Game;

use Framework\Rarity\Casino\Game\Rarity_5930;
use Framework\Support\Facades\Facade;
use Framework\Foundation\RandomTest;
use PHPUnit\Framework\TestCase;

class Rarity_5930_Test extends TestCase
{
    private $oMod;
    private $aSetting = [
        'MaxRows' => 8,
        'MaxCols' => 8,
        'MainGameCardWeight' => [
            1 => 50,
            2 => 70,
            3 => 70,
            4 => 100,
            5 => 120,
            6 => 115,
            7 => 120,
        ],
        //由內而外
        'CardDirection' => [
            0 => 36,
            1 => 28,
            2 => 27,
            3 => 35,
            4 => 43,
            5 => 44,
            6 => 45,
            7 => 37,
            8 => 29,
            9 => 21,
            10 => 20,
            11 => 19,
            12 => 18,
            13 => 26,
            14 => 34,
            15 => 42,
            16 => 50,
            17 => 51,
            18 => 52,
            19 => 53,
            20 => 54,
            21 => 46,
            22 => 38,
            23 => 30,
            24 => 22,
            25 => 14,
            26 => 13,
            27 => 12,
            28 => 11,
            29 => 10,
            30 => 9,
            31 => 17,
            32 => 25,
            33 => 33,
            34 => 41,
            35 => 49,
            36 => 57,
            37 => 58,
            38 => 59,
            39 => 60,
            40 => 61,
            41 => 62,
            42 => 63,
            43 => 55,
            44 => 47,
            45 => 39,
            46 => 31,
            47 => 23,
            48 => 15,
            49 => 7,
            50 => 6,
            51 => 5,
            52 => 4,
            53 => 3,
            54 => 2,
            55 => 1,
            56 => 0,
            57 => 8,
            58 => 16,
            59 => 24,
            60 => 32,
            61 => 40,
            62 => 48,
            63 => 56,
        ],
        // 開啟特殊遊戲權重
        'HitSpecialGameWeight' => [
            0 => 34,
            1 => 1,
        ],
        // 各特殊遊戲權重
        'SpecialGameTypeWeight' => [
            8  => 10,
            9  => 40,
            10 => 37,
        ],
        // 特殊遊戲出牌權重
        'SpecialGameCardWeight9'  => [
            1 => 5,
            2 => 8,
            3 => 4,
            4 => 4,
            5 => 8,
            6 => 5,
            7 => 3,
        ],
        'SpecialGameCardWeight10'  => [
            1 => 3,
            2 => 8,
            3 => 6,
            4 => 4,
            5 => 5,
            6 => 7,
            7 => 10,
        ],

    ];
    public function setUp()
    {
        //清除Facde以免影響後面TestCase
        Facade::clearResolvedInstances();
        $aFacades['Random']     = new RandomTest();
        Facade::setFacadeApplication($aFacades);
        $this->oMod = new Rarity_5930();
    }

    /**
     * 產牌_初始 8x8
     * @test
     * @group Rarity
     */
    public function generateFirstCards8x8()
    {
        //Arrange
        $aCardInfo = [];
        $aInput = [
            1, 1, 1, 1, 1, 1, 1, 1,
            1, 1, 1, 1, 1, 1, 1, 1,
            1, 1, 1, 1, 1, 1, 1, 1,
            1, 1, 1, 1, 1, 1, 1, 1,
            1, 1, 1, 1, 1, 1, 1, 1,
            1, 1, 1, 1, 1, 1, 1, 1,
            1, 1, 1, 1, 1, 1, 1, 1,
            1, 1, 1, 1, 1, 1, 1, 1
        ];
        $oMock = $this->getMockBuilder('Framework\Foundation\RandomTest')->setMethods(['randByWeight'])->getMock();
        $oMock->method('randByWeight')->will($this->returnValue(1));
        RandomTest::$oMock = $oMock;

        // Act
        $aOutput = $this->oMod->generateFirstCards($this->aSetting);

        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 計算連線
     * @test
     * @group Rarity
     */
    public function getLine()
    {
        //Arrange
        $aCard = [
            2,2,2,1,3,2,2,2,
            2,2,1,3,2,1,3,2
        ];
        $iGrid = 0;
        $aLine[0] = $iGrid;
        $iFirstCard = $aCard[$iGrid];
        $aInput = [0,1,2,8,9];

        // Act
        $aOutput = $this->oMod->getLine($iGrid, $aLine, $aCard, $iFirstCard);

        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 計算全部連線 一條
     * @test
     * @group Rarity
     */
    public function calculateOneLineInfo()
    {
        //Arrange
        $aCard = [
            'Cards' => [
                [
                    1, 1, 1, 1, 1, 2, 1, 1,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 1, 1, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5
                ]
            ],
            'Special' => [],
            'Lines' => [],
        ];

        $aInput = [
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
            'HitLine' => true,
        ];


        $oMock = $this->getMockBuilder('Framework\Foundation\RandomTest')->setMethods(['randByWeight'])->getMock();
        $oMock->method('randByWeight')->will($this->returnValue(2));
        RandomTest::$oMock = $oMock;
        // Act
        $aOutput = $this->oMod->calculateLineInfo($aCard, $this->aSetting);

        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 計算全部連線 三條
     * @test
     * @group Rarity
     */
    public function calculateThreeLineInfo()
    {
        //Arrange
        $aCard = [
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
                ]
            ],
            'Special' => [],
            'Lines' => [],
        ];

        $aInput = [
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
                ]
            ],
            'Special' => [],
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
            'HitLine' => true,
        ];

        $oMock = $this->getMockBuilder('Framework\Foundation\RandomTest')->setMethods(['randByWeight'])->getMock();
        $oMock->method('randByWeight')->will($this->returnValue(4));
        RandomTest::$oMock = $oMock;
        // Act
        $aOutput = $this->oMod->calculateLineInfo($aCard, $this->aSetting);

        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 把連線歸0 一條
     * @test
     * @group Rarity
     */
    public function setOneLineToZero()
    {
        //Arrange
        $aCard = [
            1, 1, 1, 1, 1, 2, 1, 1,
            2, 2, 2, 4, 5, 5, 5, 5,
            6, 7, 3, 3, 2, 2, 2, 2,
            2, 1, 1, 4, 5, 5, 5, 5,
            6, 7, 3, 3, 2, 2, 2, 2,
            2, 2, 2, 4, 5, 5, 5, 5,
            6, 7, 3, 3, 2, 2, 2, 2,
            2, 2, 2, 4, 5, 5, 5, 5
        ];

        $aLine = [0,1,2,3,4];

        $aInput = [
            0, 0, 0, 0, 0, 2, 1, 1,
            2, 2, 2, 4, 5, 5, 5, 5,
            6, 7, 3, 3, 2, 2, 2, 2,
            2, 1, 1, 4, 5, 5, 5, 5,
            6, 7, 3, 3, 2, 2, 2, 2,
            2, 2, 2, 4, 5, 5, 5, 5,
            6, 7, 3, 3, 2, 2, 2, 2,
            2, 2, 2, 4, 5, 5, 5, 5
        ];

        // Act
        $aOutput = $this->oMod->setLineToZero($aCard, $aLine);

        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 把連線歸0 三條
     * @test
     * @group Rarity
     */
    public function setThreeLineToZero()
    {
        //Arrange
        $aCard = [
            1, 1, 1, 1, 1, 2, 1, 3,
            2, 2, 2, 4, 5, 5, 5, 3,
            6, 7, 3, 3, 2, 2, 2, 3,
            2, 1, 1, 4, 5, 5, 5, 3,
            6, 7, 3, 3, 2, 2, 2, 3,
            2, 2, 2, 4, 5, 5, 2, 2,
            6, 7, 3, 3, 2, 2, 2, 2,
            2, 2, 2, 4, 5, 5, 5, 5
        ];

        $aLine = [0,1,2,3,4,7,15,23,31,39,36,37,38,46,47,52,53,54,55];

        $aInput = [
            0, 0, 0, 0, 0, 2, 1, 0,
            2, 2, 2, 4, 5, 5, 5, 0,
            6, 7, 3, 3, 2, 2, 2, 0,
            2, 1, 1, 4, 5, 5, 5, 0,
            6, 7, 3, 3, 0, 0, 0, 0,
            2, 2, 2, 4, 5, 5, 0, 0,
            6, 7, 3, 3, 0, 0, 0, 0,
            2, 2, 2, 4, 5, 5, 5, 5
        ];

        // Act
        $aOutput = $this->oMod->setLineToZero($aCard, $aLine);

        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 迴旋遞補0 一條
     * @test
     * @group Rarity
     */
    public function formatOneCardFall()
    {
        //Arrange
        $aCard = [
            0, 0, 0, 0, 0, 2, 1, 1,
            2, 2, 2, 4, 5, 5, 5, 5,
            6, 7, 3, 3, 2, 2, 2, 2,
            2, 1, 1, 4, 5, 5, 5, 5,
            6, 7, 3, 3, 2, 2, 2, 2,
            2, 2, 2, 4, 5, 5, 5, 5,
            6, 7, 3, 3, 2, 2, 2, 2,
            2, 2, 2, 4, 5, 5, 5, 5
        ];

        $aInput = [
            2, 6, 2, 6, 2, 2, 1, 1,
            6, 2, 2, 4, 5, 5, 5, 5,
            2, 7, 3, 3, 2, 2, 2, 2,
            0, 1, 1, 4, 5, 5, 5, 5,
            0, 7, 3, 3, 2, 2, 2, 2,
            0, 2, 2, 4, 5, 5, 5, 5,
            0, 7, 3, 3, 2, 2, 2, 2,
            0, 2, 2, 4, 5, 5, 5, 5
        ];

        // Act
        $aOutput = $this->oMod->formatCardFall($aCard, $this->aSetting);

        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 迴旋遞補0 三條
     * @test
     * @group Rarity
     */
    public function formatThreeCardFall()
    {
        //Arrange
        $aCard = [
            0, 0, 0, 0, 0, 2, 1, 0,
            2, 2, 2, 4, 5, 5, 5, 0,
            6, 7, 3, 3, 2, 2, 2, 0,
            2, 1, 1, 4, 5, 5, 5, 0,
            6, 7, 3, 3, 0, 0, 0, 0,
            2, 2, 2, 4, 5, 5, 0, 0,
            6, 7, 3, 3, 0, 0, 0, 0,
            2, 2, 2, 4, 5, 5, 5, 5
        ];

        $aInput = [
            0, 0, 0, 0, 0, 0, 0, 0,
            0, 2, 2, 7, 2, 7, 1, 0,
            0, 4, 3, 1, 3, 3, 7, 0,
            0, 5, 2, 3, 4, 2, 2, 0,
            0, 5, 3, 4, 5, 2, 2, 0,
            0, 5, 3, 5, 5, 5, 4, 2,
            0, 5, 5, 2, 5, 5, 5, 6,
            0, 1, 2, 2, 6, 2, 6, 2
        ];

        // Act
        $aOutput = $this->oMod->formatCardFall($aCard, $this->aSetting);

        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 補0牌
     * @test
     * @group Rarity
     */
    public function generateFillCard()
    {
        //Arrange
        $aCard = [
            0, 0, 0, 0, 0, 0, 0, 0,
            0, 2, 2, 7, 2, 7, 1, 0,
            0, 4, 3, 1, 3, 3, 7, 0,
            0, 5, 2, 3, 4, 2, 2, 0,
            0, 5, 3, 4, 5, 2, 2, 0,
            0, 5, 3, 5, 5, 5, 4, 2,
            0, 5, 5, 2, 5, 5, 5, 6,
            0, 1, 2, 2, 6, 2, 6, 2
        ];

        $aInput = [
            1, 1, 1, 1, 1, 1, 1, 1,
            1, 2, 2, 7, 2, 7, 1, 1,
            1, 4, 3, 1, 3, 3, 7, 1,
            1, 5, 2, 3, 4, 2, 2, 1,
            1, 5, 3, 4, 5, 2, 2, 1,
            1, 5, 3, 5, 5, 5, 4, 2,
            1, 5, 5, 2, 5, 5, 5, 6,
            1, 1, 2, 2, 6, 2, 6, 2
        ];
        $oMock = $this->getMockBuilder('Framework\Foundation\RandomTest')->setMethods(['randByWeight'])->getMock();
        $oMock->method('randByWeight')->will($this->returnValue(1));
        RandomTest::$oMock = $oMock;

        // Act
        $aOutput = $this->oMod->generateFillCard($aCard, $this->aSetting);

        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 是否開啟特殊遊戲 是
     * @test
     * @group Rarity
     */
    public function hitSpecialGameYes()
    {
        //Arrange
        $aCard = [
            1, 1, 1, 1, 1, 2, 1, 1,
            2, 2, 2, 4, 5, 5, 5, 5,
            6, 7, 3, 3, 2, 2, 2, 2,
            2, 1, 1, 4, 5, 5, 5, 5,
            6, 7, 3, 3, 2, 2, 2, 2,
            2, 2, 2, 4, 5, 5, 5, 5,
            6, 7, 3, 3, 2, 2, 2, 2,
            2, 2, 2, 4, 5, 5, 5, 5
        ];
        $aCardInfo = [
            'Lines' => [],
            'Cards' => [],
            'Special' => [],
        ];
        $aAccumulationInfo = [
            'FreeGame' => [],
        ];

        $aInput = [
            'Cards' => [
                [
                    1, 1, 1, 1, 1, 2, 1, 1,
                    2, 2, 8, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 1, 1, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5
                ]
            ],
            'Special' => [
                'Done' => false,
                'SpecialType' => 8,
                'Grid' => 10,
            ],
            'Lines' => [],
        ];
        $oMock = $this->getMockBuilder('Framework\Foundation\RandomTest')->setMethods(['randByWeight','rand'])->getMock();
        $oMock->method('randByWeight')->will($this->onConsecutiveCalls(1, 8));
        $oMock->method('rand')->will($this->returnValue(10));

        RandomTest::$oMock = $oMock;

        // Act
        $aOutput = $this->oMod->hitSpecialGame($aCard, $aCardInfo, $this->aSetting, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 是否開啟特殊遊戲 否
     * @test
     * @group Rarity
     */
    public function hitSpecialGameNo()
    {
        //Arrange
        $aCard = [
            1, 1, 1, 1, 1, 2, 1, 1,
            2, 2, 2, 4, 5, 5, 5, 5,
            6, 7, 3, 3, 2, 2, 2, 2,
            2, 1, 1, 4, 5, 5, 5, 5,
            6, 7, 3, 3, 2, 2, 2, 2,
            2, 2, 2, 4, 5, 5, 5, 5,
            6, 7, 3, 3, 2, 2, 2, 2,
            2, 2, 2, 4, 5, 5, 5, 5
        ];
        $aCardInfo = [
            'Lines' => [],
            'Cards' => [],
            'Special' => [],
        ];
        $aAccumulationInfo = [
            'FreeGame' => [
                'FreeGameTime' => 2,
            ],
        ];

        $aInput = [
            'Cards' => [
                [
                    1, 1, 1, 1, 1, 2, 1, 1,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 1, 1, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5
                ]
            ],
            'Special' => [],
            'Lines' => [],
        ];
        $oMock = $this->getMockBuilder('Framework\Foundation\RandomTest')->setMethods(['randByWeight','rand'])->getMock();
        $oMock->method('randByWeight')->will($this->onConsecutiveCalls(0));

        RandomTest::$oMock = $oMock;

        // Act
        $aOutput = $this->oMod->hitSpecialGame($aCard, $aCardInfo, $this->aSetting, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 產牌
     * @test
     * @group Rarity
     */
    public function handleCardInfo()
    {
        //Arrange
        $aAccumulationInfo = [
            'FreeGame' => [],
        ];

        $aInput = [
            'Cards' => [
                [
                    1, 1, 1, 1, 1, 2, 1, 1,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 1, 1, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5
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
            'Special' => [],
            'Lines' => [
                [
                    [
                        'ElementID' => 1,
                        'Grids' => [0,1,2,3,4],
                        'GridNum' => 5,
                    ],
                ],
                []
             ],
            'HitLine' => false,

        ];
        $oMock = $this->getMockBuilder('Framework\Foundation\RandomTest')->setMethods(['randByWeight','rand'])->getMock();
        $oMock->method('randByWeight')->will($this->onConsecutiveCalls(
            1, 1, 1, 1, 1, 2, 1, 1,
            2, 2, 2, 4, 5, 5, 5, 5,
            6, 7, 3, 3, 2, 2, 2, 2,
            2, 1, 1, 4, 5, 5, 5, 5,
            6, 7, 3, 3, 2, 2, 2, 2,
            2, 2, 2, 4, 5, 5, 5, 5,
            6, 7, 3, 3, 2, 2, 2, 2,
            2, 2, 2, 4, 5, 5, 5, 5,
            0,
            2, 6, 2, 6, 2
        ));
        $oMock->method('rand')->will($this->returnValue(10));

        RandomTest::$oMock = $oMock;

        // Act
        $aOutput = $this->oMod->handleCardInfo($this->aSetting, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 特殊遊戲連線-9
     * @test
     * @group Rarity
     */
    public function caculateSpecialLine_9()
    {
        //Arrange
        $aCardInfo = [
            'Cards' => [
                [
                    1, 1, 1, 1, 1, 2, 1, 1,
                    2, 2, 9, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 1, 1, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5
                ],
                [
                    2, 6, 2, 6, 2, 2, 1, 1,
                    6, 2, 9, 4, 5, 5, 5, 5,
                    2, 7, 3, 3, 2, 2, 2, 2,
                    2, 1, 1, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5
                ]
            ],
            'Special' => [
                'Done' => false,
                'SpecialType' => 9,
                'Grid' => 10,
            ],
            'Lines' => [
                [
                    [
                        'ElementID' => 1,
                        'Grids' => [0,1,2,3,4],
                        'GridNum' => 5,
                    ],
                ],
                []
             ],
            'HitLine' => false,

        ];

        $aInput = [
            'Cards' => [
                [
                    1, 1, 1, 1, 1, 2, 1, 1,
                    2, 2, 9, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 1, 1, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5
                ],
                [
                    2, 6, 2, 6, 2, 2, 1, 1,
                    6, 2, 9, 4, 5, 5, 5, 5,
                    2, 7, 3, 3, 2, 2, 2, 2,
                    2, 1, 1, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5
                ],
                [
                    2, 3, 2, 3, 2, 2, 1, 1,
                    6, 2, 3, 4, 5, 5, 5, 5,
                    2, 3, 3, 3, 2, 2, 2, 2,
                    3, 1, 1, 4, 3, 5, 5, 5,
                    6, 7, 3, 3, 2, 3, 2, 2,
                    2, 2, 2, 4, 5, 5, 3, 5,
                    6, 7, 3, 3, 2, 2, 2, 3,
                    2, 2, 2, 4, 5, 5, 5, 5
                ]
            ],
            'Special' => [
                'Done' => true,
                'SpecialType' => 9,
                'Grid' => 10,
            ],
            'Lines' => [
                [
                    [
                        'ElementID' => 1,
                        'Grids' => [0,1,2,3,4],
                        'GridNum' => 5,
                    ],
                ],
                [],
                []
             ],
            'HitLine' => false,
        ];
        $oMock = $this->getMockBuilder('Framework\Foundation\RandomTest')->setMethods(['randByWeight','rand'])->getMock();
        $oMock->method('randByWeight')->will($this->onConsecutiveCalls(3));

        RandomTest::$oMock = $oMock;

        // Act
        $aOutput = $this->oMod->caculateSpecialLine($aCardInfo, $this->aSetting);

        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 特殊遊戲連線-10
     * @test
     * @group Rarity
     */
    public function caculateSpecialLine_10()
    {
        //Arrange
        $aCardInfo = [
            'Cards' => [
                [
                    1, 1, 1, 1, 1, 2, 1, 1,
                    2, 2, 10, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 1, 1, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5
                ],
                [
                    2, 6, 2, 6, 2, 2, 1, 1,
                    6, 2, 10, 4, 5, 5, 5, 5,
                    2, 7, 3, 3, 2, 2, 2, 2,
                    2, 1, 1, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5
                ]
            ],
            'Special' => [
                'Done' => false,
                'SpecialType' => 10,
                'Grid' => 10,
            ],
            'Lines' => [
                [
                    [
                        'ElementID' => 1,
                        'Grids' => [0,1,2,3,4],
                        'GridNum' => 5,
                    ],
                ],
                []
             ],
            'HitLine' => false,

        ];

        $aInput = [
            'Cards' => [
                [
                    1, 1, 1, 1, 1, 2, 1, 1,
                    2, 2, 10, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 1, 1, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5
                ],
                [
                    2, 6, 2, 6, 2, 2, 1, 1,
                    6, 2, 10, 4, 5, 5, 5, 5,
                    2, 7, 3, 3, 2, 2, 2, 2,
                    2, 1, 1, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5,
                    6, 7, 3, 3, 2, 2, 2, 2,
                    2, 2, 2, 4, 5, 5, 5, 5
                ],
                [
                    2, 6, 2, 6, 2, 2, 6, 2,
                    1, 2, 2, 7, 2, 7, 1, 6,
                    1, 4, 2, 2, 1, 2, 7, 2,
                    1, 5, 2, 4, 5, 2, 2, 6,
                    1, 5, 2, 4, 2, 5, 4, 2,
                    1, 5, 5, 5, 5, 2, 5, 2,
                    1, 5, 2, 5, 2, 5, 5, 1,
                    1, 2, 5, 2, 5, 2, 5, 1,
                ]
            ],
            'Special' => [
                'Done' => true,
                'SpecialType' => 10,
                'Grid' => 10,
            ],
            'Lines' => [
                [
                    [
                        'ElementID' => 1,
                        'Grids' => [0,1,2,3,4],
                        'GridNum' => 5,
                    ],
                ],
                [],
                [
                    [
                        'ElementID' => 3,
                        'Grids' => [10,18,19,34,35,50,51],
                        'GridNum' => 7,
                        'SpecialType' => 10,
                        'Grid' => 10,
                    ],
                ]
             ],
            'HitLine' => true,
        ];
        $oMock = $this->getMockBuilder('Framework\Foundation\RandomTest')->setMethods(['randByWeight','rand'])->getMock();
        $oMock->method('randByWeight')->will($this->onConsecutiveCalls(3, 1, 1, 1, 1, 1, 1, 1));

        RandomTest::$oMock = $oMock;

        // Act
        $aOutput = $this->oMod->caculateSpecialLine($aCardInfo, $this->aSetting);

        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 產牌(有特殊遊戲9)
     * @test
     * @group Rarity
     */
    public function handleCardInfo_Special9()
    {
        //Arrange
        $aAccumulationInfo = [
            'FreeGame' => [],
        ];

        $aInput = [
            'Cards' => [
                [
                    3, 7, 7, 7, 4, 1, 2, 5,
                    5, 1, 7, 4, 3, 5, 6, 5,
                    4, 7, 1, 9, 5, 6, 5, 5,
                    5, 5, 4, 5, 6, 4, 6, 2,
                    4, 6, 7, 7, 6, 6, 7, 5,
                    2, 3, 1, 4, 3, 1, 7, 1,
                    4, 6, 3, 6, 6, 1, 6, 6,
                    3, 5, 4, 1, 3, 1, 5, 7,
                ],
                [
                    3, 4, 7, 7, 4, 4, 2, 5,
                    5, 1, 4, 4, 4, 5, 6, 5,
                    4, 7, 1, 4, 5, 6, 5, 5,
                    5, 5, 4, 5, 4, 4, 6, 2,
                    4, 4, 7, 7, 6, 4, 7, 5,
                    4, 3, 1, 4, 3, 1, 4, 1,
                    4, 6, 3, 6, 6, 1, 6, 4,
                    3, 5, 4, 1, 3, 1, 5, 7,
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
                    1, 1, 4, 3, 3, 5, 4, 5,
                    6, 5, 6, 3, 5, 7, 1, 3,
                    5, 4, 7, 1, 5, 6, 5, 4,
                    6, 1, 1, 5, 4, 4, 6, 7,
                    5, 3, 3, 7, 6, 4, 5, 7,
                    6, 1, 6, 4, 3, 1, 6, 2,
                    7, 5, 6, 1, 6, 4, 7, 5,
                    4, 7, 4, 1, 5, 2, 5, 5,
                ],
            ],
            'Special' => [
                'Done' => true,
                'SpecialType' => 9,
                'Grid' => 19,
            ],
            'Lines' => [
                [],
                [
                    [
                        'ElementID' => 4,
                        'Grids' => [4,5,10,11,12,19],
                        'GridNum' => 6,
                        'SpecialType' => 9,
                        'Grid' => 19,
                    ],
                ],
                [
                    [
                        'ElementID' => 4,
                        'Grids' => [0,1,2,10,18],
                        'GridNum' => 5,
                    ],
                ],
                []
             ],
            'HitLine' => false,

        ];
        $oMock = $this->getMockBuilder('Framework\Foundation\RandomTest')->setMethods(['randByWeight','rand'])->getMock();
        $oMock->method('randByWeight')->will($this->onConsecutiveCalls(
            3, 7, 7, 7, 4, 1, 2, 5,
            5, 1, 7, 4, 3, 5, 6, 5,
            4, 7, 1, 1, 5, 6, 5, 5,
            5, 5, 4, 5, 6, 4, 6, 2,
            4, 6, 7, 7, 6, 6, 7, 5,
            2, 3, 1, 4, 3, 1, 7, 1,
            4, 6, 3, 6, 6, 1, 6, 6,
            3, 5, 4, 1, 3, 1, 5, 7,
            1, 9,
            4,
            3, 4, 1, 1, 6, 5,
            6, 5, 6, 7, 4
        ));
        $oMock->method('rand')->will($this->returnValue(19));

        RandomTest::$oMock = $oMock;

        // Act
        $aOutput = $this->oMod->handleCardInfo($this->aSetting, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aInput, $aOutput);
    }

    /**
     * 產牌(有特殊遊戲10)
     * @test
     * @group Rarity
     */
    public function handleCardInfo_Special10()
    {
        //Arrange
        $aAccumulationInfo = [
            'FreeGame' => [],
        ];

        $aInput = [
            'Cards' => [
                [
                    5, 6, 7, 4, 7, 7, 5, 1,
                    2, 7, 1, 3, 3, 7, 7, 4,
                    7, 6, 10, 4, 4, 2, 1, 3,
                    5, 1, 1, 6, 7, 2, 1, 5,
                    6, 5, 4, 1, 7, 1, 7, 5,
                    1, 3, 5, 4, 2, 5, 1, 5,
                    5, 7, 6, 6, 5, 2, 2, 1,
                    4, 3, 2, 6, 5, 6, 3, 7
                ],
                [
                    7, 2, 6, 6, 1, 3, 4, 1,
                    1, 7, 3, 1, 6, 7, 1, 6,
                    2, 3, 4, 1, 4, 4, 3, 7,
                    5, 2, 6, 6, 7, 2, 3, 2,
                    2, 6, 6, 1, 7, 2, 7, 6,
                    7, 6, 2, 4, 2, 1, 7, 7,
                    5, 3, 2, 1, 7, 1, 1, 4,
                    3, 7, 1, 3, 4, 1, 7, 7
                ],
                [
                    7, 2, 5, 2, 1, 7, 2, 6,
                    5, 3, 2, 3, 7, 3, 1, 6,
                    3, 7, 2, 4, 1, 4, 6, 1,
                    6, 1, 2, 1, 7, 4, 7, 3,
                    7, 3, 1, 4, 7, 2, 1, 4,
                    6, 4, 7, 2, 1, 2, 3, 1,
                    7, 1, 1, 1, 7, 7, 3, 6,
                    4, 7, 7, 4, 7, 6, 2, 7
                ]

            ],
            'Special' => [
                'Done' => true,
                'SpecialType' => 10,
                'Grid' => 18,
            ],
            'Lines' => [
                [],
                [
                    [
                        'ElementID' => 5,
                        'Grids' => [0,6,18,24,31,33,39,42,45,47,48,52,60],
                        'GridNum' => 13,
                        'SpecialType' => 10,
                        'Grid' => 18,
                    ],
                ],
                [
                    [
                        'ElementID' => 6,
                        'Grids' => [26,27,33,34,41],
                        'GridNum' => 5,
                    ],
                ],
                []
             ],
            'HitLine' => false,

        ];
        $oMock = $this->getMockBuilder('Framework\Foundation\RandomTest')->setMethods(['randByWeight','rand'])->getMock();
        $oMock->method('randByWeight')->will($this->onConsecutiveCalls(
            5, 6, 7, 4, 7, 7, 5, 1,
            2, 7, 1, 3, 3, 7, 7, 4,
            7, 6, 4, 4, 4, 2, 1, 3,
            5, 1, 1, 6, 7, 2, 1, 5,
            6, 5, 4, 1, 7, 1, 7, 5,
            1, 3, 5, 4, 2, 5, 1, 5,
            5, 7, 6, 6, 5, 2, 2, 1,
            4, 3, 2, 6, 5, 6, 3, 7,
            1, 10,
            5,
            7, 2, 6, 6, 1, 3, 1, 2, 5, 2, 7, 5, 3,
            6, 7, 6, 7, 4
        ));
        $oMock->method('rand')->will($this->returnValue(18));

        RandomTest::$oMock = $oMock;

        // Act
        $aOutput = $this->oMod->handleCardInfo($this->aSetting, $aAccumulationInfo);

        //Assert
        $this->assertEquals($aInput, $aOutput);
    }
}