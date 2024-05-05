<?php

namespace UserManagement\Controller;

use Teacher\Examples\Enumerations\DaysOfWeekEnum;
use Teacher\GivenCode\Abstracts\AbstractController;
use Teacher\GivenCode\Exceptions\RequestException;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use UserManagement\Service\UserService;

/**
 * User Controller
 */
class UserController extends AbstractController {
    private UserService $userService;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->userService = new UserService();
    }
    
    /**
     * Gets a user
     *
     * @throws RequestException
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function get() : void {
        if (empty($_REQUEST['userId'])) {
            throw new RequestException('Bad request: required parameter [userId] not found in the request.', 400);
        }
        
        if (!is_numeric($_REQUEST['userId'])) {
            throw new RequestException('Bad request: parameter [userId] value [' . $_REQUEST['userId'] .
                                       '] is not numeric.', 400);
        }
        $userId = (int) $_REQUEST['userId'];
        $instance = $this->userService->getById($userId);
        
        header('Content-Type: application/json;charset=UTF-8');
        
        echo $instance->toJson();
    }
    
    /**
     * Creates a user
     *
     * @return void
     * @throws RequestException
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function post() : void {
        if (empty($_REQUEST['username'])) {
            throw new RequestException('Bad request: required parameter [username] not found in the request.', 400);
        }
        if (empty($_REQUEST['email'])) {
            throw new RequestException('Bad request: required parameter [email] not found in the request.', 400);
        }
        if (empty($_REQUEST['password'])) {
            throw new RequestException('Bad request: required parameter [password] not found in the request.', 400);
        }
        $username = $_REQUEST['username'];
        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];
        
        // NOTE: no need for validation of the string lengths here, as that is done by the setter methods of the
        // ExampleDTO class used when creating an ExampleDTO instance in the creation method of ExampleService.
        
        $instance = $this->userService->create($username, $email, $password);
        header('Content-Type: application/json;charset=UTF-8');
        echo $instance->toJson();
    }
    
    /**
     * Updates a user
     *
     * @throws RequestException
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function put() : void {
        /*
         * This example PUT request handler is designed to update an example entity record in the database
         * and return it to the client for handling client-side.
         *
         * NOTE: PHP does not always parse PUT and DELETE requests. It must be done manually by reading
         * the PHP://input data stream.
         *
         * It expects the required data attributes for an example entity as well as the ID as JSON request data  and
         * returns the updated record data also as JSON.
         */
        
        // As stated, we need to manually parse the input content of PUT and DELETE requests.
        // For this PUT update example, that is application/json content so we use json_decode()
        $request_contents = file_get_contents('php://input');
        try {
            $_REQUEST = json_decode($request_contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $json_excep) {
            throw new RequestException('Invalid request contents format. Valid JSON is required.', 400, [], 400,
                                       $json_excep);
        }
        
        
        if (empty($_REQUEST['id'])) {
            throw new RequestException('Bad request: required parameter [id] not found in the request.', 400);
        }
        if (!is_numeric($_REQUEST['id'])) {
            throw new RequestException('Bad request: parameter [id] value [' . $_REQUEST['id'] .
                                       '] is not numeric.', 400);
        }
        if (empty($_REQUEST['email'])) {
            throw new RequestException('Bad request: required parameter [email] not found in the request.', 400);
        }
        
        $user_id = (int) $_REQUEST['id'];
        
        $instance = $this->userService->update($user_id, $_REQUEST['email']);
        header('Content-Type: application/json;charset=UTF-8');
        echo $instance->toJson();
    }
    
    /**
     * Deletes a user
     *
     * @return void
     * @throws RequestException
     * @throws RuntimeException
     */
    public function delete() : void {
        /*
         * This example DELETE request handler is designed to delete an example entity record in the database..
         * NOTE: PHP does not always parse PUT and DELETE requests. It must be done manually by reading
         * the PHP://input data stream.
         *
         * It expects the ID of an example entity as urlencoded request data and returns nothing in the response.
         */
        
        // As stated, we need to manually parse the input content of PUT and DELETE requests.
        // For this DELETE deletion example, that is application/x-www-form-urlencoded content.
        // We need to use parse_str() function to decode urlencoded string data instead of the json_decode() used
        // for JSON data.
        $request_contents = file_get_contents('php://input');
        parse_str($request_contents, $_REQUEST);
        
        
        if (empty($_REQUEST['id'])) {
            throw new RequestException('Bad request: required parameter [id] not found in the request.', 400);
        }
        if (!is_numeric($_REQUEST['id'])) {
            throw new RequestException('Bad request: parameter [id] value [' . $_REQUEST['id'] .
                                       '] is not numeric.', 400);
        }
        $user_id = (int) $_REQUEST['id'];
        $this->userService->delete($user_id);
        header('Content-Type: application/json;charset=UTF-8');
        http_response_code(204);
    }
}
