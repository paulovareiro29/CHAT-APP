<?php

namespace App\DAO;

class UserDAO extends Connection
{

    public function __construct()
    {
        parent::__construct();
    }

    public function validateToken($token) //serve para validação do token e ir buscar um utilizador pelo token
    {

        $user = $this->db->user()->where('token', $token)->fetch();

        if (!$user) {
            return [
                "status" => 401,
                "message" => "Unauthorized",
                "body" => [
                    "Token does not exist"
                ]
            ];
        }

        if ($user['tokenExpDate'] < time()) {
            return [
                "status" => 401,
                "message" => "Unauthorized",
                "body" => [
                    "Token has expired"
                ]
            ];
        }

        $perms = array();
        foreach ($this->db->userpermission()->where('user_id', $user['id']) as $permission) {
            if ($permission['active']) {
                array_push($perms, [
                    'id' => $permission['permission_id'],
                    'name' => $this->db->permission[$permission['permission_id']]['name']
                ]);
            }
        }

        return [
            "status" => 200,
            "message" => "Authorized",
            "body" => [
                'id' => $user['id'],
                'username' => $user['username'],
                'name' => $user['name'],
                'createdAt' => $user['createdAt'],
                'deletedAt' => $user['deletedAt'],
                'permissions' => $perms
            ]
        ];
    }

    public function login($data)
    {
        if (!isset($data['username']) || !isset($data['password'])) {
            $arr = array();
            if (!isset($data['username'])) array_push($arr, "Username was not provided");
            if (!isset($data['password'])) array_push($arr, "Password was not provided");
            return [
                "status" => 400,
                "message" => "Failed to login",
                "body" => $arr
            ];
        }

        $user = $this->db->user()->where('username', $data['username'])->fetch();

        if (!$user) {
            return [
                "status" => 400,
                "message" => "Failed to login",
                "body" => [
                    "Username doesn't exist"
                ]
            ];
        }

        if ($user['password'] == hash("sha256", $data['password'])) {
            $token = bin2hex(openssl_random_pseudo_bytes(16));
            $expDate = time() + (7 * 24 * 60 * 60);

            $user->update([
                "token" => $token,
                "tokenExpDate" => $expDate
            ]);

            return [
                "status" => 200,
                "message" => "Login successful",
                "body" => [
                    "token" => $token,
                    "expDate" => $expDate
                ]
            ];
        }

        return [
            "status" => 400,
            "message" => "Failed to login",
            "body" => [
                "Password is wrong"
            ]
        ];
    }

    public function get()
    {
        $users = array();


        foreach ($this->db->user() as $user) {
            $perms = array();
            foreach ($this->db->userpermission()->where('user_id', $user['id']) as $permission) {
                if ($permission['active']) {
                    array_push($perms, [
                        'id' => $permission['permission_id'],
                        'name' => $this->db->permission[$permission['permission_id']]['name']
                    ]);
                }
            }


            array_push($users, [
                'id' => $user['id'],
                'username' => $user['username'],
                'name' => $user['name'],
                'createdAt' => $user['createdAt'],
                'deletedAt' => $user['deletedAt'],
                'permissions' => $perms
            ]);
        }

        return [
            "status" => 200,
            "message" => "List of all users",
            "body" => $users
        ];
    }

    public function show($id)
    {
        $user = $this->db->user[$id];

        if (!$user) {
            return [
                "status" => 404,
                "message" => "User not found",
                "body" => [
                    "User does not exist"
                ]
            ];
        }

        $perms = array();
        foreach ($this->db->userpermission()->where('user_id', $user['id']) as $permission) {
            if ($permission['active']) {
                array_push($perms, [
                    'id' => $permission['permission_id'],
                    'name' => $this->db->permission[$permission['permission_id']]['name']
                ]);
            }
        }

        return [
            "status" => 200,
            "message" => "Fetch user successful",
            "body" => [
                'id' => $user['id'],
                'username' => $user['username'],
                'name' => $user['name'],
                'createdAt' => $user['createdAt'],
                'deletedAt' => $user['deletedAt'],
                'permissions' => $perms
            ]
        ];
    }

    public function insert($data)
    {
        $exists = $this->db->user()->where('username', $data['username'])->fetch();
        if ($exists) {
            return [
                "status" => 400,
                "message" => "Error creating user",
                "body" => [
                    "Username already exists!"
                ]
            ];
        }

        $data['createdAt'] = time();
        $data['password'] = hash("sha256", $data['password']);

        $id = $this->db->user()->insert($data);
        if ($id) {
            return [
                "status" => 201,
                "message" => "User created successfuly",
                "body" => $id
            ];
        } else {
            return [
                "status" => 400,
                "message" => "Error creating user",
                "body" => []
            ];
        }
    }

    public function update($data)
    {
        $user = $this->db->user[$data['id']];

        if (!$user) {
            return [
                "status" => 404,
                "message" => "User not found",
                "body" => [
                    "User does not exist"
                ]
            ];
        }
        if (isset($data['password'])) {
            $data['password'] = hash("sha256", $data['password']);
        }

        $id = $user->update($data);

        if (!$id) {
            return [
                "status" => 400,
                "message" => "Failed to update user",
                "body" => []
            ];
        }

        $newUser = $this->db->user[$data['id']];

        return [
            "status" => 200,
            "message" => "User updated successfuly",
            "body" => [
                'id' => $newUser['id'],
                'username' => $newUser['username'],
                'name' => $newUser['name'],
                'createdAt' => $newUser['createdAt'],
                'deletedAt' => $newUser['deletedAt']
            ]
        ];
    }

