
<!-- Modal Confirmacao Sair -->
<div id="modalSair" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <div class="modal-header alert-info">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
        </button>
        <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-power-off"></i> 	Sair com Seguran&ccedil;a</h4>
      </div>
      <div class="modal-body">
        <p id="textoLogoff">Deseja realmente sair do sistema?</p>
        <p align="center" class="text-danger" id="carregandoLogoff" style="display: none;"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-danger" onClick="javascript: $('#textoLogoff').hide(); $('#carregandoLogoff').show(); window.location.href='logoff.php';">
          <i class="fa fa-check"></i> Sair</button>
          <button type="button" class="btn btn-default" onClick="javascript:$('#textoLogoff').show(); $('#carregandoLogoff').hide();" data-dismiss="modal">
          <i class="fa fa-times"></i> Voltar</button>
      </div>

    </div>
  </div>
</div>
<!-- Fim Modal Confirmacao Sair -->


<!-- Modal Contratacao -->
<div id="modalContratacao" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <div class="modal-header alert-info">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
        </button>
        <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-clipboard"></i>  Contrata&ccedil;&atilde;o</h4>
      </div>
      <div class="modal-body">
        <p id="solContrP" align="center">
          <button type="button" class="btn btn-primary" onClick="javascript:window.location.href='listarContratacao.php'; $('#solContrP').hide(); $('#carregandoSolContrP').show();">
          <i class="fa fa-search"></i> Buscar</button>
          <button type="button" class="btn btn-primary" onClick="javascript:window.location.href='contratacaoCad.php'; $('#solContrP').hide(); $('#carregandoSolContrP').show();">
          <i class="fa fa-plus-circle"></i> Solicitar</button>

        </p>
        <p align="center" class="text-primary" id="carregandoSolContrP" style="display: none;"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
      </div>
      <!--<div class="modal-footer">

      </div>-->

    </div>
  </div>
</div>
<!-- Fim Modal Contratacao -->


<!-- Modal Candidatos -->
<div id="modalCandidatos" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <div class="modal-header alert-info">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
        </button>
        <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-user"></i>  Candidatos</h4>
      </div>
      <div class="modal-body">
        <p id="textoCandidatoP" align="center">
          <button  type="button" class="btn btn-primary" onClick="javascript:window.location.href='listarCandidatos.php'; $('#textoCandidatoP').hide(); $('#carregandoTextoCandidatoP').show();">
          <i class="fa fa-search"></i> Buscar</button>
          <button type="button" class="btn btn-primary" onclick="javascript:$('#divCargoMenuCan').show(500)">
          <i class="fa fa-plus-circle"></i> Cadastrar</button>

          <div class="form-group" id="divCargoMenuCan" style="display: none; margin-top: 20px;">
            <select id="cargoSelMenuCan" class="form-control" style="border: 1px solid #999;">
              <option value="">*Especifique o Cargo</option>
              <option value="">-------------------------------------</option>
              <?php $qPsm=  mysqli_query($bd, "SELECT * FROM tb_cargo where status = 'A' order by cargo");
                  while ($rsPcm = mysqli_fetch_array($qPsm)){ ?>
              <option value="<?=$rsPcm['id']?>" onclick="javascript:$('#textoCandidatoP').hide(); $('#carregandoTextoCandidatoP').show();"><?=$rsPcm['cargo']?></option>
              <?php } ?>
            </select>
          </div>

        </p>
        <p align="center" class="text-primary" id="carregandoTextoCandidatoP" style="display: none;"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
      </div>
      <!--<div class="modal-footer">

      </div>-->

    </div>
  </div>
</div>
<!-- Fim Modal Candidatos -->

<!-- Modal Processo Seletivo -->
<div id="modalProcessoSeletivo" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <div class="modal-header alert-info">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
        </button>
        <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-book"></i> Processo Seletivo</h4>
      </div>
      <div class="modal-body">
        <p id="textoProcessoSeletP" align="center">
          <button type="button" class="btn btn-primary" onClick="javascript:window.location.href='listarProcesso.php'; $('#textoProcessoSeletP').hide(); $('#carregandoTextoProcessoSeletP').show();">
          <i class="fa fa-search"></i> Buscar</button>
          <button type="button" class="btn btn-primary" onclick="javascript:$('#divCargoMenu').show(500)">
          <i class="fa fa-plus-circle"></i> Cadastrar</button>

          <div class="form-group" id="divCargoMenu" style="display: none; margin-top: 20px;">
            <select id="cargoSelMenu" class="form-control" style="border: 1px solid #999;">
              <option value="">*Especifique o Cargo</option>
              <option value="">-------------------------------------</option>
              <?php $qPsm=  mysqli_query($bd, "SELECT * FROM tb_cargo where status = 'A' order by cargo");
                  while ($rsPcm = mysqli_fetch_array($qPsm)){ ?>
              <option value="<?=$rsPcm['id']?>" onclick="javascript:$('#textoProcessoSeletP').hide(); $('#carregandoTextoProcessoSeletP').show();"><?=$rsPcm['cargo']?></option>
              <?php } ?>
            </select>
          </div>

        </p>
        <p align="center" class="text-primary" id="carregandoTextoProcessoSeletP" style="display: none;"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
      </div>
      <!--<div class="modal-footer">

      </div>-->

    </div>
  </div>
</div>
<!-- Fim Modal Processo Seletivo -->
