<?php

namespace App\DAO;

class RoomDAO extends Connection
{

    public function __construct()
    {
        parent::__construct();
        $this->userDAO = new UserDAO();
    }

    public function get()
    {
        $rooms = array();


        foreach ($this->db->room() as $room) {
            $members = array();
            foreach ($this->db->member()->where('room_id', $room['id']) as $member) {
                $user = $this->userDAO->show($member['user_id'])['body'];
                array_push($members, [
                    'member' => $user,
                    'addedAt' => $member['addedAt'],
                    'admin' => $member['admin'] ? true : false
                ]);
            }


            array_push($rooms, [
                'id' => $room['id'],
                'name' => $room['name'],
                'owner' => $this->userDAO->show($room['user_id'])['body'],
                'createdAt' => $room['createdAt'],
                'deletedAt' => $room['deletedAt'],
                'members' => $members
            ]);
        }

        return [
            "status" => 200,
            "message" => "List of all rooms",
            "body" => $rooms
        ];
    }

    public function show($id)
    {
        $room = $this->db->room[$id];

        if (!$room) {
            return [
                "status" => 404,
                "message" => "Room not found",
                "body" => [
                    "Room does not exist"
                ]
            ];
        }

        $members = array();
        foreach ($this->db->member()->where('room_id', $room['id']) as $member) {
            $user = $this->userDAO->show($member['user_id'])['body'];
            array_push($members, [
                'member' => $user,
                'addedAt' => $member['addedAt'],
                'admin' => $member['admin'] ? true : false
            ]);
        }

        return [
            "status" => 200,
            "message" => "Fetch room successful",
            "body" => [
                'id' => $room['id'],
                'name' => $room['name'],
                'owner' => $this->userDAO->show($room['user_id'])['body'],
                'createdAt' => $room['createdAt'],
                'deletedAt' => $room['deletedAt'],
                'members' => $members
            ]
        ];
    }

    public function insert($data)
    {
        $data['createdAt'] = time();
        $room = $this->db->room()->insert($data);

        if ($room) {
            $room = $this->db->room[$room['id']];

            $this->db->member()->insert([
                'room_id' => $room['id'],
                'user_id' => $room['user_id'],
                'addedAt' => time(),
                'admin' => 1
            ]);

            return [
                "status" => 201,
                "message" => "Room created successfuly",
                "body" => [
                    'id' => $room['id'],
                    'name' => $room['name'],
                    'owner' => $this->userDAO->show($room['user_id'])['body'],
                    'createdAt' => $room['createdAt'],
                    'deletedAt' => $room['deletedAt']
                ]
            ];
        } else {
            return [
                "status" => 400,
                "message" => "Error creating room",
                "body" => []
            ];
        }
    }

    public function update($data)
    {
        $room = $this->db->room[$data['id']];

        if (!$room) {
            return [
                "status" => 404,
                "message" => "Room not found",
                "body" => [
                    "Room does not exist"
                ]
            ];
        }

        $id = $room->update($data);

        if (!$id) {
            return [
                "status" => 400,
                "message" => "Failed to update room",
                "body" => []
            ];
        }

        $room = $this->db->room[$data['id']];

        return [
            "status" => 201,
            "message" => "Room created successfuly",
            "body" => [
                'id' => $room['id'],
                'name' => $room['name'],
                'owner' => $this->userDAO->show($room['user_id'])['body'],
                'createdAt' => $room['createdAt'],
                'deletedAt' => $room['deletedAt']
            ]
        ];
    }

    public function delete($id)
    {
        $room = $this->db->room[$id];

        if (!$room) {
            return [
                "status" => 404,
                "message" => "Room not found",
                "body" => [
                    "Room does not exist"
                ]
            ];
        } else {

            if ($room['deletedAt']) {
                return [
                    "status" => 400,
                    "message" => "Error deleting room",
                    "body" => [
                        "Room is already deleted"
                    ]
                ];
            }

            $status = $room->update([
                'deletedAt' => time()
            ]);
            if ($status) {
                return [
                    "status" => 200,
                    "message" => "Room deleted successfuly",
                    "body" => []
                ];
            } else {
                return [
                    "status" => 400,
                    "message" => "Error deleting room",
                    "body" => []
                ];
            }
        }
    }

