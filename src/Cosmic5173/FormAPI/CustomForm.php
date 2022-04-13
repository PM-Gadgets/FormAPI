<?php

namespace Cosmic5173\FormAPI;

use Cosmic5173\FormAPI\elements\Content;
use Cosmic5173\FormAPI\elements\CustomFormElement;
use Cosmic5173\FormAPI\elements\Dropdown;
use Cosmic5173\FormAPI\elements\Input;
use Cosmic5173\FormAPI\elements\Label;
use Cosmic5173\FormAPI\elements\Slider;
use Cosmic5173\FormAPI\elements\StepSlider;
use Cosmic5173\FormAPI\elements\Title;
use Cosmic5173\FormAPI\elements\Toggle;
use pocketmine\form\FormValidationException;

class CustomForm extends Form {

    private Title $title;
    /** @var CustomFormElement[] */
    private array $elements = [];

    private array $labelMap = [];
    private array $validationMethods = [];

    /**
     * @param callable|null $callable
     */
    public function __construct(?callable $callable) {
        parent::__construct($callable);
        $this->data["type"] = "custom_form";
        $this->data["title"] = "";
        $this->data["content"] = [];
    }

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
     */
    public function setTitle(Title $title): void {
        $this->title = $title;
    }

    /**
     * @param Label $label
     */
    public function addLabel(Label $label) : void {
        $this->elements[] = $label;
        $this->labelMap[] = $label->getLabel() ?? count($this->labelMap);
        $this->validationMethods[] = static fn($v) => $v === null;
    }

    /**
     * @param Toggle $toggle
     */
    public function addToggle(Toggle $toggle) : void {
        $this->elements[] = $toggle;
        $this->labelMap[] = $toggle->getLabel() ?? count($this->labelMap);
        $this->validationMethods[] = static fn($v) => is_bool($v);
    }

    /**
     * @param Slider $slider
     */
    public function addSlider(Slider $slider) : void {
        $this->elements[] = $slider;
        $this->labelMap[] = $slider->getLabel() ?? count($this->labelMap);
        $this->validationMethods[] = static fn($v) => (is_float($v) || is_int($v)) && $v >= $slider->getMin() && $v <= $slider->getMax();
    }

    /**
     * @param StepSlider $slider
     */
    public function addStepSlider(StepSlider $slider) : void {
        $this->elements[] = $slider;
        $this->labelMap[] = $slider->getLabel() ?? count($this->labelMap);
        $this->validationMethods[] = static fn($v) => is_int($v) && isset($slider->getSteps()[$v]);
    }

    /**
     * @param Dropdown $dropdown
     */
    public function addDropdown(Dropdown $dropdown) : void {
        $this->elements[] = $dropdown;
        $this->labelMap[] = $dropdown->getLabel() ?? count($this->labelMap);
        $this->validationMethods[] = static fn($v) => is_int($v) && isset($options[$v]);
    }

    /**
     * @param Input $input
     */
    public function addInput(Input $input) : void {
        $this->elements[] = $input;
        $this->labelMap[] = $input->getLabel() ?? count($this->labelMap);
        $this->validationMethods[] = static fn($v) => is_string($v);
    }
}