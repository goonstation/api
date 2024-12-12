<?php

namespace App\Rules;

use App\Models\Player;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;

class PlayerIdWithCkey implements DataAwareRule, InvokableRule
{
    /**
     * All of the data under validation.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Set the data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return \Illuminate\Translation\PotentiallyTranslatedString|void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if ((! array_key_exists('ckey', $this->data) || ! $this->data['ckey']) && ! $value) {
            return $fail('The :attribute is required when ckey is empty');
        }

        if ((int) $value !== 0) {
            $playerExists = Player::where('id', $value)->exists();
            if (! $playerExists) {
                return $fail('The selected player ID is invalid');
            }
        }
    }
}