    public function getMembers($roomID)
    {

        $room = $this->db->room[$roomID];

        if (!$room) {
            return [
                "status" => 404,
                "message" => "Room not found",
                "body" => [
                    "Room does not exist"
                ]
            ];
        }


        $members = array();
        foreach ($this->db->member()->where('room_id', $roomID) as $member) {
            $user = $this->userDAO->show($member['user_id'])['body'];
            array_push($members, [
                'member' => $user,
                'addedAt' => $member['addedAt'],
                'admin' => $member['admin'] ? true : false
            ]);
        }


        return [
            "status" => 404,
            "message" => "List of members",
            "body" =>  $members
        ];
    }

    public function addMember($roomID, $userID)
    {

        $user = $this->db->user[$userID];

        if (!$user) {
            return [
                "status" => 404,
                "message" => "User not found",
                "body" => [
                    "User does not exist"
                ]
            ];
        }

        $room = $this->db->room[$roomID];

        if (!$room) {
            return [
                "status" => 404,
                "message" => "Room not found",
                "body" => [
                    "Room does not exist"
                ]
            ];
        }

        $isMember = $this->db->member()->where('room_id', $roomID)->where('user_id', $userID)->fetch();

        if ($isMember) {
            return [
                "status" => 400,
                "message" => "Failed to add member",
                "body" => [
                    "User already is a member of this room"
                ]
            ];
        }


        $member = $this->db->member()->insert([
            'room_id' => $roomID,
            'user_id' => $userID,
            'addedAt' => time()
        ]);

        if ($member) {
            return [
                "status" => 200,
                "message" => "Member added successfuly",
                "body" => []
            ];
        } else {
            return [
                "status" => 400,
                "message" => "Failed to add member",
                "body" => []
            ];
        }
    }

    public function removeMember($roomID, $userID)
    {
        $user = $this->db->user[$userID];

        if (!$user) {
            return [
                "status" => 404,
                "message" => "User not found",
                "body" => [
                    "User does not exist"
                ]
            ];
        }

        $room = $this->db->room[$roomID];

        if (!$room) {
            return [
                "status" => 404,
                "message" => "Room not found",
                "body" => [
                    "Room does not exist"
                ]
            ];
        }

        $member = $this->db->member()->where('room_id', $roomID)->where('user_id', $userID)->fetch();

        if (!$member) {
            return [
                "status" => 400,
                "message" => "Failed to remove member",
                "body" => [
                    "User already is not a member of this room"
                ]
            ];
        }

        $status = $this->db->member()->where('room_id', $roomID)->where('user_id', $userID)->delete();

        if ($status) {
            return [
                "status" => 200,
                "message" => "Removed member successfuly",
                "body" => []
            ];
        } else {
            return [
                "status" => 400,
                "message" => "Failed to remove member",
                "body" => []
            ];
        }
    }

    public function giveAdmin($roomID, $userID)
    {
        $user = $this->db->user[$userID];
        if (!$user) {
            return [
                "status" => 404,
                "message" => "User not found",
                "body" => [
                    "User does not exist"
                ]
            ];
        }

        $room = $this->db->room[$roomID];
        if (!$room) {
            return [
                "status" => 404,
                "message" => "Room not found",
                "body" => [
                    "Room does not exist"
                ]
            ];
        }

        $isAdmin = $this->db->member()->where('room_id', $roomID)->where('user_id', $userID)->fetch()['admin'];

        if ($isAdmin) {
            return [
                "status" => 400,
                "message" => "Failed to give admin permission",
                "body" => [
                    "Member already is an admin"
                ]
            ];
        }

        $status = $this->db->member()->where('room_id', $roomID)->where('user_id', $userID)->update([
            'admin' => 1
        ]);

        if ($status) {
            return [
                "status" => 200,
                "message" => "Admin permission granted",
                "body" => []
            ];
        } else {
            return [
                "status" => 400,
                "message" => "Failed to give admin permission",
                "body" => []
            ];
        }
    }

