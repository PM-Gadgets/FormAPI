<?php

namespace Cosmic5173\FormAPI;

use Cosmic5173\FormAPI\elements\CustomFormElement;
use Cosmic5173\FormAPI\elements\Dropdown;
use Cosmic5173\FormAPI\elements\Input;
use Cosmic5173\FormAPI\elements\Label;
use Cosmic5173\FormAPI\elements\Slider;
use Cosmic5173\FormAPI\elements\StepSlider;
use Cosmic5173\FormAPI\elements\Title;
use Cosmic5173\FormAPI\elements\Toggle;
use Cosmic5173\MultiLanguage\language\Language;
use pocketmine\form\FormValidationException;
use pocketmine\player\Player;

class CustomForm extends Form {

    private Title $title;
    /** @var CustomFormElement[] */
    private array $elements = [];

    private array $labelMap = [];
    private array $validationMethods = [];

    public function processData(&$data) : void {
        if($data !== null && !is_array($data)) {
            throw new FormValidationException("Expected an array response, got " . gettype($data));
        }
        if(is_array($data)) {
            if(count($data) !== count($this->validationMethods)) {
                throw new FormValidationException("Expected an array response with the size " . count($this->validationMethods) . ", got " . count($data));
            }
            $new = [];
            foreach($data as $i => $v){
                $validationMethod = $this->validationMethods[$i] ?? null;
                if($validationMethod === null) {
                    throw new FormValidationException("Invalid element " . $i);
                }
                if(!$validationMethod($v)) {
                    throw new FormValidationException("Invalid type given for element " . $this->labelMap[$i]);
                }
                $new[$this->labelMap[$i]] = $v;
            }
            $data = $new;
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
     * @return CustomForm
     */
    public function setTitle(Title $title): self {
        $this->title = $title;
        return $this;
    }

    /**
     * @param Label $label
     * @return CustomForm
     */
    public function addLabel(Label $label) : self {
        $this->elements[] = $label;
        $this->labelMap[] = $label->getLabel() ?? count($this->labelMap);
        $this->validationMethods[] = static fn($v) => $v === null;
        return $this;
    }

    /**
     * @param Toggle $toggle
     * @return CustomForm
     */
    public function addToggle(Toggle $toggle) : self {
        $this->elements[] = $toggle;
        $this->labelMap[] = $toggle->getLabel() ?? count($this->labelMap);
        $this->validationMethods[] = static fn($v) => is_bool($v);
        return $this;
    }

    /**
     * @param Slider $slider
     * @return CustomForm
     */
    public function addSlider(Slider $slider) : self {
        $this->elements[] = $slider;
        $this->labelMap[] = $slider->getLabel() ?? count($this->labelMap);
        $this->validationMethods[] = static fn($v) => (is_float($v) || is_int($v)) && $v >= $slider->getMin() && $v <= $slider->getMax();
        return $this;
    }

    /**
     * @param StepSlider $slider
     * @return CustomForm
     */
    public function addStepSlider(StepSlider $slider) : self {
        $this->elements[] = $slider;
        $this->labelMap[] = $slider->getLabel() ?? count($this->labelMap);
        $this->validationMethods[] = static fn($v) => is_int($v) && isset($slider->getSteps()[$v]);
        return $this;
    }

    /**
     * @param Dropdown $dropdown
     * @return CustomForm
     */
    public function addDropdown(Dropdown $dropdown) : self {
        $this->elements[] = $dropdown;
        $this->labelMap[] = $dropdown->getLabel() ?? count($this->labelMap);
        $this->validationMethods[] = static fn($v) => is_int($v) && isset($dropdown->getDropdownOptions()[$v]);
        return $this;
    }

    /**
     * @param Input $input
     * @return CustomForm
     */
    public function addInput(Input $input) : self {
        $this->elements[] = $input;
        $this->labelMap[] = $input->getLabel() ?? count($this->labelMap);
        $this->validationMethods[] = static fn($v) => is_string($v);
        return $this;
    }

    public function processElements(Player $player, ?Language $language = null): CustomForm {
        $this->data = [
            "type" => "custom_form",
            "title" => $this->title->process($player, $language)["title"],
            "content" => array_map(static fn($element) => $element->process($player, $language), $this->elements)
        ];
        return $this;
    }
}