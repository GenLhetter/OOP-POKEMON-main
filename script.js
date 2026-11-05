'use strict';

/* ================= Data ================= */
// Type chart (attack type -> defender type -> multiplier), from your original code
const typeEffectiveness = {
    normal:   { rock: 0.5, steel: 0.5 },
    fire:     { fire: 0.5, water: 2, rock: 0.5, dragon: 3 },
    water:    { fire: 2, water: 0.5, ground: 2, rock: 2 },
    electric: { water: 2, flying: 0.5 },
    grass:    { fire: 0.5, grass: 0.5, poison: 0.5, flying: 2, bug: 2, dragon: 3 },
    ice:      { fire: 0.5, water: 0.5, ice: 0.5, dragon: 3 },
    fighting: { normal: 2, ice: 0.5, rock: 2, steel: 2, dark: 2 },
    poison:   { poison: 0.5, ground: 2, rock: 0.5 },
    ground:   { fire: 2, electric: 2, poison: 2, rock: 2, steel: 2 },
    flying:   { electric: 2, ice: 0.5, rock: 0.5 },
    psychic:  { fighting: 2, poison: 2 },
    bug:      { grass: 2, psychic: 2, dark: 0.5 },
    rock:     { fire: 2, ice: 2, flying: 2, bug: 2 },
    ghost:    { ghost: 2, psychic: 2 },
    dragon:   { dragon: 3 },
    dark:     { fighting: 0.5, psychic: 2, ghost: 2 },
    steel:    { fire: 0.5, water: 0.5, ice: 2, fairy: 0.5 },
    fairy:    { fighting: 2, poison: 2, steel: 0.5 }
};

// [name, power, type, accuracy]
const ROSTER = [
    { name: 'Bulbasaur',  id: 1,   types: ['grass', 'poison'], hp: 100, speed: 45, attacks: [['Vine Whip', 49, 'grass', 100], ['Poison Powder', 60, 'poison', 75], ['Seed Bomb', 80, 'grass', 100], ['Slam', 65, 'normal', 75]] },
    { name: 'Charmander', id: 4,   types: ['fire'],            hp: 100, speed: 65, attacks: [['Ember', 40, 'fire', 100], ['Flamethrower', 90, 'fire', 90], ['Fire Spin', 50, 'fire', 70], ['Slam', 65, 'normal', 75]] },
    { name: 'Squirtle',   id: 7,   types: ['water'],           hp: 100, speed: 65, attacks: [['Tackle', 40, 'normal', 100], ['Water Gun', 40, 'water', 100], ['Bubble', 40, 'water', 100], ['Slam', 65, 'normal', 75]] },
    { name: 'Pikachu',    id: 25,  types: ['electric'],        hp: 100, speed: 90, attacks: [['Thunderbolt', 55, 'electric', 90], ['Quick Attack', 40, 'normal', 100], ['Iron Tail', 50, 'steel', 80], ['Slam', 65, 'normal', 75]] },
    { name: 'Eevee',      id: 133, types: ['normal'],          hp: 100, speed: 55, attacks: [['Tackle', 40, 'normal', 100], ['Tail Whip', 0, 'normal', 100], ['Quick Attack', 40, 'normal', 100], ['Sand Attack', 0, 'ground', 90]] },
    { name: 'Rattata',    id: 19,  types: ['normal'],          hp: 80,  speed: 70, attacks: [['Tackle', 40, 'normal', 100], ['Quick Attack', 40, 'normal', 100], ['Hyper Fang', 70, 'normal', 90], ['Tail Whip', 0, 'normal', 100]] }
];

const POTION_HEAL = 70;
const POTIONS_PER_PLAYER = 1;
const spriteUrl = id => `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${id}.png`;

