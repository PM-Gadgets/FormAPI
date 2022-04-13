<?php

namespace Cosmic5173\FormAPI\elements;

use Cosmic5173\MultiLanguage\language\Language;
use Cosmic5173\ScoreboardAPI\utils\TagUtils;
use JetBrains\PhpStorm\ArrayShape;
use pocketmine\player\Player;
use pocketmine\Server;

class Input extends CustomFormElement implements Translatable {

    private string $placeholderTranslationKey;
    private string $fallbackPlaceholder;
    private string $defaultTranslationKey;
    private string $fallbackDefault;

    /**
     * @param string $textTranslationKey
     * @param string $placeholderTranslationKey
     * @param string $fallbackPlaceholder
     * @param string $defaultTranslationKey
     * @param string $fallbackDefault
     * @param bool|null $default
     * @param string|null $label
     * @param string $fallbackText
     */
    public function __construct(string $textTranslationKey, string $placeholderTranslationKey = "", string $fallbackPlaceholder = "", string $defaultTranslationKey = "", string $fallbackDefault = "", ?bool $default = null, ?string $label = null, string $fallbackText = "") {
        $this->textTranslationKey = $textTranslationKey;
        $this->placeholderTranslationKey = $placeholderTranslationKey;
        $this->fallbackPlaceholder = $fallbackPlaceholder;
        $this->defaultTranslationKey = $defaultTranslationKey;
        $this->fallbackDefault = $fallbackDefault;
        $this->label = $label;
        $this->fallbackText = $fallbackText;
    }

    /**
     * @return string
     */
    public function getPlaceholderTranslationKey(): string {
        return $this->placeholderTranslationKey;
    }

    /**
     * @param string $placeholderTranslationKey
     */
    public function setPlaceholderTranslationKey(string $placeholderTranslationKey): void {
        $this->placeholderTranslationKey = $placeholderTranslationKey;
    }

    /**
     * @return string
     */
    public function getFallbackPlaceholder(): string {
        return $this->fallbackPlaceholder;
    }

    /**
     * @param string $fallbackPlaceholder
     */
    public function setFallbackPlaceholder(string $fallbackPlaceholder): void {
        $this->fallbackPlaceholder = $fallbackPlaceholder;
    }

    /**
     * @return string
     */
    public function getDefaultTranslationKey(): string {
        return $this->defaultTranslationKey;
    }

    /**
     * @param string $defaultTranslationKey
     */
    public function setDefaultTranslationKey(string $defaultTranslationKey): void {
        $this->defaultTranslationKey = $defaultTranslationKey;
    }

    /**
     * @return string
     */
    public function getFallbackDefault(): string {
        return $this->fallbackDefault;
    }

    /**
     * @param string $fallbackDefault
     */
    public function setFallbackDefault(string $fallbackDefault): void {
        $this->fallbackDefault = $fallbackDefault;
    }

    #[ArrayShape(["type" => "string", "text" => "string", "placeholder" => "string", "default" => "string"])]
    public function process(Player $player, ?Language $language = null): array {
        $translations = $this->translate($language);
        $content = [
            "type" => "input",
            "text" => $language ? $translations["text"] : $this->fallbackText,
            "placeholder" => $this->placeholderTranslationKey !== "" ? $language ? $translations["placeholder"] : $this->fallbackPlaceholder : "",
            "default" => $this->defaultTranslationKey !== "" ? $language ? $translations["default"] : $this->fallbackDefault : "",
        ];

        $tags = TagUtils::fetchTags($content["text"]);
        foreach ($tags as $tag) {
            $content["text"] = str_replace($tag, TagUtils::resolveTag($player, $tag)->getValue(), $content["text"]);
        }
        $tags = TagUtils::fetchTags($content["placeholder"]);
        foreach ($tags as $tag) {
            $content["placeholder"] = str_replace($tag, TagUtils::resolveTag($player, $tag)->getValue(), $content["placeholder"]);
        }
        $tags = TagUtils::fetchTags($content["default"]);
        foreach ($tags as $tag) {
            $content["default"] = str_replace($tag, TagUtils::resolveTag($player, $tag)->getValue(), $content["default"]);
        }

        return $content;
    }

    public function translate(Language $language): array {
        $translations = [];
        try {
            $translations["text"] = $language->translate($this->textTranslationKey);
        } catch (\Exception $exception) {
            Server::getInstance()->getLogger()->error($exception->getMessage());
            $translations["text"] = $this->fallbackText;
        }
        try {
            $translations["placeholder"] = $language->translate($this->placeholderTranslationKey);
        } catch (\Exception $exception) {
            Server::getInstance()->getLogger()->error($exception->getMessage());
            $translations["placeholder"] = $this->fallbackPlaceholder;
        }
        try {
            $translations["default"] = $language->translate($this->defaultTranslationKey);
        } catch (\Exception $exception) {
            Server::getInstance()->getLogger()->error($exception->getMessage());
            $translations["default"] = $this->fallbackDefault;
        }

        return $translations;
    }
}