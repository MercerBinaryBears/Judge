<?php

class EloquentLanguageRepository implements LanguageRepository {
	
	public function getSelectBoxData() {
		return Language::orderBy('name')->lists('name', 'id');
	}
}
