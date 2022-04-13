<?php

namespace Cosmic5173\FormAPI\elements;

abstract class LabeledElement extends Element {

    /** @var string|null */
    protected ?string $label;

    /**
     * @return string|null
     */
    public function getLabel(): ?string {
        return $this->label;
    }

    /**
     * @param string|null $label
     */
    public function setLabel(?string $label) {
        $this->label = $label;
    }
}