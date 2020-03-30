<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class ChatMember extends Entity
{
    public function user(): User
    {
        return new User(parent::_data('user'));
    }

    public function status(): ?string
    {
        return parent::_data('status');
    }

    public function customTitle(): ?string
    {
        return parent::_data('custom_title');
    }

    public function untilDate(): ?int
    {
        return parent::_data('until_date');
    }

    public function canBeEdited(): ?bool
    {
        return parent::_data('can_be_edited');
    }

    public function canChangeInfo(): ?bool
    {
        return parent::_data('can_change_info');
    }

    public function canPostMessages(): ?bool
    {
        return parent::_data('can_post_messages');
    }

    public function canEditMessages(): ?bool
    {
        return parent::_data('can_edit_messages');
    }

    public function canDeleteMessages(): ?bool
    {
        return parent::_data('can_delete_messages');
    }

    public function canInviteUsers(): ?bool
    {
        return parent::_data('can_invite_users');
    }

    public function canRestrictMembers(): ?bool
    {
        return parent::_data('can_restrict_members');
    }

    public function canPinMessages(): ?bool
    {
        return parent::_data('can_pin_messages');
    }

    public function canPromoteMembers(): ?bool
    {
        return parent::_data('can_promote_members');
    }

    public function canSendMessages(): ?bool
    {
        return parent::_data('can_send_messages');
    }

    public function canSendMediaMessages(): ?bool
    {
        return parent::_data('can_send_media_messages');
    }

    public function canSendOtherMessages(): ?bool
    {
        return parent::_data('can_send_other_messages');
    }

    public function canAddWebPagePreviews(): ?bool
    {
        return parent::_data('can_add_web_page_previews');
    }
}