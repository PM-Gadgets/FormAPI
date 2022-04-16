<?php

namespace Cosmic5173\FormAPI\elements;

use Cosmic5173\MultiLanguage\language\Language;
use Cosmic5173\FormAPI\tag\TagUtils;
use JetBrains\PhpStorm\ArrayShape;
use pocketmine\player\Player;
use pocketmine\Server;

class Button extends LabeledElement implements Translatable {

    private string $translationKey;
    private string $fallbackText;
    private ?Image $image;

    public function __construct(string $translationKey, ?string $label = null, ?Image $image = null, string $fallbackText = "") {
        $this->translationKey = $translationKey;
        $this->fallbackText = $fallbackText;
        $this->label = $label;
        $this->image = $image;
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

    /**
     * @return string|null
     */
    public function getLabel(): ?string {
        return $this->label;
    }

    /**
     * @param string|null $label
     */
    public function setLabel(?string $label): void {
        $this->label = $label;
    }

    /**
     * @return Image|null
     */
    public function getImage(): ?Image {
        return $this->image;
    }

    /**
     * @param Image|null $image
     */
    public function setImage(?Image $image): void {
        $this->image = $image;
    }

    #[ArrayShape(["text" => "string", "image" => ["type" => "int", "path" => "string"]])]
    public function process(Player $player, ?Language $language = null): array {
        $content = ["text" => $this->translate($language)];

        $tags = TagUtils::fetchTags($content["text"]);
        foreach ($tags as $tag) {
            $content["text"] = str_replace($tag, TagUtils::resolveTag($player, $tag)->getValue(), $content["text"]);
        }

        if($this->image !== null) {
            $content["image"] = $this->image->process($player, $language);
        }

        return $content;
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