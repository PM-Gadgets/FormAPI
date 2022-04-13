<?php

namespace Cosmic5173\FormAPI\elements;

use Cosmic5173\MultiLanguage\language\Language;
use Cosmic5173\ScoreboardAPI\utils\TagUtils;
use JetBrains\PhpStorm\ArrayShape;
use pocketmine\player\Player;
use pocketmine\Server;

class Label extends CustomFormElement implements Translatable {

    public function __construct(string $textTranslationKey, ?string $label = null, string $fallbackText = "") {
        $this->textTranslationKey = $textTranslationKey;
        $this->fallbackText = $fallbackText;
        $this->label = $label;
    }

    #[ArrayShape(["type" => "string", "text" => "string"])]
    public function process(Player $player, ?Language $language = null): array {
        $content = $language ? $language->translate($this->textTranslationKey) : $this->fallbackText;

        $tags = TagUtils::fetchTags($content);
        foreach ($tags as $tag) {
            $content = str_replace($tag, TagUtils::resolveTag($player, $tag)->getValue(), $content);
        }

        return [
            "type" => "label",
            "text" => $content
        ];
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