    public function delete($id)
    {
        $user = $this->db->user[$id];

        if ($user) {
            if ($user['deletedAt']) {
                return [
                    "status" => 400,
                    "message" => "Error deleting user",
                    "body" => [
                        "User is already deleted"
                    ]
                ];
            }


            $user->update(["deletedAt" => time()]);

            return [
                "status" => 200,
                "message" => "User deleted successfuly",
                "body" => []
            ];
        }

        return [
            "status" => 404,
            "message" => "User not found",
            "body" => [
                "User does not exist"
            ]
        ];
    }

    public function getPermission($userID)
    {
        $array = array();

        $user = $this->db->user[$userID];

        if (!$user) {
            return [
                "status" => 404,
                "message" => "User not found",
                "body" => [
                    "User does not exist!"
                ]
            ];
        }

        foreach ($this->db->userpermission()->where('user_id', $userID) as $perm) {
            if ($perm['active']) {
                $p = $this->db->permission[$perm['permission_id']];
                array_push($array, [
                    "id" => $p['id'],
                    "name" => $p['name']
                ]);
            }
        }

        return [
            "status" => 200,
            "message" => "List of permissions",
            "body" => $array
        ];
    }

    public function givePermission($userID, $permID)
    {

        $userExists = $this->db->user[$userID];
        if (!$userExists) {
            return [
                "status" => 404,
                "message" => "User not found",
                "body" => [
                    "User does not exist!"
                ]
            ];
        }

        $permExists = $this->db->permission[$permID];
        if (!$permExists) {
            return [
                "status" => 404,
                "message" => "Permission not found",
                "body" => [
                    "Permission does not exist!"
                ]
            ];
        }

        $alreadyHas = $this->db->userpermission()->where('user_id', $userID)->where('permission_id', $permID)->fetch();

        if ($alreadyHas) {
            if ($alreadyHas['active']) {
                return [
                    "status" => 400,
                    "message" => "Error giving permission",
                    "body" => [
                        "User already has this permission"
                    ]
                ];
            } else {
                $status = $this->db->userpermission()->where('user_id', $userID)->where('permission_id', $permID)->update(['active' => 1]);
                if ($status) {
                    return [
                        "status" => 200,
                        "message" => "Permission given successfuly",
                        "body" => []
                    ];
                } else {
                    return [
                        "status" => 400,
                        "message" => "Error giving permission",
                        "body" => []
                    ];
                }
            }
        }

        $id = $this->db->userpermission()->insert([
            "user_id" => $userID,
            "permission_id" => $permID
        ]);

        if ($id) {
            return [
                "status" => 201,
                "message" => "Permission given successfuly",
                "body" => $id
            ];
        } else {
            return [
                "status" => 400,
                "message" => "Error giving permission",
                "body" => []
            ];
        }
    }

    public function revokePermission($userID, $permID)
    {
        $userExists = $this->db->user[$userID];
        if (!$userExists) {
            return [
                "status" => 404,
                "message" => "User not found",
                "body" => [
                    "User does not exist!"
                ]
            ];
        }

        $permExists = $this->db->permission[$permID];
        if (!$permExists) {
            return [
                "status" => 404,
                "message" => "Permission not found",
                "body" => [
                    "Permission does not exist!"
                ]
            ];
        }

        $hasPerm = $this->db->userpermission()->where('user_id', $userID)->where('permission_id', $permID)->fetch();

        if ($hasPerm) {
            $status = $this->db->userpermission()->where('user_id', $userID)->where('permission_id', $permID)->update(['active' => 0]);
            if ($status) {
                return [
                    "status" => 200,
                    "message" => "Permission revoked successfuly",
                    "body" => []
                ];
            } else {
                return [
                    "status" => 400,
                    "message" => "Error revoking permission",
                    "body" => []
                ];
            }
        } else {
            $id = $this->db->userpermission()->insert([
                "user_id" => $userID,
                "permission_id" => $permID,
                "active" => 0
            ]);

            if ($id) {
                return [
                    "status" => 200,
                    "message" => "Permission revoked successfuly",
                    "body" => []
                ];
            } else {
                return [
                    "status" => 400,
                    "message" => "Error revoking permission",
                    "body" => []
                ];
            }
        }
    }

    /*public function isAdmin($userID)
    {
        $adminPerm = $this->db->userpermission()->where('user_id', $userID)->where('permission_id', 1)->fetch();
        if ($adminPerm){
            if ($adminPerm['active']){
                return true;
            }
        }
        return false;
    }

    public function isModerator($userID)
    {
        $modPerm = $this->db->userpermission()->where('user_id', $userID)->where('permission_id', 2)->fetch();
        $adminPerm = $this->db->userpermission()->where('user_id', $userID)->where('permission_id', 1)->fetch();
        if($modPerm){
            if($modPerm['active']){
                return true;
            }
        }

        if ($adminPerm){
            if ($adminPerm['active']){
                return true;
            }
        }
        return false;
    }*/
}
