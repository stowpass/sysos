<script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="../js/jquery.mask.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/fontawesome-all.min.js"></script>
<script type="text/javascript" src="../js/util.js"></script>

<script type="text/javascript">
    $(function () {
        $('[data-toggle=popover]').popover()

        $('#meusDadosModal form').submit(validarAtualizarDados)
        $('#meusDadosModal form').on('reset', limparAtualizarDados)
    })

    function validarAtualizarDados () {
        event.preventDefault()

        const $alert = $('.modal-body .alert')

        this.$alert = $alert

        $alert.children()
            .first()
            .fadeOut(function () {
                this.innerHTML = ''
            })

        const senha = this.senha.value
        const senha_conf = this.senha_conf.value
        const erros = []

        if (!senha) {
            erros.push('<li>Informe a nova <strong>senha</strong>.</li>')
            this.senha.style.border = '1px solid red'
        } else {
            this.senha.style.border = ''
        }

        if (!senha_conf) {
            erros.push('<li>Repita a nova <strong>senha</strong> informada.</li>')
            this.senha_conf.style.border = '1px solid red'
        } else if (senha !== senha_conf) {
            erros.push('<li>As senhas informadas não são iguais.</li>')
            this.senha_conf.style.border = '1px solid red'
        } else {
            this.senha_conf.style.border = ''
        }

        if (erros.length) {
            $alert.removeClass('alert-success')

            if (!$alert.is('.alert-danger'))
                $alert.addClass('alert-danger')

            $alert.children()
                .first()
                .fadeOut(function () {
                    erros.forEach((e) => {
                        $(this).append(e)
                    })
                }).fadeIn()

            $alert.show(500)
        } else {
            $alert.hide(300)
            const e = atualizarDados.bind(this)
            e()
        }
    }

    function atualizarDados () {
        const $footer = $('#meusDadosModal .modal-footer')
        const button = $footer.find('button').attr('disabled', true).get(0)
        const senha = this.senha.value
        const senha_conf = this.senha_conf.value

        ativarSpinner(button)

        $.post('meusDadosAjax.php', { senha, senha_conf })
            .done((res) => {
                this.$alert.removeClass('alert-danger')

                if (!this.$alert.is('.alert-success'))
                    this.$alert.addClass('alert-success')


                this.$alert.children()
                    .fadeOut(function () {
                        this.innerHTML = `<li>${res.mensagem}</li>`
                    })
                    .fadeIn()

                this.$alert.show(500)

                this.reset()
            })
            .fail(({ responseJSON }) => {
                this.$alert.removeClass('alert-success')

                if (!this.$alert.is('.alert-danger'))
                    this.$alert.addClass('alert-danger')

                const mensagem = responseJSON.erro || responseJSON.mensagem

                this.$alert.fadeOut(function () {
                    this.innerHTML = mensagem
                }).fadeIn()

                this.$alert.show(500)
            })
            .always(() => {
                desativarSpinner(button, null, 'Sim')
                $footer.find('button').attr('disabled', false)
            })
    }

    function limparAtualizarDados () {
        for (const el of this.elements) {
            this.style.border = ''
        }
    }
</script>
