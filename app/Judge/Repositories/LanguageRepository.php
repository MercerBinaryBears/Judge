<?php namespace Judge\Repositories;

use Judge\Models\Language;

class LanguageRepository
{
    public function all()
    {
        return Language::all();
    }
}
