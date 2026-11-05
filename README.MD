1. Project Overview
Game Title: Pokemon Battle Arena

Description:
Pokemon Battle Arena is a web-based, turn-based battle game where two players can select teams of three Pokemon each and engage in strategic battles. The game features authentic Pokemon mechanics including type effectiveness, STAB (Same Type Attack Bonus), accuracy checks, and damage calculation. Players can attack, switch Pokemon, or use healing items during their turns. The game includes visual effects, animations, and battle music for an immersive experience.

Game Objectives:

Defeat all three Pokemon in your opponent's team to win the battle.

Strategically use type advantages, switching, and items to gain an edge.

Win Condition: When all three Pokemon in the opponent's team are fainted (HP reduced to 0).

Lose Condition: When all three Pokemon in your team are fainted.

2. Technology Stack
PHP 7.4+ - Server-side programming language

HTML5 - Markup structure

CSS3 - Styling and animations

JavaScript (ES6) - Client-side interactivity

XAMPP - Local development environment (Apache + MySQL)

Object-Oriented Programming (OOP) - PHP classes and interfaces

Session Management - PHP sessions for game state persistence

3. Team Members
Leader: Gen Lhetter Villanueva - PHP, CSS, Javascript, HTML
Members:Jian Kyle Ruiz - PHP
        Efren Barona - Javascript
        Carl Kian Pierce - CSS, HTML
        Brian Kierby - PHP

4. How to Play
Game Rules:
Team Selection Phase:

Each player selects 3 Pokemon from the available pool

Pokemon have different types, HP, speed, and attacks

Players cannot select the same Pokemon

Battle Phase:

Turns alternate between players based on active Pokemon's speed

During your turn, you can:

Attack: Use one of your Pokemon's four attacks

Switch: Swap your active Pokemon with a benched one

Use Item: Heal your active Pokemon with a potion (+70 HP)

Battle Mechanics:

Type Effectiveness: Attacks deal more or less damage based on type matchups (e.g., Water > Fire)

STAB Bonus: Same-type attacks deal 1.5x damage

Accuracy: Some attacks may miss (e.g., Thunder has 70% accuracy)

Damage Calculation: Includes random variation (85%-100% of calculated damage)

Controls:
Team Selection: Click Pokemon cards to select/deselect (3 maximum)

Battle Actions: Click attack buttons, switch options, or item buttons

Music Controls: Play/pause and volume slider in bottom-right corner

New Game: "New Game" button to reset everything

5. How to Run the Program
Prerequisites:
XAMPP (or any PHP server environment)

Web browser (Chrome, Firefox, Edge, etc.)

Installation Steps:
Install XAMPP:

Download from https://www.apachefriends.org/

Install following the installer instructions

Start Apache from the XAMPP Control Panel

Setup Project Files:

Create a folder named pokemon-battle in C:\xampp\htdocs\

Copy all three provided files into this folder:

index.php

main.php

script.js

Create a music folder inside pokemon-battle and add POKEMONBATTLESOUND.mp3

Folder Structure:

C:\xampp\htdocs\pokemon-battle\
├── index.php
├── main.php
├── script.js
└── music/
    └── POKEMONBATTLESOUND.mp3


Run the Game:

Ensure XAMPP's Apache is running (green "Running" status)

Open your web browser

Navigate to: http://localhost/pokemon-battle/main.php

The game should load and be ready to play

Optional: Music Setup
The game expects battle music at music/POKEMONBATTLESOUND.mp3

You can use any MP3 file or download Pokemon battle music

If music doesn't play, check browser console for errors and ensure file path is correct

Troubleshooting:
Sometimes 2nd Player Choices Get Bugged

PHP errors: Ensure XAMPP's Apache is running

No music: Check browser's autoplay policies or click the play button manually


Sometimes No Damage on any Pokemon


