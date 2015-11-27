<?php

namespace Proyecto404\UtilBundle\Controller;

use Proyecto404\UtilBundle\Http\JsonResponse;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Base class with convenient utility methods for controllers.
 *
 * @author Nicolas Bottarini <nicolasbottarini@gmail.com>
 */
abstract class ControllerBase
{
    /**
     * @var ControllerUtil
     */
    private $controllerUtil;

    /**
     * Constructor.
     *
     * @param ControllerUtil $controllerUtil
     */
    public function __construct(ControllerUtil $controllerUtil)
    {
        $this->controllerUtil = $controllerUtil;
    }

    /**
     * Returns a NotFoundHttpException.
     *
     * This will result in a 404 response code. Usage example:
     * <code>
     *     throw $this->createNotFoundException('Page not found!');
     * </code>
     *
     * @param string          $message  An error message
     * @param \Exception|null $previous The previous exception
     *
     * @return NotFoundHttpException
     */
    protected function createNotFoundException($message = 'Not Found', \Exception $previous = null)
    {
        return new NotFoundHttpException($message, $previous);
    }

    /**
     * Returns an AccessDeniedException.
     *
     * This will result in a 403 response code. Usage example:
     * <code>
     *     throw $this->createAccessDeniedException('Unable to access this page!');
     * </code>
     *
     * @param string          $message  An error message
     * @param \Exception|null $previous The previous exception
     *
     * @return AccessDeniedException
     */
    protected function createAccessDeniedException($message = 'Access Denied', \Exception $previous = null)
    {
        return new AccessDeniedException($message, $previous);
    }

