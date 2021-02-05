<?php

namespace App\DAO;

class PermissionDAO extends Connection
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        $permissions = array();


        foreach ($this->db->permission() as $permission) {
            array_push($permissions, [
                'id' => $permission['id'],
                'name' => $permission['name'],
                'description' => $permission['description'],
            ]);
        }

        return [
            "status" => 200,
            "message" => "List of all permissions",
            "body" => $permissions
        ];
    }

    public function show($id)
    {
        $permission = $this->db->permission[$id];

        if (!$permission) {
            return [
                "status" => 404,
                "message" => "Permission not found",
                "body" => [
                    "Permission does not exist"
                ]
            ];
        }

        return [
            "status" => 200,
            "message" => "Fetch permission successful",
            "body" => [
                'id' => $permission['id'],
                'name' => $permission['name'],
                'description' => $permission['description']
            ]
        ];
    }

    public function insert($data)
    {
        $exists = $this->db->permission()->where('name', $data['name'])->fetch();
        if ($exists) {
            return [
                "status" => 400,
                "message" => "Error creating permission",
                "body" => [
                    "Permission already exists!"
                ]
            ];
        }

        $id = $this->db->permission()->insert($data);
        if ($id) {
            return [
                "status" => 201,
                "message" => "Permission created successfuly",
                "body" => $id
            ];
        } else {
            return [
                "status" => 400,
                "message" => "Error creating Permission",
                "body" => []
            ];
        }
    }

    public function update($data)
    {
        $permission = $this->db->permission[$data['id']];

        if (!$permission) {
            return [
                "status" => 404,
                "message" => "Permission not found",
                "body" => [
                    "Permission does not exist"
                ]
            ];
        }

        $id = $permission->update($data);

        if (!$id) {
            return [
                "status" => 400,
                "message" => "Failed to update permission",
                "body" => []
            ];
        }

        $newPermission = $this->db->permission[$data['id']];

        return [
            "status" => 200,
            "message" => "Permission updated successfuly",
            "body" => [
                'id' => $newPermission['id'],
                'name' => $newPermission['name'],
                'description' => $newPermission['description']
            ]
        ];
    }

    public function delete($id)
    {
        $permission = $this->db->permission[$id];

        if ($permission) {
            $status = $permission->delete();

            if ($status) {
                return [
                    "status" => 200,
                    "message" => "Permission deleted successfuly",
                    "body" => []
                ];
            }else{
                return [
                    "status" => 400,
                    "message" => "Error deleting permission",
                    "body" => []
                ];
            }
        } else {
            return [
                "status" => 404,
                "message" => "Permission not found",
                "body" => [
                    "Permission does not exist!"
                ]
            ];
        }
    }
}
