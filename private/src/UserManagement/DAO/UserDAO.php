<?php

namespace UserManagement\DAO;

use PDO;
use Teacher\GivenCode\Abstracts\IDAO;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Teacher\GivenCode\Services\DBConnectionService;
use UserManagement\DTO\UserDTO;

/**
 * User DAO
 */
class UserDAO implements IDAO {
    // <editor-fold defaultstate="collapsed" desc="QUERY STRING CONSTANTS">
    
    private const GET_QUERY = "SELECT * FROM `" . UserDTO::TABLE_NAME . "` WHERE `id` = :id;";
    private const GET_QUERY_BY_USERNAME = "SELECT * FROM `" . UserDTO::TABLE_NAME . "` WHERE `username` = :username ;";
    private const CREATE_QUERY = "INSERT INTO `" . UserDTO::TABLE_NAME .
    "` (`" . UserDTO::COLUMN_NAME_USERNAME . "`, `" . UserDTO::COLUMN_NAME_EMAIL . "`, `" .
    UserDTO::COLUMN_NAME_PASSWORD . "`) VALUES (:username, :email, :password) ;";
    private const UPDATE_EMAIL_QUERY = "UPDATE `" . UserDTO::TABLE_NAME .
    "` SET `" . UserDTO::COLUMN_NAME_EMAIL . "` = :email WHERE `id` = :id ;";
    private const UPDATE_PASSWORD_QUERY = "UPDATE `" . UserDTO::TABLE_NAME .
    "` SET `" . UserDTO::COLUMN_NAME_PASSWORD . "` = :password WHERE `id` = :id ;";
    private const DELETE_QUERY = "DELETE FROM `" . UserDTO::TABLE_NAME . "` WHERE `id` = :id ;";
    
    // </editor-fold>
    
    /**
     * Constructor
     */
    public function __construct() {}
    
    /**
     * Gets all users
     *
     * @returns UserDTO[]
     * @throws ValidationException
     * @throws RuntimeException
     */
    public function getAll() : array {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare("SELECT * FROM " . UserDTO::TABLE_NAME);
        $statement->execute();
        $results_array = $statement->fetchAll(PDO::FETCH_ASSOC);
        $object_array = [];
        foreach ($results_array as $result) {
            $object_array[] = UserDTO::fromDbArray($result);
        }
        
        return $object_array;
    }
    
    /**
     * Specialized for {@see UserDTO} DTO objects.
     *
     * @param int $id               The identifier value of the {@see UserDTO} record to retrieve.
     *                              false.
     * @return UserDTO|null The created {@see UserDTO} instance or null if no record was found for the specified
     *                              id.
     *
     * @throws ValidationException If a {@see ValidationException} is thrown when creating the updated instance.
     * @throws RuntimeException If no record is found for the specified id.
     */
    public function getById(int $id) : ?UserDTO {
        $connection = DBConnectionService::getConnection();
        
        $statement = $connection->prepare(self::GET_QUERY);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();
        
        $array = $statement->fetch(PDO::FETCH_ASSOC);
        if (is_bool($array) && !$array) {
            // failed fetch
            throw new RuntimeException("No record found for id# [$id].");
        }
        return UserDTO::fromDbArray($array);
    }
    
    /**
     * Gets user by username.
     *
     * @param string $username      The username value of the {@see UserDTO} record to retrieve.
     * @return UserDTO|null The created {@see UserDTO} instance or null if no record was found for the specified
     *                              username.
     *
     * @throws ValidationException If a {@see ValidationException} is thrown when creating the updated instance.
     * @throws RuntimeException If no record is found for the specified username.
     */
    public function getByUsername(string $username) : ?UserDTO {
        $connection = DBConnectionService::getConnection();
        
        $statement = $connection->prepare(self::GET_QUERY_BY_USERNAME);
        $statement->bindValue(":username", $username, PDO::PARAM_INT);
        $statement->execute();
        
        $array = $statement->fetch(PDO::FETCH_ASSOC);
        if (is_bool($array) && !$array) {
            // failed fetch
            throw new RuntimeException("No record found for username# [$username].");
        }
        return UserDTO::fromDbArray($array);
    }
    
