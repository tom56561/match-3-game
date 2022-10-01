<?php

namespace Framework\Calculator\Casino\Game;

use Framework\Support\Facades\Calculator;
use Framework\Support\Facades\Random;

class Calculator_5930
{
    /**
     * 派彩計算
     * @param $_aSetting          array 遊戲設定
     * @param $_aCardInfo         array 排組資訊
     * @param $_aBetInfo          array 下注資訊
     * @param $_aAccumulationInfo array 暫存資訊
     * @return $aPayoffInfo       array 派彩資料
     */
    public function calculate($_aSetting, $_aCardInfo, $_aBetInfo, $_aAccumulationInfo)
    {
        //計算免費遊戲連派彩or 一般遊戲派彩
        if (isset($_aAccumulationInfo['FreeGame']['FreeGameTime']) && $_aAccumulationInfo['FreeGame']['FreeGameTime'] > 0) {
            $aPayoffInfo = $this->calculateFreeGameLines($_aSetting, $_aCardInfo, $_aBetInfo);
        } else {
            $aPayoffInfo = $this->calculateLines($_aSetting, $_aCardInfo, $_aBetInfo);
        }
        $aPayoffInfo = $this->calculateFreeGame($aPayoffInfo, $_aSetting, $_aCardInfo, $_aBetInfo, $_aAccumulationInfo);
        $aPayoffInfo = $this->formatCards($aPayoffInfo, $_aCardInfo);
        return $aPayoffInfo;
    }

    /**
     * 計算免費遊戲連線派彩
     * @param $_aSetting     array 遊戲設定
     * @param $_aCardInfo    array 排組資訊
     * @param $_aBetInfo     array 下注資訊
     * @return $aPayoffInfo  array 一般連線派彩資訊
     */
    public function calculateFreeGameLines($_aSetting, $_aCardInfo, $_aBetInfo)
    {
        $aRate = $_aSetting['Rate'];
        $aPayoffInfo['Lines'] = $_aCardInfo['Lines'];
        $iPayTotal = 0;
        foreach ($aPayoffInfo['Lines'] as $iRound => $aLine) {
            $iDoubleTime = $_aSetting['FreeGameDouble'][$iRound + 1];
            foreach ($aLine as $iKey => $aLineInfo) {
                $aPayoffInfo['Lines'][$iRound][$iKey]['Payoff'] = $iDoubleTime * $aRate[$aLineInfo['ElementID']][$aLineInfo['GridNum'] - 1] * $_aBetInfo['BetLevel'];
                $aPayoffInfo['Lines'][$iRound][$iKey]['DoubleTime'] = $iDoubleTime;
                $iPayTotal += $aPayoffInfo['Lines'][$iRound][$iKey]['Payoff'];
            }
        }

        $aPayoffInfo['PayTotal'] = $iPayTotal;
        $aPayoffInfo['BetTotal'] = $_aBetInfo['BetCredit'];
        return $aPayoffInfo;
    }

    /**
     * 計算一般連線派彩
     * @param $_aSetting     array 遊戲設定
     * @param $_aCardInfo    array 排組資訊
     * @param $_aBetInfo     array 下注資訊
     * @return $aPayoffInfo  array 一般連線派彩資訊
     */
    public function calculateLines($_aSetting, $_aCardInfo, $_aBetInfo)
    {
        $aRate = $_aSetting['Rate'];
        $aPayoffInfo['Lines'] = $_aCardInfo['Lines'];
        $iPayTotal = 0;
        foreach ($aPayoffInfo['Lines'] as $iRound => $aLine) {
            foreach ($aLine as $iKey => $aLineInfo) {
                $aPayoffInfo['Lines'][$iRound][$iKey]['Payoff'] = $aRate[$aLineInfo['ElementID']][$aLineInfo['GridNum'] - 1] * $_aBetInfo['BetLevel'];
                $iPayTotal += $aPayoffInfo['Lines'][$iRound][$iKey]['Payoff'];
            }
        }

        $aPayoffInfo['PayTotal'] = $iPayTotal;
        $aPayoffInfo['BetTotal'] = $_aBetInfo['BetCredit'];
        return $aPayoffInfo;
    }

    /**
     * 整理牌型
     * @param $aPayoffInfo   array 一般連線派彩資訊
     * @param $_aCardInfo    array 排組資訊
     * @return $aPayoffInfo  array 一般連線派彩資訊
     */
    public function formatCards($_aPayoffInfo, $_aCardInfo)
    {
        $aCards = $_aCardInfo['Cards'];
        $aCardCol = [];
        $aSigleCards   = [];
        $aChangeCards = [];
        foreach ($aCards as $aCard) {
            foreach ($aCard as $iKey => $iCard) {
                $aCardCol[] = $iCard;
                if ($iKey % 8 == 7) {
                    $aSigleCards[] = implode('-', $aCardCol);
                    $aCardCol = [];
                }
            }
            $aChangeCards[] = $aSigleCards;
            $aSigleCards = [];
        }
        $_aPayoffInfo['Cards'] = $aChangeCards;
        return $_aPayoffInfo;
    }

    /**
     * 計算免費連線派彩
     * @param $aPayoffInfo        array 一般連線派彩資訊
     * @param $_aSetting          array 遊戲設定
     * @param $_aCardInfo         array 排組資訊
     * @param $_aBetInfo          array 下注資訊
     * @param $_aAccumulationInfo array 暫存資訊
     * @return $aPayoffInfo       array 免費連線派彩資訊
     */
    public function calculateFreeGame($_aPayoffInfo, $_aSetting, $_aCardInfo, $_aBetInfo, $_aAccumulationInfo)
    {
        $_aPayoffInfo['FreeGame'] = [];
        $_aPayoffInfo['FreeGameSpin'] = [];
        $_aPayoffInfo['Special'] = $_aCardInfo['Special'];
        if (isset($_aAccumulationInfo['FreeGame']['FreeGameTime']) && $_aAccumulationInfo['FreeGame']['FreeGameTime'] > 0) {
            $aFreeGame = $_aAccumulationInfo['FreeGame'];
            $aFreeGameSpin['FreeGameTime'] = $aFreeGame['FreeGameTime'] - 1;
            $aFreeGameSpin['FreeGamePayoffTotal'] = $aFreeGame['FreeGamePayoffTotal'] + $_aPayoffInfo['PayTotal'];
            $_aPayoffInfo['FreeGameSpin'] = $aFreeGameSpin;
            $aFreeGame = array_merge($aFreeGame, $aFreeGameSpin);
            $_aPayoffInfo['AccumulationInfo']['FreeGame'] = $aFreeGame;
        } else {
            $_aPayoffInfo['AccumulationInfo']['FreeGame'] = $_aPayoffInfo['FreeGame'];
            if (isset($_aCardInfo['Special']['SpecialType']) && $_aCardInfo['Special']['SpecialType'] == 8) {
                $aFreeGame = [
                    'ID' => 8,
                    'Grid' => array_search(8, end($_aCardInfo['Cards'])),
                    'FreeGameTime' => 10,
                    "FreeGamePayoffTotal" => 0,
                ];
                $_aPayoffInfo['Special']['Done'] = true;
                $_aPayoffInfo['FreeGame'] = $aFreeGame;
                unset($aFreeGame['ID']);
                unset($aFreeGame['Grid']);
                $_aPayoffInfo['AccumulationInfo']['FreeGame'] =  $aFreeGame;
            }
        }
        return $_aPayoffInfo;
    }
}
