<?php

namespace Cosmic5173\FormAPI\tag;

use pocketmine\event\player\PlayerEvent;

abstract class TagEvent extends PlayerEvent {

    /** @var FormTag */
    protected FormTag $tag;

    /**
     * @return FormTag
     */
    public function getTag(): FormTag {
        return $this->tag;
    }

    /**
     * @param FormTag $tag
     */
    public function setTag(FormTag $tag): void {
        $this->tag = $tag;
    }
}