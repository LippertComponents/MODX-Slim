<?php
/**
 * Created by PhpStorm.
 * User: jgulledge
 * Date: 9/11/2017
 * Time: 12:32 PM
 */

namespace LCI\ModxSlim;

use \LCI\ModxSlim\Helpers\ResponseHelper;
use \Psr\Container\ContainerInterface;
use \Slim\Http\Request;
use \Slim\Http\Response;
use \Psr\Container\NotFoundExceptionInterface;
use \Psr\Container\ContainerExceptionInterface;


class Users
{
    use ResponseHelper;

    /** @var ContainerInterface ~ Slim\Container  */
    protected $container;

    /** @var \modX  */
    protected $modx;

    /** @var array  */
    protected $config = [];

    protected $context = 'web';

    protected $additional_contexts = [];

    /** @var array  */
    protected $data = [];

    /**
     * Users constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;

        try {
            $this->modx = &$this->container->get('modx');

        } catch (NotFoundExceptionInterface $exception) {
            // @TODO return with Error!!!!!

        } catch (ContainerExceptionInterface $exception) {
            // @TODO return with Error!!!!!

        } catch (\Exception $exception) {
            // @TODO return with Error!!!!!
        }

        try {
            $this->config = $this->container->get('config');
            if (isset($this->config['context'])) {
                $this->context = $this->config['context'];
            }
            if (isset($this->config['additional_contexts'])) {
                $this->additional_contexts = $this->config['additional_contexts'];
            }

        } catch (NotFoundExceptionInterface $exception) {
            $this->config = [];

        } catch (ContainerExceptionInterface $exception) {
            $this->config = [];

        } catch (\Exception $exception) {
            $this->config = [];
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function signIn(Request $request, Response $response, array $args)
    {
        // prefer email as username
        $username = empty($request->getParam('email')) ? $request->getParam('username') : $request->getParam('email');
        $password = $request->getParam('password');//, '---');
        $remember_me = $request->getParam('remember', true);

        $return_url = '';
        if (isset($this->config['return_url'])) {
            $return_url = $this->config['return_url'];
        }

        /* send to login processor and handle response */
        $properties = array(
            'login_context' => $this->context,
            'add_contexts'  => $this->additional_contexts,
            'username'      => $username,
            'password'      => $password,
            'returnUrl'     => $return_url,
            'rememberme'    => $remember_me,
        );

        /** @var \modProcessorResponse $result */
        $result = $this->modx->runProcessor('security/login', $properties);

        $this->setDataFromModResponse($result, [], false, 'You have been signed in.');

        return $this->makeJsonResponse($request, $response, $this->data);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function signOut(Request $request, Response $response, array $args)
    {
        /* send to login processor and handle response */
        $properties = array(
            'login_context' => $this->context,
            'add_contexts'  => $this->additional_contexts
        );

        /** @var \modProcessorResponse $result */
        $result = $this->modx->runProcessor('security/logout', $properties);

        $this->setDataFromModResponse($result);

        return $this->makeJsonResponse($request, $response, $this->data);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getProfile(Request $request, Response $response, array $args)
    {
        $this->context = $this->modx->getOption('context', null, $this->modx->context->get('key') );

        $data = [];
        /* check if user logged in */
        $is_logged_in = $this->modx->user->hasSessionContext($this->context);
        if ($is_logged_in) {
            /** @var \modProfile $profile */
            $profile = $this->modx->user->getOne('Profile');
            $data = $profile->toArray();

            unset($data['sessionid']);
            unset($data['extended']);
            unset($data['password']);
            unset($data['token']);
            unset($data['key']);
            unset($data['id']);

            /** @param array $fields get the extended fields */
            $data['extended'] = $profile->get('extended');
        }

        $this->data = [
            'success' => $is_logged_in,
            'message' => '',
            'errors' => '',
            'data' => $data,
            'object' => ''
        ];

        return $this->makeJsonResponse($request, $response, $this->data);
    }


    /**
     * - if possible refactor Login Extra snippets/controllers to do User management
     */
    public function register()
    {
        // @TODO
    }

    public function confirmRegister()
    {
        // @TODO
    }

    public function changePassword()
    {
        // @TODO
    }

    public function resetPassword()
    {
        // @TODO
    }

    public function updateProfile()
    {
        // @TODO
    }

}
