<?php

namespace Cosmic5173\FormAPI;

use Cosmic5173\FormAPI\elements\Button;
use Cosmic5173\FormAPI\elements\Content;
use Cosmic5173\FormAPI\elements\Title;
use Cosmic5173\MultiLanguage\language\Language;
use pocketmine\form\FormValidationException;
use pocketmine\player\Player;

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
     * @return SimpleForm
     */
    public function addButton(Button $button) : self {
        $this->buttons[] = $button;
        $this->labelMap[] = $button->getLabel() ?? count($this->labelMap);
        return $this;
    }

    public function processElements(Player $player, ?Language $language = null): SimpleForm {
        $this->data = [
            "type" => "form",
            "title" => $this->title->process($player, $language),
            "content" => $this->content->process($player, $language),
            "buttons" => array_map(static function(Button $button) use ($player, $language) {
                return $button->process($player, $language);
            }, $this->buttons)
        ];
        return $this;
    }
}