/* ================= Classes ================= */
class BattleEntity {
    constructor(name, health) {
        this.name = name;
        this.health = health;
        this.maxHealth = health;
    }
    getName() { return this.name; }
    getHealth() { return this.health; }
    getMaxHealth() { return this.maxHealth; }
    isFainted() { return this.health <= 0; }
    takeDamage(damage) {
        const dealt = Math.min(damage, this.health);
        this.health -= dealt;
        return dealt;
    }
    heal(amount) {
        const before = this.health;
        this.health = Math.min(this.maxHealth, this.health + amount);
        return this.health - before;
    }
}

class Attack {
    constructor(name, power, type, accuracy = 90) {
        this.name = name;
        this.power = power;
        this.type = type;
        this.accuracy = accuracy;
    }
    getName() { return this.name; }
    getPower() { return this.power; }
    getType() { return this.type; }
    getAccuracy() { return this.accuracy; }

    getEffectiveness(defenderTypes) {
        const chart = typeEffectiveness[this.type] || {};
        return defenderTypes.reduce((mult, t) => mult * (chart[t] ?? 1), 1);
    }
    calculateDamage(attackerTypes, defenderTypes) {
        const stab = attackerTypes.includes(this.type) ? 1.5 : 1;
        const randomVariation = 0.85 + Math.random() * 0.15;
        return Math.floor(this.power * this.getEffectiveness(defenderTypes) * stab * randomVariation);
    }
}

class Pokemon extends BattleEntity {
    constructor(name, types, health, speed, attacks, image, fallbackImage) {
        super(name, health);
        this.types = Array.isArray(types) ? types : [types];
        this.speed = speed;
        this.attacks = attacks;
        this.image = image;
        this.fallbackImage = fallbackImage;
    }
    getTypes() { return this.types; }
    getSpeed() { return this.speed; }
    getAttacks() { return this.attacks; }
    getImage() { return this.image; }
    getFallbackImage() { return this.fallbackImage; }
}

// Fresh Pokemon every call, so health never carries over between games
function createRoster() {
    return ROSTER.map(d => new Pokemon(
        d.name, d.types, d.hp, d.speed,
        d.attacks.map(a => new Attack(...a)),
        `images/${d.name.toLowerCase()}.png`,   // local file first
        spriteUrl(d.id)                          // online sprite if the local one is missing
    ));
}

class PokemonBattle {
    constructor(name1, name2, team1, team2) {
        this.players = {
            player1: { name: name1, team: team1, active: 0, potions: POTIONS_PER_PLAYER },
            player2: { name: name2, team: team2, active: 0, potions: POTIONS_PER_PLAYER }
        };
        this.turn = 'player1';
        this.gameOver = false;
        this.winner = null;
        this.log = [];
    }
    opponentOf(key) { return key === 'player1' ? 'player2' : 'player1'; }
    getActive(key) { const p = this.players[key]; return p.team[p.active]; }
    addToLog(message, cls = '') { this.log.push({ message, cls }); }

    // After a Pokemon faints, send out the next one that can still fight
    sendNext(key) {
        const p = this.players[key];
        const next = p.team.findIndex(mon => !mon.isFainted());
        if (next === -1) {
            this.gameOver = true;
            this.winner = this.opponentOf(key);
            this.addToLog(`${p.name} has no more Pokemon! ${this.players[this.winner].name} wins!`);
        } else {
            p.active = next;
            this.addToLog(`${p.name} sends out ${p.team[next].getName()}!`);
        }
    }

