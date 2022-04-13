<?php

namespace Cosmic5173\FormAPI\elements;

use Cosmic5173\MultiLanguage\language\Language;
use Cosmic5173\ScoreboardAPI\utils\TagUtils;
use JetBrains\PhpStorm\ArrayShape;
use pocketmine\player\Player;
use pocketmine\Server;

class Slider extends CustomFormElement implements Translatable {

    private int $min;
    private int $max;
    private ?int $step;
    private ?int $default;

    public function __construct(string $textTranslationKey, int $min, int $max, ?int $step = null, ?int $default = null, ?string $label = null, string $fallbackText = "") {
        $this->textTranslationKey = $textTranslationKey;
        $this->min = $min;
        $this->max = $max;
        $this->step = $step;
        $this->default = $default;
        $this->label = $label;
        $this->fallbackText = $fallbackText;
    }

    /**
     * @return int
     */
    public function getMin(): int {
        return $this->min;
    }

    /**
     * @param int $min
     */
    public function setMin(int $min): void {
        $this->min = $min;
    }

    /**
     * @return int
     */
    public function getMax(): int {
        return $this->max;
    }

    /**
     * @param int $max
     */
    public function setMax(int $max): void {
        $this->max = $max;
    }

    /**
     * @return int|null
     */
    public function getStep(): ?int {
        return $this->step;
    }

    /**
     * @param int|null $step
     */
    public function setStep(?int $step): void {
        $this->step = $step;
    }

    /**
     * @return int|null
     */
    public function getDefault(): ?int {
        return $this->default;
    }

    /**
     * @param int|null $default
     */
    public function setDefault(?int $default): void {
        $this->default = $default;
    }

    #[ArrayShape(["type" => "string", "text" => "string", "min" => "int", "max" => "int", "default" => "int|null", "step" => "int|null"])]
    public function process(Player $player, ?Language $language = null): array {
        $content = [
            "type" => "slider",
            "text" => $language ? $language->translate($this->textTranslationKey) : $this->fallbackText,
            "min" => $this->min,
            "max" => $this->max
        ];

        $tags = TagUtils::fetchTags($content["text"]);
        foreach ($tags as $tag) {
            $content["text"] = str_replace($tag, TagUtils::resolveTag($player, $tag)->getValue(), $content["text"]);
        }

        if ($this->step !== null) {
            $content["step"] = $this->step;
        }
        if ($this->default !== null) {
            $content["default"] = $this->default;
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