    /**
     * {@inheritDoc}
     * Specialized for {@see UserDTO} DTO objects.
     *
     * @param UserDTO $dto    The {@see UserDTO} instance to create the record of.
     * @return UserDTO The updated {@see UserDTO} instance.
     * @throws ValidationException If the <code>$dto</code> object parameter is not an {@see UserDTO} instance or if
     *                        a {@see ValidationException} is thrown when creating the updated instance.
     * @throws RuntimeException If no record of the created instance is found to create the instance.
     */
    public function create(object $dto) : UserDTO {
        if (!($dto instanceof UserDTO)) {
            throw new ValidationException("Passed dto object is not an instance of [" . UserDTO::class . "].");
        }
        $dto->validateForDbCreation();
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":username", $dto->getUsername(), PDO::PARAM_STR);
        $statement->bindValue(":email", $dto->getEmail(), PDO::PARAM_STR);
        $statement->bindValue(":password", $dto->getPassword(), PDO::PARAM_STR);
        $statement->execute();
        $new_id = (int) $connection->lastInsertId();
        
        $created_dto = $this->getById($new_id);
        
        if ($created_dto === null) {
            throw new RuntimeException(sprintf('Newly created user with id %d was not found in DB', $new_id));
        }
        
        return $created_dto;
    }
    
    /**
     * {@inheritDoc}
     * Specialized for {@see UserDTO} DTO objects.
     *
     * @param UserDTO $dto     The {@see UserDTO} instance to update the record of.
     * @return UserDTO The updated {@see UserDTO} instance.
     * @throws RuntimeException If no record of the updated instance is found to create the updated instance.
     * @throws ValidationException If the <code>$dto</code> object parameter is not an {@see UserDTO} instance or if
     *                         a {@see ValidationException} is thrown when creating the updated instance.
     */
    public function update(object $dto) : UserDTO {
        if (!($dto instanceof UserDTO)) {
            throw new ValidationException("Passed dto object is not an instance of [" . UserDTO::class . "].");
        }
        $dto->validateForDbUpdate();
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::UPDATE_EMAIL_QUERY);
        $statement->bindValue(":email", $dto->getEmail(), PDO::PARAM_STR);
        $statement->bindValue(":id", $dto->getPrimaryKeyValue(), PDO::PARAM_INT);
        $statement->execute();
        
        $updated_dto = $this->getById($dto->getPrimaryKeyValue());
        
        if ($updated_dto === null) {
            throw new RuntimeException(sprintf('The updated user with id %d was not found in DB',
                                               $dto->getPrimaryKeyValue()));
        }
        return $this->getById($dto->getPrimaryKeyValue());
    }
    
    /**
     * {@inheritDoc}
     * Specialized for {@see UserDTO} DTO objects.
     *
     * @param UserDTO $dto         The {@see UserDTO} instance to delete the record of.
     * @param bool    $realDeletes [OPTIONAL] whether to perform a real record deletion or just mark it with a deletion
     *                             date. Defaults to <code>false</code>.
     * @return void
     * @throws ValidationException If the <code>$dto</code> object parameter is not an {@see UserDTO} instance.
     * @throws RuntimeException
     */
    public function delete(object $dto, bool $realDeletes = false) : void {
        if (!($dto instanceof UserDTO)) {
            throw new ValidationException("Passed dto object is not an instance of [" . UserDTO::class . "].");
        }
        $dto->validateForDbDelete();
        $this->deleteById($dto->getPrimaryKeyValue());
    }
    
    /**
     * {@inheritDoc}
     * Specialized for {@see UserDTO} DTO objects.
     *
     * @param int  $id          The identifier value of the {@see UserDTO} entity to delete
     * @param bool $realDeletes [OPTIONAL] whether to perform a real record deletion or just mark it with a deletion
     *                          date. Defaults to <code>false</code>.
     * @return void
     *
     * @throws RuntimeException
     */
    public function deleteById(int $id, bool $realDeletes = false) : void {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::DELETE_QUERY);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();
    }
}