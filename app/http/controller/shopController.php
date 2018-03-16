<?php
/**
 * Pagina home do site
 */
namespace app\http\controller;
class shopController extends controller
{
    function home(){
        $this->view->load('home.php');
    }
}

 ?>
