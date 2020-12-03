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

    <a class="list-group-item text-center" data-toggle="popover" data-trigger="hover" data-content="Gestão de Gente" href="gestaoGenteDashboard.php"><i class="fa fa-chart-pie"></i></a>

 <?php } else { ?>

    <a class="list-group-item text-center" data-toggle="popover" data-trigger="hover" data-content="Dashboard" href="home.php"><i class="fa fa-chart-bar"></i></a>
    <a class="list-group-item text-center" data-toggle="popover" data-trigger="hover" data-content="Candidatos" href="listarCandidatos.php"><i class="fa fa-user"></i></a>
    <a class="list-group-item text-center" data-toggle="popover" data-trigger="hover" data-content="Trabalhe Conosco" href="listarTrabalheConosco.php"><i class="fa fa-briefcase"></i></a>
    <a class="list-group-item text-center" data-toggle="popover" data-trigger="hover" data-content="Processo Seletivo" href="listarProcesso.php"><i class="fa fa-book"></i></a>
    <a class="list-group-item text-center" data-toggle="popover" data-trigger="hover" data-content="Contratação" href="listarContratacao.php"><i class="fa fa-clipboard"></i></a>

 <?php } ?>

    <a class="list-group-item text-center" data-toggle="popover" data-trigger="hover" data-content="Usuários" href="usuarios.php">
        <i class="fa fa-cog"></i>
    </a>
    <a class="list-group-item text-center" data-toggle="popover" data-trigger="hover" data-content="Meus Dados" href="javascript:;" onclick="javascript:$('#meusDadosModal').modal();"><i class="fa fa-id-card"></i></a>
    <a class="list-group-item text-center" data-toggle="popover" data-trigger="hover" data-content="Sair" href="javascript:;" onclick="javascript:$('#modalSair').modal();"><i class="fa fa-sign-out-alt"></i></a>

</div>

<?php
include_once 'meusDadosModal.php';
include_once 'menu_modals.php';
?>
