<?php

namespace Framework\Rarity\Casino\Game;

use Framework\Support\Facades\Random;

class Rarity_5930
{
    /**
     * 產牌
     * @param  array $_aSetting 遊戲設定
     * @param  array $_aAccumulationInfo 暫存資料
     * @return array $aCardInfo 牌組資料
     */
    public function handleCardInfo($_aSetting, $_aAccumulationInfo)
    {
        $aCardInfo['Lines'] = [];
        $aCardInfo['Cards'] = [];
        $aCardInfo['Special'] = [];

        $aFirstCards = $this->generateFirstCards($_aSetting);
        $aCardInfo = $this->hitSpecialGame($aFirstCards, $aCardInfo, $_aSetting, $_aAccumulationInfo);
        do {
            $aCardInfo = $this->calculateLineInfo($aCardInfo, $_aSetting);
            $aCardInfo = $this->caculateSpecialLine($aCardInfo, $_aSetting);
        } while ($aCardInfo['HitLine'] || (isset($aCardInfo['Special']['Done']) && $aCardInfo['Special']['Done'] == false));


        return $aCardInfo;
    }

    /**
     * 產第一組牌 一維
     * @param  array $_aSetting 遊戲設定
     * @return array $aCardInfo 牌組資料
     */
    public function generateFirstCards($_aSetting)
    {
        $iGrid     = 0;
        $aElements = [];
        $iGridMax  = $_aSetting['MaxRows'] * $_aSetting['MaxCols'];
        for ($iGrid = 1; $iGrid <= $iGridMax; $iGrid++) {
            $aElements[] = Random::randByWeight($_aSetting['MainGameCardWeight']);
        }
        return $aElements;
    }

    /**
     * 計算連線
     * @param  array $_aSetting 遊戲設定
     * @param  array $_aCard 牌組資料
     * @return array $aCardInfo 牌組資料
     */
    public function calculateLineInfo($_aCardInfo, $_aSetting)
    {
        $aCard = end($_aCardInfo['Cards']);
        $_aCardInfo['HitLine'] = false;
        $aCardLine = [];
        $aCheckRepeat = [];
        $aRoundLine = [];
        for ($iGrid = 0; $iGrid < 64; $iGrid++) {
            $iFirstCard = $aCard[$iGrid];
            $aLine = [];
            $aLine[0] = $iGrid;
            if (in_array($iGrid, $aCheckRepeat)) {
                continue;
            }
            $aLine = $this->getLine($iGrid, $aLine, $aCard, $iFirstCard);
            if (count($aLine) >= 5) {
                $_aCardInfo['HitLine'] = true;
                if (isset($_aCardInfo['Special']['Do9Grid'])) {
                    $aCardLine = [
                        'ElementID' => $iFirstCard,
                        'Grids' => $aLine,
                        'GridNum' => count($aLine),
                        'SpecialType' => $_aCardInfo['Special']['SpecialType'],
                        'Grid' => $_aCardInfo['Special']['Do9Grid'],
                    ];
                } else {
                    $aCardLine = [
                        'ElementID' => $iFirstCard,
                        'Grids' => $aLine,
                        'GridNum' => count($aLine),
                    ];
                }
                $aRoundLine[] = $aCardLine;
                $aCard = $this->setLineToZero($aCard, $aLine);
                $aCheckRepeat = array_merge($aCheckRepeat, $aLine);
            }
        }
        $_aCardInfo['Lines'][] = $aRoundLine;
        // array_push($_aCardInfo['Lines'], $aRoundLine);

        if ($_aCardInfo['HitLine']) {
            $aCardFall = $this->formatCardFall($aCard, $_aSetting);
            $aNewCard = $this->generateFillCard($aCardFall, $_aSetting);
            array_push($_aCardInfo['Cards'], $aNewCard);
        } else {
        }
        return $_aCardInfo;
    }

