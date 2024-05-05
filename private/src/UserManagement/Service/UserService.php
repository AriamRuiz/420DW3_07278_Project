<?php

namespace UserManagement\Service;

use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Teacher\GivenCode\Services\DBConnectionService;
use UserManagement\DAO\UserDAO;
use UserManagement\DTO\UserDTO;

/**
 * User Service
 */
class UserService implements IService {
    private UserDAO $userDAO;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->userDAO = new UserDAO();
    }
    
    /**
     * Gets all users
     *
     * @return UserDTO[]
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getAll() : array {
        return $this->userDAO->getAll();
    }
    
    /**
     * Get user by id
     *
     * @param int $id
     * @return UserDTO|null
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getById(int $id) : ?UserDTO {
        return $this->userDAO->getById($id);
    }
    
    /**
     * Get user by username
     *
     * @param string $username
     * @return UserDTO|null
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getByUsername(string $username) : ?UserDTO {
        return $this->userDAO->getByUsername($username);
    }
    
    /**
     * Creates a new user
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @return UserDTO
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function create(string $username, string $email, string $password) : UserDTO {
        $instance = new UserDTO();
        $instance->setUsername($username);
        $instance->setEmail($email);
        
        // Use password hash to ensure password is securely stored
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $instance->setPassword($password_hash);
        
        return $this->userDAO->create($instance);
    }
    
    /**
     * Updates a user
     *
     * @param int    $id
     * @param string $email
     * @return UserDTO
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function update(int $id, string $email) : UserDTO {
        $connection = DBConnectionService::getConnection();
        $connection->beginTransaction();
        $instance = $this->userDAO->getById($id);
        
        if ($instance === null) {
            throw new RuntimeException(sprintf('The user with id %d was not found in DB', $id));
        }
        
        $update_dto = new UserDTO();
        $update_dto->setId($id);
        $update_dto->setEmail($email);
        $instance = $this->userDAO->update($instance);
        $connection->commit();
        
        return $instance;
    }
    
    /**
     * Deletes a user
     *
     * @param int $id
     * @return void
     * @throws RuntimeException
     * @author Marc-Eric Boury
     * @since  2024-03-28
     */
    public function delete(int $id) : void {
        $this->userDAO->deleteById($id);
    }
}