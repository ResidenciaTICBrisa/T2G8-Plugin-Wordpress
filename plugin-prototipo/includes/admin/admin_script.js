function mostrarDescricaoCompleta(id) {
    var descricaoResumida = document.getElementById('descricaoResumida_' + id);
    var descricaoCompleta = document.getElementById('descricaoCompleta_' + id);
    var botao = document.querySelector('button[data-id="' + id + '"]');

    if (descricaoResumida.style.display === 'none') {
        descricaoResumida.style.display = 'inline';
        descricaoCompleta.style.display = 'none';
        botao.innerText = 'Ver mais';
    } else {
        descricaoResumida.style.display = 'none';
        descricaoCompleta.style.display = 'inline';
        botao.innerText = 'Ver menos';
    }
}
