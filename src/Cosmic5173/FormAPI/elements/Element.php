<?php

namespace Cosmic5173\FormAPI\elements;

use Cosmic5173\MultiLanguage\language\Language;
use pocketmine\player\Player;

abstract class Element {

    abstract public function process(Player $player, ?Language $language = null): array;
}