    /**
     * 上下左右相同元件
     * @param  array $_iGrid 目前牌的位置
     * @param  array $aLine  連線資訊
     * @param  array $_aCard 牌組資料
     * @param  array $_iFirstCard 目前牌的值
     * @return array $aLine 連線資訊
     */
    public function getLine($_iGrid, $aLine, $_aCard, $_iFirstCard)
    {
        //左右邊界
        $aRightBoundary = [0,8,16,24,32,40,48,56,64];
        $aLeftBoundary = [-1,7,15,23,31,39,47,55,63];

        //右
        $iGridRight = $_iGrid + 1;
        if (!in_array($iGridRight, $aRightBoundary) && !in_array($iGridRight, $aLine) && $_iFirstCard == $_aCard[$iGridRight]) {
            array_push($aLine, $iGridRight);
            $aLine = $this->getLine($iGridRight, $aLine, $_aCard, $_iFirstCard);
        }

        //下
        $iGridDown = $_iGrid + 8;
        if (isset($_aCard[$iGridDown]) && !in_array($iGridDown, $aLine) && $_iFirstCard == $_aCard[$iGridDown]) {
            array_push($aLine, $iGridDown);
            $aLine = $this->getLine($iGridDown, $aLine, $_aCard, $_iFirstCard);
        }

        //左
        $iGridLeft = $_iGrid - 1;
        if (!in_array($iGridLeft, $aLeftBoundary) && !in_array($iGridLeft, $aLine) && $_iFirstCard == $_aCard[$iGridLeft]) {
            array_push($aLine, $iGridLeft);
            $aLine = $this->getLine($iGridLeft, $aLine, $_aCard, $_iFirstCard);
        }


        //上
        $iGridUp = $_iGrid - 8;
        if (isset($_aCard[$iGridUp]) && !in_array($iGridUp, $aLine) && $_iFirstCard == $_aCard[$iGridUp]) {
            array_push($aLine, $iGridUp);
            $aLine = $this->getLine($iGridUp, $aLine, $_aCard, $_iFirstCard);
        }

        sort($aLine);
        return $aLine;
    }

    /**
     * 把連線的牌歸0
     * @param  array $_aCard 牌組資料
     * @param  array $_aCardLineInfo 連線資料
     * @return array $_aCard 牌組資料
     */
    public function setLineToZero($_aCard, $_aLine)
    {
        foreach ($_aLine as $iIndex) {
            $_aCard[$iIndex] = 0;
        }
        return $_aCard;
    }

    /**
     * 迴旋遞補0
     * @param  array $_aCard 牌組資料
     * @param  array $_aSetting 遊戲設定
     * @return array $_aCard 牌組資料
     */
    public function formatCardFall($_aCard, $_aSetting)
    {
        $aCardDirection = $_aSetting['CardDirection'];
        $iFast = 0;
        foreach ($aCardDirection as $iKey => $iIndex) {
            if ($_aCard[$iIndex] != 0) {
                continue;
            }
            $iFast = $iKey;
            while ($_aCard[$aCardDirection[$iFast]] == 0 && $iFast < 63) {
                $iFast += 1;
            }
            $_aCard[$iIndex] = $_aCard[$aCardDirection[$iFast]];
            $_aCard[$aCardDirection[$iFast]] = 0;
        }
        return $_aCard;
    }

    /**
     * 補0牌
     * @param  array $_aCard 牌組資料
     * @param  array $_aSetting 遊戲設定
     * @return array $_aCard 牌組資料
     */
    public function generateFillCard($_aCard, $_aSetting)
    {
        foreach ($_aCard as $iKey => $iCard) {
            if ($iCard == 0) {
                $_aCard[$iKey] = Random::randByWeight($_aSetting['MainGameCardWeight']);
            }
        }
        return $_aCard;
    }

