<?php

namespace Cosmic5173\FormAPI\elements;

use Cosmic5173\MultiLanguage\language\Language;
use Cosmic5173\ScoreboardAPI\utils\TagUtils;
use JetBrains\PhpStorm\ArrayShape;
use pocketmine\player\Player;
use pocketmine\Server;

class StepSlider extends CustomFormElement implements Translatable {

    private array $steps;
    private ?int $defaultIndex;

    public function __construct(string $textTranslationKey, array $steps, ?int $defaultIndex = null, ?string $label = null, string $fallbackText = "") {
        $this->textTranslationKey = $textTranslationKey;
        $this->steps = $steps;
        $this->defaultIndex = $defaultIndex;
        $this->label = $label;
        $this->fallbackText = $fallbackText;
    }

    /**
     * @return array
     */
    public function getSteps(): array {
        return $this->steps;
    }

    /**
     * @param array $steps
     */
    public function setSteps(array $steps): void {
        $this->steps = $steps;
    }

    /**
     * @return int|null
     */
    public function getDefaultIndex(): ?int {
        return $this->defaultIndex;
    }

    /**
     * @param int|null $defaultIndex
     */
    public function setDefaultIndex(?int $defaultIndex): void {
        $this->defaultIndex = $defaultIndex;
    }

    #[ArrayShape(["type" => "string", "text" => "string", "steps" => "array", "default" => "int|null"])]
    public function process(Player $player, ?Language $language = null): array {
        $content = [
            "type" => "step_slider",
            "text" => $language ? $language->translate($this->textTranslationKey) : $this->fallbackText,
            "steps" => $this->steps
        ];

        $tags = TagUtils::fetchTags($content["text"]);
        foreach ($tags as $tag) {
            $content["text"] = str_replace($tag, TagUtils::resolveTag($player, $tag)->getValue(), $content["text"]);
        }

        if ($this->defaultIndex !== null) {
            $content["default"] = $this->defaultIndex;
        }

        return $content;
    }

    public function translate(Language $language): string {
        try {
            return $language->translate($this->textTranslationKey);
        } catch (\Exception $exception) {
            Server::getInstance()->getLogger()->error($exception->getMessage());
            return $this->fallbackText;
        }
    }
}