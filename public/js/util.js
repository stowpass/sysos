/**
 * Impede que uma chamada seja executada várias vezes
 * seguidas determinando um tempo para que seja chamada
 * de fato a função.
 * Veja 'Efeito bouncing'.
 * @param {Function} callback Função à ser chamada quando o tempo for atingido
 * @param {number} timeout Tempo em milissegundos para chamar uma função (padrão: 200)
 * @return {Function} Constroi uma nova instância debounce.
 */
function debounce (callback, timeout = 200) {
    let timer

    return function () {
        const ctx = this
        const args = arguments

        clearTimeout(timer)

        timer = setTimeout(function () {
            callback.apply(ctx, args)
        }, timeout)
    }
}

function getButtonSource (target) {
    let el = target
    while (!el.tagName && el.tagName !== 'BUTTON') {
        if (el.parentElement)
            el = el.parentElement
        else
            throw new Error('Falha ao obter o botão de origem.')
    }
    return el
}

function ativarSpinner (target) {
    const button = getButtonSource(target)
    button.disabled = true
    button.innerHTML = '<i class="fa fa-spinner fa-pulse"></i>'
    return button
}

function desativarSpinner (target, icon = 'info-circle', legenda = null) {
    const button = getButtonSource(target)
    button.disabled = false
    if (icon)
        button.innerHTML = `<i class="fa fa-${icon}"></i>${legenda ? ' ' + legenda : ''}`
    else
        button.innerHTML = `${legenda ? ' ' + legenda : ''}`
}

/**
 * Dispara um ping para o servidor manter a sessão do usuário ativa.
 */
function manterVivo() {
    $.get('atividadeAjax.php')
        .done(function () {
            console.log('[atividade] estou vivo')
        })
        .fail(function() {
            alert('Ops, parece que você passou muito tempo nessa página e foi desconectado.')
            window.location.reload()
        })
}