    /**
     * Returns a RedirectResponse to the given URL.
     *
     * @param string $url    The URL to redirect to
     * @param int    $status The status code to use for the Response
     *
     * @return RedirectResponse
     */
    protected function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * Redirects user to 404 page unless the request is an Ajax request.
     *
     * @param Request $request Http request.
     *
     * @throws NotFoundHttpException if the request is not an ajax request
     */
    protected function forward404UnlessIsAjaxRequest(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }
    }

    /**
     * Redirects user to 404 page if the condition is met.
     *
     * @param bool   $condition Condition
     * @param string $message   Message to return in the 404 error
     *
     * @throws NotFoundHttpException if the $condition is met
     */
    protected function forward404If($condition, $message = 'Not Found')
    {
        if ($condition) {
            throw $this->createNotFoundException($message);
        }
    }

    /**
     * Redirects user to 404 page unless the condition is met.
     *
     * @param mixed  $condition Condition
     * @param string $message   Message to return in the 404 error
     *
     * @throws NotFoundHttpException if the $condition is not met
     */
    protected function forward404Unless($condition, $message = 'Not Found')
    {
        if (!$condition) {
            throw $this->createNotFoundException($message);
        }
    }

    /**
     * Returns a json response with a json encoded array.
     *
     * @param array $result Array to encode to Json
     *
     * @return JsonResponse Json encoded response
     */
    protected function json(array $result = array())
    {
        return new JsonResponse($result);
    }

    /**
     * Returns a json response that contains an html view and a json encoded array.
     *
     * @param string $viewName       View name to render
     * @param array  $viewParameters View parameters
     * @param array  $result         Array to encode to Json
     *
     * @return JsonResponse Json encoded response
     */
    protected function jsonView($viewName, array $viewParameters = array(), array $result = array())
    {
        $html = $this->renderView($viewName, $viewParameters);
        $result = array_merge($result, array('html' => $html));

        return new JsonResponse($result);
    }

    /**
     * Returns an excel file to be downloaded.
     *
     * @param string $viewName       Excel html view name to render
     * @param string $filename       Downloaded file name
     * @param array  $viewParameters View parameters
     *
     * @return Response
     */
    protected function excelView($viewName, $filename, $viewParameters = array())
    {
        $response = $this->render($viewName, $viewParameters);
        $response->headers->set('Content-Type', 'application/vnd.ms-excel;charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename='.$filename);

        return $response;
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @param string         $route         The name of the route
     * @param mixed          $parameters    An array of parameters
     * @param Boolean|string $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
     *
     * @return string The generated URL
     *
     * @see UrlGeneratorInterface
     */
    protected function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->controllerUtil->generateUrl($route, $parameters, $referenceType);
    }

    /**
     * Forwards the request to another controller.
     *
     * @param Request $request    The current request
     * @param string  $controller The controller name (a string like BlogBundle:Post:index)
     * @param array   $path       An array of path parameters
     * @param array   $query      An array of query parameters
     *
     * @return Response A Response instance
     */
    protected function forward(Request $request, $controller, array $path = array(), array $query = array())
    {
        return $this->controllerUtil->forward($request, $controller, $path, $query);
    }

    /**
     * Returns a rendered view.
     *
     * @param string $view       The view name
     * @param array  $parameters An array of parameters to pass to the view
     *
     * @return string The rendered view
     */
    protected function renderView($view, array $parameters = array())
    {
        return $this->controllerUtil->renderView($view, $parameters);
    }

    /**
     * Renders a view.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A response instance
     *
     * @return Response A Response instance
     */
    protected function render($view, array $parameters = array(), Response $response = null)
    {
        return $this->controllerUtil->render($view, $parameters, $response);
    }

    /**
     * Streams a view.
     *
     * @param string           $view       The view name
     * @param array            $parameters An array of parameters to pass to the view
     * @param StreamedResponse $response   A response instance
     *
     * @return StreamedResponse A StreamedResponse instance
     */
    protected function stream($view, array $parameters = array(), StreamedResponse $response = null)
    {
        return $this->controllerUtil->stream($view, $parameters, $response);
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @param string|FormTypeInterface $type    The built type of the form
     * @param mixed                    $data    The initial data for the form
     * @param array                    $options Options for the form
     *
     * @return Form
     */
    protected function createForm($type, $data = null, array $options = array())
    {
        return $this->controllerUtil->createForm($type, $data, $options);
    }

    /**
     * Creates and returns a form builder instance.
     *
     * @param mixed $data    The initial data for the form
     * @param array $options Options for the form
     *
     * @return FormBuilder
     */
    protected function createFormBuilder($data = null, array $options = array())
    {
        return $this->controllerUtil->createFormBuilder($data, $options);
    }

    /**
     * Gets doctrine entity manager.
     *
     * @return \Doctrine\ORM\EntityManager Doctrine's entity manager
     */
    protected function getEntityManager()
    {
        return $this->controllerUtil->getEntityManager();
    }

    /**
     * Gets a doctrine's entity proxy for use without loading object from database.
     *
     * @param string $entityName Entity class name (e.g. MyNeeds:User)
     * @param string $id         Entity database identifier
     *
     * @return object Entity proxy
     */
    protected function getEntityReference($entityName, $id)
    {
        return $this->controllerUtil->getEntityReference($entityName, $id);
    }

    /**
     * Translates the given message.
     *
     * @param string $id         The message id (may also be an object that can be cast to string)
     * @param array  $parameters An array of parameters for the message
     * @param string $domain     The domain for the message
     * @param string $locale     The locale
     *
     * @return string The translated string
     */
    protected function trans($id, array $parameters = array(), $domain = 'messages', $locale = null)
    {
        return $this->controllerUtil->trans($id, $parameters, $domain, $locale);
    }

    /**
     * Translates the given choice message by choosing a translation according to a number.
     *
     * @param string $id         The message id (may also be an object that can be cast to string)
     * @param int    $number     The number to use to find the indice of the message
     * @param array  $parameters An array of parameters for the message
     * @param string $domain     The domain for the message
     * @param string $locale     The locale
     *
     * @return string The translated string
     */
    protected function transChoice($id, $number, array $parameters = array(), $domain = 'messages', $locale = null)
    {
        return $this->controllerUtil->transChoice($id, $number, $parameters, $domain, $locale);
    }

    /**
     * Get a user from the TokenStorage.
     *
     * @return mixed
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see Symfony\Component\Security\Core\Authentication\Token\TokenInterface::getUser()
     */
    protected function getUser()
    {
        return $this->controllerUtil->getUser();
    }

    /**
     * Gets the validator component.
     *
     * @return ValidatorInterface
     */
    protected function getValidator()
    {
        return $this->controllerUtil->getValidator();
    }

    /**
     * Gets the event dispatcher component.
     *
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher()
    {
        return $this->controllerUtil->getEventDispatcher();
    }

    /**
     * Dispatchs an event in the event dispatcher.
     *
     * @param string     $eventName The name of the event
     * @param Event|null $event     The event object to be dispatched
     */
    protected function dispatchEvent($eventName, Event $event = null)
    {
        $this->controllerUtil->getEventDispatcher()->dispatch($eventName, $event);
    }

    /**
     * Gets the controllerUtil object.
     *
     * @return ControllerUtil
     */
    protected function getControllerUtil()
    {
        return $this->controllerUtil;
    }

    /**
     * Gets the token storage component.
     *
     * @return TokenStorageInterface
     */
    protected function getTokenStorage()
    {
        return $this->controllerUtil->getTokenStorage();
    }

    /**
     * Gets the Symfony kernel root dir.
     *
     * @return string
     */
    protected function getKernelRootDir()
    {
        return $this->controllerUtil->getKernelRootDir();
    }

    /**
     * Gets the project uploads path.
     *
     * @return string
     */
    protected function getUploadsPath()
    {
        return realpath($this->getKernelRootDir().'/../web/uploads/');
    }

    /**
     * Checks if the attributes are granted against the current authentication token and optionally supplied object.
     *
     * @param mixed $attributes
     * @param mixed $object
     *
     * @return bool
     */
    protected function isGranted($attributes, $object = null)
    {
        return $this->getControllerUtil()->getAuthorizationChecker()->isGranted($attributes, $object);
    }
}
