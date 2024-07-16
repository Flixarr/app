<?php

namespace App\Traits;

trait WithValidationToasts
{
    public function boot()
    {
        $this->withValidator(function ($validator) {
            $validator->after(function ($validator) {
                foreach ($validator->errors()->all() as $error) {
                    toast()->danger($error)->push();
                }
            });
        });
    }
}
