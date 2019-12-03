<?php

namespace D3jn\LaravelLanguages;

use Illuminate\Http\Request;
use D3jn\Languages\Exceptions\LanguageException;

class Languages
{
    /**
     * Stored value for current language.
     *
     * @var string
     */
    protected $cachedLanguage;

    /**
     * Array of all languages available for this app.
     *
     * @var array
     */
    protected $availableLanguages;

    /**
     * Language for this session.
     *
     * @var string
     */
    protected $currentLanguage;
    
    /**
     * Language to use when no language prefix is specified.
     *
     * @var string
     */
    protected $defaultLanguage;

    /**
     * User-defined callable for when language/locale is initialized.
     *
     * @var Callable|null
     */
    protected $setLocaleCallable = null;

    /**
     * Handler constructor.
     */
    public function __construct()
    {
        $this->availableLanguages = config('languages.available_languages');
        $this->defaultLanguage = config('languages.default_language', 'ru');
        $this->hideDefaultLanguage = config('languages.hide_default_language', true);
        $this->currentLanguage = $this->defaultLanguage;
    }
    
    /**
     * Retrieve translations in array form from request by key.
     *
     * @param Request $request
     * @param string $key
     *
     * @return array
     */
    public function getTranslationsArray(Request $request, string $key = 'translate'): array
    {
        $translations = $request->input($key, []);
        $translated = [];
        foreach ($translations as $language => $translation) {
            if (! is_translation_empty($translation)) {
                $translated[$language] = $translation;
            }
        }

        return $translated;
    }

    /**
     * Return true if specified language is available.
     *
     * @param string|null $locale
     *
     * @return bool
     */
    public function isAvailable(?string $locale): bool
    {
        return (isset($this->availableLanguages[$locale]));
    }

    /**
     * Return array of available languages keys.
     *
     * @return array
     */
    public function getAvailable(): array
    {
        return array_keys($this->availableLanguages);
    }
    
    /**
     * Return true if it was configured that default language URI should not
     * be present in site URLs.
     *
     * @return bool
     */
    public function shouldHideDefault(): bool
    {
        return $this->hideDefaultLanguage;
    }
    
    /**
     * Return URI of default language.
     *
     * @return string
     */
    public function getDefault(): string
    {
        return $this->defaultLanguage;
    }

    /**
     * Return URI of current language.
     *
     * @return string
     */
    public function getCurrent(): string
    {
        return $this->currentLanguage;
    }
    
    /**
     * Return locale for current language.
     *
     * @return string
     */
    public function getCurrentLocale(): string
    {
        return $this->availableLanguages[$this->currentLanguage];
    }

    /**
     * Set callable to be executed when locale is applied.
     *
     * @param callable $callable
     */
    public function setLocaleCallable(Callable $callable): void
    {
        $this->setLocaleCallable = $callable;
    }

    /**
     * Set current language to specified one.
     *
     * @param string $language
     */
    public function setCurrent(string $language): void
    {
        if (!$this->isAvailable($language)) {
            throw new LanguageException("Language <{$language}> is not supported!");
        }

        $this->currentLanguage = $language;

        if (is_callable($this->setLocaleCallable)) {
            call_user_func(
                $this->setLocaleCallable,
                $this->availableLanguages[$language]
            );
        }
    }
    
    /**
     * Sets current locale for current request, returns it's prefix for routes.
     *
     * @return string|null
     */
    public function init(): ?string
    {
        if ($this->cachedLanguage) {
            return $this->cachedLanguage;
        }

        $language = request()->force_lang ?: request()->segment(1);

        if ($this->isAvailable($language)) {
            $this->setCurrent($language);
            $this->cachedLanguage = $language;
        } else {
            $this->setCurrent($this->defaultLanguage);
        
            if ($this->shouldHideDefault()) {
                $this->cachedLanguage = null;
            } else {
                $this->cachedLanguage = $this->defaultLanguage;
            }

            return $this->cachedLanguage;
        }

        return $language;
    }
}
