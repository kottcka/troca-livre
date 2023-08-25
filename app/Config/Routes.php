<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->get('/', 'Home::index', ['as' => 'home']);

// Rotas que NÃO devem estar autenticadas

    $routes->get('login', 'Login::index', ['as' => 'login']);
    $routes->post('login', 'Login::store', ['as' => 'login.store', 'filter' => 'csrfThrottleAjax']);
    $routes->get('register', 'Register::index', ['as' => 'register']);
    $routes->post('register/store', 'Register::store', ['as' => 'register.store', 'filter' => 'csrfThrottleAjax']);
    $routes->get('forgot/password', 'Forgot::index', ['as' => 'forgot']);
    $routes->post('forgot', 'Forgot::store', ['as' => 'forgot.store', 'filter' => 'csrfThrottleAjax']);
    $routes->get('reset/(:alphanum)', 'Forgot::edit/$1', ['as' => 'forgot.edit']);
    $routes->post('forgot/update/(:alphanum)', 'Forgot::update/$1', ['as' => 'forgot.update', 'filter' => 'csrfThrottleAjax']);
    $routes->get('visualizar-itens', 'ItemController::listAllItems', ['as' => 'items.listAllItems']);
    $routes->get('item/(:num)', 'ItemController::show/$1', ['as' => 'items.show']);


// Rotas que devem estar autenticadas

$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('/inicio', 'HomeLogado::index', ['as' => 'HomeLogado']);
    $routes->get('/deletar-perfil', 'Register::deleteUser', ['as' => 'register.deleteUser']);
    $routes->get('items/trocas', 'ItemController::listTrocas', ['as' => 'items.trocas']);
    $routes->get('items/doacoes', 'ItemController::listDoacoes', ['as' => 'items.doacoes']);
    $routes->get('cadastrar-item', 'ItemController::create', ['as' => 'items.create']);
    $routes->post('items/store', 'ItemController::store', ['as' => 'items.store', 'filter' => 'csrfThrottle']);
    $routes->get('items/edit/(:num)', 'ItemController::edit/$1', ['as' => 'items.edit']);
    $routes->post('items/update/(:num)', 'ItemController::update/$1', ['as' => 'items.update', 'filter' => 'csrfThrottle']);
    $routes->post('items/delete', 'ItemController::delete', ['as' => 'items.delete.post', 'filter' => 'csrfThrottle']);
    $routes->get('items', 'ItemController::index', ['as' => 'items.index']);
    $routes->get('lista-trocas', 'ItemController::listTrocas', ['as' => 'lista.trocas']);
    $routes->get('lista-doacoes', 'ItemController::listDoacoes', ['as' => 'lista.doacoes']);
    $routes->get('solicitacoes/enviadas', 'SolicitacaoController::solicitacoesEnviadas', ['as' => 'solicitacoes.enviadas']);
    $routes->get('solicitacoes/recebidas', 'SolicitacaoController::solicitacoesRecebidas', ['as' => 'solicitacoes.recebidas']);
    $routes->get('solicitacao/confirmarDadosTroca/(:num)', 'SolicitacaoController::confirmarDadosTroca/$1', ['as' => 'solicitacoes.confirmarDadosTroca']);
    $routes->get('solicitacao/confirmarDadosDoacao/(:num)', 'SolicitacaoController::confirmarDadosDoacao/$1', ['as' => 'solicitacoes.confirmarDadosDoacao']);
    $routes->post('solicitacao/solicitarTroca/(:num)', 'SolicitacaoController::solicitarTroca/$1', ['as' => 'solicitacao.solicitarTroca', 'filter' => 'csrfThrottle']);
    $routes->post('solicitacao/solicitarDoacao/(:num)', 'SolicitacaoController::solicitarDoacao/$1', ['as' => 'solicitacao.solicitarDoacao', 'filter' => 'csrfThrottle']);
    $routes->post('solicitacao/selecionarItemTroca/(:num)', 'SolicitacaoController::selecionarItemTroca/$1', ['as' => 'solicitacao.selecionarItemTroca', 'filter' => 'csrfThrottle']);
    $routes->post('solicitacao/processarTroca/(:num)', 'SolicitacaoController::processarTroca/$1', ['as' => 'solicitacao.processarTroca', 'filter' => 'csrfThrottle']);
    $routes->post('solicitacao/trocarItem', 'SolicitacaoController::trocarItem', ['as' => 'solicitacao.trocarItem', 'filter' => 'csrfThrottle']);
    $routes->post('solicitacoes/aceitar/(:num)', 'SolicitacaoController::aceitarSolicitacao/$1', ['as' => 'solicitacoes.aceitar', 'filter' => 'csrfThrottle']);
    $routes->get('solicitacoes/finalizadas', 'SolicitacaoController::solicitacoesFinalizadas', ['as' => 'solicitacoes.finalizadas']);
    $routes->get('perfil', 'Register::edit', ['as' => 'user.edit']);
    $routes->post('perfil', 'Register::update', ['as' => 'user.update', 'filter' => 'csrfThrottle']);
    $routes->post('solicitacao/processaAvaliacao', 'SolicitacaoController::processaAvaliacao', ['as' => 'solicitacao.avaliacao']);
    $routes->get('solicitacoes-finalizadas', 'SolicitacaoController::solicitacoesFinalizadas', ['filter' => 'csrfThrottle']);
    $routes->post('item/postPerguntaResposta', 'ItemController::postPerguntaResposta', ['as' => 'item.postPerguntaResposta', 'filter' => 'csrfThrottle']);
    $routes->get('/items/search', 'ItemController::search', ['filter' => 'csrfThrottle']);
    $routes->post('solicitacao/processarDoacao/(:num)', 'SolicitacaoController::processarDoacao/$1', ['as' => 'solicitacao.processarDoacao', 'filter' => 'csrfThrottle']);
    $routes->post('solicitacoes/negar/(:num)', 'SolicitacaoController::negarSolicitacao/$1', ['as' => 'solicitacoes.negar', 'filter' => 'csrfThrottle']);
});

$routes->get('logout', 'Login::destroy', ['as' => 'login.destroy', 'filter' => 'auth']);















/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
