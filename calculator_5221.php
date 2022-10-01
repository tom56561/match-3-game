<?php

namespace Framework\Calculator\Casino\Game;

use Framework\Support\Facades\Calculator;

class Calculator_5221
{

    /**
     * 計算派彩
     * @param array $_aSetting 遊戲設定值
     * @param array $_aCardInfo 牌組
     * @param array $_aBetInfo 下注資訊
     * @param array $_aAccumulationInfo 暫存資料
     * @return array                派彩資料
     */
    public function calculate(array $_aSetting, array $_aCardInfo, array $_aBetInfo, array $_aAccumulationInfo): array
    {
        $aPayoffInfo = Calculator::load('Casino', 'Lib', 'LabarLine')->calculate([$_aSetting, $_aCardInfo, $_aBetInfo]);
        $aPayoffInfo = $this->calculateScatter($_aSetting, $_aCardInfo, $_aBetInfo, $aPayoffInfo, $_aAccumulationInfo);
        $aPayoffInfo = $this->calculateFreeGame($_aSetting, $_aCardInfo, $_aBetInfo, $aPayoffInfo, $_aAccumulationInfo);

        return $aPayoffInfo;
    }

    /**
     * 計算Scatter派彩
     * @param array $_aSetting    遊戲設定
     * @param array $_aCardInfo   牌組資訊
     * @param array $_aBetInfo    下注資訊
     * @param array $_aPayoffInfo 派彩資訊
     * @param array $_aAccumulationInfo 暫存資料
     * @return array              計算Scatter派彩後的派彩資訊
     */
    private function calculateScatter(array $_aSetting, array $_aCardInfo, array $_aBetInfo, array $_aPayoffInfo, array $_aAccumulationInfo): array
    {
        $_aPayoffInfo['Scatter'] = [
            'ID' => 0,
            'GridNum' => 0,
            'Grids' => '',
            'Payoff' => 0,
        ];

        if (isset($_aCardInfo['CardsCount']['GridNum'][10]) && $_aCardInfo['CardsCount']['GridNum'][10] >= 3) {
            $iScatterGridNum = $_aCardInfo['CardsCount']['GridNum'][10];

            if ($_aAccumulationInfo['RollerNumber'] == 1) {
                $iRate = 0;
            } else {
                $iRate = $_aSetting['ScatterRate'][10][$iScatterGridNum];
            }

            $_aPayoffInfo['Scatter'] = [
                'ID' => 10,
                'GridNum' => $iScatterGridNum,
                'Grids' => $_aCardInfo['CardsCount']['Grid'][10],
                'Payoff' => $iRate * $_aBetInfo['LineBet'] * $_aBetInfo['BetLevel'],
            ];

            $_aPayoffInfo['PayTotal'] += $_aPayoffInfo['Scatter']['Payoff'];
            $_aPayoffInfo['GamePayTotal']['Lines'] += $_aPayoffInfo['Scatter']['Payoff'];
            $_aPayoffInfo['AllPayTotal'] += $_aPayoffInfo['Scatter']['Payoff'];

        }

        return $_aPayoffInfo;
    }

    /**
     * FreeGame派彩
     * @param array $_aSetting 遊戲設定
     * @param array $_aCardInfo 牌組資訊
     * @param array $_aBetInfo 下注資訊
     * @param array $_aPayoffInfo 派彩資訊
     * @param array $_aAccumulationInfo 暫存資料
     * @return array
     */
    private function calculateFreeGame(array $_aSetting, array $_aCardInfo, array $_aBetInfo, array $_aPayoffInfo, array $_aAccumulationInfo): array
    {
        //初始化
        $bHitFree = false;
        $_aPayoffInfo['AccumulationInfo'] = $_aAccumulationInfo;
        $_aPayoffInfo['FreeGame'] = [
            'HitFree' => $bHitFree,
            "ID" => 0,
            "FreeGameTime" => 0,
            "FreeGamePayoffTotal" => 0,
            "AddFreeGame" => 0,
            "Lead" => 0,
        ];
        $_aPayoffInfo['FreeGameSpin'] = [
            'FreeGameTime' => 0,
            'FreeGamePayoffTotal' => 0,
        ];

        if ($_aAccumulationInfo['RollerNumber'] == 0) {
            if (isset($_aCardInfo['CardsCount']['GridNum'][10]) && $_aCardInfo['CardsCount']['GridNum'][10] >= 3) {
                $bHitFree = true;
            }
        } else {
            $aFreeGame = $_aPayoffInfo['AccumulationInfo']['FreeGame'];
            $aFreeGameSpin['FreeGameTime'] = $aFreeGame['FreeGameTime'] - 1;
            //FreeGame中FreeGame
            if (isset($_aCardInfo['CardsCount']['GridNum'][10]) && $_aCardInfo['CardsCount']['GridNum'][10] == 9 && $aFreeGame['AddFreeGame'] < 10) {
                $bHitFree = true;
                $aFreeGame['AddFreeGame'] += 1;
                $aFreeGameSpin['FreeGameTime'] += $aFreeGame['FirstFreeGameTime'];
                if ($aFreeGame['Lead'] == 1 || $aFreeGame['Lead'] == 9) {
                    $aFreeGame['Lead'] = 9;
                } else {
                    $aFreeGame['Lead'] -= 1;
                }
            }
            if ($aFreeGameSpin['FreeGameTime'] == 0) {
                $aFreeGame['Lead'] = 0;
            }
            $aFreeGameSpin['FreeGamePayoffTotal'] = $aFreeGame['FreeGamePayoffTotal'] + $_aPayoffInfo['PayTotal'];
            $_aPayoffInfo['FreeGameSpin'] = $aFreeGameSpin;
            $aFreeGame = array_merge($aFreeGame, $aFreeGameSpin);

            $_aPayoffInfo['AccumulationInfo']['FreeGame'] = $aFreeGame;
        }

        if ($bHitFree) {
            if ($_aAccumulationInfo['RollerNumber'] == 0) {
                $iScatterGridNum = $_aCardInfo['CardsCount']['GridNum'][10];
                $aFreeGame = [
                    'HitFree' => $bHitFree,
                    "ID" => 10,
                    "FreeGameTime" => $_aSetting['FreeGameTime'][$iScatterGridNum],
                    "FreeGamePayoffTotal" => 0,
                    "AddFreeGame" => 0,
                    "Lead" => 4,
                    "BetLevel" => $_aBetInfo['BetLevel'],
                ];
                $_aPayoffInfo['FreeGame'] = $aFreeGame;
                unset($aFreeGame['HitFree']);
                unset($aFreeGame['ID']);
                $_aPayoffInfo['AccumulationInfo']['FreeGame'] = $aFreeGame;
            } else {
                $_aPayoffInfo['FreeGame'] = [
                    'HitFree' => $bHitFree,
                    "ID" => 10,
                    "FreeGameTime" => $aFreeGame['FirstFreeGameTime'],
                    "FreeGamePayoffTotal" => 0,
                    "AddFreeGame" => $aFreeGame['AddFreeGame'],
                    "Lead" => $aFreeGame['Lead'],
                ];
            }
        }

        return $_aPayoffInfo;
    }
}