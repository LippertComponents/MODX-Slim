<?php
/**
 * Created by PhpStorm.
 * User: jgulledge
 * Date: 1/26/2018
 * Time: 9:07 AM
 */

namespace LCI\ModxSlim\Helpers;

use \Slim\Http\Request;
use \Slim\Http\Response;


trait ResponseHelper
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array|string $data
     *
     * @return Response|static
     */
    protected function makeJsonResponse(Request $request, Response $response, $data)
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $callback = $request->getParam('callback', false);

        if ( !empty($callback) ) {
            $output =  $callback . '('.json_encode($data).')';

        } else {
            $output = json_encode($data);
        }
        $response->getBody()->write($output);
        return $response;
    }

    /**
     * @param Response $response
     * @param $message
     *
     * @return Response|static
     */
    protected function setResponseError(Response $response, $message)
    {
        // @TODO status codes???
        $response = $response->withHeader('Content-type', 'application/json');
        $response->getBody()->write(json_encode(
            [
                'error' => true,
                'message' => $message,
                'success' => false
            ]
        ));

        return $response;
    }

    /**
     * @param \modProcessorResponse $modProcessorResponse
     * @param array $data
     * @param bool $send_object
     * @param string $message
     *
     * @return $this
     */
    protected function setDataFromModResponse(\modProcessorResponse $modProcessorResponse, $data=[], $send_object=false, $message='')
    {
        $errors = [];
        /** @var \modProcessorResponseError $error */
        foreach ($modProcessorResponse->getFieldErrors() as $error) {
            $errors[] = [
                'field' => $error->getField(),
                'message' => $error->getMessage(),
                // this is raw:
                'error' => $error->getError()
            ];
        }
        $object_array = [];
        if ($send_object && $modProcessorResponse->getObject()) {
            $object = $modProcessorResponse->getObject();
            if (method_exists($object, 'toArray')) {
                $object_array = $object->toArray();
            } else {
                // review
                $errors[] = [
                    'field' => '$modProcessorResponse->getObject()',
                    'message' => 'Object is not a valid xPDO object',
                    'error' => []
                ];
            }
        }
        $mod_message = $modProcessorResponse->getMessage();
        $this->data = [
            'success' => ($modProcessorResponse->isError() ? false : true),
            'message' => empty($mod_message) && !$modProcessorResponse->isError() ? $message : $mod_message,
            'errors' => $errors,
            'data' => $data,
            'object' => $object_array
        ];

        return $this;
    }
}
