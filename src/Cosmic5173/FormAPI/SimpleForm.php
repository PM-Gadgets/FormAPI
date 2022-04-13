<?php

namespace Cosmic5173\FormAPI;

use Cosmic5173\FormAPI\elements\Button;
use Cosmic5173\FormAPI\elements\Content;
use Cosmic5173\FormAPI\elements\Title;
use pocketmine\form\FormValidationException;

class SimpleForm extends Form {

    private Title $title;
    private Content $content;
    /** @var Button[] */
    private array $buttons = [];

    private array $labelMap = [];

    /**
     * @param callable|null $callable
     */
    public function __construct(?callable $callable) {
        parent::__construct($callable);
        $this->data["type"] = "form";
        $this->data["title"] = "";
        $this->data["content"] = "";
        $this->data["buttons"] = [];
    }

    public function processData(&$data) : void {
        if($data !== null){
            if(!is_int($data)) {
                throw new FormValidationException("Expected an integer response, got " . gettype($data));
            }
            $count = count($this->data["buttons"]);
            if($data >= $count || $data < 0) {
                throw new FormValidationException("Button $data does not exist");
            }
            $data = $this->labelMap[$data] ?? null;
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
     * @return SimpleForm
     */
    public function setTitle(Title $title): self {
       $this->title = $title;
       return $this;
    }

    /**
     * @return Content
     */
    public function getContent(): Content {
        return $this->content;
    }

    /**
     * @param Content $content
     * @return SimpleForm
     */
    public function setContent(Content $content): self {
        $this->content = $content;
        return $this;
    }

    /**
     * @param Button $button
     */
    public function addButton(Button $button) : void {
        $this->buttons[] = $button;
        $this->labelMap[] = $button->getLabel() ?? count($this->labelMap);
    }
}