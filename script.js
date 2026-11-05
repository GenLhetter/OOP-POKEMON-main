document.addEventListener('DOMContentLoaded', function() {
    const battleMusic = document.getElementById('battleMusic');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const volumeSlider = document.getElementById('volumeSlider');
    const musicControls = document.getElementById('musicControls');

    // Only show music controls and play music during battle
    if (typeof IS_BATTLE !== "undefined" && IS_BATTLE) {

        musicControls.style.display = 'flex';
        battleMusic.volume = volumeSlider.value;

        const playMusic = () => {
            battleMusic.play().catch(() => {
                playPauseBtn.textContent = '▶️';
            });
        };

        playMusic();

        document.addEventListener('click', function() {
            if (battleMusic.paused) playMusic();
        }, { once: true });

        playPauseBtn.addEventListener('click', function() {
            if (battleMusic.paused) {
                battleMusic.play();
                playPauseBtn.textContent = '⏸️';
            } else {
                battleMusic.pause();
                playPauseBtn.textContent = '▶️';
            }
        });

        volumeSlider.addEventListener('input', function() {
            battleMusic.volume = this.value;
        });

        battleMusic.addEventListener('ended', function() {
            battleMusic.currentTime = 0;
            battleMusic.play();
        });
    }

    // Attack animation
    function showAttackAnimation(targetPlayer) {
        const animationElement = document.getElementById(`animation-${targetPlayer}`);
        const pokemonCard = document.getElementById(`${targetPlayer}-pokemon-0`);
        const pokemonSprite = document.getElementById(`${targetPlayer}-sprite-0`);

        if (animationElement) {
            animationElement.classList.remove('hidden');
            setTimeout(() => animationElement.classList.add('hidden'), 1000);
        }

        if (pokemonCard) {
            pokemonCard.classList.add('screen-shake');
            setTimeout(() => pokemonCard.classList.remove('screen-shake'), 500);
        }

        if (pokemonSprite) {
            pokemonSprite.classList.add('pokemon-shake');
            setTimeout(() => pokemonSprite.classList.remove('pokemon-shake'), 500);
        }

        if (pokemonCard) {
            pokemonCard.classList.add('damage-flash');
            setTimeout(() => pokemonCard.classList.remove('damage-flash'), 300);
        }
    }

    // Global attack handler
    window.handleAttack = function(form, player, attackIndex) {
        const attackLoading = document.getElementById('attackLoading');
        const attackBtn = form.querySelector('.attack-btn');

        attackLoading.style.display = 'block';
        attackBtn.disabled = true;

        const targetPlayer = (player === PLAYER1_NAME) ? 'player2' : 'player1';

        showAttackAnimation(targetPlayer);

        setTimeout(() => form.submit(), 100);
        return false;
    };

    // PHP-triggered animation on refresh
    if (typeof SHOW_ANIMATION !== "undefined" && SHOW_ANIMATION) {
        setTimeout(() => {
            showAttackAnimation(TARGET_PLAYER);
        }, 100);
    }

    // Team selection
    const pokemonOptions = document.querySelectorAll('.pokemon-option');
    const submitButtons = document.querySelectorAll('.select-btn');

    if (pokemonOptions.length > 0) {
        let selectedPokemon = [];

        pokemonOptions.forEach(option => {
            option.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                const checkbox = this.querySelector('input[type="checkbox"]');

                if (selectedPokemon.includes(index)) {
                    selectedPokemon = selectedPokemon.filter(i => i !== index);
                    this.classList.remove('selected');
                    checkbox.checked = false;
                } else if (selectedPokemon.length < 3) {
                    selectedPokemon.push(index);
                    this.classList.add('selected');
                    checkbox.checked = true;
                }

                submitButtons.forEach(btn => {
                    btn.disabled = selectedPokemon.length !== 3;
                });

                if (selectedPokemon.length >= 3) {
                    pokemonOptions.forEach(opt => {
                        if (!selectedPokemon.includes(opt.getAttribute('data-index'))) {
                            opt.style.opacity = '0.5';
                        }
                    });
                } else {
                    pokemonOptions.forEach(opt => opt.style.opacity = '1');
                }
            });
        });

        submitButtons.forEach(btn => btn.disabled = true);
    }
});
