<?php

namespace Cosmic5173\FormAPI;

use Cosmic5173\FormAPI\elements\Button;
use Cosmic5173\FormAPI\elements\Content;
use Cosmic5173\FormAPI\elements\Title;
use Cosmic5173\MultiLanguage\language\Language;
use pocketmine\form\FormValidationException;
use pocketmine\player\Player;

class ModalForm extends Form {

    /** @var Title */
    private Title $title;
    /** @var Content */
    private Content $content;
    private Button $button1;
    private Button $button2;

    /**
     * @param callable|null $callable
     */
    public function __construct(?callable $callable) {
        parent::__construct($callable);
    }

    public function processData(&$data) : void {
        if(!is_bool($data)) {
            throw new FormValidationException("Expected a boolean response, got " . gettype($data));
        }
    }

    /**
     * @return Title
     */
    public function getTitle(): Title {
        return $this->title;
    }

    /**
     * @param Title $title
     */
    public function setTitle(Title $title): void {
        $this->title = $title;
    }

    /**
     * @return Content
     */
    public function getContent(): Content {
        return $this->content;
    }

    /**
     * @param Content $content
     */
    public function setContent(Content $content): void {
        $this->content = $content;
    }

    /**
     * @return Button
     */
    public function getButton1(): Button {
        return $this->button1;
    }

    /**
     * @param Button $button1
     */
    public function setButton1(Button $button1): void {
        $this->button1 = $button1;
    }

    /**
     * @return Button
     */
    public function getButton2(): Button {
        return $this->button2;
    }

    /**
     * @param Button $button2
     */
    public function setButton2(Button $button2): void {
        $this->button2 = $button2;
    }

    public function processElements(Player $player, ?Language $language = null): ModalForm {
        $this->data = [
            "type" => "modal",
            "title" => $this->title->process($player, $language),
            "content" => $this->content->process($player, $language),
            "button1" => $this->button1->process($player, $language)["text"],
            "button2" => $this->button2->process($player, $language)["text"]
        ];
        return $this;
    }
}