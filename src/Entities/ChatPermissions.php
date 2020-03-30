<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class ChatPermissions extends Entity
{
    public function canSendMessages(): ?bool
    {
        return parent::_data('can_send_messages');
    }

    public function canSendMediaMessages(): ?bool
    {
        return parent::_data('can_send_media_messages');
    }

    public function canSendPolls(): ?bool
    {
        return parent::_data('can_send_polls');
    }

    public function canSendOtherMessages(): ?bool
    {
        return parent::_data('can_send_other_messages');
    }

    public function canAddWebPagePreviews(): ?bool
    {
        return parent::_data('can_add_web_page_previews');
    }

    public function canChangeInfo(): ?bool
    {
        return parent::_data('can_change_info');
    }

    public function canInviteUsers(): ?bool
    {
        return parent::_data('can_invite_users');
    }

    public function canPinMessages(): ?bool
    {
        return parent::_data('can_pin_messages');
    }
}