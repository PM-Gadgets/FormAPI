<?php

namespace Cosmic5173\FormAPI\elements;

use Cosmic5173\MultiLanguage\language\Language;
use Cosmic5173\ScoreboardAPI\utils\TagUtils;
use JetBrains\PhpStorm\ArrayShape;
use pocketmine\player\Player;
use pocketmine\Server;

class Toggle extends CustomFormElement implements Translatable {

    private ?bool $default;

    public function __construct(string $textTranslationKey, ?bool $default = null, ?string $label = null, string $fallbackText = "") {
        $this->textTranslationKey = $textTranslationKey;
        $this->default = $default;
        $this->label = $label;
        $this->fallbackText = $fallbackText;
    }

    /**
     * @return bool|null
     */
    public function getDefault(): ?bool {
        return $this->default;
    }

    /**
     * @param bool|null $default
     */
    public function setDefault(?bool $default): void {
        $this->default = $default;
    }

    #[ArrayShape(["type" => "string", "text" => "string", "default" => "bool|null"])]
    public function process(Player $player, ?Language $language = null): array {
        $content = [
            "type" => "toggle",
            "text" => $language ? $language->translate($this->textTranslationKey) : $this->fallbackText
        ];

        $tags = TagUtils::fetchTags($content["text"]);
        foreach ($tags as $tag) {
            $content["text"] = str_replace($tag, TagUtils::resolveTag($player, $tag)->getValue(), $content["text"]);
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