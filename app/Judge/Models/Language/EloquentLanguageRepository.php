<?php namespace Judge\Models\Language;

class EloquentLanguageRepository implements LanguageRepository
{
    public function all()
    {
        return Language::all();
    }
}
