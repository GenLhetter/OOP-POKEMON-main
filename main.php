<?php include "index.php"?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokemon Battle Arena</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(
            45deg,
            #300000,
            #302000,
            #303000,
            #203000,
            #003000,
            #003020,
            #003030,
            #001030,
            #000030,
            #200030,
            #300030,
            #300020
);
            background-size: 1200% 1200%;
            animation: rgbMove 30s ease infinite;
            color: #fff;
            min-height: 100vh;
            padding: 20px;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        @keyframes rgbMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        h1 {
            text-align: center;
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            color: #ffcc00;
        }
        
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #ffcc00;
        }
        
        .new-game-btn {
            padding: 10px 20px;
            background: #ffcc00;
            color: #333;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .new-game-btn:hover {
            background: #ffd633;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .music-controls {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.7);
            padding: 10px 15px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }
        
        .music-btn {
            background: #ffcc00;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.3s;
        }
        
        .music-btn:hover {
            background: #ffd633;
            transform: scale(1.1);
        }
        
        .volume-slider {
            width: 100px;
            cursor: pointer;
        }
        
        .pokemon-image-container {
            position: relative;
            display: inline-block;
        }
        
        .attack-animation {
            position: absolute;
            top: 100%;
            left: 100%;
            transform: translate(-50%, -50%);
            z-index: 100;
            pointer-events: none;
        }
        
        .attack-animation.hidden {
            display: none;
        }
        
        .attack-image {
            width: 100px;
            height: 100px;
            animation: attackEffect 1s ease-in-out forwards;
        }
        
        @keyframes attackEffect {
            0% { 
                transform: translate(-50%, -50%) scale(0.5); 
                opacity: 0; 
            }
            50% { 
                transform: translate(-50%, -50%) scale(1.2); 
                opacity: 1; 
            }
            100% { 
                transform: translate(-50%, -50%) scale(1); 
                opacity: 0;
            }
        }
        
        .screen-shake {
            animation: screenShake 0.5s ease-in-out;
        }
        
        @keyframes screenShake {
            0%, 100% { transform: translateX(0) translateY(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px) translateY(-3px); }
            20%, 40%, 60%, 80% { transform: translateX(5px) translateY(3px); }
        }
        
        .pokemon-shake {
            animation: pokemonShake 0.5s ease-in-out;
        }
        
        @keyframes pokemonShake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-8px); }
            50% { transform: translateX(8px); }
            75% { transform: translateX(-8px); }
        }
        
        .damage-flash {
            animation: damageFlash 0.3s ease-in-out;
        }
        
        @keyframes damageFlash {
            0%, 100% { background-color: transparent; }
            50% { background-color: rgba(255, 0, 0, 0.3); }
        }
        
        .attack-loading {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            z-index: 1001;
            display: none;
        }
        
        .fainted {
            border: 3px solid #f44336;
            box-shadow: 0 0 20px rgba(244, 67, 54, 0.7);
            position: relative;
            overflow: hidden;
        }

        .fainted::before {
            content: "FAINTED";
            position: absolute;
            top: 15%;
            left: 12.5%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 32px;
            font-weight: bold;
            color: rgba(244, 67, 54, 0.5);
            z-index: 1;
            pointer-events: none;
        }

        .fainted .pokemon-image {
            filter: grayscale(0.8);
            opacity: 0.9;
        }

        .fainted2 {
            border: 3px solid #f44336 !important;
            box-shadow: 0 0 20px rgba(244, 67, 54, 0.7);
            position: relative;
            overflow: hidden;
        }

        .fainted2::before {
            content: "FAINTED";
            position: absolute;
            top: 42.5%;
            left: 12.5%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 32px;
            font-weight: bold;
            color: rgba(244, 67, 54, 0.5);
            z-index: 1;
            pointer-events: none;
        }

        .fainted2 .pokemon-image {
            filter: grayscale(0.8);
            opacity: 0.9;
        }

        .switch-option.disabled {
            border: 2px solid #f44336 !important;
            background: rgba(244, 67, 54, 0.2) !important;
            position: relative;
            overflow: hidden;
        }

        .switch-option.disabled::before {
            content: "FAINTED";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 20px;
            font-weight: bold;
            color: rgba(244, 67, 54, 0.15);
            z-index: 1;
            pointer-events: none;
        }

        .switch-option.disabled .switch-pokemon-image {
            filter: grayscale(0.8);
            opacity: 0.9;
        }
        
        .team-selection {
            text-align: center;
        }
        
        .player-form {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .player-input {
            padding: 10px;
            margin: 10px 0;
            width: 200px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
        }
        
        .pokemon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        
        .pokemon-option {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            border: 3px solid transparent;
        }
        
        .pokemon-option:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-5px);
        }
        
        .pokemon-option.selected {
            background: rgba(76, 175, 80, 0.2);
            border: 3px solid #4caf50;
            box-shadow: 0 0 15px rgba(76, 175, 80, 0.5);
        }
        
        .pokemon-option input {
            display: none; /* Hide the actual checkbox */
        }
        
        .pokemon-option img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        
        .pokemon-name {
            font-weight: bold;
            margin: 10px 0 5px;
        }
        
        .pokemon-types {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-bottom: 5px;
            flex-wrap: wrap;
        }
        
        .pokemon-type {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
        }
        
        .type-normal { background: #a8a878; color: white; }
        .type-fire { background: #f08030; color: white; }
        .type-water { background: #6890f0; color: white; }
        .type-grass { background: #78c850; color: white; }
        .type-electric { background: #f8d030; color: black; }
        .type-ice { background: #98d8d8; color: black; }
        .type-fighting { background: #c03028; color: white; }
        .type-poison { background: #a040a0; color: white; }
        .type-ground { background: #e0c068; color: black; }
        .type-flying { background: #a890f0; color: white; }
        .type-psychic { background: #f85888; color: white; }
        .type-bug { background: #a8b820; color: white; }
        .type-rock { background: #b8a038; color: white; }
        .type-ghost { background: #705898; color: white; }
        .type-dragon { background: #7038f8; color: white; }
        .type-dark { background: #705848; color: white; }
        .type-steel { background: #b8b8d0; color: black; }
        .type-fairy { background: #ee99ac; color: white; }
        
        .select-btn {
            padding: 12px 30px;
            background: #ffcc00;
            color: #333;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 20px;
        }
        
        .select-btn:hover {
            background: #ffd633;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .select-btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .instruction {
            text-align: center;
            margin: 20px 0;
            font-size: 1.2rem;
            color: #ffcc00;
        }
        
        .waiting-message {
            text-align: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            margin: 20px 0;
        }
        
        .battle-field {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .player-area {
            flex: 1;
            min-width: 300px;
            margin: 0 15px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            position: relative;
        }
        
        .player-header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        .player-name {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ffcc00;
        }
        
        .current-turn {
            background: rgba(255, 204, 0, 0.2);
            box-shadow: 0 0 15px rgba(255, 204, 0, 0.5);
        }
        
        .pokemon-card {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
            transition: all 0.3s;
        }
        
        .active-pokemon {
            border: 3px solid #ffcc00;
            background: rgba(255, 204, 0, 0.1);
        }
        
        .pokemon-display {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }
        
        .pokemon-image {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            padding: 5px;
            transition: all 0.3s;
        }
        
        .pokemon-info {
            flex: 1;
        }
        
        .health-bar {
            height: 20px;
            background: #333;
            border-radius: 10px;
            margin: 10px 0;
            overflow: hidden;
            position: relative;
            z-index: 2; /* Ensure it appears above the FAINTED watermark */
        }
        
        .health-fill {
            height: 100%;
            border-radius: 10px;
            transition: width 0.5s;
        }
        
        .health-high { background: #4caf50; }
        .health-medium { background: #ff9800; }
        .health-low { background: #f44336; }
        .health-zero { 
            background: #9e9e9e; 
            width: 100% !important;
        }
        
        .health-text {
            text-align: center;
            font-size: 0.9rem;
            font-weight: bold;
            position: relative;
            z-index: 2;
        }
        
        .attacks {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 15px;
        }
        
        .attack-btn {
            padding: 10px;
            background: rgba(255, 204, 0, 0.3);
            border: 1px solid #ffcc00;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .attack-btn:hover:not(:disabled) {
            background: rgba(255, 204, 0, 0.5);
        }
        
        .attack-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .switch-section {
            margin-top: 15px;
        }
        
        .switch-title {
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
            color: #ffcc00;
        }
        
        .switch-options {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .switch-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: rgba(76, 175, 80, 0.3);
            border: 1px solid #4caf50;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .switch-option:hover:not(:disabled) {
            background: rgba(76, 175, 80, 0.5);
        }
        
        .switch-option:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .switch-form {
            display: inline;
        }
        
        .switch-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            text-align: left;
        }
        
        .switch-btn:disabled {
            cursor: not-allowed;
        }
        
        .switch-pokemon-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .switch-pokemon-image {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .item-section {
            margin-top: 15px;
        }
        
        .item-btn {
            width: 100%;
            padding: 10px;
            background: #2196f3;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .item-btn:hover:not(:disabled) {
            background: #42a5f5;
            transform: translateY(-2px);
        }
        
        .item-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .battle-log {
            background: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
            height: 200px;
            overflow-y: auto;
        }
        
        .log-title {
            text-align: center;
            margin-bottom: 10px;
            color: #ffcc00;
            font-weight: bold;
        }
        
        .log-entry {
            padding: 5px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .game-over {
            text-align: center;
            padding: 30px;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 15px;
            margin-top: 20px;
        }
        
        .winner {
            font-size: 2rem;
            color: #ffcc00;
            margin: 20px 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .reset-btn {
            padding: 12px 30px;
            background: #ffcc00;
            color: #333;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .reset-btn:hover {
            background: #ffd633;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        @media (max-width: 768px) {
            .battle-field {
                flex-direction: column;
            }
            
            .player-area {
                margin: 10px 0;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
            }
            
            .pokemon-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
            
            .music-controls {
                bottom: 10px;
                right: 10px;
                padding: 8px 12px;
            }
            
            .volume-slider {
                width: 80px;
            }
            
            .attack-image {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>
    <!-- Background Music -->
    <audio id="battleMusic" loop>
        <source src="music/POKEMONBATTLESOUND.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
    
    <!-- Music Controls -->
    <div class="music-controls" id="musicControls" style="display: none;">
        <button class="music-btn" id="playPauseBtn">⏸️</button>
        <input type="range" id="volumeSlider" class="volume-slider" min="0" max="1" step="0.1" value="0.5">
    </div>

    <!-- Attack Loading Indicator -->
    <div class="attack-loading" id="attackLoading">Attacking...</div>

    <div class="container">
        <div class="header">
            <h1>Pokemon Battle Arena</h1>
            <form method="post">
                <button type="submit" name="new_game" class="new-game-btn">New Game</button>
            </form>
        </div>
        
        <?php if (!$battle): ?>
            <!-- Team Selection Phase -->
            <div class="team-selection">
                <?php if (!isset($_SESSION['player1_team']) || count($_SESSION['player1_team']) < 3): ?>
                    <!-- Player 1 Team Selection -->
                    <h2>Player 1: Choose Your Team</h2>
                    <p class="instruction">Select 3 Pokemon for your team</p>
                    
                    <form method="post" class="player-form" id="player1Form">
                        <input type="text" name="player1_name" class="player-input" placeholder="Player 1 Name" value="<?php echo $_SESSION['player1_name']; ?>" required>
                        
                        <div class="pokemon-grid" id="player1Grid">
                            <?php foreach ($_SESSION['pokemon_pool'] as $index => $pokemon): ?>
                                <div class="pokemon-option" data-index="<?php echo $index; ?>">
                                    <input type="checkbox" name="pokemon[]" value="<?php echo $index; ?>">
                                    <img src="<?php echo $pokemon->getImage(); ?>" alt="<?php echo $pokemon->getName(); ?>">
                                    <div class="pokemon-name"><?php echo $pokemon->getName(); ?></div>
                                    <div class="pokemon-types">
                                        <?php foreach ($pokemon->getTypes() as $type): ?>
                                            <div class="pokemon-type type-<?php echo strtolower($type); ?>">
                                                <?php echo $type; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div>HP: <?php echo $pokemon->getMaxHealth(); ?></div>
                                    <div>Speed: <?php echo $pokemon->getSpeed(); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <input type="hidden" name="player" value="player1">
                        <button type="submit" name="select_team" class="select-btn" id="player1Submit" disabled>Select Team</button>
                    </form>
                    
                <?php elseif (!isset($_SESSION['player2_team']) || count($_SESSION['player2_team']) < 3): ?>
                    <!-- Player 2 Team Selection -->
                    <h2>Player 2: Choose Your Team</h2>
                    <p class="instruction">Select 3 Pokemon for your team</p>
                    
                    <form method="post" class="player-form" id="player2Form">
                        <input type="text" name="player2_name" class="player-input" placeholder="Player 2 Name" value="<?php echo $_SESSION['player2_name']; ?>" required>
                        
                        <div class="pokemon-grid" id="player2Grid">
                            <?php foreach ($_SESSION['pokemon_pool'] as $index => $pokemon): ?>
                                <div class="pokemon-option" data-index="<?php echo $index; ?>">
                                    <input type="checkbox" name="pokemon[]" value="<?php echo $index; ?>">
                                    <img src="<?php echo $pokemon->getImage(); ?>" alt="<?php echo $pokemon->getName(); ?>">
                                    <div class="pokemon-name"><?php echo $pokemon->getName(); ?></div>
                                    <div class="pokemon-types">
                                        <?php foreach ($pokemon->getTypes() as $type): ?>
                                            <div class="pokemon-type type-<?php echo strtolower($type); ?>">
                                                <?php echo $type; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div>HP: <?php echo $pokemon->getMaxHealth(); ?></div>
                                    <div>Speed: <?php echo $pokemon->getSpeed(); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <input type="hidden" name="player" value="player2">
                        <button type="submit" name="select_team" class="select-btn" id="player2Submit" disabled>Select Team</button>
                    </form>
                    
                <?php else: ?>
                    <!-- Both teams selected, ready to start battle -->
                    <div class="waiting-message">
                        <h2>Teams Selected!</h2>
                        <p class="instruction">Ready to start the battle!</p>
                        <form method="post">
                            <button type="submit" name="start_battle" class="select-btn">Start Battle</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            
        <?php elseif ($battle->isGameOver()): ?>
            <!-- Game Over Screen -->
            <div class="game-over">
                <h2>Battle Over!</h2>
                <div class="winner">🏆 <?php echo $battle->getWinner(); ?> wins! 🏆</div>
                <form method="post">
                    <button type="submit" name="reset" class="reset-btn">New Battle</button>
                </form>
            </div>
            
        <?php else: ?>
            <!-- Battle Screen -->
            <div class="battle-field">
                <!-- Player 1 Area -->
                <div class="player-area <?php echo $battle->getCurrentTurn() === $battle->getPlayer1() ? 'current-turn' : ''; ?>" id="player1-area">
                    <div class="player-header">
                        <div class="player-name"><?php echo $battle->getPlayer1(); ?></div>
                        <div><?php echo $battle->getCurrentTurn() === $battle->getPlayer1() ? 'Your Turn!' : ''; ?></div>
                    </div>
                    
                    <?php foreach ($battle->getPlayer1Pokemon() as $index => $pokemon): ?>
                        <div class="pokemon-card 
                            <?php echo $index === 0 ? 'active-pokemon' : ''; ?> 
                            <?php echo $pokemon->isFainted() ? ($index === 0 ? 'fainted' : 'fainted2') : ''; ?>" 
                            id="player1-pokemon-<?php echo $index; ?>">
                            <div class="pokemon-display">
                                <div class="pokemon-image-container">
                                    <img src="<?php echo $pokemon->getImage(); ?>" alt="<?php echo $pokemon->getName(); ?>" class="pokemon-image" id="player1-sprite-<?php echo $index; ?>">
                                    <!-- Animation container for player 1 - positioned directly on sprite -->
                                    <div class="attack-animation hidden" id="animation-player1">
                                        <img src="https://www.spriters-resource.com/media/asset_icons/173/176527.gif?updated=1755486559" class="attack-image" alt="Attack Animation">
                                    </div>
                                </div>
                                <div class="pokemon-info">
                                    <div class="pokemon-name"><?php echo $pokemon->getName(); ?></div>
                                    <div class="pokemon-types">
                                        <?php foreach ($pokemon->getTypes() as $type): ?>
                                            <div class="pokemon-type type-<?php echo strtolower($type); ?>">
                                                <?php echo $type; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <div class="health-bar">
                                        <?php 
                                        $healthPercent = ($pokemon->getHealth() / $pokemon->getMaxHealth()) * 100;
                                        $healthClass = 'health-high';
                                        if ($healthPercent < 50) $healthClass = 'health-medium';
                                        if ($healthPercent < 25) $healthClass = 'health-low';
                                        if ($pokemon->isFainted()) $healthClass = 'health-zero';
                                        ?>
                                        <div class="health-fill <?php echo $healthClass; ?>" style="width: <?php echo $pokemon->isFainted() ? '100' : $healthPercent; ?>%"></div>
                                    </div>
                                    <div class="health-text">
                                        HP: <?php echo $pokemon->getHealth(); ?>/<?php echo $pokemon->getMaxHealth(); ?>
                                        <?php if ($pokemon->isFainted()): ?>
                                            <span style="color: #f44336; font-weight: bold;"> - FAINTED</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($index === 0): ?>
                                <div class="attacks">
                                    <?php foreach ($pokemon->getAttacks() as $attackIndex => $attack): ?>
                                        <form method="post" class="attack-form" onsubmit="return handleAttack(this, '<?php echo $battle->getPlayer1(); ?>', <?php echo $attackIndex; ?>)">
                                            <input type="hidden" name="player" value="<?php echo $battle->getPlayer1(); ?>">
                                            <input type="hidden" name="action" value="attack">  
                                            <input type="hidden" name="attack_index" value="<?php echo $attackIndex; ?>">
                                            <button type="submit" class="attack-btn" 
                                                    <?php echo $battle->getCurrentTurn() !== $battle->getPlayer1() || $pokemon->isFainted() ? 'disabled' : ''; ?>>
                                                <?php echo $attack->getName(); ?> (<?php echo $attack->getPower(); ?>)
                                            </button>
                                        </form>
                                    <?php endforeach; ?>
                                </div>
                                
                                <!-- Switch Pokemon Section -->
                                <div class="switch-section">
                                    <div class="switch-title">Switch Pokemon:</div>
                                    <div class="switch-options">
                                        <?php foreach ($battle->getPlayer1Pokemon() as $switchIndex => $switchPokemon): ?>
                                            <?php if ($switchIndex !== 0): ?>
                                                <div class="switch-option <?php echo $switchPokemon->isFainted() ? 'disabled' : ''; ?>">
                                                    <div class="switch-pokemon-info">
                                                        <img src="<?php echo $switchPokemon->getImage(); ?>" 
                                                             alt="<?php echo $switchPokemon->getName(); ?>" 
                                                             class="switch-pokemon-image 
                                                                    <?php echo $switchPokemon->isFainted() ? 
                                                                        ($switchIndex === 0 ? 'fainted' : 'fainted2') : ''; ?>">
                                                        <span><?php echo $switchPokemon->getName(); ?></span>
                                                        <div class="pokemon-types">
                                                            <?php foreach ($switchPokemon->getTypes() as $type): ?>
                                                                <div class="pokemon-type type-<?php echo strtolower($type); ?>">
                                                                    <?php echo $type; ?>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <span>HP: <?php echo $switchPokemon->getHealth(); ?>/<?php echo $switchPokemon->getMaxHealth(); ?></span>
                                                        <?php if ($switchPokemon->isFainted()): ?>
                                                            <span style="color: #f44336; font-weight: bold;"> - FAINTED</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <form method="post" class="switch-form">
                                                        <input type="hidden" name="player" value="<?php echo $battle->getPlayer1(); ?>">
                                                        <input type="hidden" name="action" value="switch">
                                                        <input type="hidden" name="pokemon_index" value="<?php echo $switchIndex; ?>">
                                                        <button type="submit" class="switch-btn" 
                                                                <?php echo $battle->getCurrentTurn() !== $battle->getPlayer1() || $switchPokemon->isFainted() ? 'disabled' : ''; ?>>
                                                            Switch
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                
                                <!-- Use Item Section -->
                                <div class="item-section">
                                    <form method="post">
                                        <input type="hidden" name="player" value="<?php echo $battle->getPlayer1(); ?>">
                                        <input type="hidden" name="action" value="item">
                                        <button type="submit" class="item-btn"
                                                <?php echo $battle->getCurrentTurn() !== $battle->getPlayer1() || $pokemon->isFainted() ? 'disabled' : ''; ?>>
                                            Use Potion (+70 HP)
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Player 2 Area -->
                <div class="player-area <?php echo $battle->getCurrentTurn() === $battle->getPlayer2() ? 'current-turn' : ''; ?>" id="player2-area">
                    <div class="player-header">
                        <div class="player-name"><?php echo $battle->getPlayer2(); ?></div>
                        <div><?php echo $battle->getCurrentTurn() === $battle->getPlayer2() ? 'Your Turn!' : ''; ?></div>
                    </div>
                    
                    <?php foreach ($battle->getPlayer2Pokemon() as $index => $pokemon): ?>
                        <div class="pokemon-card 
                            <?php echo $index === 0 ? 'active-pokemon' : ''; ?> 
                            <?php echo $pokemon->isFainted() ? ($index === 0 ? 'fainted' : 'fainted2') : ''; ?>" 
                            id="player2-pokemon-<?php echo $index; ?>">
                            <div class="pokemon-display">
                                <div class="pokemon-image-container">
                                    <img src="<?php echo $pokemon->getImage(); ?>" alt="<?php echo $pokemon->getName(); ?>" class="pokemon-image" id="player2-sprite-<?php echo $index; ?>">
                                    <!-- Animation container for player 2 - positioned directly on sprite -->
                                    <div class="attack-animation hidden" id="animation-player2">
                                        <img src="https://www.spriters-resource.com/media/asset_icons/173/176527.gif?updated=1755486559" class="attack-image" alt="Attack Animation">
                                    </div>
                                </div>
                                <div class="pokemon-info">
                                    <div class="pokemon-name"><?php echo $pokemon->getName(); ?></div>
                                    <div class="pokemon-types">
                                        <?php foreach ($pokemon->getTypes() as $type): ?>
                                            <div class="pokemon-type type-<?php echo strtolower($type); ?>">
                                                <?php echo $type; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <div class="health-bar">
                                        <?php 
                                        $healthPercent = ($pokemon->getHealth() / $pokemon->getMaxHealth()) * 100;
                                        $healthClass = 'health-high';
                                        if ($healthPercent < 50) $healthClass = 'health-medium';
                                        if ($healthPercent < 25) $healthClass = 'health-low';
                                        if ($pokemon->isFainted()) $healthClass = 'health-zero';
                                        ?>
                                        <div class="health-fill <?php echo $healthClass; ?>" style="width: <?php echo $pokemon->isFainted() ? '100' : $healthPercent; ?>%"></div>
                                    </div>
                                    <div class="health-text">
                                        HP: <?php echo $pokemon->getHealth(); ?>/<?php echo $pokemon->getMaxHealth(); ?>
                                        <?php if ($pokemon->isFainted()): ?>
                                            <span style="color: #f44336; font-weight: bold;"> - FAINTED</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($index === 0): ?>
                                <div class="attacks">
                                    <?php foreach ($pokemon->getAttacks() as $attackIndex => $attack): ?>
                                        <form method="post" class="attack-form" onsubmit="return handleAttack(this, '<?php echo $battle->getPlayer2(); ?>', <?php echo $attackIndex; ?>)">
                                            <input type="hidden" name="player" value="<?php echo $battle->getPlayer2(); ?>">
                                            <input type="hidden" name="action" value="attack">
                                            <input type="hidden" name="attack_index" value="<?php echo $attackIndex; ?>">
                                            <button type="submit" class="attack-btn" 
                                                    <?php echo $battle->getCurrentTurn() !== $battle->getPlayer2() || $pokemon->isFainted() ? 'disabled' : ''; ?>>
                                                <?php echo $attack->getName(); ?> (<?php echo $attack->getPower(); ?>)
                                            </button>
                                        </form>
                                    <?php endforeach; ?>
                                </div>
                                
                                <!-- Switch Pokemon Section -->
                                <div class="switch-section">
                                    <div class="switch-title">Switch Pokemon:</div>
                                    <div class="switch-options">
                                        <?php foreach ($battle->getPlayer2Pokemon() as $switchIndex => $switchPokemon): ?>
                                            <?php if ($switchIndex !== 0): ?>
                                                <div class="switch-option <?php echo $switchPokemon->isFainted() ? 'disabled' : ''; ?>">
                                                    <div class="switch-pokemon-info">
                                                        <img src="<?php echo $switchPokemon->getImage(); ?>" 
                                                             alt="<?php echo $switchPokemon->getName(); ?>" 
                                                             class="switch-pokemon-image 
                                                                    <?php echo $switchPokemon->isFainted() ? 
                                                                        ($switchIndex === 0 ? 'fainted' : 'fainted2') : ''; ?>">
                                                        <span><?php echo $switchPokemon->getName(); ?></span>
                                                        <div class="pokemon-types">
                                                            <?php foreach ($switchPokemon->getTypes() as $type): ?>
                                                                <div class="pokemon-type type-<?php echo strtolower($type); ?>">
                                                                    <?php echo $type; ?>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <span>HP: <?php echo $switchPokemon->getHealth(); ?>/<?php echo $switchPokemon->getMaxHealth(); ?></span>
                                                        <?php if ($switchPokemon->isFainted()): ?>
                                                            <span style="color: #f44336; font-weight: bold;"> - FAINTED</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <form method="post" class="switch-form">
                                                        <input type="hidden" name="player" value="<?php echo $battle->getPlayer2(); ?>">
                                                        <input type="hidden" name="action" value="switch">
                                                        <input type="hidden" name="pokemon_index" value="<?php echo $switchIndex; ?>">
                                                        <button type="submit" class="switch-btn" 
                                                                <?php echo $battle->getCurrentTurn() !== $battle->getPlayer2() || $switchPokemon->isFainted() ? 'disabled' : ''; ?>>
                                                            Switch
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                
                                <!-- Use Item Section -->
                                <div class="item-section">
                                    <form method="post">
                                        <input type="hidden" name="player" value="<?php echo $battle->getPlayer2(); ?>">
                                        <input type="hidden" name="action" value="item">
                                        <button type="submit" class="item-btn"
                                                <?php echo $battle->getCurrentTurn() !== $battle->getPlayer2() || $pokemon->isFainted() ? 'disabled' : ''; ?>>
                                            Use Potion (+70 HP)
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="battle-log">
                <div class="log-title">Battle Log</div>
                <?php foreach (array_reverse($battle->getBattleLog()) as $logEntry): ?>
                    <div class="log-entry"><?php echo $logEntry; ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="script.js"></script>
</body>
</html>