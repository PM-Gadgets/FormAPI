<?php

namespace Cosmic5173\FormAPI\elements;

use Cosmic5173\MultiLanguage\language\Language;

interface Translatable {

    public function translate(Language $language): mixed;

}