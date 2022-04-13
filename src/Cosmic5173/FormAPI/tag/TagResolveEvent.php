<?php

namespace Cosmic5173\FormAPI\tag;

use pocketmine\player\Player;

// Called when a form tag is attempted to be resolved.
// You can use this event to set that tags value.
class TagResolveEvent extends TagEvent {

    public function __construct(FormTag $tag, Player $player) {
        $this->tag = $tag;
        $this->player = $player;
    }
}