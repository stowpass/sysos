<div class="modal fade" id="meusDadosModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header alert-info">
                <h4 class="modal-title">
                    <i class="fa fa-id-card"></i> Suas informações
                </h4>
            </div>

            <div class="modal-body">
                <div class="alert alert-success" style="display: none">
                    <ul></ul>
                </div>

                <div class="form-group">
                    <label>Equipe/Setor</label>
                    <input type="text" class="form-control" disabled="disabled" value="<?=$_SESSION['setor']?>">
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nome</label>
                        <input type="text" class="form-control" disabled="disabled" value="<?=$_SESSION['nome']?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label>E-mail</label>
                        <input type="text" class="form-control" disabled="disabled" value="<?=$_SESSION['email']?>">
                    </div>
                </div>

                <div class="row">
                    <form id="atualizarSenha">
                        <div class="form-group col-md-6">
                            <label>Nova senha</label>
                            <input type="password" class="form-control" name="senha">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Repetir nova senha</label>
                            <input type="password" class="form-control" name="senha_conf">
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" form="atualizarSenha" type="submit">Salvar</button>
                <button class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