    executeTurn(key, action) {
        if (this.gameOver || key !== this.turn) return { error: 'Not your turn' };
        const me = this.players[key];
        const foeKey = this.opponentOf(key);
        const attacker = this.getActive(key);
        const defender = this.getActive(foeKey);
        let result;

        if (action.type === 'attack') {
            const attack = attacker.getAttacks()[action.attackIndex];
            if (!attack) return { error: 'Invalid attack' };
            if (Math.random() * 100 > attack.getAccuracy()) {
                this.addToLog(`${attacker.getName()} used ${attack.getName()} but it missed!`);
                result = { missed: true, target: foeKey };
            } else {
                const effect = attack.getEffectiveness(defender.getTypes());
                const dealt = defender.takeDamage(attack.calculateDamage(attacker.getTypes(), defender.getTypes()));
                this.addToLog(`${attacker.getName()} used ${attack.getName()} on ${defender.getName()} for ${dealt} damage!`);
                if (attack.getPower() > 0 && effect > 1) this.addToLog("It's super effective!");
                if (attack.getPower() > 0 && effect < 1) this.addToLog("It's not very effective...");
                result = { hit: true, target: foeKey, damage: dealt };
                if (defender.isFainted()) {
                    this.addToLog(`${defender.getName()} fainted!`);
                    this.sendNext(foeKey);
                }
            }
        } else if (action.type === 'item') {
            if (me.potions <= 0 || attacker.getHealth() >= attacker.getMaxHealth()) return { error: 'Cannot use potion' };
            me.potions--;
            const healed = attacker.heal(POTION_HEAL);
            this.addToLog(`${me.name} used a Potion on ${attacker.getName()} for +${healed} HP!`);
            result = { healed };
        } else if (action.type === 'switch') {
            const target = me.team[action.pokemonIndex];
            if (!target || target.isFainted() || action.pokemonIndex === me.active) return { error: 'Invalid switch' };
            me.active = action.pokemonIndex;
            this.addToLog(`${me.name} switched to ${target.getName()}!`);
            result = { switched: true };
        } else {
            return { error: 'Unknown action' };
        }

        if (!this.gameOver) this.turn = foeKey;
        return result;
    }
}

/* ================= UI ================= */
const $ = id => document.getElementById(id);
const esc = s => String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

const el = {
    selection: $('teamSelection'), selectTitle: $('selectTitle'), playerName: $('playerName'),
    grid: $('pokemonGrid'), confirm: $('confirmBtn'), battle: $('battlePhase'),
    areas: { player1: $('player1-area'), player2: $('player2-area') },
    log: $('battleLog'), gameOver: $('gameOver'), winnerText: $('winnerText'),
    reset: $('resetBtn'), newGame: $('newGameBtn'),
    musicControls: $('musicControls'), music: $('battleMusic'), playPause: $('playPauseBtn'), volume: $('volumeSlider')
};

let game = null;
let selectingPlayer = 1;
let picks = [];
let setup = { names: ['Player 1', 'Player 2'], teams: [[], []] };
let logShown = 0;
const previewRoster = createRoster();

/* ---------- Team selection ---------- */
function renderGrid() {
    el.grid.innerHTML = '';
    previewRoster.forEach((p, i) => {
        const opt = document.createElement('div');
        opt.className = 'pokemon-option' + (picks.includes(i) ? ' selected' : '');
        opt.style.opacity = picks.length >= 3 && !picks.includes(i) ? '0.5' : '1';
        opt.innerHTML = `
            <img src="${esc(p.getImage())}" alt="${esc(p.getName())}" onerror="this.onerror=null;this.src='${esc(p.getFallbackImage())}'">
            <div class="pokemon-name">${esc(p.getName())}</div>
            <div class="pokemon-types">${p.getTypes().map(t => `<span class="pokemon-type type-${t}">${t}</span>`).join('')}</div>`;
        opt.addEventListener('click', () => {
            if (picks.includes(i)) picks = picks.filter(x => x !== i);
            else if (picks.length < 3) picks.push(i);
            renderGrid();
        });
        el.grid.appendChild(opt);
    });
    el.confirm.disabled = picks.length !== 3;
}

function startSelection() {
    selectingPlayer = 1;
    picks = [];
    setup = { names: ['Player 1', 'Player 2'], teams: [[], []] };
    el.playerName.value = '';
    el.playerName.placeholder = 'Player 1 name';
    el.selectTitle.textContent = 'Player 1: choose your team';
    el.confirm.textContent = 'Confirm Team';
    el.selection.hidden = false;
    el.battle.hidden = true;
    el.gameOver.hidden = true;
    renderGrid();
}

