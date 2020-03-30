<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class Message extends Entity
{

    public function messageId(): ?int
    {
        return parent::_data('message_id');
    }

    public function from(): User
    {
        return new User(parent::_data('from'));
    }

    public function date(): ?int
    {
        return parent::_data('date');
    }

    public function chat(): Chat
    {
        return new Chat(parent::_data('chat'));
    }

    public function forwardFrom(): User
    {
        return new User(parent::_data('forward_from'));
    }

    public function forwardFromChat(): Chat
    {
        return new Chat(parent::_data('forward_from_chat'));
    }

    public function forwardFromMessageId(): ?int
    {
        return parent::_data('forward_from_message_id');
    }

    public function forwardSignature(): ?string
    {
        return parent::_data('forward_signature');
    }

    public function forwardDate(): ?int
    {
        return parent::_data('forward_date');
    }

    public function replyToMessage(): Message
    {
        return new Message(parent::_data('reply_to_message'));
    }

    public function editDate(): ?int
    {
        return parent::_data('edit_date');
    }

    public function mediaGroupId(): ?string
    {
        return parent::_data('media_group_id');
    }

    public function authorSignature(): ?string
    {
        return parent::_data('author_signature');
    }

    public function text(): ?string
    {
        return parent::_data('text');
    }

    /**
     * @return MessageEntity[]
     */
    public function entities(): array
    {
        $entities = parent::_data('entities');
        if (!is_null($entities)) {
            foreach ($entities as $key => $entity) {
                $entities[$key] = new MessageEntity($entity);
            }
            return $entities;
        } else return [];
    }


    /**
     * @return MessageEntity[]
     */
    public function caption_entities(): array
    {
        $entities = parent::_data('caption_entities');
        if (!is_null($entities)) {
            foreach ($entities as $key => $entity) {
                $entities[$key] = new MessageEntity($entity);
            }
            return $entities;
        } else return [];
    }

    public function audio(): Audio
    {
        return new Audio(parent::_data('audio'));
    }

    public function document(): Document
    {
        return new Document(parent::_data('document'));
    }

    public function animation(): Animation
    {
        return new Animation(parent::_data('animation'));
    }

    public function game(): Game
    {
        return new Game(parent::_data('animation'));
    }

    /**
     * @return PhotoSize[]
     */
    public function photo(): array
    {
        $photos = parent::_data('photo');
        if (!is_null($photos)) {
            foreach ($photos as $key => $photo) {
                $photos[$key] = new PhotoSize($photo);
            }
            return $photos;
        } else return [];
    }

    public function sticker(): Sticker
    {
        return new Sticker(parent::_data('sticker'));
    }

    public function video(): Video
    {
        return new Video(parent::_data('video'));
    }

    public function voice(): Voice
    {
        return new Voice(parent::_data('voice'));
    }

    public function videoNote(): VideoNote
    {
        return new VideoNote(parent::_data('video_note'));
    }

    public function caption(): ?string
    {
        return parent::_data('caption');
    }

    public function contact(): Contact
    {
        return new Contact(parent::_data('contact'));
    }

    public function location(): Location
    {
        return new Location(parent::_data('location'));
    }

    public function venue(): Venue
    {
        return new Venue(parent::_data('venue'));
    }

    public function dice(): Dice
    {
        return new Dice(parent::_data('dice'));
    }

    /**
     * @return User[]
     */
    public function newChatMembers(): array
    {
        $users = parent::_data('new_chat_members');
        if (!is_null($users)) {
            foreach ($users as $key => $user) {
                $users[$key] = new User($user);
            }
            return $users;
        } else return [];
    }

    public function leftChatMember(): User
    {
        return new User(parent::_data('left_chat_member'));
    }

    public function newChatTitle(): ?string
    {
        return parent::_data('new_chat_title');
    }

    /**
     * @return PhotoSize[]
     */
    public function newChatPhoto(): array
    {
        $photos = parent::_data('new_chat_photo');
        if (!is_null($photos)) {
            foreach ($photos as $key => $photo) {
                $photos[$key] = new PhotoSize($photo);
            }
            return $photos;
        } else return [];
    }

    public function deleteChatPhoto(): ?bool
    {
        return parent::_data('delete_chat_photo');
    }

    public function groupChatCreated(): ?bool
    {
        return parent::_data('group_chat_created');
    }

    public function supergroupChatCreated(): ?bool
    {
        return parent::_data('supergroup_chat_created');
    }

    public function channelChatCreated(): ?bool
    {
        return parent::_data('channel_chat_created');
    }

    public function migrateToChatId(): ?int
    {
        return parent::_data('migrate_to_chat_id');
    }

    public function migrateFromChatId(): ?int
    {
        return parent::_data('migrate_from_chat_id');
    }

    public function pinnedMessage(): Message
    {
        return new Message(parent::_data('pinned_message'));
    }

    public function invoice(): Invoice
    {
        return new Invoice(parent::_data('invoice'));
    }

    public function successfulPayment(): SuccessfulPayment
    {
        return new SuccessfulPayment(parent::_data('successful_payment'));
    }

    public function connectedWebsite(): ?string
    {
        return parent::_data('connected_website');
    }

    public function passportData(): PassportData
    {
        return new PassportData(parent::_data('passport_data'));
    }

}