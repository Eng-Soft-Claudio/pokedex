document.addEventListener("DOMContentLoaded", function() {
    const searchForm = document.querySelector('form[method="GET"]');
    const searchInput = document.querySelector('input[name="search"]');
    const resultsContainer = document.querySelector('.results');
    const lightModeButton = document.getElementById('light-mode');
    const darkModeButton = document.getElementById('dark-mode');
    const body = document.body;
    const comparisonForm = document.getElementById('comparison-form');
    const comparisonResult = document.getElementById('comparison-result');
    const evolutionList = document.getElementById('evolution-list');

    // Validação de formulário
    searchForm.addEventListener('submit', function(event) {
        if (searchInput.value.trim() === "") {
            event.preventDefault();
            alert("Por favor, insira um nome ou ID de Pokémon.");
        }
    });

    // Busca dinâmica
    searchInput.addEventListener('input', function() {
        const query = searchInput.value.trim();
        if (query.length > 2) {
            fetch(`/buscar-pokemon?q=${query}`)
                .then(response => response.json())
                .then(data => {
                    resultsContainer.innerHTML = data.map(pokemon => `<p>${pokemon.name}</p>`).join('');
                })
                .catch(error => console.error('Erro na busca:', error));
        }
    });

    // Feedback visual
    function showMessage(message, type = 'success') {
        const messageElement = document.createElement('div');
        messageElement.className = `alert alert-${type}`;
        messageElement.innerText = message;
        document.body.appendChild(messageElement);

        setTimeout(() => {
            messageElement.remove();
        }, 3000);
    }

    // Modo claro e escuro
    lightModeButton.addEventListener('click', function() {
        body.classList.remove('dark-mode');
        body.classList.add('light-mode');
    });

    darkModeButton.addEventListener('click', function() {
        body.classList.remove('light-mode');
        body.classList.add('dark-mode');
    });

    // Função para buscar evoluções
    function fetchEvolutions(pokemonName) {
        fetch(`https://pokeapi.co/api/v2/pokemon-species/${pokemonName}`)
            .then(response => response.json())
            .then(data => {
                evolutionList.innerHTML = ''; // Limpar lista de evoluções
                fetch(data.evolution_chain.url)
                    .then(response => response.json())
                    .then(evolutionData => {
                        let evolutions = [];
                        let evoData = evolutionData.chain;

                        do {
                            evolutions.push(evoData.species.name);
                            evoData = evoData.evolves_to[0];
                        } while (evoData);

                        evolutions.forEach(name => {
                            let li = document.createElement('li');
                            li.textContent = name;
                            evolutionList.appendChild(li);
                        });
                    });
            });
    }

    // Função para comparar Pokémon
    comparisonForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const pokemon1 = document.getElementById('pokemon1').value.toLowerCase();
        const pokemon2 = document.getElementById('pokemon2').value.toLowerCase();

        Promise.all([
            fetch(`https://pokeapi.co/api/v2/pokemon/${pokemon1}`).then(response => response.json()),
            fetch(`https://pokeapi.co/api/v2/pokemon/${pokemon2}`).then(response => response.json())
        ])
        .then(([data1, data2]) => {
            comparisonResult.innerHTML = `
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">${data1.name} vs ${data2.name}</h5>
                        <p>Altura: ${data1.height / 10}m vs ${data2.height / 10}m</p>
                        <p>Peso: ${data1.weight / 10}kg vs ${data2.weight / 10}kg</p>
                    </div>
                </div>
            `;
        });
    });

    // Buscar evoluções ao carregar a página
    if (window.pokemonData) {
        fetchEvolutions(window.pokemonData.name);
    }
});
