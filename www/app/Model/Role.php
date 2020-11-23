<?php

namespace App\Model;

use Exception;
use App\Core;
use App\Utility;

/**
 * Role Model:
 *
 * @author John Alex
 * @since 1.0.6
 */
class Role extends Core\Model {

    /** @var array */
    protected $permissions = [];

    /**
     * Construct:
     * @access protected
     * @since 1.0.6
     */
    protected function __construct() {
        $this->permissions = array();
    }

    /**
     * Get Role Permission: Return a role object with associated permissions
     * @access public
     * @param string $role_id
     * @static static method
     * @return string|null
     */
    public static function getRolePerms($role_id) {
        $role = new Role();

        $Db = Utility\Database::getInstance();
        $sql = "SELECT t2.perm_desc FROM role_perm as t1
                JOIN permissions as t2 ON t1.perm_id = t2.perm_id
                WHERE t1.role_id = " . $role_id;
        $sql = $Db->query($sql);

        while($result = $sql->results()) {
            $role->permissions[$result[0]->perm_desc] = true;
        }

        return $role;
    }

    /**
     * Get Role: Populate roles with their associated permissions
     * @access protected
     * @param string $user_id
     * @return string
     * @since 1.0.6
     */
    protected function getRole($user_id) {
        $this->roles = array();

        $Db = Utility\Database::getInstance();
        $sql = "SELECT t1.role_id, t2.role_name FROM user_role as t1
                JOIN roles as t2 ON t1.role_id = t2.role_id
                WHERE t1.id = " . $user_id;
        $sql = $Db->query($sql);

        if (!empty($result = $sql->results())) {
            return $result[0]->role_name;
        }
    }

    /**
     * Has Permission: Check if a permission is set
     * @access public
     * @param string $permission
     * @return boolean
     * @since 1.0.6
     */
    public function hasPerm($permission) {
        return isset($this->permissions[$permission]);
    }

    /**
     * Is Admin: Checks if the current User 
     * @access public
     * @param object $User
     * @static static method
     * @return boolean
     * @since 1.0.6
     */
    public static function isAdmin($User) {
        $user_id = $User->data()->id;
        $role = new Role;
        if($role->getRole($user_id) == 'admin' || $role->getRole($user_id) == 'client') {
            return true;
        }
        return false;
    }

}