    public function revokeAdmin($roomID, $userID)
    {
        $user = $this->db->user[$userID];
        if (!$user) {
            return [
                "status" => 404,
                "message" => "User not found",
                "body" => [
                    "User does not exist"
                ]
            ];
        }

        $room = $this->db->room[$roomID];
        if (!$room) {
            return [
                "status" => 404,
                "message" => "Room not found",
                "body" => [
                    "Room does not exist"
                ]
            ];
        }

        $isAdmin = $this->db->member()->where('room_id', $roomID)->where('user_id', $userID)->fetch()['admin'];

        if (!$isAdmin) {
            return [
                "status" => 400,
                "message" => "Failed to revoke admin permission",
                "body" => [
                    "Member already is not an admin"
                ]
            ];
        }

        $status = $this->db->member()->where('room_id', $roomID)->where('user_id', $userID)->update([
            'admin' => 0
        ]);

        if ($status) {
            return [
                "status" => 200,
                "message" => "Admin permission revoked",
                "body" => []
            ];
        } else {
            return [
                "status" => 400,
                "message" => "Failed to revoke admin permission",
                "body" => []
            ];
        }
    }

    public function getMessages($roomID)
    {
        $room = $this->db->room[$roomID];

        if (!$room) {
            return [
                "status" => 404,
                "message" => "Room not found",
                "body" => [
                    "Room does not exist"
                ]
            ];
        }

        $messages = array();
        foreach ($this->db->message()->where('room_id', $roomID) as $message) {
            $user = $this->userDAO->show($message['user_id']);

            array_push($messages, [
                'id' => $message['id'],
                'user' => $user,
                'content' => $message['content'],
                'sentAt' => $message['sentAt'],
                'deletedAt' => $message['deletedAt']
            ]);
        }

        return [
            "status" => 200,
            "message" => "List of all messages",
            "body" => $messages
        ];
    }

    public function insertMessage($roomID, $data)
    {
        $room = $this->db->room[$roomID];

        if (!$room) {
            return [
                "status" => 404,
                "message" => "Room not found",
                "body" => [
                    "Room does not exist"
                ]
            ];
        }

        $message = $this->db->message()->insert([
            'room_id' => $roomID,
            'user_id' => $data['user_id'],
            'content' => $data['content'],
            'sentAt' => time()
        ]);

        if ($message) {
            return [
                "status" => 200,
                "message" => "Message added",
                "body" => []
            ];
        } else {
            return [
                "status" => 400,
                "message" => "Error adding message",
                "body" => []
            ];
        }
    }

    public function deleteMessage($roomID, $messageID)
    {
        $message = $this->db->message[$messageID];
        if (!$message) {
            return [
                "status" => 404,
                "message" => "Message not found",
                "body" => [
                    "Message does not exist"
                ]
            ];
        }

        $room = $this->db->room[$roomID];
        if (!$room) {
            return [
                "status" => 404,
                "message" => "Room not found",
                "body" => [
                    "Room does not exist"
                ]
            ];
        }

        if ($message['deletedAt']) {
            return [
                "status" => 400,
                "message" => "Error deleting message",
                "body" => [
                    "Message is already deleted"
                ]
            ];
        }

        $status = $message->update([
            'deletedAt' => time()
        ]);

        if ($status) {
            return [
                "status" => 200,
                "message" => "Message deleted successfuly",
                "body" => []
            ];
        } else {
            return [
                "status" => 400,
                "message" => "Error deleting message",
                "body" => []
            ];
        }
    }

    public function editMessage($roomID, $data)
    {
        $message = $this->db->message[$data['id']];
        if (!$message) {
            return [
                "status" => 404,
                "message" => "Message not found",
                "body" => [
                    "Message does not exist"
                ]
            ];
        }

        $room = $this->db->room[$roomID];
        if (!$room) {
            return [
                "status" => 404,
                "message" => "Room not found",
                "body" => [
                    "Room does not exist"
                ]
            ];
        }


        $status = $message->update([
            'content' => $data['content'],
            'updatedBy' => $data['user_id'],
            'updatedAt' => time()
        ]);

        if($status){
            return [
                "status" => 200,
                "message" => "Message edited successfuly",
                "body" => []
            ];
        }else{
            return [
                "status" => 400,
                "message" => "Error editing message",
                "body" => []
            ];
        }
    }

    /*public function isRoomAdmin($roomID, $userID)
    {
        $member = $this->db->member()->where('room_id', $roomID)->where('user_id', $userID)->fetch();

        if ($member['admin'])
            return true;

        return false;
    }

    public function isRoomOwner($roomID, $userID): bool
    {
        $room = $this->db->room[$roomID];


        if ($room['user_id'] == $userID) {
            return true;
        }

        return false;
    }*/
}
