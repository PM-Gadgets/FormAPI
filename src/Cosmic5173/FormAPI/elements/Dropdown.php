<?php

namespace Cosmic5173\FormAPI\elements;

use Cosmic5173\MultiLanguage\language\Language;
use Cosmic5173\FormAPI\tag\TagUtils;
use JetBrains\PhpStorm\ArrayShape;
use pocketmine\player\Player;
use pocketmine\Server;

class Dropdown extends CustomFormElement implements Translatable {

    /** @var DropdownOption[] */
    private array $dropdownOptions;
    private ?int $default;

    /**
     * @param string $textTranslationKey
     * @param DropdownOption[] $options
     * @param bool|null $default
     * @param string|null $label
     * @param string $fallbackText
     */
    public function __construct(string $textTranslationKey, array $options, ?bool $default = null, ?string $label = null, string $fallbackText = "") {
        $this->textTranslationKey = $textTranslationKey;
        $this->dropdownOptions = $options;
        $this->default = $default;
        $this->label = $label;
        $this->fallbackText = $fallbackText;
    }

    /**
     * @return DropdownOption[]
     */
    public function getDropdownOptions(): array {
        return $this->dropdownOptions;
    }

    /**
     * @param DropdownOption[] $dropdownOptions
     */
    public function setDropdownOptions(array $dropdownOptions): void {
        $this->dropdownOptions = $dropdownOptions;
    }

    /**
     * @return bool|int|null
     */
    public function getDefault(): bool|int|null {
        return $this->default;
    }

    /**
     * @param bool|int|null $default
     */
    public function setDefault(bool|int|null $default): void {
        $this->default = $default;
    }

    #[ArrayShape(["type" => "string", "text" => "string", "options" => "string[]", "default" => "int|null"])]
    public function process(Player $player, ?Language $language = null): array {
        $content = [
            "type" => "dropdown",
            "text" => $language ? $language->translate($this->textTranslationKey) : $this->fallbackText,
            "options" => array_map(static function (DropdownOption $option) use ($player, $language) {
                return $option->process($player, $language)[0];
            }, $this->dropdownOptions),
            "default" => $this->default
        ];

        $tags = TagUtils::fetchTags($content["text"]);
        foreach ($tags as $tag) {
            $content["text"] = str_replace($tag, TagUtils::resolveTag($player, $tag)->getValue(), $content["text"]);
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