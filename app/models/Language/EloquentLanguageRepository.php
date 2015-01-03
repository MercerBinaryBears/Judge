<?php

class EloquentLanguageRepository implements LanguageRepository {
    public function all() {
        return Language::all();
    }
}
