﻿<!DOCTYPE html>
<html lang="zh">
    <head>
        <meta charset="utf-8" />
        <title>Travelling salesman problem sovle by PSO algorithm</title>
        <link href="style.css" media="screen" rel="stylesheet" type="text/css" />
    </head>
    <body onload="drawAll();">
        <header>
            <h1>以粒子群最佳化演算法解決旅行商問題示範</h1>
        </header>                
        <div id="inputArea">
            <form name="paramSetting" method="post">
            <input type="checkbox" name="isGenerateNewPoints" value="1" /> 產生新點
            <?= $pointInputData ?>            
            <!--
            請建立四個select選單：
            旅行點數量（5、10、15……）
            運算粒子數（20、30、40、50………）
            運算迭代數（10、20、30……）
            毀滅次數（1、2、3……）
            -->
            <input type="submit" name="submit" value="演算GO" />
            </form>
        </div>
        <h2 id="routeLength">
            最短路徑：<?= $routeLength ?>
        </h2>
        <canvas id="paintArea" width="680" height="680">
            繪圖區
        </canvas>
        <footer>
            作者：康家豪、溫國光、邱順得
        </footer>
    </body>
    <?php if ($route && $points): ?>
	    <script type="text/javascript">
			var route = <?= $route ?>;
			var points = <?= $points ?>;
	    </script>
    <?php endif; ?>       
    <script type="text/javascript" src="canvas.js"></script>
</html>