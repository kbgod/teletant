<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class ChatMember extends Entity
{
    /**
     * @return User
     */
    public function user(): User
    {
        return new User(parent::_data('user'));
    }

    /**
     * @return string|null
     */
    public function status(): ?string
    {
        return parent::_data('status');
    }

    /**
     * @return string|null
     */
    public function customTitle(): ?string
    {
        return parent::_data('custom_title');
    }

    /**
     * @return int|null
     */
    public function untilDate(): ?int
    {
        return parent::_data('until_date');
    }

    /**
     * @return bool|null
     */
    public function canBeEdited(): ?bool
    {
        return parent::_data('can_be_edited');
    }

    /**
     * @return bool|null
     */
    public function canChangeInfo(): ?bool
    {
        return parent::_data('can_change_info');
    }

    /**
     * @return bool|null
     */
    public function canPostMessages(): ?bool
    {
        return parent::_data('can_post_messages');
    }

    /**
     * @return bool|null
     */
    public function canEditMessages(): ?bool
    {
        return parent::_data('can_edit_messages');
    }

    /**
     * @return bool|null
     */
    public function canDeleteMessages(): ?bool
    {
        return parent::_data('can_delete_messages');
    }

    /**
     * @return bool|null
     */
    public function canInviteUsers(): ?bool
    {
        return parent::_data('can_invite_users');
    }

    /**
     * @return bool|null
     */
    public function canRestrictMembers(): ?bool
    {
        return parent::_data('can_restrict_members');
    }

    /**
     * @return bool|null
     */
    public function canPinMessages(): ?bool
    {
        return parent::_data('can_pin_messages');
    }

    /**
     * @return bool|null
     */
    public function canPromoteMembers(): ?bool
    {
        return parent::_data('can_promote_members');
    }

    /**
     * @return bool|null
     */
    public function canSendMessages(): ?bool
    {
        return parent::_data('can_send_messages');
    }

    /**
     * @return bool|null
     */
    public function canSendMediaMessages(): ?bool
    {
        return parent::_data('can_send_media_messages');
    }

    /**
     * @return bool|null
     */
    public function canSendOtherMessages(): ?bool
    {
        return parent::_data('can_send_other_messages');
    }

    /**
     * @return bool|null
     */
    public function canAddWebPagePreviews(): ?bool
    {
        return parent::_data('can_add_web_page_previews');
    }
}