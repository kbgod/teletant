<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class Poll extends Entity
{
    /**
     * @return string|null
     */
    public function id(): ?string
    {
        return parent::_data('id');
    }

    /**
     * @return string|null
     */
    public function question(): ?string
    {
        return parent::_data('question');
    }

    /**
     * @return PollOption[]
     */
    public function options(): array
    {
        $options = parent::_data('options');
        if (!is_null($options)) {
            foreach ($options as $key => $option) {
                $options[$key] = new PollOption($option);
            }
            return $options;
        } else return [];
    }

    /**
     * @return int|null
     */
    public function totalVoterCount(): ?int
    {
        return parent::_data('total_voter_count');
    }

    /**
     * @return bool|null
     */
    public function isClosed(): ?bool
    {
        return parent::_data('is_closed');
    }

    /**
     * @return bool|null
     */
    public function isAnonymous(): ?bool
    {
        return parent::_data('is_anonymous');
    }

    /**
     * @return string|null
     */
    public function type(): ?string
    {
        return parent::_data('type');
    }

    /**
     * @return bool|null
     */
    public function allowsMultipleAnswers(): ?bool
    {
        return parent::_data('allows_multiple_answers');
    }

    /**
     * @return int|null
     */
    public function correctOptionId(): ?int
    {
        return parent::_data('correct_option_id');
    }

    /**
     * @return string|null
     */
    public function explanation(): ?string
    {
        return parent::_data('explanation');
    }

    /**
     * @return MessageEntity[]
     */
    public function explanationEntities(): array
    {
        $entities = parent::_data('explanation_entities');
        if (!is_null($entities)) {
            foreach ($entities as $key => $entity) {
                $entities[$key] = new MessageEntity($entity);
            }
            return $entities;
        } else return [];
    }

    /**
     * @return int|null
     */
    public function openPeriod(): ?int
    {
        return parent::_data('open_period');
    }

    /**
     * @return int|null
     */
    public function closeDate(): ?int
    {
        return parent::_data('close_date');
    }
}