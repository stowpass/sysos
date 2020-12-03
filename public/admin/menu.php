<style type="text/css">
  a.list-group-item{
    color:#30a5ff;
    text-decoration: none;
  }
  a.list-group-item:hover{
    color:#999;
    text-decoration: none;
  }
</style>

<hr/>

<div class="list-group panel panel-primary">
 <?php if ($_SESSION["id"] == 5 || $_SESSION["id"] == 7 || $_SESSION["id"] == 9 || $_SESSION["id"] == 10 || $_SESSION["id"] == 11){ ?>

    <a class="list-group-item" href="gestaoGenteDashboard.php"><i class="fa fa-chart-pie"></i> Gestão de Gente</a>

 <?php } else { ?>

    <a class="list-group-item" href="home.php"><i class="fa fa-chart-bar"></i> Dashboard</a>
    <a class="list-group-item" href="listarCandidatos.php"><i class="fa fa-user"></i> Candidatos</a>
    <a class="list-group-item" href="listarTrabalheConosco.php"><i class="fa fa-briefcase"></i> Trabalhe Conosco</a>
    <a class="list-group-item" href="listarProcesso.php"><i class="fa fa-book"></i> Processo Seletivo</a>
    <a class="list-group-item" href="listarContratacao.php"><i class="fa fa-clipboard"></i> Contrata&ccedil;&atilde;o</a>

 <?php } ?>

    <a class="list-group-item" href="usuarios.php">
        <i class="fa fa-cog"></i> Usuários
    </a>
    <a class="list-group-item" href="javascript:;" onclick="javascript:$('#meusDadosModal').modal();"><i class="fa fa-id-card"></i> Meus Dados</a>
    <a class="list-group-item" href="javascript:;" onclick="javascript:$('#modalSair').modal();"><i class="fa fa-sign-out-alt"></i> Sair</a>

</div>

<?php
include_once 'meusDadosModal.php';
include_once 'menu_modals.php';
?>
