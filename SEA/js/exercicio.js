let intervals = [4, 7, 8]; // tempos em segundos: 4-7-8
let currentInterval = 0; // Índice do intervalo atual no array 'intervals'
let timer; // Variável para armazenar o identificador do intervalo (setInterval)
let isRunning = false; // Variável para verificar se o cronômetro está em execução
const display = document.getElementById('display'); // Seleciona o elemento do cronômetro
const status = document.getElementById('status'); // Seleciona o elemento de status
const controlButton = document.getElementById('controlButton'); // Seleciona o botão de controle

// Adiciona um evento de clique ao botão de controle
controlButton.addEventListener('click', () => {
    if (!isRunning) {
        startBreathing(); // Inicia o exercício de respiração se não estiver em execução
    } else {
        stopBreathing(); // Para o exercício de respiração se estiver em execução
    }
});

function startBreathing() {
    isRunning = true; // Marca que o cronômetro está em execução
    controlButton.textContent = 'PARAR'; // Muda o texto do botão para "PARAR"
    currentInterval = 0; // Reseta para o primeiro intervalo
    runInterval(); // Inicia a execução do primeiro intervalo
}

function runInterval() {
    let time = intervals[currentInterval]; // Define o tempo do intervalo atual
    let countdown = time; // Inicia o contador com o tempo do intervalo
    updateStatus(); // Atualiza a mensagem de status de acordo com o intervalo
    display.textContent = formatTime(countdown); // Exibe o tempo inicial no display

    // Define um intervalo que será executado a cada segundo
    timer = setInterval(() => {
        countdown--; // Decrementa o contador
        display.textContent = formatTime(countdown); // Atualiza o display a cada segundo

        if (countdown < 0) { // Quando o contador atinge 0
            clearInterval(timer); // Para o intervalo atual
            currentInterval++; // Passa para o próximo intervalo
            if (currentInterval < intervals.length) {
                runInterval(); // Inicia o próximo intervalo
            } else {
                currentInterval = 0; // Reinicia o ciclo após o último intervalo
                runInterval(); // Recomeça o ciclo automaticamente
            }
        }
    }, 1000); // A função é chamada a cada 1000 milissegundos (1 segundo)
}

function stopBreathing() {
    isRunning = false; // Marca que o cronômetro não está em execução
    clearInterval(timer); // Para o intervalo atual
    controlButton.textContent = 'INICIAR'; // Muda o texto do botão para "INICIAR"
    display.textContent = '00:00'; // Reseta o display para "00:00"
    status.textContent = ''; // Limpa o status
}

function updateStatus() {
    // Atualiza a mensagem de status de acordo com o intervalo atual
    switch(currentInterval) {
        case 0:
            status.textContent = 'Inspire...'; // Mensagem para a primeira fase (inspirar)
            break;
        case 1:
            status.textContent = 'Segure a respiração...'; // Mensagem para a segunda fase (segurar a respiração)
            break;
        case 2:
            status.textContent = 'Expire...'; // Mensagem para a terceira fase (expirar)
            break;
    }
}

function formatTime(seconds) {
    // Formata o tempo para sempre mostrar dois dígitos
    return `00:${seconds < 10 ? '0' : ''}${seconds}`;
}

// Script para o pop-up de login
const usuarioIcon = document.getElementById('usuario-icon'); // Seleciona o ícone de usuário pelo ID
const loginPopup = document.getElementById('login-popup'); // Seleciona o pop-up de login pelo ID

// Adiciona um evento de clique no ícone de usuário para mostrar/ocultar o pop-up
usuarioIcon.addEventListener('click', () => {
    loginPopup.style.display = loginPopup.style.display === 'block' ? 'none' : 'block';
});

// Fecha o pop-up ao clicar fora dele
window.addEventListener('click', (event) => {
    if (!usuarioIcon.contains(event.target) && !loginPopup.contains(event.target)) {
        loginPopup.style.display = 'none';
    }
});
