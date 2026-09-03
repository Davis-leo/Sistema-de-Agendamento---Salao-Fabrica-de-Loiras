<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    protected $helpers = ['form', 'general', 'html'];
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }


    /**
     * Valida se a requisição é realmente do tipo informado: post/put/delete/ajax
     * @param string $method
     * @throws PageNotFoundException
     * @return boolean
     */
    protected function checkMethod(string $method): bool
    {

        $method = strtolower($method);

        if (!$this->request->is($method)) {

            throw new PageNotFoundException("Página não encontrada");
        }

        return true;
    }


    /**
     * Remove do post a posição '_method' do spoofing, pois estamos trabalhando com roteamento RESTful.
     * @return array
     */
    protected function clearRequest(): array {

        $data = $this->request->getPost();

        unset($data['_method']);

        return $data;
    }
}
