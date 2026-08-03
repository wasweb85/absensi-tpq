<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
   /**
    * Instance of the main Request object.
    *
    * @var CLIRequest|IncomingRequest
    */
   protected $request;

   /**
    * An array of helpers to be loaded automatically upon
    * class instantiation. These helpers will be available
    * to all other controllers that extend BaseController.
    *
    * @var array
    */
   protected $helpers = [];

   /**
    * Be sure to declare properties for any property fetch you initialized.
    * The creation of dynamic property is deprecated in PHP 8.2.
    */

   protected $session;

   protected $generalSettings;

   /**
    * Constructor.
    */
   public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
   {
      // Do Not Edit This Line
      parent::initController($request, $response, $logger);

      // Preload any models, libraries, etc, here.

      $this->session = \Config\Services::session();
      $schoolConfigurations  = new \Config\School();
      $this->generalSettings = $schoolConfigurations::$generalSettings;


      // Passing global variable to views
      $view = \Config\Services::renderer();
      $view->setData([
         'generalSettings' => $this->generalSettings,
         // Inject layout selector globally so every view automatically uses the
         // shell-less ajax_layout on SPA/Fetch requests — no per-controller change needed.
         '_layout' => $this->isAjaxRequest() ? 'ajax' : 'full',
      ]);
   }

   /**
    * Detect if the current request was sent by the SPA navigation engine (spa-nav.js).
    * Checks for the standard XMLHttpRequest header injected by fetch().
    */
   protected function isAjaxRequest(): bool
   {
      return $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
   }

   /**
    * Render a view using the appropriate layout:
    * - Normal browser request  → full admin_page_layout (sidebar + navbar + footer)
    * - SPA/AJAX navigation     → ajax_layout (content + scripts only, no HTML shell)
    *
    * Usage in controller: return $this->viewAjax('admin/dashboard', $data);
    */
   protected function viewAjax(string $view, array $data = []): string
   {
      $data['_layout'] = $this->isAjaxRequest() ? 'ajax' : 'full';
      return view($view, $data);
   }
}
