<?php

namespace Framework\Rarity\Casino\Game;
use Framework\Support\Facades\Generator;
use Framework\Support\Facades\Random;


class Rarity_5221
{
    public function handleRollerNumber(array $_aAccumulationInfo): array
    {
        if (!empty($_aAccumulationInfo['FreeGame']) && $_aAccumulationInfo['FreeGame']['FreeGameTime'] > 0) {
            $_aAccumulationInfo['RollerNumber'] = 1;
        } else {
            $_aAccumulationInfo['RollerNumber'] = 0;
        }
        return $_aAccumulationInfo;
    }


    public function changeCards($_aCardInfo, $_aChangeCards)
    {
        $aCards = explode("-", $_aCardInfo['Program']);

        foreach ($aCards as $ikey => $iCards) {
            $aCards[$ikey] = $_aChangeCards[$aCards[$ikey]];
        }

        $aCards = implode("-", $aCards);

        return $aCards;
    }

    public function formatCards($_aChangeCards)
    {
        $aOriginCards = explode(",", $_aChangeCards['Flash']);
        return $aOriginCards;
    }

     public function changeRoller($_aAccumulationInfo, $_aSetting)
    {
        //產生權重變數
        if ($_aAccumulationInfo['RollerNumber'] == 0) {
            $iLead = Random::randByWeight($_aSetting['RateWeight']);
        }

        $_aSetting['AccumulationInfo']['ExpandRoller'] = 0;

        //換輪軸
        if ($_aAccumulationInfo['RollerNumber'] == 1 || $iLead != 0) {
            if (isset($iLead)) {
                $iLead = $iLead;
                $iSpecialRollerNumber = 0;
            }
            if ($_aAccumulationInfo['RollerNumber'] == 1) {
                $iLead = $_aAccumulationInfo['FreeGame']['Lead'];
                $iSpecialRollerNumber = 1;
            }
            $_aSetting['RollerContainer'][0] = [
                0 => [$iLead, $iLead, $iLead],
                1 => [$iLead, $iLead, $iLead],
                2 => $_aSetting['SpecialRoller'][$iSpecialRollerNumber][$iLead],
                3 => $_aSetting['SpecialRoller'][$iSpecialRollerNumber][$iLead],
                4 => $_aSetting['SpecialRoller'][$iSpecialRollerNumber][$iLead],
            ];

            $_aSetting['AccumulationInfo']['ExpandRoller'] = 1;

        }

        return $_aSetting;
    }

    public function expand4and5Roller($_aCardInfo, $_aSetting) {

        if ($_aSetting['AccumulationInfo']['ExpandRoller'] == 1 ) {
        $aCards = explode(",", $_aCardInfo['Flash']);

        $aCards[3] = $aCards[2];
        $aCards[4] = $aCards[2];

        $sCards = implode("-", $aCards);

        $_aCardInfo = Generator::load('Casino', 'AssignCards')->generate(array($sCards, $_aSetting['MaxRows'], $_aSetting['MaxCols']));
        }

        return $_aCardInfo;

    }
}