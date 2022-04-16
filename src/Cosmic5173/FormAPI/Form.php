<?php

namespace Cosmic5173\FormAPI;

use Cosmic5173\MultiLanguage\language\Language;
use pocketmine\form\Form as IForm;
use pocketmine\form\FormValidationException;
use pocketmine\player\Player;

abstract class Form implements IForm {

    /** @var array */
    protected array $data = [];
    /** @var callable|null */
    private $callable;

    /**
     * @param callable|null $callable
     */
    public function __construct(?callable $callable = null) {
        $this->callable = $callable;
    }

    /**
     * @param Player $player
     * @param Language|null $language
     */
    public function sendToPlayer(Player $player, ?Language $language = null): void {
        if (empty($this->data)) {
            $this->processElements($player, $language);
        }
        $player->sendForm($this);
    }

    public function getCallable(): ?callable {
        return $this->callable;
    }

    public function setCallable(?callable $callable) {
        $this->callable = $callable;
    }

    public function handleResponse(Player $player, $data): void {
        $this->processData($data);
        $callable = $this->getCallable();
        if ($callable !== null) {
            $callable($player, $data);
        }
    }

    abstract public function processElements(Player $player, ?Language $language = null): self;

    public function processData(&$data): void {
    }

    public function jsonSerialize() {
        if (empty($this->data)) {
            throw new FormValidationException("Form has not been processed yet. Please process form elements before sending form.");
        }
        return $this->data;
    }
}