el.confirm.addEventListener('click', () => {
    if (picks.length !== 3) return;
    const idx = selectingPlayer - 1;
    setup.names[idx] = el.playerName.value.trim() || `Player ${selectingPlayer}`;
    setup.teams[idx] = picks.slice();

    if (selectingPlayer === 1) {
        selectingPlayer = 2;
        picks = [];
        el.playerName.value = '';
        el.playerName.placeholder = 'Player 2 name';
        el.selectTitle.textContent = 'Player 2: choose your team';
        el.confirm.textContent = 'Start Battle!';
        renderGrid();
    } else {
        startBattle();
    }
});

/* ---------- Battle ---------- */
function startBattle() {
    const r1 = createRoster(), r2 = createRoster();
    game = new PokemonBattle(
        setup.names[0], setup.names[1],
        setup.teams[0].map(i => r1[i]), setup.teams[1].map(i => r2[i])
    );
    logShown = 0;
    el.log.innerHTML = '';
    game.addToLog(`Battle started! ${setup.names[0]} goes first.`, 'turn');
    el.selection.hidden = true;
    el.battle.hidden = false;
    el.gameOver.hidden = true;
    startMusic();
    render();
}

function healthClass(p) {
    if (p.isFainted()) return 'health-zero';
    const pct = p.getHealth() / p.getMaxHealth();
    return pct > 0.5 ? 'health-high' : pct > 0.25 ? 'health-medium' : 'health-low';
}

function cardHTML(p, key) {
    const pct = Math.round((p.getHealth() / p.getMaxHealth()) * 100);
    return `
    <div class="pokemon-card active-pokemon ${p.isFainted() ? 'fainted' : ''}" id="${key}-card">
        <div class="pokemon-display">
            <div class="pokemon-image-container">
                <img src="${esc(p.getImage())}" alt="${esc(p.getName())}" class="pokemon-image" id="${key}-sprite"
                     onerror="this.onerror=null;this.src='${esc(p.getFallbackImage())}'">
                <div class="attack-animation hidden" id="animation-${key}">
                    <img src="https://www.spriters-resource.com/media/asset_icons/173/176527.gif?updated=1755486559" class="attack-image" alt="" onerror="this.remove()">
                </div>
            </div>
            <div class="pokemon-info">
                <div class="pokemon-name">${esc(p.getName())}</div>
                <div class="pokemon-types">${p.getTypes().map(t => `<div class="pokemon-type type-${t}">${t}</div>`).join('')}</div>
                <div class="health-bar"><div class="health-fill ${healthClass(p)}" style="width:${p.isFainted() ? 100 : pct}%"></div></div>
                <div class="health-text">HP: ${p.getHealth()}/${p.getMaxHealth()}</div>
                ${p.isFainted() ? '<span style="color:#f44336;font-weight:bold"> - FAINTED</span>' : ''}
            </div>
        </div>
    </div>`;
}

function areaHTML(key) {
    const pl = game.players[key];
    const active = game.getActive(key);
    const myTurn = game.turn === key && !game.gameOver;

    const attacks = active.getAttacks().map((a, i) =>
        `<button class="attack-btn" type="button" data-action="attack" data-index="${i}" ${myTurn ? '' : 'disabled'}>${esc(a.getName())} (Power: ${a.getPower()})</button>`
    ).join('');

    const switches = pl.team.map((p, i) => {
        if (i === pl.active) return '';
        const fainted = p.isFainted();
        return `<button class="switch-option ${fainted ? 'disabled' : ''}" type="button" data-action="switch" data-index="${i}" ${myTurn && !fainted ? '' : 'disabled'}>
            <span class="switch-pokemon-info">
                <img class="switch-pokemon-image" src="${esc(p.getImage())}" alt="" onerror="this.onerror=null;this.src='${esc(p.getFallbackImage())}'">
                <span>${esc(p.getName())}</span>
            </span>
            <span>HP: ${p.getHealth()}/${p.getMaxHealth()}</span>
        </button>`;
    }).join('');

    const canPotion = myTurn && pl.potions > 0 && active.getHealth() < active.getMaxHealth();

    return `
        <div class="player-header"><div class="player-name">${esc(pl.name)}</div></div>
        ${cardHTML(active, key)}
        <div class="attacks">${attacks}</div>
        <div class="switch-section">
            <div class="switch-title">Switch Pokemon</div>
            <div class="switch-options">${switches}</div>
        </div>
        <div class="item-section">
            <button class="item-btn" type="button" data-action="item" ${canPotion ? '' : 'disabled'}>Use Potion (+${POTION_HEAL} HP) x${pl.potions}</button>
        </div>`;
}

