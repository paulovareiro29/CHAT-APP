
CREATE TABLE `user`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `token` VARCHAR(255),
    `tokenExpDate` INT,
    `createdAt` INT NOT NULL,
    `deletedAt` INT,
    PRIMARY KEY (`id`)
);

CREATE TABLE `permission`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `description` VARCHAR(255),
    PRIMARY KEY (`id`)
);

CREATE TABLE `userpermission`(
    `user_id` INT NOT NULL,
    `permission_id` INT NOT NULL,
    `active` BIT NOT NULL DEFAULT 1,
    PRIMARY KEY (`user_id`,`permission_id`),
    CONSTRAINT FK_USER_PERMISSION
        FOREIGN KEY (`user_id`)
            REFERENCES `user`(`id`),
    CONSTRAINT FK_PERMISSION_USER
        FOREIGN KEY (`permission_id`)
            REFERENCES `permission`(`id`)
);

CREATE TABLE `room`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `user_id` INT NOT NULL,
    `createdAt` INT NOT NULL,
    `deletedAt` INT,
    PRIMARY KEY (`id`),
    CONSTRAINT FK_ROOM_OWNER
        FOREIGN KEY (`user_id`)
            REFERENCES `user`(`id`)
);

CREATE TABLE `message`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `room_id` INT NOT NULL,
    `content` VARCHAR(640) NOT NULL,
    `sentAt` INT NOT NULL,
    `deletedAt` INT,
    `updatedAt` INT,
    `updatedBy` INT,
    PRIMARY KEY (`id`),
    CONSTRAINT FK_MESSAGE_USER
        FOREIGN KEY (`user_id`)
            REFERENCES `user`(`id`),
    CONSTRAINT FK_MESSAGE_ROOM
        FOREIGN KEY (`room_id`)
            REFERENCES `room`(`id`),
    CONSTRAINT FK_MESSAGE_EDITED_BY
        FOREIGN KEY (`updatedBy`)
            REFERENCES `user`(`id`)
);

CREATE TABLE `member`(
    `room_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `addedAt` INT NOT NULL,
    `admin` BIT NOT NULL DEFAULT 0,
    PRIMARY KEY (`room_id`,`user_id`),
    CONSTRAINT FK_MEMBER_ROOM
        FOREIGN KEY (`room_id`)
            REFERENCES `room`(`id`),
    CONSTRAINT FK_MEMBER_USER
        FOREIGN KEY (`user_id`)
            REFERENCES `user`(`id`)
);

INSERT INTO `permission` (`id`, `name`, `description`) VALUES 
    (NULL, 'admin', 'Super permission. Created by default. Can access anything.'),
    (NULL, 'moderator', 'Moderator permission. Can do anything the admin can except managing permissions.');

INSERT INTO `user` (`id`, `username`, `password`, `name`, `token`, `tokenExpDate`, `createdAt`, `deletedAt`) VALUES
    (NULL, 'root', '4813494d137e1631bba301d5acab6e7bb7aa74ce1185d456565ef51d737677b2', 'root', NULL, NULL, UNIX_TIMESTAMP(), NULL);

INSERT INTO `userpermission` (`user_id`, `permission_id`) 
    VALUES ('1', '1')