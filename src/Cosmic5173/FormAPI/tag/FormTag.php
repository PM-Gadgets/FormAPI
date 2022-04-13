<?php

namespace Cosmic5173\FormAPI\tag;

class FormTag {

    private string $name;
    private mixed $value;

    public function __construct(string $name, mixed $value) {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue(mixed $value): void {
        $this->value = $value;
    }
}