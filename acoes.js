 // Dados da agenda (simulando um banco de dados)
 let eventos = [
    { id: 1, titulo: "Reunião de Equipe", horario: "09:00 - 10:30", descricao: "Reunião semanal com a equipe de desenvolvimento" },
    { id: 2, titulo: "Almoço com Cliente", horario: "12:30 - 14:00", descricao: "Almoço de negócios com representantes da empresa XYZ" },
    { id: 3, titulo: "Apresentação de Resultados", horario: "15:00 - 16:30", descricao: "Apresentação dos resultados do trimestre" }
];

// Elementos DOM
const dataAtualElement = document.getElementById('data-atual');
const listaEventosElement = document.getElementById('lista-eventos');
const loginLink = document.getElementById('loginLink');
const agendaLink = document.getElementById('agendaLink');
const overlay = document.getElementById('overlay');
const loginForm = document.getElementById('loginForm');
const btnLogin = document.getElementById('btn-login');
const btnAdicionar = document.getElementById('btn-adicionar');
const btnHoje = document.getElementById('btn-hoje');

// Atualizar data atual
function atualizarData() {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const dataAtual = new Date().toLocaleDateString('pt-BR', options);
    dataAtualElement.textContent = dataAtual;
}

// Carregar eventos na agenda
function carregarEventos() {
    listaEventosElement.innerHTML = '';
    
    if (eventos.length === 0) {
        listaEventosElement.innerHTML = '<div class="evento"><div class="evento-titulo">Nenhum evento agendado</div><div class="evento-descricao">Clique em "Adicionar Evento" para criar seu primeiro evento.</div></div>';
        return;
    }
    
    eventos.forEach(evento => {
        const eventoElement = document.createElement('div');
        eventoElement.className = 'evento';
        eventoElement.innerHTML = `
            <div class="evento-titulo">${evento.titulo}</div>
            <div class="evento-horario">${evento.horario}</div>
            <div class="evento-descricao">${evento.descricao}</div>
            <button class="btn" style="margin-top: 10px; background-color: #e74c3c;" onclick="removerEvento(${evento.id})">Remover</button>
        `;
        listaEventosElement.appendChild(eventoElement);
    });
}

// Adicionar novo evento
function adicionarEvento() {
    const titulo = prompt("Título do evento:");
    if (!titulo) return;
    
    const horario = prompt("Horário (ex: 14:00 - 15:30):");
    if (!horario) return;
    
    const descricao = prompt("Descrição do evento:") || "Sem descrição";
    
    const novoEvento = {
        id: eventos.length > 0 ? Math.max(...eventos.map(e => e.id)) + 1 : 1,
        titulo,
        horario,
        descricao
    };
    
    eventos.push(novoEvento);
    carregarEventos();
    alert("Evento adicionado com sucesso!");
}

// Remover evento
function removerEvento(id) {
    if (confirm("Tem certeza que deseja remover este evento?")) {
        eventos = eventos.filter(evento => evento.id !== id);
        carregarEventos();
    }
}

// Mostrar formulário de login
function mostrarLogin() {
    overlay.style.display = 'block';
    loginForm.style.display = 'block';
}

// Esconder formulário de login
function esconderLogin() {
    overlay.style.display = 'none';
    loginForm.style.display = 'none';
}

// Event Listeners
loginLink.addEventListener('click', function(e) {
    e.preventDefault();
    mostrarLogin();
});

agendaLink.addEventListener('click', function(e) {
    e.preventDefault();
    alert("Você já está na página de agenda!");
});

overlay.addEventListener('click', esconderLogin);

btnLogin.addEventListener('click', function() {
    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;
    
    if (email && senha) {
        alert(`Login realizado com sucesso!\nE-mail: ${email}`);
        esconderLogin();
    } else {
        alert("Por favor, preencha todos os campos.");
    }
});

btnAdicionar.addEventListener('click', adicionarEvento);

btnHoje.addEventListener('click', function() {
    atualizarData();
    alert("Data resetada para hoje!");
});

// Inicializar a página
window.onload = function() {
    atualizarData();
    carregarEventos();
};