<?php

namespace Cosmic5173\ScoreboardAPI\utils;

use Cosmic5173\FormAPI\tag\FormTag;
use Cosmic5173\FormAPI\tag\TagResolveEvent;
use pocketmine\player\Player;

class TagUtils {

    /**
     * Singleton REGEX pattern for finding tags.
     * @var string
     */
    private static string $REGEX = "";

    /**
     * Massive shout-out to Cortex/Marshall for this bit of code
     * used from HRKChat
     *
     * Searches for a tag in a string and returns that tag. Tags are formatted as {tag}.
     * @return string
     */
    private static function REGEX(): string{
        if(self::$REGEX === ""){
            self::$REGEX = "/(?:" . preg_quote("{") . ")((?:[A-Za-z0-9_\-]{2,})(?:\.[A-Za-z0-9_\-]+)+)(?:" . preg_quote("}") . ")/";
        }

        return self::$REGEX;
    }

    /**
     * Massive shout-out to Cortex/Marshall for this bit of code
     * used from HRKChat
     *
     * Fetch all the tags present in the string. Tags are formatted as {tag}.
     * @param string $text
     * @return string[]
     */
    public static function fetchTags(string $text): array{
        $tags = [];

        if(preg_match_all(self::REGEX(), $text, $matches)){
            $tags = $matches[1];
        }

        return $tags;
    }

    /**
     * Allow tag addons/plugins to set what value needs to be set to the resolved tag.
     * This method should be called to get any tags.
     *
     * @param Player $player
     * @param string $tag
     * @return FormTag
     */
    public static function resolveTag(Player $player, string $tag): FormTag {
        $tag = new FormTag($tag, "");

        $ev = new TagResolveEvent($tag, $player);
        $ev->call();

        return $ev->getTag();
    }
}