    /**
     * 是否開啟特殊遊戲
     * @param  array $_aCard 牌組資料
     * @param  array $_aSetting 遊戲設定
     * @return array $_aCard 牌組資料
     */
    public function hitSpecialGame($_aCard, $_aCardInfo, $_aSetting, $_aAccumulationInfo)
    {
        //初始化
        $_aCardInfo['Cards'][0] = $_aCard;
        if (isset($_aAccumulationInfo['FreeGame']['FreeGameTime'])) {
            return $_aCardInfo;
        }
        $iHitSpecial = Random::randByWeight($_aSetting['HitSpecialGameWeight']);
        if ($iHitSpecial == 1) {
            $iSpecialType = Random::randByWeight($_aSetting['SpecialGameTypeWeight']);
            $iGrid = Random::rand(0, 63);
            $_aCard[$iGrid] = $iSpecialType;
            $_aCardInfo['Cards'][0] = $_aCard;
            $_aCardInfo['Special'] = [
                'Done' => false,
                'SpecialType' => $iSpecialType,
                'Grid' => $iGrid,
            ];
        }
        return $_aCardInfo;
    }

     /**
     * 特殊遊戲連線
     * @param  array $_aCard 牌組資料
     * @param  array $_aSetting 遊戲設定
     * @return array $_aCard 牌組資料
     */
    public function caculateSpecialLine($_aCardInfo, $_aSetting)
    {

        if ($_aCardInfo['HitLine'] || !isset($_aCardInfo['Special']['Done']) || $_aCardInfo['Special']['Done'] == true) {
            return $_aCardInfo;
        }
        $iSpecialType = $_aCardInfo['Special']['SpecialType'];
        $iGrid =  $_aCardInfo['Special']['Grid'];
        $aCard = end($_aCardInfo['Cards']);

        if ($iSpecialType == 9) {
            $iChangeItem = Random::randByWeight($_aSetting['SpecialGameCardWeight9']);
            $iGrid = array_search(9, $aCard);
            //左右邊界
            $aRightBoundary = [0,8,16,24,32,40,48,56,64];
            $aLeftBoundary = [-1,7,15,23,31,39,47,55,63];
            $iTempGrid = $iGrid;
            //左上
            do {
                $aCard[$iTempGrid] = $iChangeItem;
                $iTempGrid -= 9;
            } while (isset($aCard[$iTempGrid]) && !in_array($iTempGrid, $aLeftBoundary));

            $iTempGrid = $iGrid;
            //右上
            do {
                $aCard[$iTempGrid] = $iChangeItem;
                $iTempGrid -= 7;
            } while (isset($aCard[$iTempGrid]) && !in_array($iTempGrid, $aRightBoundary));

            $iTempGrid = $iGrid;
            //左下
            do {
                $aCard[$iTempGrid] = $iChangeItem;
                $iTempGrid += 7;
            } while (isset($aCard[$iTempGrid]) && !in_array($iTempGrid, $aLeftBoundary));

            $iTempGrid = $iGrid;
            //右下
            do {
                $aCard[$iTempGrid] = $iChangeItem;
                $iTempGrid += 9;
            } while (isset($aCard[$iTempGrid]) && !in_array($iTempGrid, $aRightBoundary));

            array_push($_aCardInfo['Cards'], $aCard);
            $_aCardInfo['Special']['Do9Grid'] = $iGrid;
            $_aCardInfo = $this->calculateLineInfo($_aCardInfo, $_aSetting);
            unset($_aCardInfo['Special']['Do9Grid']);
        } elseif ($iSpecialType == 10) {
            $iChangeItem = Random::randByWeight($_aSetting['SpecialGameCardWeight10']);
            $aLine = [];
            $aCardLine = [];
            $iGrid = array_search(10, $aCard);
            foreach ($aCard as $iKey => $iItem) {
                if ($iItem == $iChangeItem || $iItem == 10) {
                    $aCard[$iKey] = 0;
                    $aLine[] += $iKey;
                }
            }
            $aCardLine = [
                'ElementID' => $iChangeItem,
                'Grids' => $aLine,
                'GridNum' => count($aLine),
                'SpecialType' => $iSpecialType,
                'Grid' => $iGrid,
            ];
            $_aCardInfo['HitLine'] = true;
            $aCardFall = $this->formatCardFall($aCard, $_aSetting);
            $aNewCard = $this->generateFillCard($aCardFall, $_aSetting);
            array_push($_aCardInfo['Cards'], $aNewCard);
            $_aCardInfo['Lines'][][] = $aCardLine;
        }
        $_aCardInfo['Special']['Done'] = true;

        return $_aCardInfo;
    }
}