<?php

namespace Cosmic5173\FormAPI\elements;

use Cosmic5173\MultiLanguage\language\Language;
use JetBrains\PhpStorm\ArrayShape;
use pocketmine\player\Player;

class Image extends Element {

    public const IMAGE_TYPE_PATH = 0;
    public const IMAGE_TYPE_URL = 1;

    private int $imageType;
    private string $imagePath;

    public function __construct(string $imagePath, int $imageType = self::IMAGE_TYPE_PATH) {
        $this->imagePath = $imagePath;
        $this->imageType = $imageType;
    }

    /**
     * @return int
     */
    public function getImageType(): int {
        return $this->imageType;
    }

    /**
     * @param int $imageType
     */
    public function setImageType(int $imageType): void {
        $this->imageType = $imageType;
    }

    /**
     * @return string
     */
    public function getImagePath(): string {
        return $this->imagePath;
    }

    /**
     * @param string $imagePath
     */
    public function setImagePath(string $imagePath): void {
        $this->imagePath = $imagePath;
    }

    #[ArrayShape(["type" => "string", "data" => "string"])]
    public function process(Player $player, ?Language $language = null): array {
        return [
            "type" => $this->imageType === 0 ? "path" : "url",
            "data" => $this->imagePath
        ];
    }
}