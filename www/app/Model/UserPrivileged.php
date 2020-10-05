<?php

namespace App\Model;

use Exception;
use App\Core;
use App\Utility;

/**
 * Privileged Model:
 *
 * @author John Alex
 * @since 1.0.6
 */
class UserPrivileged extends User {

    /** @var array */
    private $roles = [];

    /**
     * Construct:
     * @access public
     * @since 1.0.6
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Get User: Returns an instance of the User model if the specified user
     * exists in the database. 
     * @access public
     * @param string $id
     * @return User|null
     * @since 1.0.6
     */
    public static function getUser($id) {

        $Db = Utility\Database::getInstance();
        $sql = $Db->select("users", ["id", "=", $id]);

        if ($sql->count()) {
            $result = $sql->results();
        }

        if (!empty($result)) {
            $privUser = new UserPrivileged();
            $privUser->user_id = $id;
            $privUser->email = $result[0]->email;
            $privUser->initRoles();
            return $privUser;
        } else {
            return false;
        }
    }

    /**
     * Init Roles: Populate roles with their associated permissions
     * @access public
     * @return mixed
     * @since 1.0.6
     */
    protected function initRoles() {
        $this->roles = array();

        $Db = Utility\Database::getInstance();
        $sql = "SELECT t1.role_id, t2.role_name FROM user_role as t1
                JOIN roles as t2 ON t1.role_id = t2.role_id
                WHERE t1.id = " . $this->user_id;
        $sql = $Db->query($sql);

        while($result = $sql->results()) {
            $this->roles[$result[0]->role_name] = Role::getRolePerms($result[0]->role_id);
        }
    }

    /**
     * Has Privilege: Check if user has a specific privilege
     * @access public
     * @param string $perm
     * @return boolean
     * @since 1.0.6
     */
    public function hasPrivilege($perm) {
        foreach ($this->roles as $role) {
            if ($role->hasPerm($perm)) {
                return true;
            }
        }
        return false;
    }


}