function render() {
    ['player1', 'player2'].forEach(key => {
        el.areas[key].innerHTML = areaHTML(key);
        el.areas[key].classList.toggle('current-turn', game.turn === key && !game.gameOver);
    });

    // append only the new log lines
    for (; logShown < game.log.length; logShown++) {
        const entry = document.createElement('div');
        entry.className = 'log-entry ' + game.log[logShown].cls;
        entry.textContent = game.log[logShown].message;
        el.log.appendChild(entry);
    }
    el.log.scrollTop = el.log.scrollHeight;

    if (game.gameOver) {
        el.winnerText.textContent = `${game.players[game.winner].name} wins!`;
        el.gameOver.hidden = false;
    }
}

function playHitAnimation(targetKey) {
    const anim = $(`animation-${targetKey}`);
    const card = $(`${targetKey}-card`);
    const sprite = $(`${targetKey}-sprite`);
    if (anim) { anim.classList.remove('hidden'); setTimeout(() => anim.classList.add('hidden'), 1000); }
    if (card) {
        card.classList.add('screen-shake', 'damage-flash');
        setTimeout(() => card.classList.remove('screen-shake', 'damage-flash'), 500);
    }
    if (sprite) {
        sprite.classList.add('pokemon-shake');
        setTimeout(() => sprite.classList.remove('pokemon-shake'), 500);
    }
}

function onAreaClick(key, e) {
    const btn = e.target.closest('button[data-action]');
    if (!btn || btn.disabled || !game) return;
    const action = { type: btn.dataset.action };
    if (action.type === 'attack') action.attackIndex = Number(btn.dataset.index);
    if (action.type === 'switch') action.pokemonIndex = Number(btn.dataset.index);

    const result = game.executeTurn(key, action);
    if (result.error) return;

    if (!game.gameOver) game.addToLog(`--- It's ${game.players[game.turn].name}'s turn! ---`, 'turn');
    render();
    if (result.hit) playHitAnimation(result.target);
}
el.areas.player1.addEventListener('click', e => onAreaClick('player1', e));
el.areas.player2.addEventListener('click', e => onAreaClick('player2', e));

/* ---------- Music ---------- */
function startMusic() {
    el.musicControls.style.display = 'flex';
    el.music.volume = Number(el.volume.value);
    el.music.play().then(() => { el.playPause.textContent = '⏸️'; })
        .catch(() => { el.playPause.textContent = '▶️'; });   // missing file or autoplay blocked
}
el.playPause.addEventListener('click', () => {
    if (el.music.paused) { el.music.play().catch(() => {}); el.playPause.textContent = '⏸️'; }
    else { el.music.pause(); el.playPause.textContent = '▶️'; }
});
el.volume.addEventListener('input', () => { el.music.volume = Number(el.volume.value); });

/* ---------- Reset / new game ---------- */
function backToStart() {
    el.music.pause();
    el.music.currentTime = 0;
    el.musicControls.style.display = 'none';
    game = null;
    startSelection();
}
el.reset.addEventListener('click', backToStart);
el.newGame.addEventListener('click', backToStart);

startSelection();