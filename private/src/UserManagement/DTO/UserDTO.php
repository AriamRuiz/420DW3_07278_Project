<?php

namespace UserManagement\DTO;

use DateTime;
use Teacher\GivenCode\Abstracts\AbstractDTO;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 * Users DTO
 */
class UserDTO extends AbstractDTO {
    /**
     * DB table name for <code>users</code>
     */
    public const TABLE_NAME = 'users';
    public const COLUMN_NAME_USERNAME = 'username';
    public const COLUMN_NAME_EMAIL = 'email';
    public const COLUMN_NAME_PASSWORD = 'password';
    public const COLUMN_NAME_CREATED_AT = 'created_at';
    public const COLUMN_NAME_UPDATED_AT = 'updated_at';
    private string $username;
    private string $email;
    private string $password;
    private ?DateTime $createdAt = null;
    private ?DateTime $updatedAt = null;
    
    // <editor-fold defaultstate="collapsed" desc="CONSTRUCTORS">
    
    /**
     * Empty protected constructor function.
     * Use {@see UserDTO::fromValues()} or {@see UserDTO::fromDbArray()} to create object instances.
     *
     * This empty constructor allows the internal creation of instances with or without the normally required 'id' and
     * other database-managed attributes.
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Static constructor-like function to create {@see UserDTO} instances without an id or temporal management
     * attribute values. Used to create instances before inserting them in the database.
     *
     * @static
     * @param string $username
     * @param string $email
     * @param string $password
     * @return UserDTO
     * @throws ValidationException
     */
    public static function fromValues(string $username, string $email, string $password) : UserDTO {
        // Use the protected constructor to create an empty UserDTO instance
        $object = new UserDTO();
        
        // Set the property values from the parameters.
        // Using the setter methods allows me to validate the values on the spot.
        $object->setUsername($username);
        $object->setPassword($password);
        $object->setEmail($email);
        
        // return the created instance
        return $object;
    }
    
