<?php

namespace Cosmic5173\FormAPI\elements;

use Cosmic5173\MultiLanguage\language\Language;
use Cosmic5173\FormAPI\tag\TagUtils;
use JetBrains\PhpStorm\ArrayShape;
use pocketmine\player\Player;
use pocketmine\Server;

class Title extends Element implements Translatable {

    private string $translationKey;
    private string $fallbackText;

    public function __construct(string $translationKey, string $fallbackText = "") {
        $this->translationKey = $translationKey;
        $this->fallbackText = $fallbackText;
    }

    /**
     * @return string
     */
    public function getTranslationKey(): string {
        return $this->translationKey;
    }

    /**
     * @param string $translationKey
     */
    public function setTranslationKey(string $translationKey): void {
        $this->translationKey = $translationKey;
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

    #[ArrayShape(["title" => "string"])]
    public function process(Player $player, ?Language $language = null): array {
        $title = $language ? $language->translate($this->translationKey) : $this->fallbackText;

        $tags = TagUtils::fetchTags($title);
        foreach ($tags as $tag) {
            $title = str_replace($tag, TagUtils::resolveTag($player, $tag)->getValue(), $title);
        }

        return ["title" => $title];
    }

    public function translate(Language $language): string {
        try {
            return $language->translate($this->translationKey);
        } catch (\Exception $exception) {
            Server::getInstance()->getLogger()->error($exception->getMessage());
            return $this->fallbackText;
        }
    }
}