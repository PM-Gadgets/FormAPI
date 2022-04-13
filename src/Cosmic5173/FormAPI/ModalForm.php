<?php

namespace Cosmic5173\FormAPI;

use Cosmic5173\FormAPI\elements\Button;
use Cosmic5173\FormAPI\elements\Content;
use Cosmic5173\FormAPI\elements\Title;
use pocketmine\form\FormValidationException;

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
        $this->data["type"] = "modal";
        $this->data["title"] = "";
        $this->data["content"] = "";
        $this->data["button1"] = "";
        $this->data["button2"] = "";
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
}