    /**
     * Static constructor-like function to create {@see UserDTO} instances with an id and temporal management
     * attribute values. Used to create instances from database-fetched arrays.
     *
     * @static
     * @param array $dbAssocArray The associative array of a fetched record of an {@see UserDTO} entity from the
     *                            database.
     *
     * @return UserDTO
     * @throws ValidationException
     */
    public static function fromDbArray(array $dbAssocArray) : UserDTO {
        // Use the protected constructor to create an empty UserDTO instance
        $object = new UserDTO();
        
        // Set the property values from the array parameter
        $object->setId((int) $dbAssocArray[self::$primaryKeyColumnName]);
        $object->setUsername($dbAssocArray[self::COLUMN_NAME_USERNAME]);
        $object->setEmail($dbAssocArray[self::COLUMN_NAME_EMAIL]);
        $object->setPassword($dbAssocArray[self::COLUMN_NAME_PASSWORD]);
        $object->setCreatedAt(
            DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbAssocArray[self::COLUMN_NAME_CREATED_AT])
        );
        $object->setUpdatedAt(
            (empty($dbAssocArray[self::COLUMN_NAME_UPDATED_AT])) ? null
                : DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbAssocArray[self::COLUMN_NAME_UPDATED_AT])
        );
        // return the created instance
        return $object;
    }
    
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="GETTERS AND SETTERS">
    
    /**
     * @inheritDoc
     */
    public function getDatabaseTableName() : string {
        return self::TABLE_NAME;
    }
    
    /**
     * Gets the username
     *
     * @return string
     */
    public function getUsername() : string {
        return $this->username;
    }
    
    /**
     * Sets the username
     *
     * @param string $username
     * @return void
     * @throws ValidationException
     */
    public function setUsername(string $username) : void {
        if (preg_match("/^(?=.*[A-Z])(?=.*[0-9])(?=.*[;+\-])(?!.*[\s]).{8,}$/", $username) === false) {
            throw new ValidationException('Username is invalid. Make sure it contains at least 8 characters and contain at least one number, one uppercase and lowercase letter and one of the following symbols: ;+-');
        }
        
        $this->username = $username;
    }
    
    /**
     * Gets the user's email
     *
     * @return string
     */
    public function getEmail() : string {
        return $this->email;
    }
    
    /**
     * Sets the user's email
     *
     * @param string $email
     * @return void
     * @throws ValidationException
     */
    public function setEmail(string $email) : void {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new ValidationException("Invalid email provided");
        }
        
        $this->email = $email;
    }
    
    /**
     * Gets the user password hash
     *
     * @return string
     */
    public function getPassword() : string {
        return $this->password;
    }
    
    /**
     * Sets the user's password hash
     *
     * @param string $password
     * @return void
     */
    public function setPassword(string $password) : void {
        $this->password = $password;
    }
    
    /**
     * Sets the datetime a user was created at
     *
     * @return DateTime|null
     */
    public function getCreatedAt() : ?DateTime {
        return $this->createdAt;
    }
    
    /**
     * Sets the datetime a user was created at
     *
     * @param DateTime|null $createdAt
     * @return void
     */
    public function setCreatedAt(?DateTime $createdAt = null) : void {
        $this->createdAt = $createdAt;
    }
    
    /**
     * Gets the datetime a user was updated at
     *
     * @return DateTime|null
     */
    public function getUpdatedAt() : ?DateTime {
        return $this->updatedAt;
    }
    
    /**
     * Sets the datetime a user was updated at
     *
     * @param DateTime|null $updatedAt
     * @return void
     */
    public function setUpdatedAt(?DateTime $updatedAt = null) : void {
        $this->updatedAt = $updatedAt;
    }
    
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="VALIDATION METHODS">
    
    /**
     * Validates the instance for creation of its record in the database.
     *
     * @param bool $optThrowExceptions [OPTIONAL] Whether to throw exceptions or not if invalid. Defaults to true.
     * @return bool <code>True</code> if valid, <code>false</code> otherwise.
     * @throws ValidationException If the instance is invalid and the <code>$optThrowExceptions</code> parameter is
     *                                 <code>true</code>.
     */
    public function validateForDbCreation(bool $optThrowExceptions = true) : bool {
        // ID must not be set
        if (!empty($this->id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: ID value already set.");
            }
            return false;
        }
        // username is required
        if (empty($this->username)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: username value not set.");
            }
            return false;
        }
        // email is required
        if (empty($this->email)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: email value not set.");
            }
            return false;
        }
        // creationDate must not be set
        if (!is_null($this->createdAt)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: createdAt value already set.");
            }
            return false;
        }
        // lastModificationDate must not be set
        if (!is_null($this->updatedAt)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: updatedAt value already set.");
            }
            return false;
        }
        
        return true;
    }
    
    /**
     * Validates the instance for the update of its record in the database.
     *
     * @param bool $optThrowExceptions [OPTIONAL] Whether to throw exceptions or not if invalid. Defaults to true.
     * @return bool <code>True</code> if valid, <code>false</code> otherwise.
     * @throws ValidationException If the instance is invalid and the <code>$optThrowExceptions</code> parameter is
     *                                 <code>true</code>.
     *
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function validateForDbUpdate(bool $optThrowExceptions = true) : bool {
        // ID is required
        if (empty($this->id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB update: ID value is not set.");
            }
            return false;
        }
        // username cannot be updated
        if (!empty($this->username)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB update: username cannot be changed.");
            }
            return false;
        }
        // email is required
        if (empty($this->email)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB update: email value not set.");
            }
            return false;
        }
        return true;
    }
    
    /**
     * Validates the instance for the deletion of its record in the database.
     *
     * @param bool $optThrowExceptions [OPTIONAL] Whether to throw exceptions or not if invalid. Defaults to true.
     * @return bool <code>True</code> if valid, <code>false</code> otherwise.
     * @throws ValidationException If the instance is invalid and the <code>$optThrowExceptions</code> parameter is
     *                                 <code>true</code>.
     *
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function validateForDbDelete(bool $optThrowExceptions = true) : bool {
        // ID is required
        if (empty($this->id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB deletion: ID value is not set.");
            }
            return false;
        }
        return true;
    }
    
    // </editor-fold>
    
    /**
     * Converts UserDTO object to a JSON string representation
     *
     * @return string
     */
    public function toJson() : string {
        $array = [
            self::$primaryKeyColumnName => $this->getId(),
            self::COLUMN_NAME_USERNAME => $this->getUsername(),
            self::COLUMN_NAME_EMAIL => $this->getEmail(),
            self::COLUMN_NAME_CREATED_AT => $this->getCreatedAt()?->format(HTML_DATETIME_FORMAT),
            self::COLUMN_NAME_UPDATED_AT => $this->getUpdatedAt()?->format(HTML_DATETIME_FORMAT),
        ];
        return json_encode($array, JSON_PRETTY_PRINT);
    }
}
