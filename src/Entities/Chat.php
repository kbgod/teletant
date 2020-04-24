<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class Chat extends Entity
{

    /**
     * @return int|null
     */
    public function id(): ?int
    {
        return parent::_data('id');
    }

    /**
     * @return string|null
     */
    public function type(): ?string
    {
        return parent::_data('type');
    }

    /**
     * @return string|null
     */
    public function title(): ?string
    {
        return parent::_data('title');

    }

    /**
     * @return string|null
     */
    public function username(): ?string
    {
        return parent::_data('username');
    }

    /**
     * @return string|null
     */
    public function firstName(): ?string
    {
        return parent::_data('first_name');

    }

    /**
     * @return string|null
     */
    public function lastName(): ?string
    {
        return parent::_data('last_name');
    }

    /**
     * @return bool|null
     */
    public function allMembersAreAdministrators(): ?bool
    {
        return parent::_data('all_members_are_administrators');
    }

    /**
     * @return ChatPhoto
     */
    public function photo(): ChatPhoto
    {
        return new ChatPhoto(parent::_data('photo'));
    }

    /**
     * @return string|null
     */
    public function description(): ?string
    {
        return parent::_data('description');
    }

    /**
     * @return string|null
     */
    public function inviteLink(): ?string
    {
        return parent::_data('invite_link');
    }

    /**
     * @return Message
     */
    public function pinnedMessage(): Message
    {
        return new Message(parent::_data('pinned_message'));
    }

    /**
     * @return ChatPermissions
     */
    public function permissions(): ChatPermissions
    {
        return new ChatPermissions(parent::_data('permissions'));
    }

    /**
     * @return int|null
     */
    public function slowModeDelay(): ?int
    {
        return parent::_data('slow_mode_delay');
    }

    /**
     * @return string|null
     */
    public function stickerSetName(): ?string
    {
        return parent::_data('sticker_set_name');
    }

    /**
     * @return bool|null
     */
    public function canSetStickerSet(): ?bool
    {
        return parent::_data('can_set_sticker_set');
    }
}