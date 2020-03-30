<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class Chat extends Entity
{

    public function id(): ?int
    {
        return parent::_data('id');
    }

    public function type(): ?string
    {
        return parent::_data('type');
    }

    public function title(): ?string
    {
        return parent::_data('title');

    }

    public function username(): ?string
    {
        return parent::_data('username');
    }

    public function firstName(): ?string
    {
        return parent::_data('first_name');

    }

    public function lastName(): ?string
    {
        return parent::_data('last_name');
    }

    public function allMembersAreAdministrators(): ?bool
    {
        return parent::_data('all_members_are_administrators');
    }

    public function photo(): ChatPhoto
    {
        return new ChatPhoto(parent::_data('photo'));
    }

    public function description(): ?string
    {
        return parent::_data('description');
    }

    public function inviteLink(): ?string
    {
        return parent::_data('invite_link');
    }

    public function pinnedMessage(): Message
    {
        return new Message(parent::_data('pinned_message'));
    }

    public function permissions(): ChatPermissions
    {
        return new ChatPermissions(parent::_data('permissions'));
    }

    public function slowModeDelay(): ?int
    {
        return parent::_data('slow_mode_delay');
    }

    public function stickerSetName(): ?string
    {
        return parent::_data('sticker_set_name');
    }

    public function canSetStickerSet(): ?bool
    {
        return parent::_data('can_set_sticker_set');
    }
}