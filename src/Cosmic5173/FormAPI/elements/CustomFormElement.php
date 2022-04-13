<?php

namespace Cosmic5173\FormAPI\elements;

abstract class CustomFormElement extends LabeledElement{

    protected string $type;
    protected string $textTranslationKey;
    protected string $fallbackText;

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getTextTranslationKey(): string {
        return $this->textTranslationKey;
    }

    /**
     * @param string $textTranslationKey
     */
    public function setTextTranslationKey(string $textTranslationKey): void {
        $this->textTranslationKey = $textTranslationKey;
    }

    /**
     * @return string
     */
    public function getFallbackText(): string {
        return $this->fallbackText;
    }

    /**
     * @param string $fallbackText
     */
    public function setFallbackText(string $fallbackText): void {
        $this->fallbackText = $fallbackText;
    }
}