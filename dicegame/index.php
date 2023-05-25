<?php

function playGame($currentPlayer, $diceValue, $gameState)
{
    $size = 9;
    $position = array_search((int) $currentPlayer, $gameState);
    // if (!$position) {
    //     $position = array_search(12, $gameState);
    // }

    if ($gameState[4] == 12) {
        $position = 4;
    }

    $position = (int) $position;

    if ($position + $diceValue <= $size - 1) {
        if ($gameState[4] == 12) {
            $gameState[4] = $currentPlayer == 1 ? 2 : 1;
        } else if ($gameState[0] == 12) {
            $gameState[0] = $currentPlayer == 1 ? 2 : 1;
        } else {
            $gameState[$position] = 0;
        }

        if ($position + $diceValue == 4) {
            $gameState[$position] = 0;
            if ($gameState[4] == 0) {
                $gameState[4] = $currentPlayer;
            } else {
                $gameState[4] = 12;
            }
        } else {
            if ($gameState[$position + $diceValue] == 1 || $gameState[$position + $diceValue] == 2) {
                $gameState[0] = $currentPlayer == 1 ? 2 : 1;
            }
            $gameState[$position + $diceValue] = $currentPlayer;
        }
    }

    return $gameState;
}

session_start();

if (isset($_SESSION['gameState'])) {
    $gameState = $_SESSION['gameState'];
} else {
    $gameState = array_fill(0, 9, 0);
    $gameState[0] = 12;
    $gameState['currentPlayer'] = 1;
}

if (!isset($gameState['currentPlayer'])) {
    $gameState['currentPlayer'] = 1;
}

if (!isset($gameState['currentDice'])) {
    $gameState['currentDice'] = 0;
}

if (isset($_POST['rollButton'])) {
    $diceValue = rand(1, 3);
    $gameState['currentDice'] = $diceValue;
    $currentPlayer = $gameState['currentPlayer'];
    $gameState = playGame($currentPlayer, $diceValue, $gameState);

    $gameState['currentPlayer'] = $currentPlayer == 1 ? 2 : 1;

    $_SESSION['gameState'] = $gameState;

    if ($gameState[8] == 1 || $gameState[8] == 2) {
        $winner = $gameState[8];
        $gameState = array_fill(0, 9, 0);
        $gameState[0] = 12;
        echo '<h1>"Player ' . $winner . ' is the winner! and the dice value was ' . $diceValue . '."</h1>';

        $gameState['currentPlayer'] = 1;
        $gameState['currentDice'] = 0;
        session_unset();
        session_destroy();
    }

}

?>


<html>

<head>
    <title>Two Player</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="row">
        <div class="column" style="background-color:#FFB695;">
            <h2>DICE</h2>
            <p id="placeholder">
                <!-- ============================ PHP =================================== -->
                <?php
                if(!$diceValue){
                    $dice='';
                }else{
                    $dice=$diceValue;
                }
                echo $dice;
                ?>
                <!-- ============================ PHP =================================== -->
            </p>

            <form method="post">
                <button type="submit" name="rollButton" id="button">Move</button>
            </form>

        </div>
        <!-- ----------------------- COLUMN 2 ----------------------- -->
        <div class="column" style="background-color:#96D1CD;">
           
            <?php

            
            function display($a)
            {
                ?>
                <!-- ============================ PHP =================================== -->
                <div class="parent">

                    <?php
                    for ($i = 7; $i >= 5; $i--)
                        echo '<div>' . $a[$i] . '</div>';
                    echo '<div>' . $a[0] . '</div>' . "  " . '<div>' . $a[8] . '</div>' . "  " . '<div>' . $a[4] . '</div>';
                    for ($i = 1; $i <= 3; $i++)
                        echo '<div>' . $a[$i] . '</div>';
                    ?>
                </div>
                <!-- ============================ PHP =================================== -->
                <?php

            }
            display($gameState);

            ?>
        </div>
    </div>
    </div>
    </div>
</body>

</html>