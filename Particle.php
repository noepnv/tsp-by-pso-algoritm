<?php
/**
 * 粒子類別
 */
class Particle
{
    // 粒子的位置（解）
    private $position = array();

    // 粒子速度
    private $velocity = array();

    // 粒子適應值
    private $fitness = NULL;

    // 粒子個體最佳位置
    private $particleBestPostion = array();

    // 粒子個體最佳適應值
    private $particleBestFitness = NULL;

    // 產生初始粒子和速度
    public function __construct()
    {
        // 產生初始粒子
        $this->position = range(0, __PONIT_MAX__ - 1);
        shuffle($this->position);

        // 建立速度
        for ($i = 0; $i < __PONIT_MAX__; $i++) {
            $this->velocity[$i] = mt_rand(-6, 6);
        }

        // 建立初始粒子適應值以及粒子個體最佳適應值
        $this->calculateFitness();
        $this->particleBestFitness = $this->fitness;
        $this->particleBestPostion = $this->position;
    }

    // 取得粒子位置
    public function getPosition()
    {
        return $this->position;
    }

    // 取得粒子適應值
    public function getFitness()
    {
        return $this->fitness;
    }

    /**
     * 更新速度
     */
    public function updateVelocity($globalBestPosition)
    {
        // 設定速度更新參數
        $w = 0.8;
        $c1 = 2;
        $c2 = 2;
        $r1 = mt_rand(0, 100) / 100;
        $r2 = mt_rand(0, 100) / 100;

        // 計算粒子和粒子最佳值以及群體最佳值距離
        $particleDistances = $this->_calculateDistances($this->position, $this->particleBestPostion);
        $globalDistances = $this->_calculateDistances($this->position, $globalBestPosition);

        // 更新速度
        for ($i = 0; $i < __PONIT_MAX__; $i++) {
            $this->velocity[$i] = $w * $this->velocity[$i] + $c1 * $r1 * $particleDistances[$i] + $c2 * $r2 * $globalDistances[$i];
        }
    }

    /**
     * 套用粒子速度，移動到新位置
     */
    public function applyVelocity()
    {
        // 建立套用速度的順序（隨機）
        $order = range(0, __PONIT_MAX__ - 1);
        shuffle($order);

        // 將粒子移動到新位置
        $newPosition = array();
        $newVelocity = array();
        for ($i = 0; $i < __PONIT_MAX__; $i++) {
            $newSlot = $order[$i] + $this->velocity[$order[$i]];
            $this->_moveToNewPosiotion($newPosition, $newVelocity, $order[$i], $newSlot);
        }

        $this->position = $newPosition;
        $this->velocity = $newVelocity;
    }

    /**
     * 計算適應值
     */
    public function calculateFitness()
    {
        // 重置適應值
        $this->fitness = 0;

        // 計算各點之間的距離
        for ($i = 0; $i < __PONIT_MAX__ - 1; $i++) {
            $x2 = pow($GLOBALS['travelPoints'][$this->position[$i]][0] - $GLOBALS['travelPoints'][$this->position[$i + 1]][0], 2);
            $y2 = pow($GLOBALS['travelPoints'][$this->position[$i]][1] - $GLOBALS['travelPoints'][$this->position[$i + 1]][1], 2);
            $this->fitness += sqrt($x2 + $y2);
        }

        // 加上尾端到起始點的距離
        $x2 = pow($GLOBALS['travelPoints'][0][0] - $GLOBALS['travelPoints'][__PONIT_MAX__][0], 2);
        $y2 = pow($GLOBALS['travelPoints'][0][1] - $GLOBALS['travelPoints'][__PONIT_MAX__][1], 2);
        $this->fitness += sqrt($x2 + $y2);

        // 判斷是否為個體最佳值
        if ($this->particleBestFitness > $this->fitness) {
            $this->particleBestPostion = $this->position;
            $this->particleBestFitness = $this->fitness;
        }
    }

    /**
     * 計算粒子位置間距離
     */
    private function _calculateDistances($array1, $array2)
    {
        $result = array();
        for ($i = 0; $i < __PONIT_MAX__; $i++) {
            $result[$i] = array_search($array1[$i], $array2) - $i;
        }

        return $result;
    }

    /**
     * 將旅行點移動到新位置
     */
    private function _moveToNewPosiotion(&$newPosition, &$newVelocity, $i, $newSlot)
    {
        // 建立探索位置參數
        $direction = mt_rand(0, 1);
        $range = 1;

        // 限制粒子位置
        $newSlot = $this->_limitSlot($newSlot);

        // 移動到新位置，如果該位置已經有點存在，或者超出移動範圍，則向鄰近的位置探尋
        while (isset($newPosition[$newSlot]) || (($newSlot > __PONIT_MAX__ - 1) || ($newSlot < 0))) {
            if ($direction == 0) {
                $newSlot -= $range;
                $direction = 1;
            } else {
                $newSlot += $range;
                $direction = 0;
            }

            $range++;
        }

        // 將粒子位置和速度移動到新位置
        $newPosition[$newSlot] = $this->position[$i];
        $newVelocity[$newSlot] = $this->velocity[$i];
    }

    /**
     * 限制粒子移動位置
     */
    private function _limitSlot($slot)
    {
        if ($slot > __PONIT_MAX__ - 1) {
            return __PONIT_MAX__ - 1;
        } elseif ($slot < 0) {
            return 0;
        } else {
            return round($slot, 0);
        }
    }

}
?>